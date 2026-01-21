# Badge Congratulation Modal - Integration Guide

## Overview

The Badge Congratulation Modal is a celebratory modal component that displays when users earn badges. It features:

- **Confetti Animation**: Colorful confetti particles that fall from the top
- **Theme Integration**: Uses Kokokah application theme colors (#004a53, #2b6870, #ff6b35)
- **Badge Display**: Shows badge icon (emoji or image), name, description, and points
- **Responsive Design**: Works seamlessly on mobile and desktop
- **Smooth Animations**: Slide-in modal with scale and fade effects

## Theme Colors Used

- **Primary**: `#004a53` (Dark Teal)
- **Primary Hover**: `#2b6870` (Medium Teal)
- **Accent**: `#ff6b35` (Orange)
- **Accent Light**: `#ffa366` (Light Orange)
- **Success**: `#16b265` (Green)
- **Warning**: `#fdaf22` (Yellow)

## Files

- **CSS**: `public/css/badgeCongratulationModal.css`
- **JavaScript**: `public/js/components/badgeCongratulationModal.js`
- **Example**: `public/examples/badge-congratulation-modal-example.html`

## Installation

### 1. Include CSS and JavaScript

Add to your HTML head:
```html
<link rel="stylesheet" href="/css/badgeCongratulationModal.css">
<script src="/js/components/badgeCongratulationModal.js"></script>
```

### 2. Initialize (Optional)

The modal auto-initializes, but you can manually initialize:
```javascript
window.BadgeCongratulationModal.init();
```

## Usage

### Basic Usage

```javascript
const badge = {
    name: 'Achievement Name',
    description: 'Achievement description',
    icon: '🏆',  // Emoji icon
    points: 100
};

window.BadgeCongratulationModal.show(badge);
```

### With Image Icon

```javascript
const badge = {
    name: 'Badge Name',
    description: 'Badge description',
    icon_path: '/storage/badges/icons/badge.png',  // Image path
    points: 50
};

window.BadgeCongratulationModal.show(badge);
```

## API Reference

### Methods

#### `show(badge)`
Display the modal with badge information.

**Parameters:**
- `badge` (Object):
  - `name` (string): Badge name
  - `description` (string): Badge description
  - `icon` (string): Emoji icon (if no icon_path)
  - `icon_path` (string): Path to badge image (optional)
  - `points` (number): Points earned

#### `hide()`
Hide the modal and stop confetti animation.

#### `init()`
Initialize the modal component.

## Styling Customization

Edit `public/css/badgeCongratulationModal.css` to customize:

- Modal colors and gradients
- Animation speeds
- Confetti particle count
- Button styles
- Responsive breakpoints

## Testing

Open `public/examples/badge-congratulation-modal-example.html` in your browser to test the modal with different badge types.

## Integration with Badge System

### Backend Integration (Laravel)

When awarding a badge in your controller:

```php
// In BadgeController or similar
$badge = Badge::find($badgeId);
$user->badges()->attach($badge->id, ['earned_at' => now()]);

// Return badge data for frontend
return response()->json([
    'success' => true,
    'badge' => [
        'name' => $badge->name,
        'description' => $badge->description,
        'icon' => $badge->icon,
        'icon_path' => $badge->icon_path,
        'points' => $badge->points
    ]
]);
```

### Frontend Integration (JavaScript)

```javascript
// After badge is awarded via API
async function awardBadge(badgeId) {
    try {
        const response = await fetch(`/api/badges/award`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ badge_id: badgeId })
        });

        const data = await response.json();
        if (data.success) {
            window.BadgeCongratulationModal.show(data.badge);
        }
    } catch (error) {
        console.error('Error awarding badge:', error);
    }
}
```

## Browser Support

- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- Mobile browsers: Full support with responsive design

