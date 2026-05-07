<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    protected $fillable = [

        'target_role',

        'title',

        'recommendation',

        'prediction',

        'confidence',

        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
