#!/bin/bash

# Fix Storage Permissions for Plesk
# This script fixes 403 Forbidden errors for profile photos

echo "=========================================="
echo "Fixing Storage Permissions for Plesk"
echo "=========================================="

# Get the current directory
APP_ROOT=$(pwd)
echo "App Root: $APP_ROOT"

# Step 1: Check if symlink exists
echo ""
echo "Step 1: Checking symlink..."
if [ -L "$APP_ROOT/public/storage" ]; then
    echo "✓ Symlink exists at public/storage"
    ls -la "$APP_ROOT/public/storage"
else
    echo "✗ Symlink does NOT exist. Creating it..."
    php artisan storage:link
fi

# Step 2: Fix permissions for storage directory
echo ""
echo "Step 2: Fixing storage directory permissions..."
chmod -R 755 "$APP_ROOT/storage/app/public"
echo "✓ Set storage/app/public to 755"

# Step 3: Fix permissions for public/storage
echo ""
echo "Step 3: Fixing public/storage permissions..."
chmod -R 755 "$APP_ROOT/public/storage"
echo "✓ Set public/storage to 755"

# Step 4: Fix file permissions (make files readable)
echo ""
echo "Step 4: Fixing file permissions..."
find "$APP_ROOT/storage/app/public" -type f -exec chmod 644 {} \;
find "$APP_ROOT/public/storage" -type f -exec chmod 644 {} \;
echo "✓ Set all files to 644"

# Step 5: Check Plesk user
echo ""
echo "Step 5: Checking current user and group..."
CURRENT_USER=$(whoami)
echo "Current user: $CURRENT_USER"

# Step 6: Display final permissions
echo ""
echo "Step 6: Final permissions check..."
echo "storage/app/public:"
ls -la "$APP_ROOT/storage/app/public" | head -5
echo ""
echo "public/storage:"
ls -la "$APP_ROOT/public/storage" | head -5

echo ""
echo "=========================================="
echo "✓ Permissions fixed!"
echo "=========================================="

