# 🔘 Button Logic - Quick Reference

## Two Buttons, Two Functions

### **Update Button**
```
Purpose: Update announcement data only
Status: Keeps current status (draft or published)
Action: Click → Save changes → Redirect
```

### **Status Button (Dynamic)**
```
Purpose: Toggle announcement status
Text: Changes based on current status
- "Publish" when draft
- "Save as Draft" when published
Action: Click → Change status → Redirect
```

---

## 📊 Status Matrix

| Current Status | Update Button | Status Button | Result |
|---|---|---|---|
| Draft | Updates data, keeps draft | Shows "Publish" | Can publish |
| Published | Updates data, keeps published | Shows "Save as Draft" | Can revert to draft |

---

## 🔄 Examples

### **Example 1: Edit Draft Announcement**
```
1. Load draft announcement
2. Status button shows "Publish"
3. Edit title → Click "Update"
   Result: Title updated, status stays draft
4. Click "Publish"
   Result: Status changes to published
```

### **Example 2: Edit Published Announcement**
```
1. Load published announcement
2. Status button shows "Save as Draft"
3. Edit description → Click "Update"
   Result: Description updated, status stays published
4. Click "Save as Draft"
   Result: Status changes to draft
```

---

## 💻 Code Logic

### **Update Button**
```javascript
document.querySelector('.update-btn').addEventListener('click', () => {
    this.submitForm(this.currentStatus); // Keep current status
});
```

### **Status Button**
```javascript
document.querySelector('.status-btn').addEventListener('click', () => {
    const newStatus = this.currentStatus === 'draft' ? 'published' : 'draft';
    this.submitForm(newStatus); // Toggle status
});
```

### **Button Text Update**
```javascript
updateStatusButton() {
    const statusBtn = document.getElementById('statusBtn');
    if (this.currentStatus === 'draft') {
        statusBtn.textContent = 'Publish';
    } else if (this.currentStatus === 'published') {
        statusBtn.textContent = 'Save as Draft';
    }
}
```

---

## ✅ Testing Checklist

- [ ] Load draft announcement → Status button shows "Publish"
- [ ] Load published announcement → Status button shows "Save as Draft"
- [ ] Click "Update" on draft → Data saved, status stays draft
- [ ] Click "Update" on published → Data saved, status stays published
- [ ] Click "Publish" on draft → Status changes to published
- [ ] Click "Save as Draft" on published → Status changes to draft

---

## 🎯 Key Points

✅ Update button only updates data
✅ Status button toggles status
✅ Button text changes dynamically
✅ Current status tracked in memory
✅ Status updates after submission
✅ Form validation before save

---

**Ready to use!**

