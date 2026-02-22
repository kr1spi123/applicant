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
            'duration' => 'required|string',
            'qualification' => 'required|string',
            'description' => 'required|string',
            'budget_places' => 'required|integer|min:0',
            'total_places' => 'nullable|integer|min:0',
            'skills' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'cost_full_time' => 'nullable|numeric|min:0',
            'cost_part_time' => 'nullable|numeric|min:0',
            'cost_distance' => 'nullable|numeric|min:0',
        ]);

        if (isset($validated['total_places']) && $validated['total_places'] < $validated['budget_places']) {
            $validated['total_places'] = $validated['budget_places'];
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('specialties', 'total_places')) {
            unset($validated['total_places']);
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('specialties', 'cost_full_time')) {
            unset($validated['cost_full_time'], $validated['cost_part_time'], $validated['cost_distance']);
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('specialties', 'public');
            $validated['photo'] = $path;
        }

        Specialty::create($validated);

        return redirect()->route('admin.specialties.index')->with('success', 'Специальность успешно добавлена');
    }

    public function updateSpecialty(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string',
            'qualification' => 'required|string',
            'description' => 'required|string',
            'budget_places' => 'required|integer|min:0',
            'total_places' => 'nullable|integer|min:0',
            'skills' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'cost_full_time' => 'nullable|numeric|min:0',
            'cost_part_time' => 'nullable|numeric|min:0',
            'cost_distance' => 'nullable|numeric|min:0',
        ]);

        if (isset($validated['total_places']) && $validated['total_places'] < $validated['budget_places']) {
            $validated['total_places'] = $validated['budget_places'];
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('specialties', 'total_places')) {
            unset($validated['total_places']);
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('specialties', 'cost_full_time')) {
            unset($validated['cost_full_time'], $validated['cost_part_time'], $validated['cost_distance']);
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('specialties', 'public');
            $validated['photo'] = $path;
            
            // Optional: Delete old photo if exists
            // if ($specialty->photo) Storage::disk('public')->delete($specialty->photo);
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
