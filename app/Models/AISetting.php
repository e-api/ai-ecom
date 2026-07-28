<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AISetting extends Model
{
    //
    use HasFactory;

    protected $table = 'ai_settings';

    protected $fillable = [
        'openai_model',
        'temperature',
        'max_tokens',
        'writing_tone',
        'description_length',
        'short_description_length',
        'keyword_count',
        'system_prompt',
    ];

    protected $casts = [
        'temperature' => 'float',
        'max_tokens' => 'integer',
        'description_length' => 'integer',
        'short_description_length' => 'integer',
        'keyword_count' => 'integer',
    ];
}
