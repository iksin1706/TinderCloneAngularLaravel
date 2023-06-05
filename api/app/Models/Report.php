<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporting_id',
        'reported_id',
        'reason',
    ];

    public function reportingUser()
    {
        return $this->belongsTo(User::class, 'reporting_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_id');
    }
}

