<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_room_id',
        'user_id',
        'content',
        'type',
        'reply_to_id',
        'edited_content',
        'edited_at',
        'reaction_count',
        'is_pinned',
        'is_deleted',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_pinned' => 'boolean',
        'is_deleted' => 'boolean',
        'edited_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * The attributes that should be appended to arrays.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_edited',
        'is_text',
        'is_image',
        'is_audio',
        'is_file',
        'is_system',
    ];

    // ============ RELATIONSHIPS ============

    /**
     * Get the chat room this message belongs to.
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Get the user who sent this message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the message this message is replying to.
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to_id');
    }

    /**
     * Get all replies to this message.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'reply_to_id');
    }

    /**
     * Get all reactions on this message.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(MessageReaction::class);
    }

    // ============ SCOPES ============

    /**
     * Scope to get messages from a specific chat room.
     */
    public function scopeInRoom($query, $roomId)
    {
        return $query->where('chat_room_id', $roomId);
    }

    /**
     * Scope to get messages from a specific user.
     */
    public function scopeFromUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get only text messages.
     */
    public function scopeTextMessages($query)
    {
        return $query->where('type', 'text');
    }

    /**
     * Scope to get only image messages.
     */
    public function scopeImageMessages($query)
    {
        return $query->where('type', 'image');
    }

    /**
     * Scope to get only file messages.
     */
    public function scopeFileMessages($query)
    {
        return $query->where('type', 'file');
    }

    /**
     * Scope to get only system messages.
     */
    public function scopeSystemMessages($query)
    {
        return $query->where('type', 'system');
    }

    /**
     * Scope to get pinned messages.
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope to get recent messages.
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope to get edited messages.
     */
    public function scopeEdited($query)
    {
        return $query->whereNotNull('edited_at');
    }

    /**
     * Scope to get messages with replies.
     */
    public function scopeWithReplies($query)
    {
        return $query->whereHas('replies');
    }

    // ============ ACCESSORS ============

    /**
     * Check if message has been edited.
     */
    public function getIsEditedAttribute(): bool
    {
        return $this->edited_at !== null;
    }

    /**
     * Check if this is a text message.
     */
    public function getIsTextAttribute(): bool
    {
        return $this->type === 'text';
    }

    /**
     * Check if this is an image message.
     */
    public function getIsImageAttribute(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Check if this is an audio message.
     */
    public function getIsAudioAttribute(): bool
    {
        return $this->type === 'audio';
    }

    /**
     * Check if this is a file message.
     */
    public function getIsFileAttribute(): bool
    {
        return $this->type === 'file';
    }

    /**
     * Check if this is a system message.
     */
    public function getIsSystemAttribute(): bool
    {
        return $this->type === 'system';
    }

    // ============ METHODS ============

    /**
     * Get the display content (edited or original).
     */
    public function getDisplayContent(): string
    {
        return $this->edited_content ?? $this->content;
    }

    /**
     * Get formatted creation time.
     */
    public function getFormattedTime(): string
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Get formatted creation date and time.
     */
    public function getFormattedDateTime(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }
}
