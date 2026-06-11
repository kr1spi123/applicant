<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Specialty;
use App\Services\RankingService;
use App\Jobs\GeneratePdfJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ApplicationController extends Controller
{
    protected $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    public function index()
    {
        $user = Auth::user();

        $applications = $user->applications()->with('specialty')->latest()->get();

        $stats = [
            'total' => $applications->count(),
            'pending' => $applications->where('status', 'На рассмотрении')->count(),
            'approved' => $applications->where('status', 'Одобрено')->count(),
            'rejected' => $applications->where('status', 'Отклонено')->count(),
        ];

        $latestApplication = $applications->first();

        return view('applications.index', compact('applications', 'user', 'stats', 'latestApplication'));
    }

    public function recalculateRatings()
    {
        $user = Auth::user();
        $rankingService = app(\App\Services\RankingService::class);
        $applications = $user->applications;
        foreach ($applications as $application) {
            $rankingService->calculateRating($application);
        }
        return redirect()->back()->with('success', 'Рейтинги ваших заявок обновлены!');
    }

    public function enrollment()
    {
        $user = Auth::user();
        $specialties = Specialty::with(['applications' => function ($q) {
            $q->with('user')
                ->orderByDesc('rating')
                ->orderByDesc('created_at');
        }])
            ->orderBy('name')
            ->get();

        return view('applications.enrollment', compact('specialties', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $existingApplications = $user->applications()
            ->get()
            ->groupBy('specialty_id')
            ->map(function ($apps) {
                return $apps->pluck('study_form')->toArray();
            })
            ->toArray();

        $existingCount = $user->applications()->count();

        $specialties = Specialty::all();
        return view('applications.create', compact('specialties', 'existingCount', 'existingApplications'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Total limit check (existing + new)
        $existingCount = $user->applications()->count();
        $newCount = is_array($request->input('specialty')) ? count($request->input('specialty')) : 0;

        if (($existingCount + $newCount) > 3) {
            $message = $existingCount > 0
                ? "У вас уже есть $existingCount заявки(ок). Вы можете добавить еще не более " . (3 - $existingCount) . "."
                : "Вы не можете подать более 3 заявок суммарно.";

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => ['specialty' => [$message]]
                ], 422);
            }
            return redirect()->back()->withErrors(['specialty' => $message]);
        }

        try {
            $validated = $request->validate([
                'specialty' => 'required|array|min:1|max:3',
                'specialty.*' => 'exists:specialties,id',
                'study_form' => 'nullable|array',
                'study_form.*' => 'string',
                'name' => 'required|string|min:2|max:50',
                'surname' => 'required|string|min:2|max:50',
                'citizenship' => 'required|string|min:2|max:50',
                'phone' => 'required|string',
                'email' => 'required|email',
                'birthdate' => 'required|date',
                'street' => 'required|string',
                'house' => 'required|string',
                'city' => 'nullable|string',
                'school' => 'required|string',
                'graduation_year' => 'required|integer',
                'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'ege_score' => 'required|integer|min:0|max:300',
                'certificate_score' => 'required|numeric|min:3.0|max:5.0',
                'has_achievements' => 'nullable|boolean',
                'benefits' => 'nullable|array',
                'benefits.*' => 'string',
                'benefit_proof' => 'nullable|array',
                'benefit_proof.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            Log::info('Application submission started', [
                'user_id' => $user ? $user->id : null,
                'specialties' => $validated['specialty'],
                'has_benefits' => !empty($validated['benefits']),
            ]);

            $applicationIds = [];

            DB::transaction(function () use ($request, $validated, $user, &$applicationIds) {
                $existingCount = $user->applications()->count();
                $newCount = count($validated['specialty']);

                if ($existingCount + $newCount > 3) {
                    if ($request->expectsJson()) {
                        throw new \RuntimeException('limit_json');
                    }
                    throw new \RuntimeException('limit_form');
                }

                $user->update([
                    'name' => $validated['name'],
                    'surname' => $validated['surname'],
                    'phone' => $validated['phone'],
                    'birthdate' => $validated['birthdate'],
                    'street' => $validated['street'],
                    'house' => $validated['house'],
                    'city' => $validated['city'] ?? null,
                    'citizenship' => $validated['citizenship'],
                    'school' => $validated['school'],
                    'graduation_year' => $validated['graduation_year'],
                ]);

                foreach ($validated['specialty'] as $specialtyId) {
                    $studyForm = $request->input("study_form.$specialtyId");
                    $exists = Application::where('user_id', $user->id)
                        ->where('specialty_id', $specialtyId)
                        ->where('study_form', $studyForm)
                        ->exists();

                    if ($exists) {
                        Log::warning('Duplicate application attempt', [
                            'user_id' => $user->id,
                            'specialty_id' => $specialtyId,
                            'study_form' => $studyForm,
                        ]);

                        if ($request->expectsJson()) {
                            throw new \RuntimeException('duplicate_json');
                        }

                        throw new \RuntimeException('duplicate_form');
                    }
                }

                $certificatePath = null;
                if ($request->hasFile('certificate_file')) {
                    $certificatePath = $request->file('certificate_file')->store('certificates', 'public');
                }

                $benefitProofPaths = [];
                if (!empty($validated['benefits']) && $request->hasFile('benefit_proof')) {
                    foreach ($request->file('benefit_proof') as $file) {
                        $benefitProofPaths[] = $file->store('benefits', 'public');
                    }
                }

                foreach ($validated['specialty'] as $specialtyId) {
                    $studyForm = $request->input("study_form.$specialtyId");
                    $appData = [
                        'user_id' => $user->id,
                        'specialty_id' => $specialtyId,
                        'study_form' => $studyForm,
                        'full_name' => $validated['name'] . ' ' . $validated['surname'],
                        'phone' => $validated['phone'],
                        'email' => $validated['email'],
                        'birthdate' => $validated['birthdate'],
                        'city' => $validated['city'] ?? null,
                        'citizenship' => $validated['citizenship'],
                        'street' => $validated['street'],
                        'house' => $validated['house'],
                        'school' => $validated['school'],
                        'graduation_year' => $validated['graduation_year'],
                        'certificate_file' => $certificatePath,
                        'ege_score' => $validated['ege_score'],
                        'certificate_score' => $validated['certificate_score'],
                        'has_achievements' => $request->has('has_achievements'),
                        'benefits' => $validated['benefits'] ?? null,
                        'benefit_proof' => !empty($benefitProofPaths) ? $benefitProofPaths : null,
                        'status' => 'Требует подтверждения',
                        'rating' => 0,
                    ];

                    $application = Application::create($appData);
                    $applicationIds[] = $application->id;
                    $this->rankingService->calculateRating($application);
                }
            });

            Log::info('Application submission completed', [
                'user_id' => $user ? $user->id : null,
                'application_ids' => $applicationIds,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Заявки успешно отправлены! Рейтинг рассчитан.',
                    'application_ids' => $applicationIds,
                ]);
            }

            return redirect()->route('applications.index')
                ->with('success', 'Заявки успешно отправлены! Рейтинг рассчитан.');
        } catch (ValidationException $e) {
            Log::warning('Application validation failed', [
                'user_id' => $user ? $user->id : null,
                'errors' => $e->errors(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'duplicate_json' && $request->expectsJson()) {
                return response()->json([
                    'message' => 'Ошибка при отправке заявки',
                    'errors' => [
                        'specialty' => ['Вы уже подали заявку на одну из выбранных специальностей.'],
                    ],
                ], 422);
            }

            if ($e->getMessage() === 'duplicate_form') {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['specialty' => 'Вы уже подали заявку на одну из выбранных специальностей.']);
            }

            Log::error('Application submission failed (runtime)', [
                'user_id' => $user ? $user->id : null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Ошибка при сохранении заявки: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['form' => 'Ошибка при сохранении заявки: ' . $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('Application submission failed (throwable)', [
                'user_id' => $user ? $user->id : null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Ошибка при сохранении заявки: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['form' => 'Ошибка при сохранении заявки: ' . $e->getMessage()]);
        }
    }

    public function show(Application $application)
    {
        // Check authorization
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $position = $this->rankingService->getPosition($application);
        return view('applications.show', compact('application', 'position'));
    }

    public function verify($id)
    {
        $application = Application::with(['user', 'specialty'])->findOrFail($id);
        return view('applications.verify', compact('application'));
    }

    public function downloadCertificate(Application $application)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($application->user_id !== $user->id && $user->role !== 'admin') {
                abort(403);
            }
        } else {
            abort(403);
        }

        if (!$application->certificate_file) {
            abort(404);
        }

        $path = \Illuminate\Support\Facades\Storage::disk('public')->path($application->certificate_file);
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
