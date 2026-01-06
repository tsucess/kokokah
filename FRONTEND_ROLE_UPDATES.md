# Frontend Role Structure Updates

## Overview
Update the frontend UI to reflect the new role hierarchy and show appropriate navigation/features based on user role.

## Files to Update

### 1. Dashboard Navigation (`resources/views/layouts/dashboardtemp.blade.php`)
**Current State**: Shows all admin features to any logged-in user
**Changes Needed**:
- Add role-based visibility to navigation items
- Show "Superadmin Only" features only to superadmin users
- Show "Instructor Features" to instructors and above
- Show "Student Features" to all authenticated users

**Navigation Structure**:
```
Dashboard (All roles)
├── Users Management (Superadmin only)
├── Course Management (Instructor+)
├── Transactions (Superadmin only)
├── Reports & Analytics (Instructor+)
├── Communication (Superadmin only)
└── Settings (Superadmin only)
```

### 2. Admin Dashboard (`resources/views/admin/dashboard.blade.php`)
**Changes Needed**:
- Update role display from "(Admins)" to "(Superadmin)"
- Show appropriate stats based on user role
- Hide superadmin-only features from regular admins

### 3. JavaScript API Clients
**Files to Update**:
- `public/js/api/adminApiClient.js` - Add role checks
- `public/js/dashboard.js` - Update role-based UI rendering

**Changes**:
- Check user role before showing admin features
- Update role display in profile section
- Add role-based feature toggles

### 4. Blade Directives
**Add Role-Based Visibility**:
```blade
@if(auth()->user()->isSuperAdmin())
    <!-- Superadmin only content -->
@endif

@if(auth()->user()->isAdminOrSuperAdmin())
    <!-- Admin and Superadmin content -->
@endif

@if(auth()->user()->isInstructorOrHigher())
    <!-- Instructor, Admin, Superadmin content -->
@endif
```

## Implementation Steps

### Step 1: Update Navigation Visibility
Add role checks to sidebar navigation items in `dashboardtemp.blade.php`

### Step 2: Update Dashboard Display
Update role labels and stats display in `admin/dashboard.blade.php`

### Step 3: Update JavaScript
- Modify `dashboard.js` to check user role before rendering features
- Update `adminApiClient.js` to handle role-based API calls

### Step 4: Update Profile Display
- Change role display from "Admin" to "Superadmin" where applicable
- Show appropriate role label based on user's actual role

## Role Display Labels
- **superadmin**: "Superadmin"
- **admin**: "Admin"
- **instructor**: "Instructor"
- **student**: "Student"

## Testing Checklist
- [ ] Superadmin sees all features
- [ ] Admin sees instructor features but not superadmin features
- [ ] Instructor sees student features but not admin features
- [ ] Student sees only student features
- [ ] Role labels display correctly
- [ ] Navigation items show/hide based on role
- [ ] API calls respect role permissions

