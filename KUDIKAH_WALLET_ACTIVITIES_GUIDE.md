# Kudikah Wallet Activities - Complete Guide

## Overview
The User Activity page now tracks **7 new Kudikah wallet-specific activities** in addition to the 10 learning activities, providing complete visibility into all user actions in the system.

## 7 New Kudikah Activity Types

### 1. **💰 Wallet Deposit**
- **Type**: `wallet_deposit`
- **Icon**: `fa-wallet`
- **Source**: `Transaction` model
- **Tracked Data**:
  - Amount deposited
  - Currency (Naira - ₦)
  - Deposit status (pending/completed/failed)
  - Timestamp
- **Description Format**: "Deposited ₦5,000.00 to wallet"
- **Status Options**: Pending, Completed, Failed

### 2. **💸 Money Transfer**
- **Type**: `money_transfer`
- **Icon**: `fa-exchange-alt`
- **Source**: `Transaction` model with `related_user_id`
- **Tracked Data**:
  - Amount transferred
  - Recipient/Sender name
  - Transfer direction (sent/received)
  - Currency (Naira - ₦)
  - Timestamp
- **Description Format**:
  - Sent: "Sent ₦2,000.00 to John Doe"
  - Received: "Received ₦2,000.00 from Jane Smith"
- **Status Options**: Pending, Completed, Failed

### 3. **🎁 Reward Earned**
- **Type**: `reward_earned`
- **Icon**: `fa-gift`
- **Source**: `Transaction` model with `reward_type`
- **Reward Types**:
  - Daily Login Reward
  - Study Time Reward
  - Course Completion Bonus
  - Perfect Quiz Score Bonus
  - Study Streak Bonus
  - Referral Bonus
- **Description Format**: "Daily Login Reward: Earned ₦100.00"
- **Status**: Always Completed

### 4. **🏆 Badge Earned**
- **Type**: `badge_earned`
- **Icon**: `fa-medal`
- **Source**: `UserBadge` model
- **Tracked Data**:
  - Badge name
  - Badge icon
  - Earned timestamp
  - Badge category
- **Description Format**: "Earned badge: Course Master"
- **Status**: Always Completed

### 5. **↩️ Refund Processed**
- **Type**: `refund_processed`
- **Icon**: `fa-undo`
- **Source**: `Transaction` model with `type = 'refund'`
- **Tracked Data**:
  - Refund amount
  - Currency (Naira - ₦)
  - Refund status
  - Original transaction reference
- **Description Format**: "Refund processed: ₦5,000.00"
- **Status Options**: Pending, Completed, Failed

### 6. **⭐ Points Earned**
- **Type**: `points_earned`
- **Icon**: `fa-star-half-alt`
- **Source**: `UserPointsHistory` model
- **Tracked Data**:
  - Points earned
  - Reason for earning
  - Points before/after
  - Action type
- **Description Format**: "Earned 50 points - Course Completion"
- **Status**: Always Completed

## Data Models Used

| Activity Type | Model | Key Fields |
|---|---|---|
| Wallet Deposit | Transaction | type, amount, status, currency |
| Money Transfer | Transaction | related_user_id, amount, type |
| Reward Earned | Transaction | reward_type, amount, currency |
| Badge Earned | UserBadge | badge_id, earned_at |
| Refund Processed | Transaction | type='refund', amount |
| Points Earned | UserPointsHistory | points_change, reason |

## Filter Options

### Status Filters
- Completed
- Pending
- Failed
- Active

### Activity Type Filters
All 7 Kudikah activities can be individually filtered

## Search Capabilities
- User name (first + last)
- User email
- Activity description
- Amount (for wallet activities)
- Reward type
- Badge name
- Points earned

## CSV Export
All Kudikah activities are included in CSV export with:
- No, User Name, Activity Type, Description, Timestamp, Status

## Performance Notes
- Each activity type limited to 20 records
- Total Kudikah activities: ~140 records max per load
- Combined with learning activities: ~340 records max
- Pagination: 10 items per page
- Client-side filtering: Instant response

