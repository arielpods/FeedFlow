<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $table    = 'survey_questions';
    public $timestamps  = true;
    
    protected $fillable = [
        'survey_id',
        'title', 
        'question_type', // text, radio, checkbox, scale
        'options',       // Stocké en JSON
    ];

    // C'est ici que JSON truc machin est converti en tableau automatiquement
    protected $casts = [
        'options' => 'array',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }



    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
