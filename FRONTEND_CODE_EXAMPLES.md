# Frontend Code Examples & Improvements

## 1. Blade Components (Recommended)

### Before: Repeated Button Code
```blade
<!-- In multiple templates -->
<button class="btn primaryButton" type="button">Click me</button>
<button class="btn secondaryButton" type="button">Cancel</button>
<button class="btn primaryButton" style="background: #004A53;">Submit</button>
```

### After: Reusable Component
```blade
<!-- resources/views/components/button.blade.php -->
@props(['variant' => 'primary', 'size' => 'md', 'disabled' => false])

<button 
  class="btn btn-{{ $variant }} btn-{{ $size }}"
  @disabled($disabled)
  {{ $attributes }}
>
  {{ $slot }}
</button>

<!-- Usage -->
<x-button variant="primary">Click me</x-button>
<x-button variant="secondary">Cancel</x-button>
<x-button variant="primary" disabled>Disabled</x-button>
```

---

## 2. CSS Organization (Tailwind CSS)

### Before: Inline Styles
```blade
<div class="row mt-3 mb-5 p-5">
  <h1 class="heroheading" style="color: #004A53; font-size: 56px;">
    Quality Learning
  </h1>
</div>
```

### After: Tailwind Utilities
```blade
<div class="grid grid-cols-1 gap-4 mt-3 mb-5 p-5">
  <h1 class="text-5xl font-bold text-primary">
    Quality Learning
  </h1>
</div>

<!-- tailwind.config.js -->
module.exports = {
  theme: {
    colors: {
      primary: '#004A53',
      secondary: '#FDAF22',
    }
  }
}
```

---

## 3. JavaScript Modules

### Before: Inline Script
```blade
<!-- In usertemplate.blade.php -->
<script>
  const sidebar = document.getElementById('sidebar');
  const hamburger = document.getElementById('hamburger');
  
  hamburger.addEventListener('click', () => {
    sidebar.classList.add('show');
  });
</script>
```

### After: Modular JavaScript
```javascript
// resources/js/modules/sidebar.js
export class SidebarManager {
  constructor() {
    this.sidebar = document.getElementById('sidebar');
    this.hamburger = document.getElementById('hamburger');
    this.overlay = document.getElementById('sidebarOverlay');
    this.init();
  }

  init() {
    this.hamburger?.addEventListener('click', () => this.open());
    this.overlay?.addEventListener('click', () => this.close());
  }

  open() {
    this.sidebar?.classList.add('show');
    this.overlay?.classList.add('show');
  }

  close() {
    this.sidebar?.classList.remove('show');
    this.overlay?.classList.remove('show');
  }
}

// resources/js/app.js
import { SidebarManager } from './modules/sidebar.js';
new SidebarManager();
```

---

## 4. Form Validation

### Before: No Validation
```blade
<form>
  <input type="email" class="form-control" placeholder="Email">
  <input type="password" class="form-control" placeholder="Password">
  <button type="submit">Login</button>
</form>
```

### After: With Validation
```blade
<!-- resources/views/components/form-input.blade.php -->
@props(['name', 'label', 'type' => 'text', 'required' => false, 'error' => null])

<div class="mb-3">
  <label for="{{ $name }}" class="form-label">{{ $label }}</label>
  <input 
    type="{{ $type }}"
    id="{{ $name }}"
    name="{{ $name }}"
    class="form-control @error($name) is-invalid @enderror"
    @required($required)
    {{ $attributes }}
  >
  @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<!-- Usage -->
<form method="POST" action="/login">
  <x-form-input name="email" label="Email" type="email" required />
  <x-form-input name="password" label="Password" type="password" required />
  <x-button type="submit">Login</x-button>
</form>
```

---

## 5. Accessibility Improvements

### Before: Missing ARIA
```blade
<nav class="navbar">
  <button class="navbar-toggler">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-collapse">
    <ul class="navbar-nav">
      <li><a href="/">Home</a></li>
    </ul>
  </div>
</nav>
```

### After: Accessible Navigation
```blade
<nav class="navbar" aria-label="Main navigation">
  <button 
    class="navbar-toggler"
    type="button"
    aria-controls="navbarNav"
    aria-expanded="false"
    aria-label="Toggle navigation"
  >
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li><a href="/" aria-current="page">Home</a></li>
      <li><a href="/about">About</a></li>
    </ul>
  </div>
</nav>
```

---

## 6. Image Optimization

### Before: No Lazy Loading
```blade
<img src="images/LMS.png" class="img-fluid">
<img src="images/Video.png" class="img-fluid">
```

### After: Lazy Loading
```blade
<img 
  src="images/LMS.png" 
  alt="LMS Dashboard"
  class="img-fluid"
  loading="lazy"
  width="800"
  height="600"
>

<!-- Or with Blade component -->
<x-image 
  src="images/LMS.png" 
  alt="LMS Dashboard"
  lazy
/>
```

---

## 7. Layout Consolidation

### Before: 3 Layout Files
```
layouts/template.blade.php (public)
admin/dashboardtemp.blade.php (admin)
users/usertemplate.blade.php (student)
```

### After: 2 Layout Files
```blade
<!-- layouts/app.blade.php (public) -->
<!DOCTYPE html>
<html>
  <head>@include('partials.head')</head>
  <body>
    <x-navbar />
    {{ $slot }}
    <x-footer />
  </body>
</html>

<!-- layouts/dashboard.blade.php (authenticated) -->
<!DOCTYPE html>
<html>
  <head>@include('partials.head')</head>
  <body>
    <x-sidebar />
    <x-topbar />
    {{ $slot }}
    <x-footer />
  </body>
</html>

<!-- Usage -->
<x-layouts.app>
  @yield('content')
</x-layouts.app>
```

---

## 8. Dark Mode Support

### Tailwind Configuration
```javascript
// tailwind.config.js
module.exports = {
  darkMode: 'class',
  theme: {
    colors: {
      primary: {
        light: '#004A53',
        dark: '#E0F2F1',
      }
    }
  }
}
```

### Blade Component
```blade
<!-- resources/views/components/theme-toggle.blade.php -->
<button 
  id="theme-toggle"
  class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700"
  aria-label="Toggle dark mode"
>
  <i class="fas fa-moon dark:hidden"></i>
  <i class="fas fa-sun hidden dark:block"></i>
</button>

<script>
  const toggle = document.getElementById('theme-toggle');
  toggle.addEventListener('click', () => {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', 
      document.documentElement.classList.contains('dark') ? 'dark' : 'light'
    );
  });
</script>
```

---

## 9. API Integration

### Before: jQuery AJAX
```javascript
$('.btn').on('click', function() {
  $.ajax({
    url: '/api/data',
    method: 'GET',
    success: function(data) {
      console.log(data);
    }
  });
});
```

### After: Fetch API
```javascript
// resources/js/utils/api.js
export async function fetchData(url, options = {}) {
  try {
    const response = await fetch(url, {
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        ...options.headers
      },
      ...options
    });
    
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return await response.json();
  } catch (error) {
    console.error('API Error:', error);
    throw error;
  }
}

// Usage
import { fetchData } from './utils/api.js';

document.querySelectorAll('.btn').forEach(btn => {
  btn.addEventListener('click', async () => {
    try {
      const data = await fetchData('/api/data');
      console.log(data);
    } catch (error) {
      console.error('Failed to fetch data:', error);
    }
  });
});
```

---

## 10. Component Library Documentation

```markdown
# Button Component

## Usage
\`\`\`blade
<x-button variant="primary" size="lg">Click me</x-button>
\`\`\`

## Props
- `variant`: primary, secondary, danger (default: primary)
- `size`: sm, md, lg (default: md)
- `disabled`: boolean (default: false)

## Examples
\`\`\`blade
<x-button>Default</x-button>
<x-button variant="secondary">Secondary</x-button>
<x-button disabled>Disabled</x-button>
\`\`\`
```

---

**These examples show the path forward for modernizing the Kokokah frontend.**

