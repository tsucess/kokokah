# Badge Congratulation Modal - Quick Reference

## Quick Start

### 1. Include in Your HTML
```html
<link rel="stylesheet" href="/css/badgeCongratulationModal.css">
<script src="/js/components/badgeCongratulationModal.js"></script>
```

### 2. Show a Badge
```javascript
window.BadgeCongratulationModal.show({
    name: 'Badge Name',
    description: 'Badge description',
    icon: '🏆',
    points: 100
});
```

## Common Use Cases

### Achievement Badge
```javascript
window.BadgeCongratulationModal.show({
    name: 'Course Completed',
    description: 'You have successfully completed the course!',
    icon: '🎓',
    points: 100
});
```

### Streak Badge
```javascript
window.BadgeCongratulationModal.show({
    name: '7-Day Streak',
    description: 'Keep up the amazing consistency!',
    icon: '🔥',
    points: 50
});
```

### Community Badge
```javascript
window.BadgeCongratulationModal.show({
    name: 'Helpful Member',
    description: 'You helped 5 students today!',
    icon: '🤝',
    points: 75
});
```

### With Image Icon
```javascript
window.BadgeCongratulationModal.show({
    name: 'Master Badge',
    description: 'You are now a master!',
    icon_path: '/storage/badges/master.png',
    points: 200
});
```

## API Methods

### show(badge)
Display the modal with badge information.

**Parameters:**
```javascript
{
    name: string,           // Badge name (required)
    description: string,    // Badge description (required)
    icon: string,          // Emoji icon (optional, default: '🏆')
    icon_path: string,     // Image path (optional, overrides icon)
    points: number         // Points earned (optional, default: 0)
}
```

### hide()
Hide the modal and stop confetti.
```javascript
window.BadgeCongratulationModal.hide();
```

### init()
Initialize the modal (auto-called).
```javascript
window.BadgeCongratulationModal.init();
```

## Theme Colors

```css
--badge-primary: #004a53;        /* Dark Teal */
--badge-primary-hover: #2b6870;  /* Medium Teal */
--badge-accent: #ff6b35;         /* Orange */
--badge-accent-light: #ffa366;   /* Light Orange */
--badge-success: #16b265;        /* Green */
--badge-warning: #fdaf22;        /* Yellow */
--badge-light-bg: #ecfdff;       /* Light Background */
```

## Customization

### Change Modal Colors
Edit `public/css/badgeCongratulationModal.css`:
```css
:root {
    --badge-primary: #your-color;
    --badge-accent: #your-color;
}
```

### Change Confetti Count
Edit `public/js/components/badgeCongratulationModal.js`:
```javascript
// Line 153: Change 50 to desired count
for (let i = 0; i < 50; i++) {
    this.confettiParticles.push(this.createConfettiParticle());
}
```

### Change Animation Duration
Edit CSS animations:
```css
.badge-modal-content {
    animation: modalSlideIn 0.4s cubic-bezier(...);
    /* Change 0.4s to desired duration */
}
```

## Testing

### Test Files
- Basic: `public/examples/badge-congratulation-modal-example.html`
- Full Suite: `public/examples/badge-modal-test.html`

### Browser Console Test
```javascript
// Open browser console and run:
window.BadgeCongratulationModal.show({
    name: 'Test Badge',
    description: 'Testing the modal',
    icon: '⭐',
    points: 100
});
```

## Troubleshooting

### Modal not showing
- Check CSS file is loaded
- Check JS file is loaded
- Verify `window.BadgeCongratulationModal` exists

### Confetti not animating
- Check browser supports Canvas
- Check JavaScript console for errors
- Verify CSS animations are not disabled

### Theme colors not applied
- Clear browser cache
- Check CSS file path is correct
- Verify CSS variables are defined

## Performance Tips

- Modal is lightweight (~250 lines CSS, ~230 lines JS)
- Confetti uses requestAnimationFrame for smooth animation
- Resources auto-cleanup after animation completes
- Safe to call multiple times

## Browser Support

| Browser | Support |
|---------|---------|
| Chrome | ✅ Full |
| Firefox | ✅ Full |
| Safari | ✅ Full |
| Edge | ✅ Full |
| Mobile | ✅ Full |

## Related Files

- CSS: `public/css/badgeCongratulationModal.css`
- JS: `public/js/components/badgeCongratulationModal.js`
- Guide: `docs/BADGE_CONGRATULATION_MODAL_GUIDE.md`
- Summary: `docs/BADGE_MODAL_IMPLEMENTATION_SUMMARY.md`

