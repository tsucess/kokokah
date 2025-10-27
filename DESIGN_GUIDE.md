# Kokokah Design Guide

**Version:** 1.0  
**Date:** October 26, 2025  
**Status:** Design System Documented

---

## 🎨 Brand Colors

### Primary Teal
```
Color: #004A53
Usage: Main brand color, text, buttons, accents
Tailwind: primary-600
```

### Secondary Yellow
```
Color: #FDAF22
Usage: Hero sections, CTAs, highlights
Tailwind: secondary-500
```

### Hover State Teal
```
Color: #2B6870
Usage: Button hover states, secondary elements
Tailwind: primary-500
```

### Neutral Colors
```
White: #FFFFFF (neutral-50)
Light Gray: #F6F8FA (neutral-100)
Medium Gray: #E9ECEF (neutral-200)
Dark Text: #1C1D1D (neutral-900)
Muted: #6b737a (neutral-600)
```

### Accent Colors
```
Orange: #FF6B35 (CTA sections)
Pink: #FF1493 (Highlights)
Green: #4CAF50 (Success)
```

---

## 📐 Typography

### Font Families
- **Headings:** Fredoka (Bold)
- **Body:** Inter or Quicksand (Regular)
- **Navigation:** Outfit

### Heading Sizes
```
H1: 56px, Bold, Fredoka
H2: 48px, Bold, Fredoka
H3: 40px, Bold, Fredoka
H4: 32px, Bold, Fredoka
H5: 24px, Bold, Fredoka
H6: 20px, Bold, Fredoka
```

### Body Text
```
Body: 16px, Regular, Inter
Small: 14px, Regular, Inter
```

---

## 🎯 Component Styles

### Primary Button (CTA)
```html
<button class="px-6 py-3 bg-secondary-500 text-black font-bold rounded-md hover:bg-secondary-600">
    Explore Kokokah Project
</button>
```

### Secondary Button
```html
<button class="px-6 py-3 bg-white text-primary-600 border-2 border-primary-600 font-bold rounded-md hover:bg-primary-600 hover:text-white">
    Get a Demo
</button>
```

### Tertiary Button
```html
<button class="px-6 py-3 bg-primary-600 text-white font-bold rounded-md hover:bg-primary-700">
    Sign Up to Teach
</button>
```

### Form Input
```html
<input type="text" class="w-full px-4 py-3 border border-neutral-200 rounded-md focus:border-primary-600 focus:outline-none">
```

### Card
```html
<div class="bg-white rounded-lg shadow-card p-6 hover:shadow-medium transition-shadow">
    <!-- Card content -->
</div>
```

---

## 📏 Spacing System

```
XS: 4px
SM: 8px
MD: 16px
LG: 24px
XL: 32px
2XL: 40px
3XL: 60px
4XL: 80px
```

### Usage
- **Padding:** p-md, p-lg, p-xl
- **Margin:** m-md, m-lg, m-xl
- **Gap:** gap-md, gap-lg, gap-xl

---

## 🎨 Section Styles

### Hero Section
```html
<section class="bg-secondary-500 py-4xl px-lg">
    <div class="max-w-container mx-auto">
        <h1 class="text-h1 text-black mb-lg">Teach. Inspire. Earn.</h1>
        <p class="text-body text-black mb-xl">Your complete ecosystem for learning and growth.</p>
        <button class="bg-primary-600 text-white px-lg py-md rounded-md">Get Started</button>
    </div>
</section>
```

### Feature Section
```html
<section class="bg-white py-4xl px-lg">
    <div class="max-w-container mx-auto">
        <h2 class="text-h2 text-primary-600 mb-3xl text-center">Why Become a Tutor?</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg">
            <!-- Feature cards -->
        </div>
    </div>
</section>
```

### CTA Section
```html
<section class="bg-accent-orange py-3xl px-lg">
    <div class="max-w-container mx-auto">
        <h2 class="text-h2 text-white mb-lg">Ready to Start Teaching?</h2>
        <button class="bg-secondary-500 text-black px-lg py-md rounded-md">Apply Now</button>
    </div>
</section>
```

### Footer
```html
<footer class="bg-primary-600 text-white py-3xl px-lg">
    <div class="max-w-container mx-auto">
        <!-- Footer content -->
    </div>
</footer>
```

---

## 📱 Responsive Design

### Breakpoints
```
Mobile: 0-576px
Tablet: 576px-768px
Desktop: 768px+
Large: 1200px+
```

### Mobile-First Approach
```html
<!-- Mobile: 1 column -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
    <!-- Items -->
</div>
```

---

## ✨ Visual Effects

### Shadows
```
Light: shadow-light (0 2px 4px rgba(0,0,0,0.05))
Card: shadow-card (0 2px 8px rgba(0,0,0,0.1))
Medium: shadow-medium (0 4px 12px rgba(0,0,0,0.15))
Heavy: shadow-heavy (0 8px 20px rgba(0,0,0,0.2))
```

### Transitions
```
Duration: 300ms
Timing: ease-in-out
Properties: background-color, color, border-color, box-shadow
```

### Border Radius
```
XS: 4px
SM: 6px
MD: 8px
LG: 12px
XL: 16px
2XL: 20px
```

---

## 🎯 Page Layouts

### Home Page Structure
1. Navigation Bar (white background)
2. Hero Section (yellow background)
3. Features Section (white background)
4. Testimonials Section (light gray background)
5. CTA Section (orange background)
6. Newsletter Section (yellow background)
7. Footer (teal background)

### Tutor Page Structure
1. Navigation Bar
2. Hero Section with Form
3. Requirements Section
4. Application Form
5. Footer

### LMS Page Structure
1. Navigation Bar
2. Hero Section
3. Features Section
4. Pricing Section
5. FAQ Section
6. CTA Section
7. Footer

---

## 🔄 Design Tokens in Tailwind

### Colors
```
primary-600: #004A53 (Main teal)
primary-500: #2B6870 (Hover teal)
secondary-500: #FDAF22 (Main yellow)
neutral-100: #F6F8FA (Light background)
neutral-900: #1C1D1D (Dark text)
```

### Typography
```
font-fredoka: Fredoka
font-inter: Inter
text-h1: 56px bold
text-h2: 48px bold
text-body: 16px regular
```

### Spacing
```
p-lg: 24px padding
m-xl: 32px margin
gap-lg: 24px gap
py-4xl: 80px vertical padding
```

---

## 📋 Implementation Checklist

- [ ] Update all page layouts to match design
- [ ] Apply correct colors to all sections
- [ ] Use proper typography hierarchy
- [ ] Implement responsive design
- [ ] Add hover states to buttons
- [ ] Verify color contrast
- [ ] Test on mobile devices
- [ ] Verify accessibility

---

## 🚀 Next Steps

1. **Update Home Page** - Apply new design system
2. **Update Tutor Page** - Apply new design system
3. **Update LMS Page** - Apply new design system
4. **Test Responsive** - Verify on all devices
5. **Accessibility Audit** - Check WCAG compliance

---

**Design Guide Complete**

