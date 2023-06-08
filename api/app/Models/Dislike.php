<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dislikes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source_user_id',
        'target_user_id',
    ];

    /**
     * Get the source user of the like.
     */
    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }

    /**
     * Get the target user of the like.
     */
    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
