<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageReaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_message_id',
        'user_id',
        'reaction',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    // ============ RELATIONSHIPS ============

    /**
     * Get the message this reaction belongs to.
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'chat_message_id');
    }

    /**
     * Get the user who added this reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ============ SCOPES ============

    /**
     * Scope to get reactions for a specific message.
     */
    public function scopeForMessage($query, $messageId)
    {
        return $query->where('chat_message_id', $messageId);
    }

    /**
     * Scope to get reactions from a specific user.
     */
    public function scopeFromUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get reactions of a specific type.
     */
    public function scopeWithReaction($query, $reaction)
    {
        return $query->where('reaction', $reaction);
    }

    /**
     * Scope to get reactions grouped by emoji.
     */
    public function scopeGroupedByReaction($query)
    {
        return $query->selectRaw('reaction, COUNT(*) as count')
                     ->groupBy('reaction');
    }

    // ============ METHODS ============

    /**
     * Get emoji representation of the reaction.
     */
    public function getEmojiAttribute(): string
    {
        return $this->reaction;
    }

    /**
     * Get human-readable reaction name.
     */
    public function getReactionNameAttribute(): string
    {
        $reactionNames = [
            '👍' => 'Like',
            '❤️' => 'Love',
            '😂' => 'Haha',
            '😮' => 'Wow',
            '😢' => 'Sad',
            '😠' => 'Angry',
            '🔥' => 'Fire',
            '🎉' => 'Party',
            '✨' => 'Sparkle',
            '🚀' => 'Rocket',
        ];

        return $reactionNames[$this->reaction] ?? 'Reaction';
    }
}

