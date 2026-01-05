# Fix Profile Photo 403 Forbidden Error on Live Server

## Problem
Profile photos return 403 Forbidden when accessed at: `https://kokokah.com/storage/profile_photos/...`

## Root Causes
1. Storage symlink not created
2. Incorrect file permissions
3. Web server not configured to serve storage files

## Solution Steps

### Step 1: Create Storage Symlink (SSH)
```bash
cd /var/www/kokokah.com  # Your app root
php artisan storage:link
```

Expected output:
```
The [public/storage] directory has been linked to [storage/app/public] successfully.
```

### Step 2: Fix File Permissions
```bash
# Set correct ownership
sudo chown -R www-data:www-data /var/www/kokokah.com/storage
sudo chown -R www-data:www-data /var/www/kokokah.com/public

# Set correct permissions
sudo chmod -R 755 /var/www/kokokah.com/storage
sudo chmod -R 755 /var/www/kokokah.com/public
sudo chmod -R 644 /var/www/kokokah.com/storage/app/public/*
```

### Step 3: Verify Symlink
```bash
ls -la /var/www/kokokah.com/public/storage
```

Should show:
```
lrwxrwxrwx ... storage -> ../storage/app/public
```

### Step 4: Clear Laravel Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Step 5: Verify in Browser
Visit: `https://kokokah.com/storage/profile_photos/[filename].png`

Should return 200 OK, not 403.

## If Still Getting 403

### Check Web Server Config (Nginx)
```nginx
location /storage {
    alias /var/www/kokokah.com/public/storage;
    expires 30d;
    add_header Cache-Control "public, immutable";
}
```

### Check Web Server Config (Apache)
Ensure `.htaccess` in `/public` allows access:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

## Environment Variables Check
Verify in `.env` on live server:
```
FILESYSTEM_DISK=local
APP_URL=https://kokokah.com
```

## Quick Diagnostic Commands
```bash
# Check if symlink exists
test -L /var/www/kokokah.com/public/storage && echo "Symlink exists" || echo "Symlink missing"

# Check storage directory permissions
ls -la /var/www/kokokah.com/storage/app/public/

# Test file access
curl -I https://kokokah.com/storage/profile_photos/test.png
```

