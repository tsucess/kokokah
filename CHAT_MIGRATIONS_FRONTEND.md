# Chat System - Migrations & Frontend Implementation

## 1. Create Chatrooms Migration

```php
<?php
// database/migrations/2025_01_01_000001_create_chatrooms_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chatrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['general', 'course'])->default('general');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('background_image')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('type');
            $table->index('course_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatrooms');
    }
};
```

## 2. Create Chatroom Members Migration

```php
<?php
// database/migrations/2025_01_01_000002_create_chatroom_members_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chatroom_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['member', 'moderator', 'admin'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('last_read_at')->nullable();
            $table->boolean('is_muted')->default(false);
            $table->timestamps();
            
            $table->unique(['chatroom_id', 'user_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatroom_members');
    }
};
```

## 3. Create Messages Migration

```php
<?php
// database/migrations/2025_01_01_000003_create_messages_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('content');
            $table->enum('message_type', ['text', 'image', 'file', 'system'])->default('text');
            $table->string('file_path')->nullable();
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            $table->index(['chatroom_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
```

## 4. Create Message Reactions Migration

```php
<?php
// database/migrations/2025_01_01_000004_create_message_reactions_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reaction');
            $table->timestamp('created_at')->useCurrent();
            
            $table->unique(['message_id', 'user_id', 'reaction']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_reactions');
    }
};
```

## 5. Frontend - Chatroom List View

```blade
{{-- resources/views/chatrooms/index.blade.php --}}
@extends('layouts.usertemplate')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5>Chatrooms</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($chatrooms as $chatroom)
                            <a href="{{ route('chatrooms.show', $chatroom) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $chatroom->name }}</strong>
                                    @if($unreadCount = $chatroom->getUnreadCount(auth()->user()))
                                        <span class="badge bg-danger">{{ $unreadCount }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    {{ $chatroom->latestMessage?->content ?? 'No messages' }}
                                </small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h5>Select a chatroom to start messaging</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

## 6. Frontend - Chatroom Show View

```blade
{{-- resources/views/chatrooms/show.blade.php --}}
@extends('layouts.usertemplate')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-0 chat-app-container">
        <!-- Sidebar -->
        <div class="col-lg-3 d-none d-lg-block sidebar-left">
            <h6 class="text-muted mb-3">Members ({{ $members->count() }})</h6>
            @foreach($members as $member)
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ $member->profile_photo }}" 
                         alt="{{ $member->first_name }}" 
                         class="rounded-circle me-2" 
                         width="32">
                    <span>{{ $member->first_name }} {{ $member->last_name }}</span>
                </div>
            @endforeach
        </div>

        <!-- Chat Panel -->
        <div class="col-12 col-lg-9 chat-panel-right">
            <div class="chat-header">
                <h5>{{ $chatroom->name }}</h5>
            </div>

            <div class="chat-history" id="chatHistory">
                @foreach($messages as $message)
                    <div class="chat-message @if($message->user_id === auth()->id()) current-user-message @endif">
                        @if($message->user_id !== auth()->id())
                            <img src="{{ $message->user->profile_photo }}" 
                                 alt="Avatar" 
                                 class="message-avatar">
                        @endif
                        <div class="message-content">
                            @if($message->user_id !== auth()->id())
                                <span class="message-user">
                                    {{ $message->user->first_name }} {{ $message->user->last_name }}
                                </span>
                            @endif
                            <p class="mb-1">{{ $message->content }}</p>
                            <span class="message-timestamp">
                                {{ $message->created_at->diffForHumans() }}
                                @if($message->is_edited)
                                    <small>(edited)</small>
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="chat-input-area">
                <form id="messageForm" class="d-flex align-items-center">
                    @csrf
                    <input type="text" 
                           id="messageInput" 
                           class="form-control me-2" 
                           placeholder="Type a message..."
                           required>
                    <button type="submit" class="btn btn-send">
                        Send <i class="bi bi-send-fill ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Echo listener
    Echo.channel('chatroom.{{ $chatroom->id }}')
        .listen('MessageSent', (event) => {
            addMessageToChat(event);
        })
        .listen('UserTyping', (event) => {
            showTypingIndicator(event);
        });

    // Handle message submission
    document.getElementById('messageForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const content = document.getElementById('messageInput').value;
        
        await fetch(`/api/chatrooms/{{ $chatroom->id }}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${document.querySelector('meta[name="api-token"]').content}`
            },
            body: JSON.stringify({ content })
        });

        document.getElementById('messageInput').value = '';
    });

    function addMessageToChat(message) {
        const chatHistory = document.getElementById('chatHistory');
        const messageEl = createMessageElement(message);
        chatHistory.appendChild(messageEl);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function createMessageElement(message) {
        const div = document.createElement('div');
        div.className = 'chat-message';
        div.innerHTML = `
            <img src="${message.user.profile_photo}" class="message-avatar">
            <div class="message-content">
                <span class="message-user">${message.user.first_name} ${message.user.last_name}</span>
                <p class="mb-1">${message.content}</p>
                <span class="message-timestamp">${new Date(message.created_at).toLocaleTimeString()}</span>
            </div>
        `;
        return div;
    }
</script>
@endsection
```

## 7. Seeder - Create General Chatroom

```php
<?php
// database/seeders/ChatroomSeeder.php

namespace Database\Seeders;

use App\Models\Chatroom;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatroomSeeder extends Seeder
{
    public function run(): void
    {
        // Create General chatroom
        $general = Chatroom::create([
            'name' => 'General',
            'slug' => 'general',
            'description' => 'General discussion room for all users',
            'type' => 'general',
            'created_by' => User::where('role', 'admin')->first()->id,
        ]);

        // Add all users to general chatroom
        User::all()->each(function ($user) use ($general) {
            $general->addMember($user);
        });
    }
}
```


