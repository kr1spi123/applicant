<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialty extends Model
{
    use HasFactory;

    protected $table = 'specialties';

    protected $fillable = [
        'name', 'code', 'duration', 'budget_places', 'total_places',
        'description', 'qualification', 'study_forms', 'photo',
        'cost_full_time', 'cost_part_time',
        'where_to_work', 'job_roles',

        // per-form overrides
        'duration_full_time', 'duration_part_time', 'duration_distance',
        'qualification_full_time', 'qualification_part_time', 'qualification_distance',
        'budget_places_full_time', 'budget_places_part_time', 'budget_places_distance',
        'total_places_full_time', 'total_places_part_time', 'total_places_distance',
    ];

    protected $casts = [
        'where_to_work' => 'array',
        'job_roles'     => 'array',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function getAvailableStudyFormsAttribute(): array
    {
        $forms = [];

        $specifiedForms = [];
        if (!empty($this->study_forms)) {
            $specifiedForms = array_map(
                fn($f) => trim(mb_strtolower($f, 'UTF-8')),
                explode(',', $this->study_forms)
            );
        }

        $formMap = [
            'очная'        => ['cost' => $this->cost_full_time, 'suffix' => 'full_time'],
            'заочная'      => ['cost' => $this->cost_part_time, 'suffix' => 'part_time'],
            'очно-заочная' => ['cost' => $this->cost_part_time, 'suffix' => 'distance'],
        ];

        foreach ($formMap as $form => $cfg) {
            if (!empty($specifiedForms) && !in_array($form, $specifiedForms)) continue;
            if (empty($specifiedForms) && is_null($cfg['cost'])) continue;

            $s = $cfg['suffix'];
            $budgetPlaces = $this->{"budget_places_{$s}"} ?? $this->budget_places;
            $totalPlaces  = $this->{"total_places_{$s}"}  ?? $this->total_places ?? $budgetPlaces;
            $paidPlaces   = max(0, (int)$totalPlaces - (int)$budgetPlaces);

            $forms[$form] = [
                'cost'          => (float)($cfg['cost'] ?? 0),
                'duration'      => $this->{"duration_{$s}"}      ?? $this->duration,
                'qualification' => $this->{"qualification_{$s}"} ?? $this->qualification,
                'budget_places' => (int)$budgetPlaces,
                'total_places'  => (int)$totalPlaces,
                'paid_places'   => $paidPlaces,
            ];
        }

        if (empty($forms)) {
            $forms['очная'] = [
                'cost'          => 0,
                'duration'      => $this->duration,
                'qualification' => $this->qualification,
                'budget_places' => (int)$this->budget_places,
                'total_places'  => (int)($this->total_places ?? $this->budget_places),
                'paid_places'   => max(0, (int)($this->total_places ?? 0) - (int)$this->budget_places),
            ];
        }

        return $forms;
    }
}