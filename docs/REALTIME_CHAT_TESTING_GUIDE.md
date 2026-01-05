# Real-time Chat - Testing Guide

## 🧪 Overview

Complete testing guide for the real-time chat system including unit tests, feature tests, and manual testing procedures.

---

## 📋 Test Files Created

### Feature Tests
1. **ChatMessageControllerTest.php** (264 lines)
   - Message fetching with pagination
   - Sending messages
   - Message editing
   - Message deletion
   - Message filtering
   - Authorization checks

2. **RealtimeChatTest.php** (200+ lines)
   - Broadcasting events
   - Message updates
   - Chat room statistics
   - User last read timestamps
   - Message metadata

3. **ChatReactionsTest.php** (200+ lines)
   - Adding reactions
   - Removing reactions
   - Reaction counts
   - Reaction summaries
   - Authorization checks

---

## 🚀 Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test File
```bash
php artisan test tests/Feature/ChatMessageControllerTest.php
php artisan test tests/Feature/RealtimeChatTest.php
php artisan test tests/Feature/ChatReactionsTest.php
```

### Run Specific Test Method
```bash
php artisan test tests/Feature/ChatMessageControllerTest.php --filter test_send_message
```

### Run Tests with Coverage
```bash
php artisan test --coverage
```

### Run Tests in Parallel
```bash
php artisan test --parallel
```

---

## 📊 Test Coverage

### ChatMessageControllerTest (12 tests)
- ✅ Fetch messages with pagination
- ✅ Send message
- ✅ Non-member cannot send message
- ✅ Muted user cannot send message
- ✅ Reply to message
- ✅ Update own message
- ✅ Cannot update others message
- ✅ Delete own message
- ✅ Filter messages by type
- ✅ Message validation
- ✅ Get specific message
- ✅ Unauthenticated access denied

### RealtimeChatTest (10 tests)
- ✅ MessageSent event dispatched
- ✅ MessageSent event contains correct data
- ✅ Message update triggers broadcast
- ✅ Message deletion triggers broadcast
- ✅ Chat room member count updated
- ✅ Chat room message count updated
- ✅ Last message timestamp updated
- ✅ User last read timestamp updated
- ✅ Message with reply_to relationship
- ✅ Message metadata stored

### ChatReactionsTest (10 tests)
- ✅ Add reaction to message
- ✅ Remove reaction from message
- ✅ Get message reactions
- ✅ Reaction count is accurate
- ✅ User cannot add duplicate reaction
- ✅ User can add different reactions
- ✅ Reaction validation
- ✅ Non-member cannot add reaction
- ✅ Reaction summary by emoji

---

## 🧪 Manual Testing Procedures

### Test 1: Message Sending
1. Open chat in browser
2. Type message in input field
3. Click send button
4. Verify message appears instantly
5. Check message in database

### Test 2: Real-time Updates
1. Open chat in two browser windows
2. Send message in first window
3. Verify message appears instantly in second window
4. No page refresh required

### Test 3: Message Editing
1. Send a message
2. Click edit button
3. Modify message content
4. Click save
5. Verify edit appears in real-time in all windows

### Test 4: Message Deletion
1. Send a message
2. Click delete button
3. Confirm deletion
4. Verify message disappears in real-time in all windows

### Test 5: Typing Indicator
1. Open chat in two browser windows
2. Start typing in first window
3. Verify typing indicator appears in second window
4. Stop typing
5. Verify typing indicator disappears

### Test 6: Emoji Reactions
1. Send a message
2. Click reaction button
3. Select emoji
4. Verify reaction appears on message
5. Click reaction again to remove
6. Verify reaction disappears

### Test 7: Message Replies
1. Send a message
2. Click reply button on another message
3. Type reply content
4. Send reply
5. Verify reply shows original message context

### Test 8: Authorization
1. Try to access chat as non-member
2. Verify access denied
3. Try to edit another user's message
4. Verify edit denied
5. Try to delete another user's message
6. Verify delete denied

### Test 9: Pagination
1. Load chat with many messages
2. Scroll to top
3. Load previous messages
4. Verify pagination works correctly
5. Check message order

### Test 10: Message Filtering
1. Send messages of different types
2. Filter by message type
3. Verify only selected type appears
4. Clear filter
5. Verify all messages appear

---

## 🔍 Test Assertions

### Common Assertions Used

```php
// Status codes
$response->assertStatus(200);
$response->assertStatus(201);
$response->assertStatus(403);
$response->assertStatus(404);
$response->assertStatus(422);

// JSON structure
$response->assertJsonStructure(['success', 'data', 'message']);

// JSON path values
$response->assertJsonPath('success', true);
$response->assertJsonPath('data.id', 1);

// Database assertions
$this->assertDatabaseHas('chat_messages', ['content' => 'Hello']);
$this->assertDatabaseMissing('chat_messages', ['is_deleted' => false]);

// Count assertions
$response->assertJsonCount(50, 'data');

// Event assertions
Event::assertDispatched(MessageSent::class);
```

---

## 📈 Test Metrics

| Metric | Value |
|--------|-------|
| Total Tests | 32 |
| Test Files | 3 |
| Lines of Test Code | 600+ |
| Coverage Target | 80%+ |
| Execution Time | < 30 seconds |

---

## 🐛 Debugging Tests

### Enable Debug Output
```bash
php artisan test --verbose
```

### Run Single Test with Output
```bash
php artisan test tests/Feature/ChatMessageControllerTest.php::test_send_message --verbose
```

### Check Test Database
```bash
# Tests use in-memory SQLite by default
# To use MySQL for testing, update phpunit.xml
```

### View Test Failures
```bash
php artisan test --stop-on-failure
```

---

## 🔧 Test Configuration

### phpunit.xml
```xml
<phpunit>
    <testsuites>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

### .env.testing
```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
BROADCAST_DRIVER=log
QUEUE_CONNECTION=sync
```

---

## ✅ Pre-deployment Checklist

- [ ] All tests passing
- [ ] Code coverage > 80%
- [ ] No deprecation warnings
- [ ] No SQL errors
- [ ] Broadcasting working
- [ ] Real-time updates working
- [ ] Authorization checks passing
- [ ] Validation working
- [ ] Error handling working
- [ ] Database migrations passing

---

## 📚 Related Documentation

- [REALTIME_CHAT_IMPLEMENTATION.md](./REALTIME_CHAT_IMPLEMENTATION.md)
- [REALTIME_CHAT_ADVANCED_FEATURES.md](./REALTIME_CHAT_ADVANCED_FEATURES.md)
- [REALTIME_CHAT_EVENTS.md](./REALTIME_CHAT_EVENTS.md)

---

## 🚀 Next Steps

1. Run all tests: `php artisan test`
2. Check coverage: `php artisan test --coverage`
3. Fix any failing tests
4. Deploy to staging
5. Run manual tests
6. Deploy to production


