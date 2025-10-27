# 🗄️ **MYSQL DATABASE SETUP GUIDE**

## 📋 **OVERVIEW**

The Kokokah.com LMS has been configured to use **MySQL** as the primary database instead of SQLite. This guide will help you set up MySQL for development and production environments.

---

## 🔧 **CONFIGURATION CHANGES MADE**

### **1. Environment Configuration (.env)**
```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kokokah
DB_USERNAME=root
DB_PASSWORD=

# Performance Optimizations (Redis optional - falls back to database)
SESSION_DRIVER=database  # Use 'redis' if Redis is installed
QUEUE_CONNECTION=database # Use 'redis' if Redis is installed
CACHE_STORE=database     # Use 'redis' if Redis is installed
```

### **2. Database Config (config/database.php)**
- Default connection changed from `sqlite` to `mysql`
- MySQL configuration optimized for Laravel 12

### **3. Files Removed**
- `database/database.sqlite` - SQLite database file removed

---

## 💻 **DEVELOPMENT SETUP**

### **Option 1: XAMPP (Recommended for Windows)**

#### **1. Download and Install XAMPP**
```bash
# Download from: https://www.apachefriends.org/download.html
# Install XAMPP with MySQL and phpMyAdmin
```

#### **2. Start MySQL Service**
```bash
# Open XAMPP Control Panel
# Start Apache and MySQL services
```

#### **3. Create Database**
```sql
-- Access phpMyAdmin at http://localhost/phpmyadmin
-- Create new database named 'kokokah'
CREATE DATABASE kokokah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### **4. Update .env File**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kokokah
DB_USERNAME=root
DB_PASSWORD=
```

### **Option 2: MySQL Server Direct Installation**

#### **1. Download MySQL**
```bash
# Download from: https://dev.mysql.com/downloads/mysql/
# Install MySQL Server 8.0+
```

#### **2. Configure MySQL**
```bash
# During installation, set root password
# Remember the password for .env configuration
```

#### **3. Create Database**
```sql
mysql -u root -p
CREATE DATABASE kokokah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON kokokah.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### **4. Update .env File**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kokokah
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

### **Option 3: Docker MySQL (Advanced)**

#### **1. Create docker-compose.yml**
```yaml
version: '3.8'
services:
  mysql:
    image: mysql:8.0
    container_name: kokokah_mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: kokokah
      MYSQL_ROOT_PASSWORD: password
      MYSQL_PASSWORD: password
      MYSQL_USER: kokokah
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"
    command: --default-authentication-plugin=mysql_native_password

  redis:
    image: redis:7-alpine
    container_name: kokokah_redis
    restart: unless-stopped
    ports:
      - "6379:6379"

volumes:
  mysql_data:
```

#### **2. Start Services**
```bash
docker-compose up -d
```

#### **3. Update .env File**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kokokah
DB_USERNAME=root
DB_PASSWORD=password

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## 🚀 **RUNNING MIGRATIONS**

### **1. Clear Configuration Cache**
```bash
php artisan config:clear
php artisan config:cache
```

### **2. Test Database Connection**
```bash
php artisan tinker
# In tinker console:
DB::connection()->getPdo();
# Should return PDO object without errors
exit
```

### **3. Run Migrations**
```bash
# Run all migrations
php artisan migrate

# If you need to reset and re-run
php artisan migrate:fresh

# Run migrations with seeding
php artisan migrate:fresh --seed
```

### **4. Seed Production Data**
```bash
php artisan db:seed --class=ProductionSeeder
```

---

## 🔧 **REDIS SETUP (OPTIONAL BUT RECOMMENDED)**

### **Windows (Using Redis for Windows)**
```bash
# Download from: https://github.com/microsoftarchive/redis/releases
# Install and start Redis service
# Default configuration should work with .env settings
```

### **Docker Redis (Recommended)**
```bash
# If using Docker Compose (see above)
docker-compose up -d redis

# Or standalone Redis container
docker run -d --name kokokah_redis -p 6379:6379 redis:7-alpine
```

### **Test Redis Connection**
```bash
php artisan tinker
# In tinker console:
Cache::put('test', 'value', 60);
Cache::get('test');
# Should return 'value'
exit
```

---

## 🏭 **PRODUCTION SETUP**

### **1. MySQL Server Configuration**
```sql
-- Create production database
CREATE DATABASE kokokah_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create dedicated user
CREATE USER 'kokokah_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON kokokah_production.* TO 'kokokah_user'@'localhost';
FLUSH PRIVILEGES;
```

### **2. Production .env Configuration**
```env
APP_ENV=production
APP_DEBUG=false

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kokokah_production
DB_USERNAME=kokokah_user
DB_PASSWORD=secure_password_here

# Use Redis for better performance
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
CACHE_STORE=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=redis_password_here
REDIS_PORT=6379
```

### **3. MySQL Performance Optimization**
```sql
-- Add to MySQL configuration (my.cnf or my.ini)
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
max_connections = 200
query_cache_size = 64M
query_cache_type = 1
```

---

## 🔍 **TROUBLESHOOTING**

### **Common Issues and Solutions**

#### **1. "SQLSTATE[HY000] [2002] Connection refused"**
```bash
# Check if MySQL service is running
# Windows: Check XAMPP Control Panel or Services
# Linux: sudo systemctl status mysql
# Mac: brew services list | grep mysql
```

#### **2. "Access denied for user 'root'@'localhost'"**
```bash
# Reset MySQL root password
# Or update .env with correct credentials
```

#### **3. "Unknown database 'kokokah'"**
```sql
-- Create the database
mysql -u root -p
CREATE DATABASE kokokah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### **4. "Class 'Redis' not found"**
```bash
# Install Redis PHP extension
# Windows: Enable php_redis.dll in php.ini
# Linux: sudo apt-get install php-redis
# Or use database driver instead of redis
```

#### **5. Migration Errors**
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Reset migrations if needed
php artisan migrate:fresh
```

---

## ✅ **VERIFICATION CHECKLIST**

### **Database Connection**
- [ ] MySQL service is running
- [ ] Database 'kokokah' exists
- [ ] User credentials are correct
- [ ] `php artisan tinker` can connect to DB

### **Migrations**
- [ ] All 38 migrations run successfully
- [ ] No migration errors in console
- [ ] Tables created in database

### **Seeding**
- [ ] ProductionSeeder runs without errors
- [ ] Admin user created
- [ ] Categories, levels, terms seeded
- [ ] Badges and settings configured

### **Performance**
- [ ] Redis connection working (if configured)
- [ ] Cache operations functional
- [ ] Session storage working

### **Application**
- [ ] Laravel application loads without errors
- [ ] API endpoints respond correctly
- [ ] Authentication works
- [ ] Database queries execute properly

---

## 📞 **SUPPORT**

If you encounter issues:

1. **Check Laravel Logs**: `storage/logs/laravel.log`
2. **Check MySQL Error Logs**: Usually in MySQL data directory
3. **Test Connection**: Use `php artisan tinker` to test DB connection
4. **Verify Configuration**: `php artisan config:show database`

---

## 🎯 **NEXT STEPS**

After successful MySQL setup:

1. **Run Tests**: `php artisan test` to ensure everything works
2. **Set Up Frontend**: Use the API documentation to build frontend
3. **Configure Production**: Follow production deployment guide
4. **Monitor Performance**: Set up database monitoring

---

**✅ MySQL database configuration is now complete and ready for development!**
