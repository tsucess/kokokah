# Chat System - Controllers & Services Implementation

## 1. ChatroomController

```php
<?php
namespace App\Http\Controllers;

use App\Models\Chatroom;
use App\Models\User;
use App\Services\ChatroomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatroomController extends Controller
{
    public function __construct(private ChatroomService $service) {}

    // List all accessible chatrooms
    public function index()
    {
        $user = Auth::user();
        $chatrooms = $user->chatrooms()
            ->with('latestMessage', 'creator')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('chatrooms.index', compact('chatrooms'));
    }

    // Show single chatroom with messages
    public function show(Chatroom $chatroom)
    {
        $this->authorize('view', $chatroom);
        
        $messages = $chatroom->messages()
            ->with('user', 'reactions')
            ->paginate(50);

        $members = $chatroom->members()
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.profile_photo')
            ->get();

        return view('chatrooms.show', compact('chatroom', 'messages', 'members'));
    }

    // Create new chatroom (admin only)
    public function store(Request $request)
    {
        $this->authorize('create', Chatroom::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'background_image' => 'nullable|image|max:2048',
        ]);

        $chatroom = $this->service->createChatroom(
            $validated,
            Auth::id()
        );

        return redirect()->route('chatrooms.show', $chatroom)
            ->with('success', 'Chatroom created successfully');
    }

    // Update chatroom
    public function update(Request $request, Chatroom $chatroom)
    {
        $this->authorize('update', $chatroom);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'background_image' => 'nullable|image|max:2048',
        ]);

        $this->service->updateChatroom($chatroom, $validated);

        return back()->with('success', 'Chatroom updated');
    }

    // Add member to chatroom
    public function addMember(Request $request, Chatroom $chatroom)
    {
        $this->authorize('manageMember', $chatroom);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'in:member,moderator,admin'
        ]);

        $this->service->addMember($chatroom, $validated['user_id'], $validated['role'] ?? 'member');

        return back()->with('success', 'Member added');
    }

    // Remove member from chatroom
    public function removeMember(Chatroom $chatroom, User $user)
    {
        $this->authorize('manageMember', $chatroom);

        $this->service->removeMember($chatroom, $user->id);

        return back()->with('success', 'Member removed');
    }
}
```

## 2. MessageController

```php
<?php
namespace App\Http\Controllers;

use App\Models\Chatroom;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct(private MessageService $service) {}

    // Store new message
    public function store(Request $request, Chatroom $chatroom)
    {
        $this->authorize('sendMessage', $chatroom);

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'message_type' => 'in:text,image,file',
            'file' => 'nullable|file|max:10240',
        ]);

        $message = $this->service->createMessage(
            $chatroom,
            Auth::user(),
            $validated
        );

        // Broadcast event for real-time update
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json($message->load('user', 'reactions'), 201);
    }

    // Edit message
    public function update(Request $request, Message $message)
    {
        $this->authorize('update', $message);

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $message = $this->service->updateMessage($message, $validated['content']);

        broadcast(new \App\Events\MessageEdited($message))->toOthers();

        return response()->json($message);
    }

    // Delete message
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $this->service->deleteMessage($message);

        broadcast(new \App\Events\MessageDeleted($message))->toOthers();

        return response()->json(['success' => true]);
    }

    // Add reaction to message
    public function addReaction(Request $request, Message $message)
    {
        $validated = $request->validate([
            'reaction' => 'required|string|max:50',
        ]);

        $this->service->addReaction($message, Auth::user(), $validated['reaction']);

        broadcast(new \App\Events\ReactionAdded($message))->toOthers();

        return response()->json(['success' => true]);
    }

    // Remove reaction from message
    public function removeReaction(Message $message, $reaction)
    {
        $this->service->removeReaction($message, Auth::user(), $reaction);

        broadcast(new \App\Events\ReactionRemoved($message))->toOthers();

        return response()->json(['success' => true]);
    }
}
```

## 3. ChatroomService

```php
<?php
namespace App\Services;

use App\Models\Chatroom;
use App\Models\User;
use Illuminate\Support\Str;

class ChatroomService
{
    public function createChatroom(array $data, $createdBy)
    {
        $data['slug'] = Str::slug($data['name']);
        $data['created_by'] = $createdBy;
        $data['type'] = $data['type'] ?? 'general';

        if (isset($data['background_image'])) {
            $data['background_image'] = $data['background_image']
                ->store('chatrooms', 'public');
        }

        return Chatroom::create($data);
    }

    public function updateChatroom(Chatroom $chatroom, array $data)
    {
        if (isset($data['background_image'])) {
            $data['background_image'] = $data['background_image']
                ->store('chatrooms', 'public');
        }

        return $chatroom->update($data);
    }

    public function addMember(Chatroom $chatroom, $userId, $role = 'member')
    {
        return $chatroom->members()->attach($userId, [
            'role' => $role,
            'joined_at' => now()
        ]);
    }

    public function removeMember(Chatroom $chatroom, $userId)
    {
        return $chatroom->members()->detach($userId);
    }

    public function createCourseChat room(Chatroom $course)
    {
        return Chatroom::create([
            'name' => $course->title . ' Chat',
            'slug' => $course->slug . '-chat',
            'description' => 'Discussion room for ' . $course->title,
            'type' => 'course',
            'course_id' => $course->id,
            'created_by' => $course->instructor_id,
        ]);
    }
}
```

## 4. MessageService

```php
<?php
namespace App\Services;

use App\Models\Chatroom;
use App\Models\Message;
use App\Models\User;

class MessageService
{
    public function createMessage(Chatroom $chatroom, User $user, array $data)
    {
        $message = $chatroom->messages()->create([
            'user_id' => $user->id,
            'content' => $data['content'],
            'message_type' => $data['message_type'] ?? 'text',
        ]);

        if (isset($data['file'])) {
            $message->file_path = $data['file']->store('messages', 'public');
            $message->save();
        }

        // Update last_read_at for sender
        $chatroom->members()
            ->where('user_id', $user->id)
            ->first()
            ->pivot
            ->updateLastRead();

        return $message;
    }

    public function updateMessage(Message $message, $content)
    {
        $message->content = $content;
        $message->markAsEdited();
        return $message;
    }

    public function deleteMessage(Message $message)
    {
        return $message->softDelete();
    }

    public function addReaction(Message $message, User $user, $reaction)
    {
        return $message->addReaction($user, $reaction);
    }

    public function removeReaction(Message $message, User $user, $reaction)
    {
        return $message->removeReaction($user, $reaction);
    }
}
```


