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
        'name',
        'code',
        'duration',
        'budget_places',
        'total_places',
        'description',
        'qualification',
        'study_forms',
        'photo',
        'cost_full_time',
        'cost_part_time',
        'where_to_work',
        'job_roles',
    ];

    protected $casts = [
        'where_to_work' => 'array',
        'job_roles' => 'array',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get available study forms based on defined costs.
     */
    public function getAvailableStudyFormsAttribute(): array
    {
        $forms = [];

        if (!empty($this->study_forms)) {
            $specifiedForms = array_map(function ($f) {
                return trim(mb_strtolower($f, 'UTF-8'));
            }, explode(',', $this->study_forms));

            foreach ($specifiedForms as $form) {
                if ($form === 'очная') {
                    $forms['очная'] = $this->cost_full_time ?? 0;
                } elseif ($form === 'заочная') {
                    $forms['заочная'] = $this->cost_part_time ?? 0;
                } elseif ($form === 'очно-заочная') {
                    $forms['очно-заочная'] = $this->cost_part_time ?? 0;
                } elseif ($form === 'дистанционная') {
                    $forms['дистанционная'] = $this->cost_part_time ?? 0;
                }
            }
        } else {
            // Fallback
            if (!is_null($this->cost_full_time)) $forms['очная'] = $this->cost_full_time;
            if (!is_null($this->cost_part_time)) $forms['заочная'] = $this->cost_part_time;
        }

        if (empty($forms)) {
            $forms['очная'] = 0;
        }

        return $forms;
    }
}
