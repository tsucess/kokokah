# Report Page Code Reference

## JavaScript Functions Overview

### Core Functions

#### 1. `loadDashboardStats()`
Fetches admin dashboard overview data
```javascript
GET /api/dashboard/admin
Updates: Total Students, Teachers, Courses, Enrollments
```

#### 2. `loadEngagementAnalytics()`
Fetches engagement analytics data
```javascript
GET /api/analytics/engagement
Updates: Engagement chart with temporal patterns
```

#### 3. `loadCoursePerformance()`
Fetches course performance metrics
```javascript
GET /api/analytics/course-performance
Updates: Course performance bar chart
```

#### 4. `loadStudentPerformance(page)`
Fetches paginated student progress data
```javascript
GET /api/analytics/student-progress?page={page}
Updates: Student performance table with pagination
```

### Helper Functions

#### `updateStatsBoxes(overview)`
Renders statistics boxes with real data

#### `initializeEngagementChart(analyticsData)`
Creates Chart.js engagement line chart

#### `initializeCoursePerformanceChart(performanceData)`
Creates Chart.js course performance bar chart

#### `renderStudentTable(students)`
Renders student data in HTML table

#### `updatePagination()`
Updates pagination controls and page numbers

#### `formatDate(dateString)`
Formats dates for display

### Event Listeners

#### Search Input
```javascript
document.getElementById('searchInput').addEventListener('input', ...)
Filters students by name/email in real-time
```

#### Filter Select
```javascript
document.getElementById('filterSelect').addEventListener('change', ...)
Filters students by course, category, or role
```

#### Chart Range Buttons
```javascript
document.querySelectorAll('.chart-menu button').forEach(...)
Updates engagement chart based on selected range
```

#### Pagination Buttons
```javascript
prevBtn.onclick = () => loadStudentPerformance(currentPage - 1)
nextBtn.onclick = () => loadStudentPerformance(currentPage + 1)
```

## Data Flow

```
Page Load
    ↓
DOMContentLoaded Event
    ├→ loadDashboardStats()
    ├→ loadEngagementAnalytics()
    ├→ loadCoursePerformance()
    └→ loadStudentPerformance(1)
    
User Interactions
    ├→ Search Input → renderStudentTable(filtered)
    ├→ Filter Select → renderStudentTable(filtered)
    ├→ Chart Range → loadEngagementDataByRange()
    └→ Pagination → loadStudentPerformance(page)
```

## Error Handling Strategy

1. **Try-Catch Blocks**: All API calls wrapped
2. **Fallback Data**: Mock data used if API fails
3. **User Feedback**: Error messages in table
4. **Console Logging**: Errors logged for debugging

## Authentication

```javascript
const token = localStorage.getItem('auth_token');

// Used in all API calls
headers: {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
}
```

## Global Variables

```javascript
const token = localStorage.getItem('auth_token');
const apiBaseUrl = '/api';
let engagementChart = null;
let coursePerformanceChart = null;
let currentPage = 1;
let totalPages = 1;
let allStudentData = [];
```

## Response Data Structure

### Dashboard Admin Response
```json
{
    "success": true,
    "data": {
        "overview": {
            "total_students": 23453,
            "total_instructors": 3456,
            "published_courses": 112,
            "total_enrollments": 45000
        }
    }
}
```

### Analytics Engagement Response
```json
{
    "success": true,
    "data": {
        "temporal_patterns": {
            "daily_activity": {
                "labels": [...],
                "data": [...]
            }
        }
    }
}
```

### Student Progress Response
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user": { "first_name": "...", "last_name": "..." },
            "course": { "title": "..." },
            "completion_rate": 75,
            "average_score": 85,
            "last_active": "2024-01-06"
        }
    ]
}
```

