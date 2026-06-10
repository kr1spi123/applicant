<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'specialty_id',
        'study_form',
        'full_name',
        'phone',
        'email',
        'birthdate',
        'street',
        'house',
        'school',
        'graduation_year',
        'certificate_file',
        'ege_score',
        'certificate_score',
        'has_achievements',
        'benefits',
        'benefit_proof',
        'rating',
        'status',
        'qr_code_path',
    ];

    protected $casts = [
        'birthdate'     => 'date',
        'has_achievements' => 'boolean',
        'benefits'      => 'array',
        'benefit_proof' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }
}


