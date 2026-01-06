# MySQL Startup & Migration Guide

## Issue
MySQL service is not running. The migration cannot execute without a database connection.

## Solution Options

### Option 1: Start MySQL Service (Recommended)
**Windows 10/11 with Admin Privileges**

1. Open Command Prompt as Administrator
2. Run one of these commands:

```bash
# Option A: Using Services
net start MySQL80

# Option B: Using PowerShell (as Admin)
Start-Service MySQL80

# Option C: Using MySQL installer
"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqld.exe" --console
```

### Option 2: Use XAMPP/WAMP/LARAVEL HERD
If you have XAMPP, WAMP, or Laravel Herd installed:
- Open XAMPP Control Panel
- Click "Start" next to MySQL
- Then run: `php artisan migrate`

### Option 3: Use Docker
If Docker is installed:
```bash
docker run --name mysql-kokokah -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=kokokah -p 3306:3306 -d mysql:8.0
```

## After Starting MySQL

### Step 1: Verify Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo()
```

### Step 2: Run Migration
```bash
php artisan migrate
```

Expected output:
```
Migration table created successfully.
Migrating: 2026_01_06_000000_convert_admin_to_superadmin
Migrated:  2026_01_06_000000_convert_admin_to_superadmin (XXms)
```

### Step 3: Verify Migration
```bash
php artisan migrate:status
```

## Troubleshooting

### MySQL Won't Start
- Check if port 3306 is already in use: `netstat -ano | findstr :3306`
- Check MySQL error log: `C:\ProgramData\MySQL\MySQL Server 8.0\Data\*.err`
- Reinstall MySQL if corrupted

### Connection Refused
- Verify MySQL is listening: `netstat -ano | findstr :3306`
- Check firewall settings
- Verify .env database credentials

### Permission Denied
- Run Command Prompt as Administrator
- Check MySQL user permissions

## Database Credentials (from .env)
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kokokah
DB_USERNAME=root
DB_PASSWORD=(empty)
```

## Next Steps After Migration
1. Clear cache: `php artisan cache:clear`
2. Run tests: `php artisan test tests/Feature/RoleStructureTest.php`
3. Verify role conversion: `php artisan tinker` → `User::where('role', 'superadmin')->count()`

## Need Help?
- Check MySQL logs in: `C:\ProgramData\MySQL\MySQL Server 8.0\Data\`
- Verify MySQL installation: `"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe" -u root`
- Contact your system administrator if service won't start

