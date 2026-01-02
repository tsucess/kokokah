# 🧪 Chat Authorization - Testing Guide

## Overview

Comprehensive testing guide for chat authorization system including unit tests, feature tests, and manual testing procedures.

---

## 📋 Test Categories

### 1. **Chat Room Access Tests**

#### Admin Access
```php
public function test_admin_can_access_all_rooms()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $room = ChatRoom::factory()->create();
    
    $this->actingAs($admin)
        ->getJson("/api/chatrooms/{$room->id}/messages")
        ->assertStatus(200);
}
```

#### Member Access
```php
public function test_member_can_access_room()
{
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    $room->users()->attach($user->id, ['role' => 'member', 'is_active' => true]);
    
    $this->actingAs($user)
        ->getJson("/api/chatrooms/{$room->id}/messages")
        ->assertStatus(200);
}
```

#### Non-Member Access
```php
public function test_non_member_cannot_access_room()
{
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    
    $this->actingAs($user)
        ->getJson("/api/chatrooms/{$room->id}/messages")
        ->assertStatus(403);
}
```

#### Course Room Access
```php
public function test_enrolled_student_can_access_course_room()
{
    $student = User::factory()->create();
    $course = Course::factory()->create();
    $course->enrollments()->create(['user_id' => $student->id, 'status' => 'active']);
    $room = ChatRoom::factory()->create(['type' => 'course', 'course_id' => $course->id]);
    
    $this->actingAs($student)
        ->getJson("/api/chatrooms/{$room->id}/messages")
        ->assertStatus(200);
}

public function test_instructor_can_access_course_room()
{
    $instructor = User::factory()->create();
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);
    $room = ChatRoom::factory()->create(['type' => 'course', 'course_id' => $course->id]);
    
    $this->actingAs($instructor)
        ->getJson("/api/chatrooms/{$room->id}/messages")
        ->assertStatus(200);
}

public function test_non_enrolled_student_cannot_access_course_room()
{
    $student = User::factory()->create();
    $course = Course::factory()->create();
    $room = ChatRoom::factory()->create(['type' => 'course', 'course_id' => $course->id]);
    
    $this->actingAs($student)
        ->getJson("/api/chatrooms/{$room->id}/messages")
        ->assertStatus(403);
}
```

### 2. **Message Operation Tests**

#### Send Message
```php
public function test_member_can_send_message()
{
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    $room->users()->attach($user->id, ['role' => 'member', 'is_active' => true]);
    
    $this->actingAs($user)
        ->postJson("/api/chatrooms/{$room->id}/messages", [
            'content' => 'Hello!'
        ])
        ->assertStatus(201);
}

public function test_muted_user_cannot_send_message()
{
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    $room->users()->attach($user->id, ['role' => 'member', 'is_active' => true, 'is_muted' => true]);
    
    $this->actingAs($user)
        ->postJson("/api/chatrooms/{$room->id}/messages", [
            'content' => 'Hello!'
        ])
        ->assertStatus(403);
}

public function test_non_member_cannot_send_message()
{
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    
    $this->actingAs($user)
        ->postJson("/api/chatrooms/{$room->id}/messages", [
            'content' => 'Hello!'
        ])
        ->assertStatus(403);
}
```

#### Edit Message
```php
public function test_user_can_edit_own_message()
{
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    $message = ChatMessage::factory()->create(['user_id' => $user->id, 'chat_room_id' => $room->id]);
    
    $this->actingAs($user)
        ->putJson("/api/chatrooms/{$room->id}/messages/{$message->id}", [
            'content' => 'Updated'
        ])
        ->assertStatus(200);
}

public function test_user_cannot_edit_others_message()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $room = ChatRoom::factory()->create();
    $message = ChatMessage::factory()->create(['user_id' => $user1->id, 'chat_room_id' => $room->id]);
    
    $this->actingAs($user2)
        ->putJson("/api/chatrooms/{$room->id}/messages/{$message->id}", [
            'content' => 'Hacked'
        ])
        ->assertStatus(403);
}

public function test_admin_can_edit_any_message()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    $message = ChatMessage::factory()->create(['user_id' => $user->id, 'chat_room_id' => $room->id]);
    
    $this->actingAs($admin)
        ->putJson("/api/chatrooms/{$room->id}/messages/{$message->id}", [
            'content' => 'Admin edit'
        ])
        ->assertStatus(200);
}
```

#### Delete Message
```php
public function test_user_can_delete_own_message()
{
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    $message = ChatMessage::factory()->create(['user_id' => $user->id, 'chat_room_id' => $room->id]);
    
    $this->actingAs($user)
        ->deleteJson("/api/chatrooms/{$room->id}/messages/{$message->id}")
        ->assertStatus(200);
}

public function test_room_creator_can_delete_any_message()
{
    $creator = User::factory()->create();
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create(['created_by' => $creator->id]);
    $message = ChatMessage::factory()->create(['user_id' => $user->id, 'chat_room_id' => $room->id]);
    
    $this->actingAs($creator)
        ->deleteJson("/api/chatrooms/{$room->id}/messages/{$message->id}")
        ->assertStatus(200);
}

public function test_instructor_can_delete_message_in_course_room()
{
    $instructor = User::factory()->create();
    $user = User::factory()->create();
    $course = Course::factory()->create(['instructor_id' => $instructor->id]);
    $room = ChatRoom::factory()->create(['type' => 'course', 'course_id' => $course->id]);
    $message = ChatMessage::factory()->create(['user_id' => $user->id, 'chat_room_id' => $room->id]);
    
    $this->actingAs($instructor)
        ->deleteJson("/api/chatrooms/{$room->id}/messages/{$message->id}")
        ->assertStatus(200);
}
```

### 3. **Room Management Tests**

#### Update Room
```php
public function test_room_creator_can_update_room()
{
    $creator = User::factory()->create();
    $room = ChatRoom::factory()->create(['created_by' => $creator->id]);
    
    $this->actingAs($creator)
        ->putJson("/api/chatrooms/{$room->id}", [
            'name' => 'Updated Room'
        ])
        ->assertStatus(200);
}

public function test_non_creator_cannot_update_room()
{
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create();
    
    $this->actingAs($user)
        ->putJson("/api/chatrooms/{$room->id}", [
            'name' => 'Hacked'
        ])
        ->assertStatus(403);
}
```

#### Manage Members
```php
public function test_room_creator_can_mute_user()
{
    $creator = User::factory()->create();
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create(['created_by' => $creator->id]);
    $room->users()->attach($user->id);
    
    $this->actingAs($creator)
        ->postJson("/api/chatrooms/{$room->id}/members/{$user->id}/mute")
        ->assertStatus(200);
}

public function test_room_creator_can_remove_user()
{
    $creator = User::factory()->create();
    $user = User::factory()->create();
    $room = ChatRoom::factory()->create(['created_by' => $creator->id]);
    $room->users()->attach($user->id);
    
    $this->actingAs($creator)
        ->deleteJson("/api/chatrooms/{$room->id}/members/{$user->id}")
        ->assertStatus(200);
}
```

---

## 🧪 Running Tests

### Run All Authorization Tests
```bash
php artisan test tests/Feature/ChatAuthorizationTest.php
```

### Run Specific Test
```bash
php artisan test tests/Feature/ChatAuthorizationTest.php --filter test_admin_can_access_all_rooms
```

### Run with Coverage
```bash
php artisan test tests/Feature/ChatAuthorizationTest.php --coverage
```

---

## 📊 Test Coverage Checklist

- [ ] Admin can access all rooms
- [ ] Members can access their rooms
- [ ] Non-members cannot access rooms
- [ ] Enrolled students can access course rooms
- [ ] Instructors can access their course rooms
- [ ] Non-enrolled students cannot access course rooms
- [ ] Users can send messages in accessible rooms
- [ ] Muted users cannot send messages
- [ ] Users can edit own messages
- [ ] Users cannot edit others messages
- [ ] Admin can edit any message
- [ ] Users can delete own messages
- [ ] Room creators can delete any message
- [ ] Instructors can delete messages in course rooms
- [ ] Room creators can update rooms
- [ ] Non-creators cannot update rooms
- [ ] Room creators can manage members
- [ ] Instructors can manage course room members
- [ ] Users cannot access archived rooms
- [ ] Users cannot access inactive rooms

---

## 🔍 Manual Testing

### Test 1: Admin Access
1. Login as admin
2. Navigate to any chat room
3. Verify you can view, send, edit, and delete messages
4. Verify you can manage room and members

### Test 2: Member Access
1. Login as regular user
2. Join a chat room
3. Verify you can view and send messages
4. Verify you cannot edit/delete others messages
5. Verify you can edit/delete own messages

### Test 3: Non-Member Access
1. Login as regular user
2. Try to access a room you're not a member of
3. Verify you get 403 Forbidden error

### Test 4: Course Room Access
1. Login as student
2. Enroll in a course
3. Verify you can access course chat room
4. Unenroll from course
5. Verify you cannot access course chat room

### Test 5: Muted User
1. Login as room creator
2. Mute a user
3. Login as muted user
4. Verify you cannot send messages
5. Verify you can still view messages

---

**Status:** ✅ **COMPREHENSIVE TESTING GUIDE COMPLETE!** 🧪


