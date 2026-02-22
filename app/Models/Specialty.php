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
        'skills',
        'photo',
        'cost_full_time',
        'cost_part_time',
        'cost_distance',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
