<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_session_id',
        'sender',
        'message'
    ];

    // Relationships
    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class);
    }

    public function user()
    {
        return $this->chatSession->user();
    }

    // Scopes
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('chat_session_id', $sessionId);
    }

    public function scopeFromUser($query)
    {
        return $query->where('sender', 'user');
    }

    public function scopeFromBot($query)
    {
        return $query->where('sender', 'bot');
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Methods
    public function isFromUser()
    {
        return $this->sender === 'user';
    }

    public function isFromBot()
    {
        return $this->sender === 'bot';
    }

    public function getFormattedTime()
    {
        return $this->created_at->format('H:i');
    }
}
