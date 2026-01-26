/**
 * Internationalization (i18n) Manager
 * Handles loading and applying translations to the DOM
 */
class I18nManager {
    constructor() {
        this.translations = {};
        this.currentLocale = 'en';
        this.isLoading = false;
    }

    /**
     * Initialize the translation system
     */
    async init() {
        try {
            console.log('[i18n] Initializing translation system...');
            await this.loadTranslations();
            console.log('[i18n] Translations loaded, current locale:', this.currentLocale);
            this.applyTranslations();
            this.setupMutationObserver();
            console.log('[i18n] Translation system initialized successfully');
        } catch (error) {
            console.error('[i18n] Failed to initialize i18n:', error);
        }
    }

    /**
     * Load translations from the API
     */
    async loadTranslations() {
        if (this.isLoading) {
            console.log('[i18n] Already loading translations, skipping...');
            return;
        }

        this.isLoading = true;
        try {
            const authToken = localStorage.getItem('auth_token');
            console.log('[i18n] Loading translations with auth token:', authToken ? 'present (length: ' + authToken.length + ')' : 'missing');
            console.log('[i18n] Current URL:', window.location.href);

            const response = await fetch('/api/language/translations', {
                headers: {
                    'Authorization': 'Bearer ' + authToken,
                    'Accept': 'application/json'
                }
            });

            console.log('[i18n] API response status:', response.status);
            console.log('[i18n] API response headers:', {
                'content-type': response.headers.get('content-type'),
                'x-powered-by': response.headers.get('x-powered-by')
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('[i18n] API error response:', errorText);
                throw new Error(`Failed to load translations: ${response.status}`);
            }

            const data = await response.json();
            console.log('[i18n] API response data:', data);

            if (data.success) {
                this.translations = data.data.messages || {};
                this.currentLocale = data.data.locale || 'en';
                console.log('[i18n] Translations loaded successfully for locale:', this.currentLocale);
                console.log('[i18n] Translation keys available:', Object.keys(this.translations));
            } else {
                console.warn('[i18n] API returned success: false', data);
            }
        } catch (error) {
            console.error('[i18n] Error loading translations:', error);
            console.error('[i18n] Error stack:', error.stack);
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Get a translated string by key
     * @param {string} key - Translation key (e.g., 'auth.login_success')
     * @param {string} defaultValue - Default value if translation not found
     * @returns {string} Translated string or default value
     */
    translate(key, defaultValue = key) {
        const keys = key.split('.');
        let value = this.translations;

        for (const k of keys) {
            if (value && typeof value === 'object' && k in value) {
                value = value[k];
            } else {
                return defaultValue;
            }
        }

        return typeof value === 'string' ? value : defaultValue;
    }

    /**
     * Apply translations to all elements with data-i18n attribute
     */
    applyTranslations() {
        const elements = document.querySelectorAll('[data-i18n]');
        console.log('[i18n] Found', elements.length, 'elements with data-i18n attribute');

        elements.forEach(element => {
            const key = element.getAttribute('data-i18n');
            const translated = this.translate(key);

            console.log('[i18n] Translating key:', key, '-> value:', translated);

            // Check if element has child elements (don't replace innerHTML)
            if (element.children.length === 0) {
                element.textContent = translated;
            } else {
                // For elements with children, only update text nodes
                element.childNodes.forEach(node => {
                    if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
                        node.textContent = translated;
                    }
                });
            }
        });
    }

    /**
     * Setup mutation observer to apply translations to dynamically added elements
     */
    setupMutationObserver() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            if (node.hasAttribute('data-i18n')) {
                                const key = node.getAttribute('data-i18n');
                                node.textContent = this.translate(key);
                            }
                            // Also check children
                            node.querySelectorAll('[data-i18n]').forEach(el => {
                                const key = el.getAttribute('data-i18n');
                                el.textContent = this.translate(key);
                            });
                        }
                    });
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Reload translations and apply them
     */
    async reload() {
        await this.loadTranslations();
        this.applyTranslations();
    }
}

// Create global instance
window.i18n = new I18nManager();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => window.i18n.init());
} else {
    window.i18n.init();
}

