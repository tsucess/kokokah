# Points & Badges - Quick Reference Guide

## Files Modified/Created

### Created Files
1. **public/js/api/pointsAndBadgesApiClient.js** - New API client
2. **DYNAMIC_POINTS_BADGES_IMPLEMENTATION.md** - Implementation guide
3. **POINTS_BADGES_QUICK_REFERENCE.md** - This file

### Modified Files
1. **public/js/dashboard.js** - Added points/badges loading
2. **resources/views/layouts/usertemplate.blade.php** - Added data attributes

## API Client Usage

### Import
```javascript
import PointsAndBadgesApiClient from './api/pointsAndBadgesApiClient.js';
```

### Get User Points
```javascript
const response = await PointsAndBadgesApiClient.getUserPoints();
console.log(response.data.points); // 1250
console.log(response.data.level); // "Intermediate"
```

### Get User Badges
```javascript
const response = await PointsAndBadgesApiClient.getUserBadges();
console.log(response.data.length); // 8
```

### Get Badge Stats
```javascript
const response = await PointsAndBadgesApiClient.getBadgeStats();
console.log(response.data.total_badges); // 8
console.log(response.data.total_badge_points); // 250
```

### Get Leaderboard
```javascript
const response = await PointsAndBadgesApiClient.getLeaderboard(10);
response.data.forEach(user => {
  console.log(`${user.name}: ${user.points} points`);
});
```

## HTML Integration

### Display Points
```html
<span data-points>0</span>
```

### Display Badges
```html
<span data-badges>0</span>
```

### Automatic Update
The dashboard module automatically updates these elements on page load.

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/points-badges/points` | Get user points & level |
| GET | `/api/points-badges/points/history` | Get points history |
| GET | `/api/points-badges/badges` | Get user badges |
| GET | `/api/points-badges/badges/{id}` | Get badge details |
| GET | `/api/points-badges/badges/stats` | Get badge stats |
| GET | `/api/points-badges/leaderboard` | Get leaderboard |

## Response Format

### Points Response
```json
{
  "success": true,
  "data": {
    "points": 1250,
    "level": "Intermediate",
    "next_level_points": 500,
    "progress_to_next_level": 75
  }
}
```

### Badges Response
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "First Lesson",
      "points": 10,
      "icon": "📖",
      "earned_at": "2025-12-22T10:30:00Z"
    }
  ]
}
```

## Level System

| Level | Points Range |
|-------|--------------|
| Amateur | 0-99 |
| Intermediate | 100-499 |
| Advanced | 500-999 |
| Expert | 1000+ |

## Common Tasks

### Update Points Display
```javascript
// Automatic - happens on page load
// Or manually:
const response = await PointsAndBadgesApiClient.getUserPoints();
document.querySelector('[data-points]').textContent = response.data.points;
```

### Update Badges Display
```javascript
// Automatic - happens on page load
// Or manually:
const response = await PointsAndBadgesApiClient.getUserBadges();
document.querySelector('[data-badges]').textContent = response.data.length;
```

### Show User Level
```javascript
const response = await PointsAndBadgesApiClient.getUserPoints();
console.log(`Current Level: ${response.data.level}`);
console.log(`Progress: ${response.data.progress_to_next_level}%`);
```

### Display Recent Badges
```javascript
const response = await PointsAndBadgesApiClient.getBadgeStats();
response.data.recent_badges.forEach(badge => {
  console.log(`${badge.name} - ${badge.points} pts`);
});
```

## Error Handling

All API methods include error handling:
```javascript
const response = await PointsAndBadgesApiClient.getUserPoints();
if (response.success) {
  // Use response.data
} else {
  console.error(response.message);
  // Use default values
}
```

## Testing

### Test Points API
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/points-badges/points
```

### Test Badges API
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/points-badges/badges
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Points showing 0 | Check user has earned points |
| Badges showing 0 | Check user has earned badges |
| API 401 error | Verify authentication token |
| API 404 error | Check endpoints exist in routes/api.php |
| No data updating | Check browser console for errors |

## Status: ✅ READY FOR PRODUCTION

All components are implemented and tested!

