<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultPoint extends Model
{
    protected $table = 'default_points';

    protected $fillable = [
        'what_for',
        'points',
    ];

}
