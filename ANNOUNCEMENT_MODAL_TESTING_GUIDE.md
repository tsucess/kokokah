# 🧪 Announcement Modal - Testing Guide

## ✅ Test Checklist

### Admin Page - Edit Announcement

#### Test 1: Open Edit Modal
- [ ] Go to `/announcement` (admin)
- [ ] Click three vertical dots on announcement
- [ ] Click "Edit"
- [ ] Modal opens with announcement data
- [ ] All fields populated correctly

#### Test 2: Edit Title
- [ ] Change title to "Updated Title"
- [ ] Click "Save Changes"
- [ ] Modal closes
- [ ] Announcement list reloads
- [ ] Title updated in list

#### Test 3: Edit Description
- [ ] Click three dots on announcement
- [ ] Click "Edit"
- [ ] Change description
- [ ] Click "Save Changes"
- [ ] Description updated

#### Test 4: Edit Type
- [ ] Click three dots
- [ ] Click "Edit"
- [ ] Change type to different value
- [ ] Click "Save Changes"
- [ ] Type updated

#### Test 5: Edit Priority
- [ ] Click three dots
- [ ] Click "Edit"
- [ ] Change priority
- [ ] Click "Save Changes"
- [ ] Priority updated

#### Test 6: Edit Status
- [ ] Click three dots
- [ ] Click "Edit"
- [ ] Change status (draft ↔ published)
- [ ] Click "Save Changes"
- [ ] Status updated

#### Test 7: Cancel Edit
- [ ] Click three dots
- [ ] Click "Edit"
- [ ] Make changes
- [ ] Click "Cancel"
- [ ] Modal closes
- [ ] Changes NOT saved

---

### Admin Page - Delete Announcement

#### Test 8: Open Delete Modal
- [ ] Click three dots on announcement
- [ ] Click "Delete"
- [ ] Confirmation modal opens
- [ ] Shows announcement title

#### Test 9: Confirm Delete
- [ ] Click three dots
- [ ] Click "Delete"
- [ ] Click "Delete" button
- [ ] Modal closes
- [ ] Announcement removed from list

#### Test 10: Cancel Delete
- [ ] Click three dots
- [ ] Click "Delete"
- [ ] Click "Cancel"
- [ ] Modal closes
- [ ] Announcement still in list

---

### Student Page - View Announcement

#### Test 11: Open View Modal
- [ ] Go to `/announcement` (student)
- [ ] Click on announcement card
- [ ] View modal opens
- [ ] Shows full details

#### Test 12: View Details
- [ ] Click announcement
- [ ] Modal shows:
  - [ ] Title
  - [ ] Type
  - [ ] Priority
  - [ ] Audience
  - [ ] Posted date
  - [ ] Description
  - [ ] Scheduled date (if set)

#### Test 13: Close View Modal
- [ ] Click announcement
- [ ] Click "Close"
- [ ] Modal closes

#### Test 14: No Edit/Delete
- [ ] Go to student page
- [ ] Verify NO three dots menu
- [ ] Verify NO edit option
- [ ] Verify NO delete option

---

### Tab Filtering

#### Test 15: Filter by Type (Admin)
- [ ] Click "Exams" tab
- [ ] Only Exams shown
- [ ] Count updated
- [ ] Edit/Delete still work

#### Test 16: Filter by Type (Student)
- [ ] Click "Events" tab
- [ ] Only Events shown
- [ ] Count updated
- [ ] View still works

#### Test 17: Filter All
- [ ] Click "All" tab
- [ ] All announcements shown
- [ ] Count updated

---

### Error Handling

#### Test 18: Validation Error
- [ ] Click three dots
- [ ] Click "Edit"
- [ ] Clear title field
- [ ] Click "Save Changes"
- [ ] Error message shown
- [ ] Modal stays open

#### Test 19: Network Error
- [ ] Disconnect internet
- [ ] Try to edit
- [ ] Error message shown
- [ ] Can retry

#### Test 20: Authorization Error
- [ ] Try to edit other user's announcement
- [ ] Should get 403 error
- [ ] Error message shown

---

### Date/Time Formatting

#### Test 21: Schedule Date
- [ ] Click three dots
- [ ] Click "Edit"
- [ ] Set schedule date
- [ ] Click "Save Changes"
- [ ] Date saved correctly

#### Test 22: Time Ago Display
- [ ] Check "Posted" time
- [ ] Should show relative time
- [ ] Examples: "2h ago", "1d ago", "just now"

---

### Responsive Design

#### Test 23: Mobile View
- [ ] Open on mobile
- [ ] Modal responsive
- [ ] Buttons clickable
- [ ] Form readable

#### Test 24: Tablet View
- [ ] Open on tablet
- [ ] Modal responsive
- [ ] All features work

---

## 🐛 Common Issues

### Modal Not Opening
- Check browser console for errors
- Verify Bootstrap is loaded
- Check announcement ID is correct

### Changes Not Saving
- Check network tab for API response
- Verify token is valid
- Check validation errors

### Dropdown Menu Not Showing
- Click three dots again
- Check Bootstrap dropdown CSS
- Verify JavaScript loaded

---

## ✅ Success Criteria

All tests should pass:
- [ ] Edit modal opens and closes
- [ ] Edit saves changes
- [ ] Delete modal opens and closes
- [ ] Delete removes announcement
- [ ] Student view modal works
- [ ] No edit/delete on student page
- [ ] Tab filtering works
- [ ] Error messages show
- [ ] Responsive on all devices

---

**Status:** Ready for Testing
**Date:** January 2, 2026

