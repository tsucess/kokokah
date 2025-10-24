import './bootstrap';

// import 'bootstrap';
// import 'bootstrap/dist/css/bootstrap.min.css';

import 'jquery';

import '@fortawesome/fontawesome-free/css/all.min.css';

import Chart from 'chart.js/auto';
window.Chart = Chart;

// ============================================
// API Service Layer & Animations
// ============================================

// Import API service layer
import './services/api';

// Import AOS (Animate On Scroll)
import AOS from 'aos';

// Initialize AOS
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100,
        easing: 'ease-in-out',
    });
});
