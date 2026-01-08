# Message Context Menu - UI Component Guide

## Context Menu (Desktop & Mobile)

```
┌─────────────────────┐
│ ✏️  Edit            │
│ 🗑️  Delete          │
└─────────────────────┘
```

**Features:**
- Fixed positioning at cursor/touch point
- Rounded corners (12px border-radius)
- Box shadow for depth
- Hover effects on items
- Delete option in red/danger color
- Icons with text labels

**Styling:**
- Background: White
- Text color: #333 (dark gray)
- Delete text: #dc3545 (red)
- Hover background: #f5f5f5 (light gray)
- Delete hover: #ffe5e5 (light red)

## Edit Message Modal

```
┌──────────────────────────────────┐
│ Edit Message                     │
├──────────────────────────────────┤
│                                  │
│ ┌──────────────────────────────┐ │
│ │ Original message text here   │ │
│ │ (pre-selected for editing)   │ │
│ │                              │ │
│ └──────────────────────────────┘ │
│                                  │
├──────────────────────────────────┤
│              [Cancel]  [Save]    │
└──────────────────────────────────┘
```

**Features:**
- Centered modal overlay
- Textarea with min-height: 100px
- Text pre-selected for easy editing
- Cancel and Save buttons
- Smooth animations
- Click outside to close
- Escape key to close

**Styling:**
- Modal background: White
- Textarea border: #ddd
- Focus border: var(--bs-dark-teal)
- Cancel button: Light gray (#f0f0f0)
- Save button: Dark teal (#004A53)

## Delete Confirmation Modal

```
┌──────────────────────────────────┐
│ Delete Message                   │
├──────────────────────────────────┤
│                                  │
│ Are you sure you want to delete  │
│ this message? This action        │
│ cannot be undone.                │
│                                  │
├──────────────────────────────────┤
│              [Cancel]  [Delete]  │
└──────────────────────────────────┘
```

**Features:**
- Warning message in gray text
- Delete button in red/danger color
- Same modal styling as Edit
- Prevents accidental deletion
- Click outside to close
- Escape key to close

**Styling:**
- Warning text: #666 (medium gray)
- Delete button: #dc3545 (red)
- Delete hover: Darker red

## Message Interaction States

### Desktop
```
Normal Message:
┌─────────────────────────────┐
│ User Name                   │
│ 2:30 PM                     │
│ This is a message           │
└─────────────────────────────┘

Hover (with context menu):
┌─────────────────────────────┐
│ User Name                   │
│ 2:30 PM                     │
│ This is a message           │
│ (cursor: context-menu)      │
└─────────────────────────────┘
Right-click → Context Menu appears
```

### Mobile
```
Normal Message:
┌─────────────────────────────┐
│ User Name                   │
│ 2:30 PM                     │
│ This is a message           │
└─────────────────────────────┘

Long-Press (500ms):
┌─────────────────────────────┐
│ User Name                   │
│ 2:30 PM                     │
│ This is a message           │
│ (highlighted background)    │
└─────────────────────────────┘
→ Context Menu appears at touch point
```

## Color Scheme

| Element | Color | Hex Code |
|---------|-------|----------|
| Primary Button | Dark Teal | #004A53 |
| Danger Button | Red | #dc3545 |
| Hover Background | Light Gray | #f5f5f5 |
| Danger Hover | Light Red | #ffe5e5 |
| Text | Dark Gray | #333 |
| Secondary Text | Medium Gray | #666 |
| Border | Light Gray | #ddd |
| Long-Press Highlight | Teal (10% opacity) | rgba(0,74,83,0.1) |

## Animation Timings

| Animation | Duration | Effect |
|-----------|----------|--------|
| Modal Fade-In | 200ms | Opacity 0 → 1 |
| Modal Slide-Up | 300ms | Transform + Opacity |
| Hover Effect | 200ms | Background color change |
| Long-Press | 500ms | Timer before menu shows |

## Responsive Design

- **Desktop**: Context menu at cursor position
- **Tablet**: Context menu at touch position
- **Mobile**: Context menu at touch position with message highlight
- **All devices**: Modals centered on screen

## Accessibility

- Keyboard navigation (Tab, Enter, Escape)
- Focus indicators on buttons
- Clear visual feedback
- Semantic HTML structure
- ARIA labels (can be added)
- High contrast colors

