# Chat System - Eloquent Models Implementation

## 1. Chatroom Model

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chatroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'type', 'course_id',
        'background_image', 'created_by', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'chatroom_members')
                    ->withPivot('role', 'joined_at', 'last_read_at', 'is_muted')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class)
                    ->where('is_deleted', false)
                    ->orderBy('created_at', 'desc');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)
                    ->where('is_deleted', false)
                    ->latestOfMany();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGeneral($query)
    {
        return $query->where('type', 'general');
    }

    public function scopeCourse($query)
    {
        return $query->where('type', 'course');
    }

    // Methods
    public function addMember(User $user, $role = 'member')
    {
        return $this->members()->attach($user->id, [
            'role' => $role,
            'joined_at' => now()
        ]);
    }

    public function removeMember(User $user)
    {
        return $this->members()->detach($user->id);
    }

    public function isMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function getUnreadCount(User $user)
    {
        $lastRead = $this->members()
            ->where('user_id', $user->id)
            ->first()
            ->pivot
            ->last_read_at;

        return $this->messages()
            ->where('created_at', '>', $lastRead ?? $this->created_at)
            ->count();
    }
}
```

## 2. Message Model

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chatroom_id', 'user_id', 'content', 'message_type',
        'file_path', 'is_edited', 'edited_at', 'is_deleted', 'deleted_at'
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'is_deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function chatroom()
    {
        return $this->belongsTo(Chatroom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }

    // Scopes
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Methods
    public function markAsEdited()
    {
        $this->update([
            'is_edited' => true,
            'edited_at' => now()
        ]);
    }

    public function softDelete()
    {
        $this->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);
    }

    public function addReaction(User $user, $reaction)
    {
        return $this->reactions()->updateOrCreate(
            ['user_id' => $user->id, 'reaction' => $reaction],
            ['reaction' => $reaction]
        );
    }

    public function removeReaction(User $user, $reaction)
    {
        return $this->reactions()
            ->where('user_id', $user->id)
            ->where('reaction', $reaction)
            ->delete();
    }
}
```

## 3. ChatroomMember Model (Pivot)

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChatroomMember extends Pivot
{
    protected $table = 'chatroom_members';
    
    protected $fillable = [
        'chatroom_id', 'user_id', 'role',
        'joined_at', 'last_read_at', 'is_muted'
    ];

    protected $casts = [
        'is_muted' => 'boolean',
        'joined_at' => 'datetime',
        'last_read_at' => 'datetime',
    ];

    public function isModerator()
    {
        return $this->role === 'moderator';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function updateLastRead()
    {
        $this->update(['last_read_at' => now()]);
    }
}
```

## 4. MessageReaction Model

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageReaction extends Model
{
    use HasFactory;

    protected $fillable = ['message_id', 'user_id', 'reaction'];
    public $timestamps = false;

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

## 5. Update User Model

Add these relationships to existing User model:

```php
public function chatrooms()
{
    return $this->belongsToMany(Chatroom::class, 'chatroom_members')
                ->withPivot('role', 'joined_at', 'last_read_at', 'is_muted')
                ->withTimestamps();
}

public function messages()
{
    return $this->hasMany(Message::class);
}

public function messageReactions()
{
    return $this->hasMany(MessageReaction::class);
}
```

## 6. Update Course Model

Add this relationship to existing Course model:

```php
public function chatroom()
{
    return $this->hasOne(Chatroom::class);
}
```


