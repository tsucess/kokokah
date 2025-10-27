# Multi-Language Support Setup - Hausa, Yoruba, Igbo

**Date:** October 26, 2025  
**Status:** ✅ **FULLY IMPLEMENTED**  
**Supported Languages:** English, French, Arabic, Hausa, Yoruba, Igbo

---

## 🌍 Overview

Your Kokokah LMS now supports **6 languages** with full localization support:

- ✅ **English** (en) - Default
- ✅ **French** (fr)
- ✅ **Arabic** (ar) - RTL Support
- ✅ **Hausa** (ha) - Nigerian
- ✅ **Yoruba** (yo) - Nigerian
- ✅ **Igbo** (ig) - Nigerian

---

## 📁 Files Created

### Language Files
```
resources/lang/
├── en/messages.php       ✅ English
├── fr/messages.php       ✅ French
├── ar/messages.php       ✅ Arabic
├── ha/messages.php       ✅ Hausa (NEW)
├── yo/messages.php       ✅ Yoruba (NEW)
└── ig/messages.php       ✅ Igbo (NEW)
```

### Middleware
- ✅ `app/Http/Middleware/SetLocale.php` - Automatic locale detection and setting

### Controller
- ✅ `app/Http/Controllers/LanguageController.php` - Language management endpoints

### Routes
- ✅ 9 new API endpoints for language management

---

## 🚀 API Endpoints

### Public Endpoints (No Authentication)

#### 1. Get Current Locale
```
GET /api/language/current
```

**Response:**
```json
{
  "success": true,
  "data": {
    "locale": "en",
    "locale_name": "English",
    "supported_locales": [
      {"code": "en", "name": "English"},
      {"code": "fr", "name": "Français"},
      {"code": "ar", "name": "العربية"},
      {"code": "yo", "name": "Yorùbá"},
      {"code": "ha", "name": "Hausa"},
      {"code": "ig", "name": "Igbo"}
    ]
  }
}
```

#### 2. Get Supported Languages
```
GET /api/language/supported
```

#### 3. Set Locale (for Guests)
```
POST /api/language/set
Content-Type: application/json

{
  "locale": "ha"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Language changed successfully",
  "data": {
    "locale": "ha",
    "locale_name": "Hausa"
  }
}
```

#### 4. Get Translations (Current Locale)
```
GET /api/language/translations
```

**Response:**
```json
{
  "success": true,
  "data": {
    "locale": "en",
    "messages": {
      "auth": {
        "login_success": "Login successful",
        ...
      },
      "courses": {...},
      "payments": {...},
      ...
    }
  }
}
```

#### 5. Get Translations (Specific Locale)
```
GET /api/language/translations/{locale}
```

Example: `GET /api/language/translations/ha`

#### 6. Get Language Info
```
GET /api/language/info/{locale}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "code": "ha",
    "name": "Hausa",
    "native_name": "Hausa",
    "direction": "ltr",
    "country": "Nigeria"
  }
}
```

#### 7. Get All Language Info
```
GET /api/language/info
```

---

### Authenticated Endpoints (Bearer Token Required)

#### 1. Set User's Preferred Language
```
POST /api/language/user/set
Authorization: Bearer {token}
Content-Type: application/json

{
  "locale": "yo"
}
```

#### 2. Get User's Language Preference
```
GET /api/language/user
Authorization: Bearer {token}
```

---

## 🔧 How It Works

### Locale Detection Priority

The `SetLocale` middleware detects locale in this order:

1. **Query Parameter** - `?locale=ha`
2. **Request Header** - `Accept-Language: ha-NG`
3. **User Preference** - Stored in `users.language_preference`
4. **Session** - Stored in session
5. **Cookie** - Stored in cookie
6. **Default** - Falls back to `en`

### Example Usage

#### Frontend - React
```javascript
// Get current language
const response = await fetch('/api/language/current');
const data = await response.json();
console.log(data.data.locale); // 'en'

// Change language
const changeResponse = await fetch('/api/language/set', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ locale: 'ha' })
});

// Get translations
const translationsResponse = await fetch('/api/language/translations/ha');
const translations = await translationsResponse.json();
console.log(translations.data.messages);
```

#### Backend - Laravel
```php
// Get current locale
$locale = App::getLocale(); // 'en'

// Set locale
App::setLocale('yo');

// Get translation
$message = trans('messages.auth.login_success'); // Yoruba translation

// Get all translations
$messages = trans('messages');
```

---

## 📝 Translation Keys

### Available Translation Categories

1. **Authentication** (`auth`)
   - login_success
   - logout_success
   - registration_success
   - invalid_credentials
   - email_already_exists
   - password_reset_sent
   - password_reset_success

2. **Courses** (`courses`)
   - created_success
   - updated_success
   - deleted_success
   - not_found
   - enrollment_success
   - already_enrolled
   - enrollment_failed

3. **Payments** (`payments`)
   - payment_success
   - payment_failed
   - insufficient_balance
   - invalid_amount
   - payment_pending

4. **Wallet** (`wallet`)
   - deposit_success
   - withdrawal_success
   - insufficient_funds
   - transaction_failed

5. **Notifications** (`notifications`)
   - marked_as_read
   - marked_all_as_read
   - deleted_success

6. **Errors** (`errors`)
   - unauthorized
   - forbidden
   - not_found
   - validation_failed
   - server_error
   - invalid_request

7. **General** (`general`)
   - success
   - error
   - warning
   - info
   - loading
   - please_wait

---

## 🎯 Usage Examples

### Example 1: Get Hausa Translations
```bash
curl -X GET http://localhost:8000/api/language/translations/ha
```

### Example 2: Set User Language to Yoruba
```bash
curl -X POST http://localhost:8000/api/language/user/set \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"locale":"yo"}'
```

### Example 3: Get Language Info for Igbo
```bash
curl -X GET http://localhost:8000/api/language/info/ig
```

### Example 4: Get All Supported Languages
```bash
curl -X GET http://localhost:8000/api/language/supported
```

---

## 🔄 Locale Detection Example

### Request with Query Parameter
```
GET /api/language/translations?locale=ha
```
→ Uses Hausa locale

### Request with Accept-Language Header
```
GET /api/language/translations
Accept-Language: yo-NG, en;q=0.9
```
→ Uses Yoruba locale (highest priority)

### Authenticated User with Preference
```
GET /api/language/translations
Authorization: Bearer {token}
```
→ Uses user's stored language preference

---

## 📊 Language Codes

| Code | Language | Native Name | Direction | Country |
|------|----------|-------------|-----------|---------|
| en | English | English | LTR | United States |
| fr | French | Français | LTR | France |
| ar | Arabic | العربية | RTL | Saudi Arabia |
| yo | Yoruba | Yorùbá | LTR | Nigeria |
| ha | Hausa | Hausa | LTR | Nigeria |
| ig | Igbo | Igbo | LTR | Nigeria |

---

## 🔐 Security Features

- ✅ Locale validation (only supported locales allowed)
- ✅ User preference stored securely
- ✅ Session-based locale persistence
- ✅ Cookie-based locale persistence
- ✅ Accept-Language header parsing
- ✅ Fallback to default locale

---

## 🚀 Next Steps

### 1. Add More Translations
Expand the translation files with more keys as needed:
```php
// resources/lang/ha/messages.php
return [
    'your_key' => 'Your Hausa translation',
    ...
];
```

### 2. Frontend Integration
Integrate language switching in your frontend:
```javascript
// Get all languages
const languages = await fetch('/api/language/info').then(r => r.json());

// Create language selector
languages.data.forEach(lang => {
  console.log(`${lang.name} (${lang.code})`);
});
```

### 3. Database Migration
Add language preference column to users table (if not already present):
```php
$table->string('language_preference')->default('en');
```

### 4. Content Translation
Use the existing `ContentTranslation` model for translating courses, lessons, etc.

---

## ✨ Features

- ✅ 6 languages supported
- ✅ Automatic locale detection
- ✅ User language preferences
- ✅ Session persistence
- ✅ Cookie persistence
- ✅ Accept-Language header support
- ✅ RTL language support (Arabic)
- ✅ Comprehensive API endpoints
- ✅ Secure locale validation
- ✅ Fallback to default locale

---

## 📞 Support

For adding new languages:
1. Create `resources/lang/{code}/messages.php`
2. Add language code to `LocalizationService::SUPPORTED_LANGUAGES`
3. Add language info to `LanguageController::getAllLanguageInfo()`
4. Update this documentation

---

*Setup Date: October 26, 2025*  
*Status: ✅ PRODUCTION READY*  
*Languages: 6 (English, French, Arabic, Hausa, Yoruba, Igbo)*

