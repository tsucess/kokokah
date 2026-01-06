# Deployment Checklist - Role Structure Changes

## Pre-Deployment Steps

### 1. Code Review
- [ ] Review all controller changes for role checks
- [ ] Verify User model helper methods are correct
- [ ] Check AuthServiceProvider gates and policies
- [ ] Review route middleware updates
- [ ] Verify frontend Blade template changes

### 2. Testing
- [ ] Run unit tests: `php artisan test tests/Unit/`
- [ ] Run feature tests: `php artisan test tests/Feature/RoleStructureTest.php`
- [ ] Test superadmin access to admin routes
- [ ] Test admin cannot access superadmin routes
- [ ] Test instructor can access student features
- [ ] Test student cannot access instructor features
- [ ] Verify role-based navigation displays correctly

### 3. Database Backup
- [ ] Create full database backup before migration
- [ ] Document backup location and timestamp
- [ ] Test backup restoration process

## Deployment Steps

### 1. Pull Latest Code
```bash
git pull origin main
```

### 2. Install Dependencies
```bash
composer install --no-dev
npm install
npm run build
```

### 3. Run Migrations
```bash
php artisan migrate
# This will run: 2026_01_06_000000_convert_admin_to_superadmin.php
# Converts all existing 'admin' users to 'superadmin'
```

### 4. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 5. Verify Deployment
- [ ] Check admin dashboard loads correctly
- [ ] Verify superadmin can access all features
- [ ] Verify admin users are now superadmin
- [ ] Check navigation shows correct items per role
- [ ] Test API endpoints with different roles

## Post-Deployment Steps

### 1. Monitor Logs
- [ ] Check application logs for errors
- [ ] Monitor error tracking service
- [ ] Check database query logs

### 2. User Communication
- [ ] Notify admins about role change (admin → superadmin)
- [ ] Provide documentation on new role structure
- [ ] Update user guides if needed

### 3. Rollback Plan
If issues occur:
```bash
# Rollback migration
php artisan migrate:rollback

# Revert code changes
git revert <commit-hash>

# Clear cache
php artisan cache:clear
```

## Files Changed Summary

### Backend
- `app/Models/User.php` - Added role helper methods
- `app/Providers/AuthServiceProvider.php` - Updated gates
- `app/Policies/ChatRoomPolicy.php` - Updated policies
- `routes/api.php` - Updated role middleware
- 15+ Controllers - Updated role checks
- `database/migrations/2026_01_06_000000_convert_admin_to_superadmin.php` - New migration

### Frontend
- `resources/views/layouts/dashboardtemp.blade.php` - Role-based navigation
- `resources/views/admin/dashboard.blade.php` - Dynamic role display

### Tests
- `tests/Feature/RoleStructureTest.php` - New test file

## Verification Commands

```bash
# Check migration status
php artisan migrate:status

# Verify user roles
php artisan tinker
>>> User::where('role', 'superadmin')->count()

# Test API endpoints
curl -H "Authorization: Bearer TOKEN" http://localhost/api/admin/dashboard
```

## Support & Rollback
- Keep deployment team on standby for 2 hours post-deployment
- Have rollback plan ready
- Monitor error tracking closely
- Be prepared to revert if critical issues arise

