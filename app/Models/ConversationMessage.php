<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'attachments',
        'is_pinned',
        'is_edited',
        'is_helpful',
        'edited_at'
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_pinned' => 'boolean',
        'is_edited' => 'boolean',
        'is_helpful' => 'boolean',
        'edited_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    // Methods
    public function pin()
    {
        $this->update(['is_pinned' => true]);
        return $this;
    }

    public function unpin()
    {
        $this->update(['is_pinned' => false]);
        return $this;
    }

    public function edit($newMessage)
    {
        $this->update([
            'message' => $newMessage,
            'is_edited' => true,
            'edited_at' => now()
        ]);
        return $this;
    }

    public function markAsHelpful()
    {
        $this->update(['is_helpful' => true]);

        // Award badge if applicable
        $this->awardHelpfulBadge();

        return $this;
    }

    public function markAsNotHelpful()
    {
        $this->update(['is_helpful' => false]);
        return $this;
    }

    private function awardHelpfulBadge()
    {
        $user = $this->user;
        $helpfulCount = ConversationMessage::where('user_id', $user->id)
            ->where('is_helpful', true)
            ->count();

        // Check for helpful post badges
        $badgeCriteria = [
            'helpful_posts:5' => 5,
            'helpful_posts:20' => 20
        ];

        foreach ($badgeCriteria as $criteria => $required) {
            if ($helpfulCount >= $required) {
                $badge = \App\Models\Badge::where('criteria', $criteria)->first();
                if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);
                }
            }
        }
    }
}

