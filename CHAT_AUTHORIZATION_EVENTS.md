# Chat System - Authorization Policies & Events

## 1. ChatroomPolicy

```php
<?php
namespace App\Policies;

use App\Models\Chatroom;
use App\Models\User;

class ChatroomPolicy
{
    public function viewAny(User $user)
    {
        return true; // All authenticated users
    }

    public function view(User $user, Chatroom $chatroom)
    {
        // General chatroom: all users
        if ($chatroom->type === 'general') {
            return true;
        }

        // Course chatroom: enrolled students + instructor + admin
        if ($chatroom->type === 'course') {
            if ($user->role === 'admin') {
                return true;
            }

            if ($chatroom->course_id) {
                return $user->enrollments()
                    ->where('course_id', $chatroom->course_id)
                    ->exists() || 
                    $chatroom->course->instructor_id === $user->id;
            }
        }

        // Check if user is member
        return $chatroom->isMember($user);
    }

    public function create(User $user)
    {
        return $user->role === 'admin' || $user->role === 'instructor';
    }

    public function update(User $user, Chatroom $chatroom)
    {
        // Creator or admin
        return $user->id === $chatroom->created_by || $user->role === 'admin';
    }

    public function delete(User $user, Chatroom $chatroom)
    {
        return $user->id === $chatroom->created_by || $user->role === 'admin';
    }

    public function manageMember(User $user, Chatroom $chatroom)
    {
        if ($user->role === 'admin') {
            return true;
        }

        $member = $chatroom->members()
            ->where('user_id', $user->id)
            ->first();

        return $member && in_array($member->pivot->role, ['moderator', 'admin']);
    }

    public function sendMessage(User $user, Chatroom $chatroom)
    {
        return $this->view($user, $chatroom);
    }
}
```

## 2. MessagePolicy

```php
<?php
namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function view(User $user, Message $message)
    {
        return $user->can('view', $message->chatroom);
    }

    public function create(User $user)
    {
        return true; // Checked in ChatroomPolicy
    }

    public function update(User $user, Message $message)
    {
        // Own message or admin
        return $user->id === $message->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Message $message)
    {
        // Own message, moderator+, or admin
        if ($user->id === $message->user_id) {
            return true;
        }

        if ($user->role === 'admin') {
            return true;
        }

        $member = $message->chatroom->members()
            ->where('user_id', $user->id)
            ->first();

        return $member && in_array($member->pivot->role, ['moderator', 'admin']);
    }
}
```

## 3. Broadcasting Events

### MessageSent Event
```php
<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message) {}

    public function broadcastOn()
    {
        return new Channel('chatroom.' . $this->message->chatroom_id);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'chatroom_id' => $this->message->chatroom_id,
            'user' => $this->message->user->only(['id', 'first_name', 'last_name', 'profile_photo']),
            'content' => $this->message->content,
            'message_type' => $this->message->message_type,
            'created_at' => $this->message->created_at,
        ];
    }
}
```

### MessageEdited Event
```php
<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageEdited implements ShouldBroadcast
{
    public function __construct(public Message $message) {}

    public function broadcastOn()
    {
        return new Channel('chatroom.' . $this->message->chatroom_id);
    }

    public function broadcastAs()
    {
        return 'message.edited';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'edited_at' => $this->message->edited_at,
        ];
    }
}
```

### MessageDeleted Event
```php
<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageDeleted implements ShouldBroadcast
{
    public function __construct(public Message $message) {}

    public function broadcastOn()
    {
        return new Channel('chatroom.' . $this->message->chatroom_id);
    }

    public function broadcastAs()
    {
        return 'message.deleted';
    }

    public function broadcastWith()
    {
        return ['id' => $this->message->id];
    }
}
```

### ReactionAdded Event
```php
<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReactionAdded implements ShouldBroadcast
{
    public function __construct(
        public Message $message,
        public $userId,
        public $reaction
    ) {}

    public function broadcastOn()
    {
        return new Channel('chatroom.' . $this->message->chatroom_id);
    }

    public function broadcastAs()
    {
        return 'reaction.added';
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->message->id,
            'user_id' => $this->userId,
            'reaction' => $this->reaction,
        ];
    }
}
```

### ReactionRemoved Event
```php
<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReactionRemoved implements ShouldBroadcast
{
    public function __construct(
        public Message $message,
        public $userId,
        public $reaction
    ) {}

    public function broadcastOn()
    {
        return new Channel('chatroom.' . $this->message->chatroom_id);
    }

    public function broadcastAs()
    {
        return 'reaction.removed';
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->message->id,
            'user_id' => $this->userId,
            'reaction' => $this->reaction,
        ];
    }
}
```

## 4. Event Listeners

### UpdateLastReadListener
```php
<?php
namespace App\Listeners;

use App\Events\MessageSent;

class UpdateLastReadListener
{
    public function handle(MessageSent $event)
    {
        $event->message->chatroom->members()
            ->where('user_id', $event->message->user_id)
            ->first()
            ->pivot
            ->updateLastRead();
    }
}
```


