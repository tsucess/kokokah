<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('messages', function ($q) {
            $q->where('created_at', '>=', now()->subHours(24));
        });
    }

    // Methods
    public function addMessage($message, $sender = 'user')
    {
        return $this->messages()->create([
            'sender' => $sender,
            'message' => $message
        ]);
    }

    public function getMessageCount()
    {
        return $this->messages()->count();
    }

    public function getLastActivity()
    {
        $lastMessage = $this->latestMessage;
        return $lastMessage ? $lastMessage->created_at : $this->created_at;
    }
}
