<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

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
        'sender_id',
        'sender_username',
        'recipient_id',
        'recipient_username',
        'content',
        'date_read',
        'message_sent',
        'sender_deleted',
        'recipient_deleted',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_read' => 'datetime',
        'message_sent' => 'datetime',
        'sender_deleted' => 'boolean',
        'recipient_deleted' => 'boolean',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of the message.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
?>