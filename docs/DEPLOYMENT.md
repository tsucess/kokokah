# 🚀 Kokokah.com LMS - Production Deployment Guide

## 📋 Table of Contents
1. [Server Requirements](#server-requirements)
2. [Environment Setup](#environment-setup)
3. [Database Configuration](#database-configuration)
4. [Application Deployment](#application-deployment)
5. [Security Configuration](#security-configuration)
6. [Performance Optimization](#performance-optimization)
7. [Monitoring & Logging](#monitoring--logging)
8. [Backup Strategy](#backup-strategy)
9. [Post-Deployment Checklist](#post-deployment-checklist)

## 🖥️ Server Requirements

### Minimum Requirements
- **OS**: Ubuntu 20.04 LTS or CentOS 8+
- **CPU**: 2 cores (4 cores recommended)
- **RAM**: 4GB (8GB recommended)
- **Storage**: 50GB SSD (100GB recommended)
- **Bandwidth**: 100Mbps

### Recommended Production Setup
- **OS**: Ubuntu 22.04 LTS
- **CPU**: 4-8 cores
- **RAM**: 16-32GB
- **Storage**: 200GB+ SSD with backup
- **Load Balancer**: Nginx or CloudFlare
- **CDN**: CloudFlare or AWS CloudFront

### Software Requirements
```bash
# PHP 8.2+
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-redis php8.2-curl php8.2-gd php8.2-mbstring php8.2-xml php8.2-zip

# Nginx
sudo apt install nginx

# MySQL 8.0+
sudo apt install mysql-server-8.0

# Redis
sudo apt install redis-server

# Node.js 18+
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## 🔧 Environment Setup

### 1. Clone Repository
```bash
cd /var/www
sudo git clone https://github.com/your-org/kokokah.com.git
sudo chown -R www-data:www-data kokokah.com
cd kokokah.com
```

### 2. Install Dependencies
```bash
# PHP dependencies
composer install --no-dev --optimize-autoloader

# Node.js dependencies
npm ci --production
npm run build
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure environment variables
nano .env
```

### 4. Essential Environment Variables
```env
# Application
APP_NAME="Kokokah Learning Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kokokah.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kokokah_production
DB_USERNAME=kokokah_user
DB_PASSWORD=secure_password_here

# Cache & Sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@kokokah.com"

# Payment Gateways
PAYSTACK_PUBLIC_KEY=pk_live_xxxxx
PAYSTACK_SECRET_KEY=sk_live_xxxxx
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST-xxxxx
FLUTTERWAVE_SECRET_KEY=FLWSECK_TEST-xxxxx

# File Storage (AWS S3)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=kokokah-storage

# Security
SECURITY_HEADERS_ENABLED=true
CORS_ALLOWED_ORIGINS="https://kokokah.com,https://www.kokokah.com"
```

## 🗄️ Database Configuration

### 1. Create Database
```sql
CREATE DATABASE kokokah_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kokokah_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON kokokah_production.* TO 'kokokah_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Run Migrations
```bash
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder
```

### 3. Optimize Database
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🌐 Nginx Configuration

### 1. Create Nginx Site Configuration
```nginx
# /etc/nginx/sites-available/kokokah.com
server {
    listen 80;
    server_name kokokah.com www.kokokah.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name kokokah.com www.kokokah.com;
    root /var/www/kokokah.com/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/kokokah.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/kokokah.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # File Upload Limits
    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 2. Enable Site
```bash
sudo ln -s /etc/nginx/sites-available/kokokah.com /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 🔒 SSL Certificate Setup
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL Certificate
sudo certbot --nginx -d kokokah.com -d www.kokokah.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## ⚡ Performance Optimization

### 1. PHP-FPM Configuration
```ini
# /etc/php/8.2/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

### 2. Redis Configuration
```conf
# /etc/redis/redis.conf
maxmemory 2gb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

### 3. MySQL Optimization
```ini
# /etc/mysql/mysql.conf.d/mysqld.cnf
innodb_buffer_pool_size = 2G
innodb_log_file_size = 256M
query_cache_size = 128M
max_connections = 200
```

## 📊 Monitoring & Logging

### 1. Application Monitoring
```bash
# Install Laravel Horizon for queue monitoring
composer require laravel/horizon
php artisan horizon:install
php artisan horizon:publish

# Create systemd service for Horizon
sudo nano /etc/systemd/system/horizon.service
```

### 2. Log Rotation
```bash
# /etc/logrotate.d/laravel
/var/www/kokokah.com/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    notifempty
    create 644 www-data www-data
}
```

## 💾 Backup Strategy

### 1. Database Backup Script
```bash
#!/bin/bash
# /usr/local/bin/backup-kokokah.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u kokokah_user -p kokokah_production > /backups/kokokah_db_$DATE.sql
aws s3 cp /backups/kokokah_db_$DATE.sql s3://kokokah-backups/database/
find /backups -name "kokokah_db_*.sql" -mtime +7 -delete
```

### 2. File Backup
```bash
# Crontab entry
0 2 * * * /usr/local/bin/backup-kokokah.sh
0 3 * * * rsync -av /var/www/kokokah.com/storage/app/ /backups/files/
```

## ✅ Post-Deployment Checklist

- [ ] SSL certificate installed and working
- [ ] All environment variables configured
- [ ] Database migrations completed
- [ ] File permissions set correctly (755 for directories, 644 for files)
- [ ] Storage and cache directories writable
- [ ] Queue workers running
- [ ] Cron jobs configured
- [ ] Backup system tested
- [ ] Monitoring alerts configured
- [ ] Performance testing completed
- [ ] Security scan passed
- [ ] DNS records configured
- [ ] CDN configured (if applicable)
- [ ] Error tracking configured (Sentry)
- [ ] Analytics configured (Google Analytics)

## 🚨 Troubleshooting

### Common Issues
1. **Permission Errors**: `sudo chown -R www-data:www-data /var/www/kokokah.com`
2. **Storage Issues**: `php artisan storage:link`
3. **Cache Issues**: `php artisan cache:clear && php artisan config:clear`
4. **Queue Issues**: `php artisan queue:restart`

### Health Check Endpoint
- **URL**: `https://kokokah.com/up`
- **Expected Response**: `200 OK`

## 📞 Support

For deployment support, contact:
- **Email**: devops@kokokah.com
- **Documentation**: https://docs.kokokah.com
- **Status Page**: https://status.kokokah.com
