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
    ];

    protected $casts = [
        'temperature' => 'float',
        'max_tokens' => 'integer',
    ];
}
