<?php
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'sender_username',
        'recipient_id',
        'recipient_username',
        'content',
        'date_read',
        'message_sent',
    ];

    protected $dates = [
        'date_read',
        'message_sent',
    ];

    // Define relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
?>