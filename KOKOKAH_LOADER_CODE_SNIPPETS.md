# 💻 Kokokah Logo Loader - Code Snippets Reference

**Quick access to all code snippets used in the implementation**

---

## 📁 File Locations

```
public/
├── css/
│   └── loader.css                    (NEW - 188 lines)
├── js/
│   ├── api/
│   │   └── baseApiClient.js          (MODIFIED - Added loader calls)
│   └── utils/
│       └── kokokahLoader.js          (NEW - 183 lines)
└── images/
    └── Kokokah_Logo.png              (EXISTING - Used by loader)

resources/
└── views/
    └── layouts/
        └── dashboardtemp.blade.php   (MODIFIED - Added CSS & script)
```

---

## 🎨 CSS Snippets

### Main Loader Container
```css
.kokokah-loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.95);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  backdrop-filter: blur(2px);
}

.kokokah-loader-overlay.active {
  display: flex;
}
```

### Logo Animation
```css
.kokokah-loader-logo {
  width: 120px;
  height: 120px;
  animation: logoFloat 2s ease-in-out infinite;
}

@keyframes logoFloat {
  0%, 100% {
    transform: translateY(0px);
    opacity: 1;
  }
  50% {
    transform: translateY(-20px);
    opacity: 0.8;
  }
}
```

### Glow Effect
```css
.kokokah-loader-logo-glow {
  animation: logoPulse 2s infinite;
  border-radius: 50%;
  padding: 10px;
}

@keyframes logoPulse {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(0, 74, 83, 0.4);
  }
  50% {
    box-shadow: 0 0 0 15px rgba(0, 74, 83, 0);
  }
}
```

### Bouncing Dots
```css
.kokokah-loader-dots span {
  animation: dotBounce 1.4s infinite;
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #FDAF22;
  margin: 0 3px;
}

.kokokah-loader-dots span:nth-child(1) {
  animation-delay: 0s;
}

.kokokah-loader-dots span:nth-child(2) {
  animation-delay: 0.2s;
}

.kokokah-loader-dots span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes dotBounce {
  0%, 60%, 100% {
    transform: translateY(0);
    opacity: 0.7;
  }
  30% {
    transform: translateY(-10px);
    opacity: 1;
  }
}
```

### Progress Bar
```css
.kokokah-loader-progress {
  width: 200px;
  height: 4px;
  background: #e0e0e0;
  border-radius: 2px;
  overflow: hidden;
  margin-top: 1rem;
}

.kokokah-loader-progress-bar {
  height: 100%;
  background: linear-gradient(90deg, #004A53, #FDAF22);
  border-radius: 2px;
  animation: progressMove 2s ease-in-out infinite;
}

@keyframes progressMove {
  0% {
    width: 0%;
  }
  50% {
    width: 100%;
  }
  100% {
    width: 100%;
  }
}
```

---

## 🔧 JavaScript Snippets

### Loader Class Constructor
```javascript
class KokokahLoader {
  constructor() {
    this.loaderElement = null;
    this.isVisible = false;
    this.hideTimeout = null;
    this.minDisplayTime = 300;
    this.showTime = null;
    this.init();
  }
}
```

### Show Method
```javascript
show() {
  if (this.isVisible) return;

  this.isVisible = true;
  this.showTime = Date.now();

  if (this.loaderElement) {
    this.loaderElement.classList.remove('fade-out');
    this.loaderElement.classList.add('active', 'fade-in');
  }

  if (this.hideTimeout) {
    clearTimeout(this.hideTimeout);
    this.hideTimeout = null;
  }
}
```

### Hide Method
```javascript
hide() {
  if (!this.isVisible) return;

  const elapsedTime = Date.now() - this.showTime;
  const remainingTime = Math.max(0, this.minDisplayTime - elapsedTime);

  if (this.hideTimeout) {
    clearTimeout(this.hideTimeout);
  }

  this.hideTimeout = setTimeout(() => {
    if (this.loaderElement) {
      this.loaderElement.classList.remove('fade-in');
      this.loaderElement.classList.add('fade-out');

      setTimeout(() => {
        this.loaderElement.classList.remove('active', 'fade-out');
        this.isVisible = false;
      }, 300);
    }
  }, remainingTime);
}
```

### Event Listeners
```javascript
setupEventListeners() {
  // Link clicks
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (link && !link.hasAttribute('data-no-loader')) {
      const href = link.getAttribute('href');
      if (href && !href.startsWith('http') && 
          !href.startsWith('mailto:') && 
          !href.startsWith('tel:')) {
        this.show();
      }
    }
  });

  // Form submissions
  document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form.hasAttribute('data-no-loader')) {
      this.show();
    }
  });

  // Page load
  window.addEventListener('load', () => {
    this.hide();
  });

  // Browser navigation
  window.addEventListener('popstate', () => {
    this.hide();
  });
}
```

---

## 🔌 Integration Snippets

### Layout Template (dashboardtemp.blade.php)
```html
<!-- Add to <head> section -->
<link rel="stylesheet" href="{{ asset('css/loader.css') }}">

<!-- Add before closing </body> tag -->
<script src="{{ asset('js/utils/kokokahLoader.js') }}"></script>
```

### BaseApiClient Integration
```javascript
// In GET method
static async get(endpoint, config = {}) {
  try {
    this.showLoader();
    const response = await this.fetchWithTimeout(...);
    if (!response.ok) {
      this.hideLoader();
      return this.handleErrorResponse(response);
    }
    this.hideLoader();
    return this.handleSuccess(response);
  } catch (error) {
    this.hideLoader();
    return this.handleError(error);
  }
}

// Add these methods
static showLoader() {
  if (window.kokokahLoader) {
    window.kokokahLoader.show();
  }
}

static hideLoader() {
  if (window.kokokahLoader) {
    window.kokokahLoader.hide();
  }
}
```

---

## 📝 HTML Snippets

### Loader HTML Structure
```html
<div id="kokokahLoaderOverlay" class="kokokah-loader-overlay">
  <div class="kokokah-loader-container">
    <div class="kokokah-loader-logo-glow">
      <div class="kokokah-loader-logo">
        <img src="/images/Kokokah_Logo.png" alt="Kokokah Loading">
      </div>
    </div>
    <div class="kokokah-loader-text">
      Loading<span class="kokokah-loader-dots">
        <span></span>
        <span></span>
        <span></span>
      </span>
    </div>
    <div class="kokokah-loader-progress">
      <div class="kokokah-loader-progress-bar"></div>
    </div>
  </div>
</div>
```

### Disable Loader for Link
```html
<a href="/page" data-no-loader>Link</a>
```

### Disable Loader for Form
```html
<form data-no-loader>
  <input type="text" name="name">
  <button type="submit">Submit</button>
</form>
```

---

## 🎯 Usage Snippets

### Show Loader
```javascript
window.kokokahLoader.show();
```

### Hide Loader
```javascript
window.kokokahLoader.hide();
```

### Force Hide
```javascript
window.kokokahLoader.forceHide();
```

### Show for Duration
```javascript
window.kokokahLoader.showForAction(1000); // 1 second
```

### Check if Visible
```javascript
if (window.kokokahLoader.isVisible) {
  console.log('Loader is visible');
}
```

---

## 🔍 Debugging Snippets

### Check Loader Exists
```javascript
console.log(window.kokokahLoader);
```

### Check Loader State
```javascript
console.log('Visible:', window.kokokahLoader.isVisible);
console.log('Element:', window.kokokahLoader.loaderElement);
```

### Force Hide (if stuck)
```javascript
window.kokokahLoader.forceHide();
```

### Check CSS Loaded
```javascript
const loaderCSS = document.querySelector('link[href*="loader.css"]');
console.log('CSS Loaded:', !!loaderCSS);
```

---

## 📊 Configuration Snippets

### Change Minimum Display Time
```javascript
// In kokokahLoader.js constructor
this.minDisplayTime = 500; // 500ms instead of 300ms
```

### Change Logo Size
```css
/* In loader.css */
.kokokah-loader-logo {
  width: 150px;  /* Change from 120px */
  height: 150px;
}
```

### Change Colors
```css
/* Text color */
.kokokah-loader-text {
  color: #FF6B6B;  /* Change from #004A53 */
}

/* Dots color */
.kokokah-loader-dots span {
  background-color: #4ECDC4;  /* Change from #FDAF22 */
}
```

### Change Animation Speed
```css
/* Logo float */
.kokokah-loader-logo {
  animation: logoFloat 3s ease-in-out infinite;  /* 3s instead of 2s */
}

/* Dots bounce */
.kokokah-loader-dots span {
  animation: dotBounce 2s infinite;  /* 2s instead of 1.4s */
}
```

---

**All code snippets are ready to use! Copy and paste as needed.** ✅


