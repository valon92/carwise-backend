<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'description', 'brand', 'model', 'year', 'vin', 'images'
    ];

    protected $casts = [
        'images' => 'array'
    ];
}
