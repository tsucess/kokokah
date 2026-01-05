# 🔍 Debugging Validation Error 422

## ❌ Error You Got
```
Error updating announcement: Validation failed
API Response: 422 (Unprocessable Content)
```

---

## 🔧 How to Debug

### **Step 1: Open Browser Console**
1. Press `F12` to open Developer Tools
2. Go to **Console** tab
3. Try to update the announcement again

### **Step 2: Look for Console Logs**
You should see logs like:
```
Sending request to: /api/announcements/4
Request body: {
    title: "...",
    description: "...",
    type: "...",
    priority: "...",
    audience: "...",
    scheduled_at: null,
    status: "draft"
}
Token: 3|GxBubCmZVc4GbsODkb...
API Error Response: {
    status: 422,
    message: "Validation failed",
    errors: {
        field_name: ["error message"]
    }
}
```

### **Step 3: Check the Errors Object**
The `errors` object will show which field failed validation.

---

## ✅ Common Validation Errors

### **1. Invalid Type**
```
errors: {
    type: ["The type field must be one of: Exams, Events, Alert, General Info"]
}
```
**Fix:** Make sure type is exactly one of:
- `Exams`
- `Events`
- `Alert`
- `General Info`

### **2. Invalid Priority**
```
errors: {
    priority: ["The priority field must be one of: Info, Urgent, Warning"]
}
```
**Fix:** Make sure priority is exactly one of:
- `Info`
- `Urgent`
- `Warning`

### **3. Invalid Audience**
```
errors: {
    audience: ["The audience field must be one of: All students, Specific class, Specific level"]
}
```
**Fix:** Make sure audience is exactly one of:
- `All students`
- `Specific class`
- `Specific level`

### **4. Invalid Status**
```
errors: {
    status: ["The status field must be one of: draft, published, archived"]
}
```
**Fix:** Make sure status is exactly one of:
- `draft`
- `published`
- `archived`

### **5. Invalid Date Format**
```
errors: {
    scheduled_at: ["The scheduled_at field must be a valid date in format Y-m-d H:i:s"]
}
```
**Fix:** Date must be in format: `YYYY-MM-DD HH:MM:SS`

### **6. Missing Required Fields**
```
errors: {
    title: ["The title field is required"],
    description: ["The description field is required"]
}
```
**Fix:** Make sure title and description are not empty

---

## 📋 Validation Rules

| Field | Rule | Example |
|-------|------|---------|
| title | required, string, max:255 | "Test Announcement" |
| description | required, string | "Description text" |
| type | required, in:Exams,Events,Alert,General Info | "Exams" |
| priority | required, in:Info,Urgent,Warning | "Info" |
| audience | required, in:All students,Specific class,Specific level | "All students" |
| audience_value | nullable, string | null or "value" |
| scheduled_at | nullable, date_format:Y-m-d H:i:s | "2026-01-02 14:30:00" |
| status | required, in:draft,published,archived | "draft" |

---

## 🔍 What to Check

### **Check 1: Title**
- [ ] Not empty
- [ ] Less than 255 characters
- [ ] Is a string

### **Check 2: Description**
- [ ] Not empty
- [ ] Is a string

### **Check 3: Type**
- [ ] Exactly one of: Exams, Events, Alert, General Info
- [ ] No extra spaces
- [ ] Correct capitalization

### **Check 4: Priority**
- [ ] Exactly one of: Info, Urgent, Warning
- [ ] No extra spaces
- [ ] Correct capitalization

### **Check 5: Audience**
- [ ] Exactly one of: All students, Specific class, Specific level
- [ ] No extra spaces
- [ ] Correct capitalization

### **Check 6: Status**
- [ ] Exactly one of: draft, published, archived
- [ ] Lowercase

### **Check 7: Scheduled At (if provided)**
- [ ] Format: YYYY-MM-DD HH:MM:SS
- [ ] Valid date
- [ ] Valid time

---

## 🚀 Next Steps

1. Open browser console (F12)
2. Try to update announcement
3. Look at the "API Error Response" in console
4. Check which field has the error
5. Fix that field
6. Try again

---

**The console logs will show you exactly which field is failing validation!**

