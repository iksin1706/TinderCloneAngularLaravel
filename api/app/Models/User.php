<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'date_of_birth',
        'known_as',
        'created',
        'last_active',
        'gender',
        'introduction',
        'looking_for',
        'interests',
        'city',
        'country',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created' => 'datetime',
        'last_active' => 'datetime',
    ];

    /**
     * Get the photos for the user.
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    public function likedByUsers()
    {
        return $this->hasMany(Like::class, 'source_user_id');
    }

    public function likedUsers()
    {
        return $this->hasMany(Like::class, 'target_user_id');
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function revicedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function age(){
        return now()->diffInYears($this->date_of_birth);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
?>
