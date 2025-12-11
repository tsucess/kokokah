# Quiz Type Mapping Fix - Complete

## Issue
When selecting "Alternative Choice" in the quiz modal, the database was storing the quiz type as "theory" instead of "mcq" (multiple choice).

## Root Cause
The frontend code had incorrect logic for mapping quiz types:

```javascript
// WRONG - This maps alternative-choice to 'theory'
type: quizType === 'multiple-choice' ? 'mcq' : 'theory'
```

This meant:
- `multiple-choice` → `mcq` ✅ (correct)
- `alternative-choice` → `theory` ❌ (wrong)

## Understanding Quiz Types

The system has **two UI options** but **one database type**:

### Frontend Options (UI)
- **Multiple Choice**: Traditional multiple choice questions
- **Alternative Choice**: True/False or Yes/No type questions

### Database Types
- **mcq**: Multiple Choice Questions (covers both UI options)
- **theory**: Essay/Short answer questions (not used in current UI)

Both "Multiple Choice" and "Alternative Choice" are variations of **MCQ** questions, so they should both map to `mcq` in the database.

## Fix Applied

**File**: `resources/views/admin/editsubject.blade.php` (Lines 3049-3063)

**Before**:
```javascript
const quizData = {
    title: questionText,
    type: quizType === 'multiple-choice' ? 'mcq' : 'theory',
    questions: [{
        question_text: questionText,
        type: quizType === 'multiple-choice' ? 'mcq' : 'theory',
        ...
    }],
    ...
};
```

**After**:
```javascript
// Both 'multiple-choice' and 'alternative-choice' are MCQ types
const quizData = {
    title: questionText,
    type: 'mcq',
    questions: [{
        question_text: questionText,
        type: 'mcq',
        ...
    }],
    ...
};
```

## Result

✅ **Multiple Choice** → Stored as `mcq` in database
✅ **Alternative Choice** → Stored as `mcq` in database (FIXED)
✅ Both question types are now correctly identified as multiple choice questions

## Database Impact

- Existing quizzes with type `theory` will remain unchanged
- New quizzes created with "Alternative Choice" will now correctly store as `mcq`
- The distinction between multiple choice and alternative choice is maintained in the UI/options, not in the database type

## Testing

To verify the fix:
1. Create a quiz with "Multiple Choice" type
2. Check database - should show `type: 'mcq'` ✅
3. Create a quiz with "Alternative Choice" type
4. Check database - should show `type: 'mcq'` ✅ (previously showed `theory`)

## Status: ✅ COMPLETE

