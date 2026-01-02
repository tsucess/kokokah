# Real-time Chat - Environment Setup Guide

## 🚀 Broadcasting Configuration

### Option 1: Pusher (Recommended for Production)

**Step 1: Create Pusher Account**
1. Go to https://pusher.com
2. Sign up for a free account
3. Create a new app
4. Copy your credentials

**Step 2: Update .env**
```env
BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=database

# Pusher Credentials
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1

# Frontend (Vite)
VITE_PUSHER_APP_KEY=your_app_key
VITE_PUSHER_APP_CLUSTER=mt1
```

**Step 3: Install Dependencies**
```bash
npm install pusher-js laravel-echo
```

**Step 4: Build Frontend**
```bash
npm run build
```

---

### Option 2: Soketi (Self-hosted, Free)

**Step 1: Install Soketi**
```bash
# Using Docker (recommended)
docker run -p 6001:6001 quay.io/soketi/soketi:latest

# Or using npm
npm install -g @soketi/soketi
soketi start
```

**Step 2: Update .env**
```env
BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=database

# Soketi Credentials
PUSHER_APP_ID=1
PUSHER_APP_KEY=app-key
PUSHER_APP_SECRET=app-secret
PUSHER_APP_CLUSTER=mt1

# Soketi Server
SOKETI_HOST=localhost
SOKETI_PORT=6001
SOKETI_SCHEME=http

# Frontend (Vite)
VITE_SOKETI_APP_KEY=app-key
VITE_SOKETI_HOST=localhost
VITE_SOKETI_PORT=6001
```

**Step 3: Update resources/js/echo.js**
Uncomment the Soketi configuration section.

**Step 4: Install Dependencies**
```bash
npm install pusher-js laravel-echo
```

**Step 5: Build Frontend**
```bash
npm run build
```

---

### Option 3: Laravel WebSockets (Database-driven)

**Step 1: Install Package**
```bash
composer require beyondcode/laravel-websockets
```

**Step 2: Publish Configuration**
```bash
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider"
```

**Step 3: Run Migrations**
```bash
php artisan migrate
```

**Step 4: Update .env**
```env
BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=database

# Laravel WebSockets
PUSHER_APP_ID=1
PUSHER_APP_KEY=app-key
PUSHER_APP_SECRET=app-secret
PUSHER_APP_CLUSTER=mt1

PUSHER_HOST=localhost
PUSHER_PORT=6001
PUSHER_SCHEME=http

# Frontend (Vite)
VITE_PUSHER_APP_KEY=app-key
VITE_PUSHER_HOST=localhost
VITE_PUSHER_PORT=6001
```

**Step 5: Start WebSocket Server**
```bash
php artisan websockets:serve
```

**Step 6: Install Dependencies**
```bash
npm install pusher-js laravel-echo
```

**Step 7: Build Frontend**
```bash
npm run build
```

---

## 📋 Complete .env Example

```env
# App
APP_NAME="Kokokah"
APP_ENV=local
APP_KEY=base64:xxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kokokah
DB_USERNAME=root
DB_PASSWORD=

# Broadcasting (Choose one)
BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=database

# Pusher (if using Pusher)
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1

# Frontend
VITE_PUSHER_APP_KEY=your_app_key
VITE_PUSHER_APP_CLUSTER=mt1

# Or Soketi (if using Soketi)
# SOKETI_HOST=localhost
# SOKETI_PORT=6001
# SOKETI_SCHEME=http
# VITE_SOKETI_APP_KEY=app-key
# VITE_SOKETI_HOST=localhost
# VITE_SOKETI_PORT=6001

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kokokah.com
MAIL_FROM_NAME="${APP_NAME}"

# Cache
CACHE_DRIVER=file

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Redis (optional, for caching)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## 🔧 Vite Configuration

Update `vite.config.js`:
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/echo.js',
            ],
            refresh: true,
        }),
    ],
});
```

---

## 📦 NPM Dependencies

Install required packages:
```bash
npm install pusher-js laravel-echo
```

Or with yarn:
```bash
yarn add pusher-js laravel-echo
```

---

## ✅ Verification Checklist

- [ ] Broadcasting driver configured in .env
- [ ] Pusher/Soketi/WebSockets credentials set
- [ ] NPM dependencies installed
- [ ] Frontend built with `npm run build`
- [ ] Queue connection configured
- [ ] Broadcasting routes accessible
- [ ] WebSocket server running (if self-hosted)
- [ ] Browser console shows no errors
- [ ] Messages appear in real-time

---

## 🐛 Troubleshooting

### WebSocket Connection Failed
1. Check if WebSocket server is running
2. Verify credentials in .env
3. Check firewall/proxy settings
4. Try polling fallback

### Messages Not Broadcasting
1. Verify BROADCAST_DRIVER in .env
2. Check queue is running: `php artisan queue:work`
3. Check browser console for errors
4. Verify channel authorization

### CORS Errors
1. Check CORS configuration in config/cors.php
2. Verify allowed origins
3. Check browser console for specific errors

---

## 📚 Resources

- [Pusher Documentation](https://pusher.com/docs)
- [Soketi Documentation](https://soketi.app/)
- [Laravel WebSockets](https://beyondcode.github.io/laravel-websockets/)
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting)


