# Internationalization (i18n) - Implementation Guide

## 🌍 Current State Analysis

### What's Already Implemented
- ✅ Single language (English)
- ✅ NGN currency support
- ✅ Africa/Lagos timezone
- ✅ Nigerian phone format (+234)
- ✅ Localization config in `config/kokokah.php`

### What's Missing
- ❌ Multi-language support (French, Arabic, Yoruba, Hausa, Igbo)
- ❌ Language selection API
- ❌ Translation files
- ❌ Multi-currency support (USD, EUR, GBP, GHS, KES, ZAR)
- ❌ Currency conversion service
- ❌ Timezone selection per user
- ❌ RTL language support (Arabic)
- ❌ Content translation (courses, lessons)
- ❌ Locale-specific formatting

---

## 🎯 Implementation Plan

### Phase 1: Set Up Laravel Localization

**Create Language Files:**
```
resources/lang/
├── en/
│   ├── messages.php
│   ├── validation.php
│   ├── auth.php
│   ├── courses.php
│   └── notifications.php
├── fr/
│   ├── messages.php
│   └── ...
├── ar/
│   ├── messages.php
│   └── ...
├── yo/ (Yoruba)
├── ha/ (Hausa)
└── ig/ (Igbo)
```

**Example `resources/lang/en/messages.php`:**
```php
return [
    'welcome' => 'Welcome to Kokokah',
    'course_enrolled' => 'You have enrolled in :course',
    'payment_success' => 'Payment successful',
];
```

### Phase 2: Create Language Middleware

**Create `app/Http/Middleware/SetLocale.php`:**
- Detect language from request header
- Check user preference
- Fall back to default (English)
- Set app locale

### Phase 3: Implement Multi-Currency Support

**Create `app/Services/CurrencyService.php`:**
- `getSupportedCurrencies()` - List all currencies
- `convertCurrency()` - Convert between currencies
- `getExchangeRate()` - Get current rates
- `formatCurrency()` - Format amount with symbol
- `getUserCurrency()` - Get user's preferred currency

**Supported Currencies:**
- NGN (Nigerian Naira) - Primary
- USD (US Dollar)
- EUR (Euro)
- GBP (British Pound)
- GHS (Ghanaian Cedi)
- KES (Kenyan Shilling)
- ZAR (South African Rand)

### Phase 4: Add User Localization Preferences

**Update User Model:**
```php
protected $fillable = [
    // ... existing fields
    'language', // en, fr, ar, yo, ha, ig
    'currency', // NGN, USD, EUR, etc.
    'timezone', // Africa/Lagos, etc.
];
```

**Create Migration:**
```sql
ALTER TABLE users ADD COLUMN language VARCHAR(10) DEFAULT 'en';
ALTER TABLE users ADD COLUMN currency VARCHAR(3) DEFAULT 'NGN';
ALTER TABLE users ADD COLUMN timezone VARCHAR(50) DEFAULT 'Africa/Lagos';
```

### Phase 5: Create Localization API Endpoints

**New Endpoints:**
```
GET  /api/localization/languages
GET  /api/localization/currencies
GET  /api/localization/timezones
GET  /api/user/preferences/localization
POST /api/user/preferences/localization
GET  /api/translations/{key}
```

### Phase 6: Implement Content Translation

**Create `ContentTranslation` Model:**
- Store translations for courses, lessons, categories
- Support multiple languages per content
- Fallback to English if translation missing

**Migration:**
```sql
CREATE TABLE content_translations (
    id, content_type, content_id, language, 
    title, description, created_at
);
```

### Phase 7: Add RTL Support

**For Arabic and other RTL languages:**
- Add `direction` field to user preferences
- Update frontend CSS for RTL
- Adjust UI components for RTL

### Phase 8: Implement Timezone Conversion

**Create `app/Services/TimezoneService.php`:**
- `convertToUserTimezone()` - Convert UTC to user timezone
- `convertToUTC()` - Convert user timezone to UTC
- `formatDateTime()` - Format with user timezone
- `getTimezoneOffset()` - Get offset from UTC

---

## 📊 Language Support Matrix

| Language | Code | Region | RTL | Priority |
|----------|------|--------|-----|----------|
| English  | en   | Global | No  | 1 (Done) |
| French   | fr   | West Africa | No | 2 |
| Arabic   | ar   | North Africa | Yes | 3 |
| Yoruba   | yo   | Nigeria | No | 4 |
| Hausa    | ha   | Nigeria | No | 5 |
| Igbo     | ig   | Nigeria | No | 6 |

---

## 🔧 Implementation Details

### Language Detection Priority:
1. User preference (if logged in)
2. Accept-Language header
3. Browser language
4. Default (English)

### Currency Conversion:
- Use OpenExchangeRates API or similar
- Cache rates for 24 hours
- Update daily
- Show conversion rates to users

### Timezone Handling:
- Store all times in UTC in database
- Convert to user timezone on display
- Allow user to override timezone

---

## 🚀 Implementation Priority

1. **High Priority:** Multi-language support (biggest impact)
2. **High Priority:** Multi-currency support (business critical)
3. **Medium Priority:** Timezone support (UX improvement)
4. **Medium Priority:** Content translation (scalability)
5. **Low Priority:** RTL support (nice to have)

---

## 📝 Estimated Timeline

- **Phase 1-2:** 1 week (Setup + Middleware)
- **Phase 3-4:** 1 week (Currency + User Prefs)
- **Phase 5-6:** 1 week (API + Content)
- **Phase 7-8:** 1 week (RTL + Timezone)
- **Total:** 4 weeks for complete implementation

---

## 💡 Quick Start

**Minimal Implementation (1 week):**
1. Add language files (en, fr, ar)
2. Create language middleware
3. Add language selection API
4. Update user model with language field
5. Test with 3 languages

**Then expand to:**
- Full currency support
- Content translation
- RTL support
- Timezone management

