# Chat Message 500 Error Fix

## Problem
When sending a photo to the chatroom, the API returned a 500 Internal Server Error:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'is_helpful' in 'where clause'
```

The error occurred in the badge system when checking if a user qualifies for the "helpful_posts" badge.

## Root Cause
The `PointsAndBadgesService.php` was trying to query a non-existent `is_helpful` column on the `ChatMessage` model:

```php
// BEFORE (Line 236)
$helpful = \App\Models\ChatMessage::where('user_id', $user->id)
    ->where('is_helpful', true)  // ❌ Column doesn't exist!
    ->count();
```

The `chat_messages` table schema does NOT include an `is_helpful` column. The actual columns are:
- `id`, `chat_room_id`, `user_id`, `content`, `type`, `reply_to_id`
- `edited_content`, `edited_at`, `reaction_count`, `is_pinned`, `is_deleted`
- `metadata`, `created_at`, `updated_at`, `deleted_at`

## Solution
Changed the query to use `reaction_count` as a proxy for helpful posts (posts with reactions are considered helpful):

```php
// AFTER (Lines 234-240)
case 'helpful_posts':
    // Count posts with reactions (as a proxy for helpful posts)
    // Since chat_messages doesn't have is_helpful column, we use reaction_count
    $helpful = \App\Models\ChatMessage::where('user_id', $user->id)
        ->where('reaction_count', '>', 0)
        ->count();
    return $helpful >= (int)$criteriaValue;
```

## File Changed
- **app/Services/PointsAndBadgesService.php** (Lines 234-240)

## Testing
✅ Query now executes without errors
✅ Badge system can properly check "helpful_posts" criteria
✅ Photo sending to chatroom works correctly

## Impact
- Fixes 500 error when sending messages to chatroom
- Allows badge system to properly evaluate "helpful_posts" criteria
- Uses reaction_count as a meaningful metric for helpful posts

