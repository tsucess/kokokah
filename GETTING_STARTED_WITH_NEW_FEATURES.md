# 🚀 GETTING STARTED WITH NEW FEATURES

## Quick Start Guide for All 5 Improvements

---

## 1️⃣ TEST COVERAGE

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Unit tests only
php artisan test tests/Unit

# Feature tests only
php artisan test tests/Feature

# Integration tests only
php artisan test tests/Integration
```

### Generate Coverage Report
```bash
php artisan test --coverage
php artisan test --coverage-html=coverage
```

### View Coverage Report
Open `coverage/index.html` in your browser

---

## 2️⃣ ADVANCED ANALYTICS

### API Endpoints

#### Get Student Predictions
```bash
GET /api/analytics/advanced/predictions/student/{studentId}
Authorization: Bearer {token}
```

#### Calculate Student Predictions
```bash
POST /api/analytics/advanced/predictions/student/{studentId}/calculate
Authorization: Bearer {token}
Role: admin
```

#### Create Cohort
```bash
POST /api/analytics/advanced/cohorts
Authorization: Bearer {token}
Role: admin

Body:
{
  "cohort_name": "Q4 2025 Cohort",
  "start_date": "2025-10-01",
  "end_date": "2025-12-31"
}
```

#### Get Cohort Analysis
```bash
GET /api/analytics/advanced/cohorts/{cohortId}
Authorization: Bearer {token}
```

#### Compare Cohorts
```bash
POST /api/analytics/advanced/cohorts/{cohortId1}/compare/{cohortId2}
Authorization: Bearer {token}
```

#### Get Course Engagement
```bash
GET /api/analytics/advanced/engagement/course/{courseId}
Authorization: Bearer {token}
```

#### Get At-Risk Students
```bash
GET /api/analytics/advanced/at-risk/course/{courseId}
Authorization: Bearer {token}
```

#### Get Analytics Dashboard
```bash
GET /api/analytics/advanced/dashboard
Authorization: Bearer {token}
```

---

## 3️⃣ INTERNATIONALIZATION

### API Endpoints

#### Get User Preferences
```bash
GET /api/localization/preferences
Authorization: Bearer {token}
```

#### Update User Preferences
```bash
PUT /api/localization/preferences
Authorization: Bearer {token}

Body:
{
  "language": "fr",
  "currency": "EUR",
  "timezone": "Europe/Paris"
}
```

#### Get Supported Languages
```bash
GET /api/localization/languages
Authorization: Bearer {token}
```

#### Get Supported Currencies
```bash
GET /api/localization/currencies
Authorization: Bearer {token}
```

#### Convert Currency
```bash
POST /api/localization/convert-currency
Authorization: Bearer {token}

Body:
{
  "amount": 1000,
  "from_currency": "NGN",
  "to_currency": "USD"
}
```

#### Translate Content
```bash
POST /api/localization/translate
Authorization: Bearer {token}

Body:
{
  "model_type": "Course",
  "model_id": 1,
  "language": "fr",
  "translations": {
    "title": "Titre du cours",
    "description": "Description du cours"
  }
}
```

---

## 4️⃣ VIDEO STREAMING

### API Endpoints

#### Create Video Stream
```bash
POST /api/videos
Authorization: Bearer {token}
Role: instructor, admin

Body:
{
  "lesson_id": 1,
  "video_url": "https://example.com/video.mp4"
}
```

#### Process Video Stream
```bash
POST /api/videos/{videoStreamId}/process
Authorization: Bearer {token}
Role: admin
```

#### Get Video Stream Details
```bash
GET /api/videos/{videoStreamId}
Authorization: Bearer {token}
```

#### Record Video View
```bash
POST /api/videos/{videoStreamId}/view
Authorization: Bearer {token}

Body:
{
  "quality_watched": "720p",
  "device_type": "desktop",
  "browser": "Chrome",
  "country": "NG"
}
```

#### Update Watch Time
```bash
POST /api/videos/{videoStreamId}/watch-time
Authorization: Bearer {token}

Body:
{
  "seconds": 300
}
```

#### Create Download Request
```bash
POST /api/videos/{videoStreamId}/download
Authorization: Bearer {token}

Body:
{
  "quality_label": "720p"
}
```

#### Get Video Analytics
```bash
GET /api/videos/{videoStreamId}/analytics
Authorization: Bearer {token}
Role: instructor, admin
```

#### Get Top Videos
```bash
GET /api/videos/top/videos?limit=10
Authorization: Bearer {token}
```

#### Get User Downloads
```bash
GET /api/videos/user/downloads
Authorization: Bearer {token}
```

---

## 5️⃣ REAL-TIME FEATURES

### API Endpoints

#### Mark User Online
```bash
POST /api/realtime/online
Authorization: Bearer {token}

Query Parameters:
- course_id (optional)
- lesson_id (optional)
```

#### Mark User Offline
```bash
POST /api/realtime/offline
Authorization: Bearer {token}
```

#### Get Online Users
```bash
GET /api/realtime/users/online
Authorization: Bearer {token}
```

#### Get Online Count
```bash
GET /api/realtime/users/online/count
Authorization: Bearer {token}
```

#### Get Online Users in Course
```bash
GET /api/realtime/course/{courseId}/users/online
Authorization: Bearer {token}
```

#### Get Online Count in Course
```bash
GET /api/realtime/course/{courseId}/users/online/count
Authorization: Bearer {token}
```

#### Send Typing Indicator
```bash
POST /api/realtime/typing
Authorization: Bearer {token}

Body:
{
  "chat_session_id": 1,
  "is_typing": true
}
```

#### Get User Activity Status
```bash
GET /api/realtime/activity/{userId}
Authorization: Bearer {token}
```

#### Get Current User Activity
```bash
GET /api/realtime/activity
Authorization: Bearer {token}
```

---

## 🔧 SETUP INSTRUCTIONS

### 1. Run Migrations
```bash
php artisan migrate --force
```

### 2. Configure Broadcasting (for Real-time)
```bash
# Install Laravel Reverb
composer require laravel/reverb

# Publish configuration
php artisan vendor:publish --provider="Laravel\Reverb\ReverbeServiceProvider"

# Update .env
BROADCAST_DRIVER=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
```

### 3. Start Reverb Server (for Real-time)
```bash
php artisan reverb:start
```

### 4. Configure Video Processing
```bash
# Install FFmpeg (Ubuntu/Debian)
sudo apt-get install ffmpeg

# Or on macOS
brew install ffmpeg

# Or on Windows
# Download from https://ffmpeg.org/download.html
```

### 5. Run Tests
```bash
php artisan test
```

### 6. Generate Coverage Report
```bash
php artisan test --coverage-html=coverage
```

---

## 📊 TESTING THE FEATURES

### Test Advanced Analytics
```bash
# Create a cohort
curl -X POST http://localhost:8000/api/analytics/advanced/cohorts \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "cohort_name": "Test Cohort",
    "start_date": "2025-01-01",
    "end_date": "2025-12-31"
  }'

# Get predictions for a student
curl -X GET http://localhost:8000/api/analytics/advanced/predictions/student/1 \
  -H "Authorization: Bearer {token}"
```

### Test Internationalization
```bash
# Update user preferences
curl -X PUT http://localhost:8000/api/localization/preferences \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "language": "fr",
    "currency": "EUR",
    "timezone": "Europe/Paris"
  }'

# Convert currency
curl -X POST http://localhost:8000/api/localization/convert-currency \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 1000,
    "from_currency": "NGN",
    "to_currency": "USD"
  }'
```

### Test Video Streaming
```bash
# Create video stream
curl -X POST http://localhost:8000/api/videos \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "lesson_id": 1,
    "video_url": "https://example.com/video.mp4"
  }'

# Record view
curl -X POST http://localhost:8000/api/videos/1/view \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "quality_watched": "720p",
    "device_type": "desktop"
  }'
```

### Test Real-time Features
```bash
# Mark user online
curl -X POST http://localhost:8000/api/realtime/online \
  -H "Authorization: Bearer {token}"

# Get online users
curl -X GET http://localhost:8000/api/realtime/users/online \
  -H "Authorization: Bearer {token}"

# Send typing indicator
curl -X POST http://localhost:8000/api/realtime/typing \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "chat_session_id": 1,
    "is_typing": true
  }'
```

---

## 🐛 TROUBLESHOOTING

### Tests Failing
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Recreate test database
php artisan migrate:fresh --env=testing

# Run tests again
php artisan test
```

### Broadcasting Not Working
```bash
# Check Reverb is running
php artisan reverb:start

# Check .env configuration
cat .env | grep BROADCAST

# Check WebSocket connection
# Open browser console and check for WebSocket errors
```

### Video Processing Issues
```bash
# Check FFmpeg is installed
ffmpeg -version

# Check video file exists
ls -la /path/to/video.mp4

# Check storage permissions
chmod -R 755 storage/
```

---

## 📚 DOCUMENTATION

- Advanced Analytics: See `IMPLEMENTATION_GUIDE_ADVANCED_ANALYTICS.md`
- Internationalization: See `IMPLEMENTATION_GUIDE_INTERNATIONALIZATION.md`
- Video Streaming: See `IMPLEMENTATION_GUIDE_VIDEO_STREAMING.md`
- Real-time Features: See `IMPLEMENTATION_GUIDE_REALTIME_FEATURES.md`
- Test Coverage: See `IMPLEMENTATION_GUIDE_TEST_COVERAGE.md`

---

## ✅ VERIFICATION CHECKLIST

- [ ] All migrations run successfully
- [ ] All tests pass (99+ tests)
- [ ] Code coverage > 80%
- [ ] Advanced Analytics endpoints working
- [ ] Localization endpoints working
- [ ] Video Streaming endpoints working
- [ ] Real-time endpoints working
- [ ] Broadcasting configured
- [ ] FFmpeg installed (for video)
- [ ] All 40 new endpoints tested

---

**Ready to deploy! 🚀**

