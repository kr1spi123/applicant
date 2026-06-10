<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\RankingService;


class AdminController extends Controller
{
    // Specialties Management (Dashboard)
    public function index()
    {
        $specialties = Specialty::orderBy('name')->get();
        return view('admin.specialties.index', compact('specialties'));
    }

    public function storeSpecialty(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'nullable|integer',
            'qualification' => 'nullable|string',
            'description' => 'required|string',
            'budget_places' => 'nullable|integer|min:0',
            'total_places' => 'nullable|integer|min:0',
            'study_forms' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'cost_full_time' => 'nullable|numeric|min:0',
            'cost_part_time' => 'nullable|numeric|min:0',
            'where_to_work' => 'nullable|string',
            'job_roles' => 'nullable|string',
            // per-form overrides
            'duration_full_time'      => 'nullable|integer',
            'duration_part_time'      => 'nullable|integer',
            'duration_distance'       => 'nullable|integer',
            'qualification_full_time' => 'nullable|string',
            'qualification_part_time' => 'nullable|string',
            'qualification_distance'  => 'nullable|string',
            'budget_places_full_time' => 'nullable|integer|min:0',
            'budget_places_part_time' => 'nullable|integer|min:0',
            'budget_places_distance'  => 'nullable|integer|min:0',
            'total_places_full_time'  => 'nullable|integer|min:0',
            'total_places_part_time'  => 'nullable|integer|min:0',
            'total_places_distance'   => 'nullable|integer|min:0',
        ]);

        // Convert comma-separated strings to arrays
        if (!empty($validated['where_to_work'])) {
            $validated['where_to_work'] = array_map('trim', explode(',', $validated['where_to_work']));
        }
        if (!empty($validated['job_roles'])) {
            $validated['job_roles'] = array_map('trim', explode(',', $validated['job_roles']));
        }

        if (isset($validated['total_places']) && $validated['total_places'] < $validated['budget_places']) {
            $validated['total_places'] = $validated['budget_places'];
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('specialties', 'total_places')) {
            unset($validated['total_places']);
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('specialties', 'cost_full_time')) {
            unset($validated['cost_full_time'], $validated['cost_part_time']);
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/specialties'), $filename);
            $validated['photo'] = $filename;
        }

        // Sync base fields with full_time values if base fields are empty
        if (empty($validated['duration']) && !empty($validated['duration_full_time'])) {
            $validated['duration'] = $validated['duration_full_time'];
        }
        if (empty($validated['qualification']) && !empty($validated['qualification_full_time'])) {
            $validated['qualification'] = $validated['qualification_full_time'];
        }
        if (empty($validated['budget_places']) && !empty($validated['budget_places_full_time'])) {
            $validated['budget_places'] = $validated['budget_places_full_time'];
        }
        if (empty($validated['total_places']) && !empty($validated['total_places_full_time'])) {
            $validated['total_places'] = $validated['total_places_full_time'];
        }

        Specialty::create($validated);

        return redirect()->route('admin.specialties.index')->with('success', 'Специальность успешно добавлена');
    }

    public function updateSpecialty(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'nullable|integer',
            'qualification' => 'nullable|string',
            'description' => 'required|string',
            'budget_places' => 'nullable|integer|min:0',
            'total_places' => 'nullable|integer|min:0',
            'study_forms' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'cost_full_time' => 'nullable|numeric|min:0',
            'cost_part_time' => 'nullable|numeric|min:0',
            'where_to_work' => 'nullable|string',
            'job_roles' => 'nullable|string',
            // per-form overrides
            'duration_full_time'      => 'nullable|integer',
            'duration_part_time'      => 'nullable|integer',
            'duration_distance'       => 'nullable|integer',
            'qualification_full_time' => 'nullable|string',
            'qualification_part_time' => 'nullable|string',
            'qualification_distance'  => 'nullable|string',
            'budget_places_full_time' => 'nullable|integer|min:0',
            'budget_places_part_time' => 'nullable|integer|min:0',
            'budget_places_distance'  => 'nullable|integer|min:0',
            'total_places_full_time'  => 'nullable|integer|min:0',
            'total_places_part_time'  => 'nullable|integer|min:0',
            'total_places_distance'   => 'nullable|integer|min:0',
        ]);

        // Convert comma-separated strings to arrays
        if (!empty($validated['where_to_work'])) {
            $validated['where_to_work'] = array_map('trim', explode(',', $validated['where_to_work']));
        }
        if (!empty($validated['job_roles'])) {
            $validated['job_roles'] = array_map('trim', explode(',', $validated['job_roles']));
        }

        if (isset($validated['total_places']) && $validated['total_places'] < $validated['budget_places']) {
            $validated['total_places'] = $validated['budget_places'];
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('specialties', 'total_places')) {
            unset($validated['total_places']);
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('specialties', 'cost_full_time')) {
            unset($validated['cost_full_time'], $validated['cost_part_time']);
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Delete old photo if it exists
            if ($specialty->photo && file_exists(public_path('assets/img/specialties/' . $specialty->photo))) {
                unlink(public_path('assets/img/specialties/' . $specialty->photo));
            }

            $file->move(public_path('assets/img/specialties'), $filename);
            $validated['photo'] = $filename;
        }

        // Sync base fields with full_time values if base fields are empty
        if (empty($validated['duration']) && !empty($validated['duration_full_time'])) {
            $validated['duration'] = $validated['duration_full_time'];
        }
        if (empty($validated['qualification']) && !empty($validated['qualification_full_time'])) {
            $validated['qualification'] = $validated['qualification_full_time'];
        }
        if (empty($validated['budget_places']) && !empty($validated['budget_places_full_time'])) {
            $validated['budget_places'] = $validated['budget_places_full_time'];
        }
        if (empty($validated['total_places']) && !empty($validated['total_places_full_time'])) {
            $validated['total_places'] = $validated['total_places_full_time'];
        }

        $specialty->update($validated);

        return redirect()->route('admin.specialties.index')->with('success', 'Специальность успешно обновлена');
    }

    public function destroySpecialty(Specialty $specialty)
    {
        $specialty->delete();
        return redirect()->route('admin.specialties.index')->with('success', 'Специальность успешно удалена');
    }

    // Applications Management
public function applications()
{
    $applications = Application::with(['specialty', 'user'])
        ->orderBy('specialty_id')
        ->orderByDesc('rating')
        ->orderByDesc('created_at')
        ->get();

    // Добавляем позиции для каждой заявки
    $applications->each(function ($application) {
        $application->position = app(RankingService::class)->getPosition($application);
    });

    return view('admin.applications.index', compact('applications'));
}

    public function updateApplicationStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:Требует подтверждения,На рассмотрении,Проверено,Одобрено,Отклонено'
        ]);

        $application->update(['status' => $validated['status']]);

        return redirect()->route('admin.applications.index')->with('success', 'Статус заявки успешно обновлен');
    }

    public function updateApplicationScores(Request $request, Application $application)
    {
        $validated = $request->validate([
            'ege_score' => 'required|integer|min:0|max:300',
            'certificate_score' => 'required|numeric|min:3|max:5',
            'verification_notes' => 'nullable|string|max:1000',
            'is_verified' => 'sometimes|boolean',
        ]);

        $application->ege_score = $validated['ege_score'];
        $application->certificate_score = $validated['certificate_score'];
        $application->verification_notes = $validated['verification_notes'] ?? null;

        if ($request->boolean('is_verified')) {
            $application->is_verified = true;
            $application->verified_by = auth()->user()->id;
            $application->verified_at = now();
            if ($application->status === 'Требует подтверждения') {
                $application->status = 'Проверено';
            }
        }

        $application->save();

        app(\App\Services\RankingService::class)->calculateRating($application);

        return redirect()->route('admin.applications.index')->with('success', 'Баллы обновлены, рейтинг пересчитан');
    }

    // Statistics
    public function statistics()
    {
        $stats = Specialty::withCount(['applications as total_applications'])
            ->withCount(['applications as today_applications' => function ($query) {
                $query->whereDate('created_at', now()->today());
            }])
            ->orderBy('name')
            ->get();

        return view('admin.statistics.index', compact('stats'));
    }

    public function recalculateRatings()
    {
        $rankingService = app(RankingService::class);
        $applications = Application::all();
        foreach ($applications as $application) {
            $rankingService->calculateRating($application);
        }
        return redirect()->back()->with('success', 'Рейтинги для всех заявок пересчитаны!');
    }

    // Enrollment boards: per-specialty ranked tables
    public function enrollmentBoards()
    {
        $specialties = Specialty::with(['applications' => function ($q) {
                $q->with('user')
                  ->orderByDesc('rating')
                  ->orderByDesc('created_at');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.enrollment.index', compact('specialties'));
    }
}