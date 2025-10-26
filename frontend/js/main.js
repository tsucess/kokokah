/**
 * Kokokah LMS - Main JavaScript
 * Utility functions and event handlers
 */

// Notification System
class Notification {
    static show(message, type = 'info', duration = 3000) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const container = document.querySelector('.container') || document.body;
        container.insertBefore(alertDiv, container.firstChild);

        if (duration) {
            setTimeout(() => {
                alertDiv.remove();
            }, duration);
        }

        return alertDiv;
    }

    static success(message, duration = 3000) {
        return this.show(message, 'success', duration);
    }

    static error(message, duration = 5000) {
        return this.show(message, 'danger', duration);
    }

    static warning(message, duration = 4000) {
        return this.show(message, 'warning', duration);
    }

    static info(message, duration = 3000) {
        return this.show(message, 'info', duration);
    }
}

// Loading Spinner
class Spinner {
    static show() {
        let spinner = document.getElementById('spinnerOverlay');
        if (!spinner) {
            spinner = document.createElement('div');
            spinner.id = 'spinnerOverlay';
            spinner.className = 'spinner-overlay';
            spinner.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            document.body.appendChild(spinner);
        }
        spinner.classList.remove('hidden');
    }

    static hide() {
        const spinner = document.getElementById('spinnerOverlay');
        if (spinner) {
            spinner.classList.add('hidden');
        }
    }
}

// Form Validation
class FormValidator {
    static validate(formElement) {
        const errors = {};

        // Get all form fields
        const fields = formElement.querySelectorAll('[required]');

        fields.forEach(field => {
            const value = field.value.trim();
            const name = field.name;
            const type = field.type;

            // Check if empty
            if (!value) {
                errors[name] = `${field.label || name} is required`;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');

                // Email validation
                if (type === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        errors[name] = 'Invalid email address';
                        field.classList.add('is-invalid');
                    }
                }

                // Password validation
                if (name === 'password' && value.length < 8) {
                    errors[name] = 'Password must be at least 8 characters';
                    field.classList.add('is-invalid');
                }
            }
        });

        return Object.keys(errors).length === 0 ? null : errors;
    }

    static displayErrors(formElement, errors) {
        if (!errors) return;

        Object.keys(errors).forEach(fieldName => {
            const field = formElement.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('is-invalid');
                const feedback = field.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = errors[fieldName];
                } else {
                    const div = document.createElement('div');
                    div.className = 'invalid-feedback';
                    div.textContent = errors[fieldName];
                    field.parentNode.insertBefore(div, field.nextSibling);
                }
            }
        });
    }
}

// Pagination Helper
class Pagination {
    static render(currentPage, totalPages, onPageChange) {
        const container = document.getElementById('pagination');
        if (!container) return;

        let html = '<nav><ul class="pagination">';

        // Previous button
        if (currentPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`;
        }

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }

        // Next button
        if (currentPage < totalPages) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`;
        }

        html += '</ul></nav>';
        container.innerHTML = html;

        // Add click handlers
        container.querySelectorAll('a[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(link.dataset.page);
                onPageChange(page);
            });
        });
    }
}

// Date Formatter
class DateFormatter {
    static format(date, format = 'short') {
        const d = new Date(date);
        const options = {
            short: { year: 'numeric', month: 'short', day: 'numeric' },
            long: { year: 'numeric', month: 'long', day: 'numeric' },
            time: { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }
        };
        return d.toLocaleDateString('en-US', options[format] || options.short);
    }

    static formatTime(date) {
        const d = new Date(date);
        return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    static formatRelative(date) {
        const d = new Date(date);
        const now = new Date();
        const seconds = Math.floor((now - d) / 1000);

        if (seconds < 60) return 'just now';
        if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
        if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
        if (seconds < 604800) return `${Math.floor(seconds / 86400)}d ago`;

        return this.format(date);
    }
}

// Storage Helper
class Storage {
    static set(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    }

    static get(key) {
        const value = localStorage.getItem(key);
        return value ? JSON.parse(value) : null;
    }

    static remove(key) {
        localStorage.removeItem(key);
    }

    static clear() {
        localStorage.clear();
    }
}

// DOM Helper
class DOM {
    static $(selector) {
        return document.querySelector(selector);
    }

    static $$(selector) {
        return document.querySelectorAll(selector);
    }

    static on(element, event, handler) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            element.addEventListener(event, handler);
        }
    }

    static off(element, event, handler) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            element.removeEventListener(event, handler);
        }
    }

    static addClass(element, className) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            element.classList.add(className);
        }
    }

    static removeClass(element, className) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            element.classList.remove(className);
        }
    }

    static toggleClass(element, className) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            element.classList.toggle(className);
        }
    }

    static hasClass(element, className) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        return element ? element.classList.contains(className) : false;
    }

    static html(element, content) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            if (content === undefined) {
                return element.innerHTML;
            }
            element.innerHTML = content;
        }
    }

    static text(element, content) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            if (content === undefined) {
                return element.textContent;
            }
            element.textContent = content;
        }
    }

    static attr(element, name, value) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            if (value === undefined) {
                return element.getAttribute(name);
            }
            element.setAttribute(name, value);
        }
    }

    static remove(element) {
        if (typeof element === 'string') {
            element = this.$(element);
        }
        if (element) {
            element.remove();
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Check authentication
    if (api.isAuthenticated()) {
        // Update navbar
        const navLinks = document.querySelector('.navbar-nav');
        if (navLinks) {
            const user = api.getUser();
            navLinks.innerHTML = `
                <li class="nav-item">
                    <a class="nav-link" href="pages/dashboard.html">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pages/my-courses.html">My Courses</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        ${user?.name || 'User'}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="pages/profile.html">Profile</a></li>
                        <li><a class="dropdown-item" href="pages/settings.html">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="api.logout(); window.location.href='pages/login.html';">Logout</a></li>
                    </ul>
                </li>
            `;
        }
    }

    // Language selector
    const languageSelect = document.getElementById('languageSelect');
    if (languageSelect) {
        languageSelect.value = api.locale;
        languageSelect.addEventListener('change', (e) => {
            api.setLanguage(e.target.value);
            location.reload();
        });
    }
});

// Export for use in other files
window.Notification = Notification;
window.Spinner = Spinner;
window.FormValidator = FormValidator;
window.Pagination = Pagination;
window.DateFormatter = DateFormatter;
window.Storage = Storage;
window.DOM = DOM;

