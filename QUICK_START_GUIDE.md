# Quick Start Guide: Points & Badges System

## 🚀 Get Started in 5 Minutes

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Seed Badges
```bash
php artisan db:seed --class=BadgesSeeder
```

### Step 3: Get Authentication Token
```bash
# Login or register to get token
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

### Step 4: Test Endpoints
```bash
# Get your points
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/points-badges/points

# Get leaderboard
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/points-badges/leaderboard
```

---

## 📚 API Endpoints

### Points
- `GET /api/points-badges/points` - Your points & level
- `GET /api/points-badges/points/history` - Points history

### Badges
- `GET /api/points-badges/badges` - Your badges
- `GET /api/points-badges/badges/{id}` - Badge details
- `GET /api/points-badges/badges/stats` - Badge stats

### Leaderboard
- `GET /api/points-badges/leaderboard` - Global ranking

---

## 💡 Common Tasks

### Get User Points
```bash
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/points-badges/points
```

### Get Points History (Last 20)
```bash
curl -H "Authorization: Bearer TOKEN" \
  "http://localhost:8000/api/points-badges/points/history?limit=20"
```

### Get Learning Badges
```bash
curl -H "Authorization: Bearer TOKEN" \
  "http://localhost:8000/api/points-badges/badges?category=learning"
```

### Get Top 10 Users
```bash
curl -H "Authorization: Bearer TOKEN" \
  "http://localhost:8000/api/points-badges/leaderboard?limit=10"
```

---

## 🎯 Points System

| Activity | Points |
|----------|--------|
| Complete Lesson | 5 |
| Pass Quiz | 10 |
| Complete Course | 50 |

---

## 📊 Levels

| Level | Points |
|-------|--------|
| Amateur | 0-99 |
| Intermediate | 100-499 |
| Advanced | 500-999 |
| Expert | 1000+ |

---

## 🏆 Badge Categories

- **learning** - Learning badges
- **achievement** - Achievement badges
- **social** - Social badges
- **special** - Special badges

---

## 📖 Documentation

| Document | Purpose |
|----------|---------|
| API_ENDPOINTS_REFERENCE.md | Complete API docs |
| POINTS_BADGES_ENDPOINTS_TESTING.md | Testing guide |
| DATABASE_AND_ENDPOINTS_MODIFICATIONS.md | Database schema |
| BADGES_POINTS_SYSTEM_COMPLETE.md | Implementation details |
| IMPLEMENTATION_CHECKLIST.md | Deployment checklist |

---

## 🔧 Database Tables

- `badges` - Badge definitions
- `user_badges` - User badge assignments
- `user_points_history` - Points transactions
- `badge_criteria_log` - Badge qualification logs
- `user_level_history` - Level progression

---

## 🛠️ Models

- `UserPointsHistory` - Points history model
- `BadgeCriteriaLog` - Badge criteria logs
- `UserLevelHistory` - Level history
- `User` - Updated with relationships
- `Badge` - Enhanced with new columns

---

## 🎮 Controller

**PointsAndBadgesController** - 6 methods:
1. `getUserPoints()` - Get points & level
2. `getPointsHistory()` - Get history
3. `getUserBadges()` - Get badges
4. `getBadgeDetails()` - Get badge info
5. `getBadgeStats()` - Get statistics
6. `getLeaderboard()` - Get ranking

---

## ✅ Verification

### Check Migration
```bash
php artisan migrate:status
```

### Check Tables
```bash
php artisan tinker
Schema::hasTable('user_points_history')
```

### Check Badges
```bash
php artisan tinker
App\Models\Badge::count()
```

---

## 🐛 Troubleshooting

### Migration Failed
```bash
# Rollback and retry
php artisan migrate:rollback
php artisan migrate
```

### Badges Not Seeded
```bash
# Seed again
php artisan db:seed --class=BadgesSeeder
```

### Endpoint Not Found
```bash
# Clear route cache
php artisan route:clear
```

---

## 📞 Need Help?

1. Check `API_ENDPOINTS_REFERENCE.md` for endpoint details
2. Check `POINTS_BADGES_ENDPOINTS_TESTING.md` for examples
3. Check `DATABASE_AND_ENDPOINTS_MODIFICATIONS.md` for schema
4. Review code comments in models and controller

---

## 🎉 You're Ready!

The Points & Badges system is now ready to use. Start testing the endpoints and integrating with your frontend!

**Happy coding! 🚀**

