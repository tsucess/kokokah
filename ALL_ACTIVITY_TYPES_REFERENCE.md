# Complete Activity Types Reference - All 17 Activities

## Master Reference Table

| # | Activity Type | Icon | Model | Status | Description |
|---|---|---|---|---|---|
| **LEARNING ACTIVITIES** |
| 1 | User Registration | fa-user-plus | User | Completed | New user account creation |
| 2 | Course Created | fa-book | Course | Completed | Instructor creates course |
| 3 | Course Enrollment | fa-graduation-cap | Enrollment | Active/Completed/Pending | Student enrolls in course |
| 4 | Lesson Completed | fa-check-circle | LessonCompletion | Completed | Student completes lesson |
| 5 | Quiz Attempted | fa-clipboard-list | QuizAttempt | Completed/Pending | Student takes quiz |
| 6 | Course Reviewed | fa-star | CourseReview | Completed | Student leaves review |
| 7 | Course Completed | fa-trophy | Enrollment | Completed | Student completes course |
| 8 | Payment Completed | fa-credit-card | Payment | Completed/Pending/Failed | Course purchase |
| 9 | Learning Path Enrolled | fa-road | LearningPathEnrollment | Active/Completed | Student enrolls in path |
| 10 | Certificate Issued | fa-certificate | Certificate | Completed | Certificate awarded |
| **KUDIKAH WALLET ACTIVITIES** |
| 11 | Wallet Deposit | fa-wallet | Transaction | Pending/Completed/Failed | User deposits to wallet |
| 12 | Money Transfer | fa-exchange-alt | Transaction | Pending/Completed/Failed | User transfers money |
| 13 | Reward Earned | fa-gift | Transaction | Completed | User earns reward |
| 14 | Badge Earned | fa-medal | UserBadge | Completed | User earns badge |
| 15 | Refund Processed | fa-undo | Transaction | Pending/Completed/Failed | User receives refund |
| 16 | Points Earned | fa-star-half-alt | UserPointsHistory | Completed | User earns points |

## Activity Type Codes

### Learning Activities
```
user_registered
course_created
course_enrolled
lesson_completed
quiz_attempted
course_reviewed
course_completed
payment_completed
learning_path_enrolled
certificate_issued
```

### Kudikah Wallet Activities
```
wallet_deposit
money_transfer
reward_earned
badge_earned
refund_processed
points_earned
```

## Status Values

| Status | Color | Meaning |
|--------|-------|---------|
| completed | Green (#28a745) | Activity finished successfully |
| pending | Yellow (#ffc107) | Activity in progress |
| failed | Red (#dc3545) | Activity did not succeed |
| active | Cyan (#17a2b8) | Activity currently ongoing |
| inactive | Gray (#6c757d) | Activity not active |

## Filter Groups

### Status Filters
- completed
- pending
- failed
- active
- inactive

### Learning Activity Filters
- user_registered
- course_created
- course_enrolled
- lesson_completed
- quiz_attempted
- course_reviewed
- course_completed
- learning_path_enrolled
- certificate_issued
- payment_completed

### Wallet Activity Filters
- wallet_deposit
- money_transfer
- reward_earned
- badge_earned
- refund_processed
- points_earned

## Performance Metrics

- **Records per activity type**: 20
- **Items per page**: 10
- **Total activities per load**: ~340 max
- **Filter response**: Instant (client-side)
- **Search response**: Instant (client-side)
- **Database queries**: Optimized with eager loading

