# Multi-Language Quick Reference

**Supported Languages:** English (en), French (fr), Arabic (ar), Hausa (ha), Yoruba (yo), Igbo (ig)

---

## 🚀 Quick Start

### Get Current Language
```bash
curl http://localhost:8000/api/language/current
```

### Change Language (Guest)
```bash
curl -X POST http://localhost:8000/api/language/set \
  -H "Content-Type: application/json" \
  -d '{"locale":"ha"}'
```

### Change Language (Authenticated)
```bash
curl -X POST http://localhost:8000/api/language/user/set \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"locale":"yo"}'
```

### Get Translations
```bash
curl http://localhost:8000/api/language/translations/ha
```

---

## 📡 API Endpoints

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| GET | `/api/language/current` | No | Get current locale |
| GET | `/api/language/supported` | No | Get all supported languages |
| POST | `/api/language/set` | No | Set locale (guest) |
| GET | `/api/language/translations` | No | Get current translations |
| GET | `/api/language/translations/{locale}` | No | Get specific locale translations |
| GET | `/api/language/info/{locale}` | No | Get language info |
| GET | `/api/language/info` | No | Get all language info |
| POST | `/api/language/user/set` | Yes | Set user language preference |
| GET | `/api/language/user` | Yes | Get user language preference |

---

## 🌍 Language Codes

```
en  → English
fr  → French
ar  → Arabic
yo  → Yoruba
ha  → Hausa
ig  → Igbo
```

---

## 💻 Frontend Examples

### React
```javascript
// Get current language
const current = await fetch('/api/language/current').then(r => r.json());

// Change language
await fetch('/api/language/set', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ locale: 'ha' })
});

// Get translations
const trans = await fetch('/api/language/translations/ha').then(r => r.json());
console.log(trans.data.messages.auth.login_success);
```

### Vue
```javascript
// Get all languages
const languages = await fetch('/api/language/info').then(r => r.json());

// Set language
const setLang = async (locale) => {
  await fetch('/api/language/set', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ locale })
  });
};
```

---

## 🔧 Backend Examples

### Laravel
```php
// Get current locale
$locale = App::getLocale();

// Set locale
App::setLocale('yo');

// Get translation
$msg = trans('messages.auth.login_success');

// Get all translations
$all = trans('messages');
```

### PHP
```php
// Using the LocalizationService
$service = app(LocalizationService::class);

// Get user language
$lang = $service->getUserLanguage($userId);

// Set user language
$service->setUserLanguage($userId, 'ha');

// Get language name
$name = $service->getLanguageName('yo'); // 'Yorùbá'
```

---

## 📝 Translation Keys

### Auth
```
messages.auth.login_success
messages.auth.logout_success
messages.auth.registration_success
messages.auth.invalid_credentials
messages.auth.email_already_exists
messages.auth.password_reset_sent
messages.auth.password_reset_success
```

### Courses
```
messages.courses.created_success
messages.courses.updated_success
messages.courses.deleted_success
messages.courses.not_found
messages.courses.enrollment_success
messages.courses.already_enrolled
messages.courses.enrollment_failed
```

### Payments
```
messages.payments.payment_success
messages.payments.payment_failed
messages.payments.insufficient_balance
messages.payments.invalid_amount
messages.payments.payment_pending
```

### Wallet
```
messages.wallet.deposit_success
messages.wallet.withdrawal_success
messages.wallet.insufficient_funds
messages.wallet.transaction_failed
```

### Notifications
```
messages.notifications.marked_as_read
messages.notifications.marked_all_as_read
messages.notifications.deleted_success
```

### Errors
```
messages.errors.unauthorized
messages.errors.forbidden
messages.errors.not_found
messages.errors.validation_failed
messages.errors.server_error
messages.errors.invalid_request
```

### General
```
messages.general.success
messages.general.error
messages.general.warning
messages.general.info
messages.general.loading
messages.general.please_wait
```

---

## 🎯 Common Tasks

### Task 1: Get Hausa Messages
```bash
curl http://localhost:8000/api/language/translations/ha
```

### Task 2: Set User to Yoruba
```bash
curl -X POST http://localhost:8000/api/language/user/set \
  -H "Authorization: Bearer {token}" \
  -d '{"locale":"yo"}'
```

### Task 3: Get Language Info
```bash
curl http://localhost:8000/api/language/info/ig
```

### Task 4: Get All Languages
```bash
curl http://localhost:8000/api/language/info
```

### Task 5: Detect Language from Header
```bash
curl -H "Accept-Language: ha-NG" \
  http://localhost:8000/api/language/current
```

---

## 🔄 Locale Detection Order

1. Query parameter: `?locale=ha`
2. Accept-Language header: `Accept-Language: ha-NG`
3. User preference (if authenticated)
4. Session value
5. Cookie value
6. Default: `en`

---

## 📊 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Optional message",
  "data": {
    "locale": "ha",
    "locale_name": "Hausa",
    ...
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message"
}
```

---

## 🔐 Security

- ✅ Only supported locales accepted
- ✅ User preferences stored securely
- ✅ Session-based persistence
- ✅ Cookie-based persistence
- ✅ Input validation on all endpoints

---

## 📞 Troubleshooting

| Issue | Solution |
|-------|----------|
| Language not changing | Check if locale is in supported list |
| Translations not loading | Verify language file exists in `resources/lang/{code}/` |
| User preference not saving | Ensure user is authenticated with valid token |
| Header not detected | Check Accept-Language format: `en-US, en;q=0.9` |

---

## 🚀 Files Created

- ✅ `resources/lang/ha/messages.php` - Hausa translations
- ✅ `resources/lang/yo/messages.php` - Yoruba translations
- ✅ `resources/lang/ig/messages.php` - Igbo translations
- ✅ `app/Http/Middleware/SetLocale.php` - Locale middleware
- ✅ `app/Http/Controllers/LanguageController.php` - Language controller
- ✅ Routes in `routes/api.php` - 9 new endpoints

---

*Last Updated: October 26, 2025*  
*Status: ✅ PRODUCTION READY*

