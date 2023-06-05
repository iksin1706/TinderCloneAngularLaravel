<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blockade extends Model
{
    protected $table = 'blockages';
    protected $fillable = ['user_id', 'admin_id', 'reason', 'until'];

    // Relacja z użytkownikiem
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacja z administratorem
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
