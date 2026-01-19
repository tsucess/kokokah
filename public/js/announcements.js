// Announcement Management System
class AnnouncementManager {
    constructor(apiBaseUrl = '/api/announcements') {
        this.apiBaseUrl = apiBaseUrl;
        this.currentAnnouncements = [];
        this.selectedPriority = 'Info';
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadAnnouncements();
    }

    setupEventListeners() {
        // Priority badge selection - select badges in priority-container
        const priorityContainer = document.querySelector('.priority-container');
        if (priorityContainer) {
            priorityContainer.querySelectorAll('[data-priority]').forEach(badge => {
                badge.addEventListener('click', (e) => this.selectPriority(e));
            });
        }

        // Tab filtering
        document.querySelectorAll('.tab-text').forEach(tab => {
            tab.addEventListener('click', (e) => this.filterByType(e));
        });

        // Form submission
        const publishBtn = document.querySelector('.publish-btn');
        const draftBtn = document.querySelector('.draft-btn');
        const cancelBtn = document.querySelector('.cancel-btn');

        if (publishBtn) publishBtn.addEventListener('click', () => this.submitAnnouncement('published'));
        if (draftBtn) draftBtn.addEventListener('click', () => this.submitAnnouncement('draft'));
        if (cancelBtn) cancelBtn.addEventListener('click', () => this.cancelForm());

        // Form input listeners for preview
        this.setupPreviewListeners();
    }

    setupPreviewListeners() {
        const titleInput = document.querySelector('input[name="title"]');
        const descInput = document.querySelector('textarea[name="description"]');

        if (titleInput) {
            titleInput.addEventListener('input', (e) => this.updatePreview());
        }
        if (descInput) {
            descInput.addEventListener('input', (e) => this.updatePreview());
        }
    }

    selectPriority(e) {
        // Remove active class from all priority badges in the priority-container
        const priorityContainer = document.querySelector('.priority-container');
        if (priorityContainer) {
            priorityContainer.querySelectorAll('[data-priority]').forEach(b => b.classList.remove('active'));
        }

        // Add active class to clicked badge
        const clickedBadge = e.target.closest('[data-priority]');
        if (clickedBadge) {
            clickedBadge.classList.add('active');

            // Get priority from data attribute
            const priority = clickedBadge.getAttribute('data-priority');
            this.selectedPriority = priority;

            // Update preview badge
            this.updatePreviewBadge(priority);
        }
    }

    updatePreviewBadge(priority) {
        const previewBadge = document.querySelector('.preview-card .preview-card-badge');
        if (!previewBadge) return;

        // Update badge text
        const badgeText = previewBadge.querySelector('i').nextSibling;
        if (badgeText) {
            badgeText.textContent = priority;
        }

        // Update badge styling based on priority
        previewBadge.classList.remove('urgent-badge', 'warning-badge');
        const icon = previewBadge.querySelector('i');

        switch (priority) {
            case 'Urgent':
                previewBadge.classList.add('urgent-badge');
                if (icon) icon.style.color = '#F56824';
                break;
            case 'Warning':
                previewBadge.classList.add('warning-badge');
                if (icon) icon.style.color = '#FDAF22';
                break;
            case 'Info':
            default:
                if (icon) icon.style.color = '#000000';
                break;
        }
    }

    updatePreview() {
        const titleInput = document.querySelector('input[name="title"]');
        const descInput = document.querySelector('textarea[name="description"]');
        const previewTitle = document.querySelector('.preview-card-title');
        const previewText = document.querySelector('.preview-card-text');

        if (previewTitle && titleInput) {
            previewTitle.textContent = titleInput.value || 'Mid-term Examination Schedule Released';
        }
        if (previewText && descInput) {
            previewText.textContent = descInput.value || 'The mid-term examination schedule for all grades has been published...';
        }
    }

    async submitAnnouncement(status) {
        // Get form inputs
        const titleInput = document.querySelector('input[name="title"]');
        const typeSelect = document.querySelector('select[name="type"]');
        const descInput = document.querySelector('textarea[name="description"]');
        const audienceSelect = document.querySelector('select[name="audience"]');
        const dateInput = document.querySelector('input[name="scheduled_at"]');

        // Validate required fields
        if (!titleInput?.value) {
            this.showToast('Validation Error', 'Please enter an announcement title', 'warning');
            return;
        }
        if (!descInput?.value) {
            this.showToast('Validation Error', 'Please enter a description', 'warning');
            return;
        }

        // Get authentication token
        const token = this.getToken();
        if (!token) {
            this.showToast('Authentication Error', 'Authentication required. Please log in again.', 'error');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
            return;
        }

        // Format scheduled_at correctly for API (Y-m-d H:i:s format)
        let scheduledAt = null;
        if (dateInput.value) {
            // dateInput.value is in format: "2026-01-02T14:30"
            // Convert to "2026-01-02 14:30:00"
            const dateObj = new Date(dateInput.value);
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            const hours = String(dateObj.getHours()).padStart(2, '0');
            const minutes = String(dateObj.getMinutes()).padStart(2, '0');
            const seconds = '00';
            scheduledAt = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        const data = {
            title: titleInput.value,
            description: descInput.value,
            type: typeSelect.value,
            priority: this.selectedPriority,
            audience: audienceSelect.value,
            scheduled_at: scheduledAt,
            status: status
        };

        try {
            const response = await fetch(this.apiBaseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                this.showToast('Success', `Announcement ${status} successfully!`, 'success');
                setTimeout(() => {
                    window.location.href = '/announcement';
                }, 1500);
            } else {
                if (response.status === 401) {
                    this.showToast('Authentication Error', 'Authentication failed. Please log in again.', 'error');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                } else if (response.status === 403) {
                    this.showToast('Permission Denied', 'You do not have permission to create announcements.', 'error');
                } else if (response.status === 422) {
                    // Validation error - show detailed error messages
                    let errorMessage = '';
                    if (result.errors) {
                        const errorList = Object.entries(result.errors)
                            .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                            .join('\n');
                        errorMessage = errorList;
                    } else {
                        errorMessage = result.message || 'Please check your form data';
                    }
                    this.showToast('Validation Error', errorMessage, 'warning');
                } else {
                    this.showToast('Error', result.message || 'Failed to save announcement', 'error');
                }
            }
        } catch (error) {
            this.showToast('Error', 'Error submitting announcement. Please try again.', 'error');
        }
    }

    async loadAnnouncements() {
        try {
            const response = await fetch(this.apiBaseUrl, {
                headers: {
                    'Authorization': `Bearer ${this.getToken()}`
                }
            });

            const result = await response.json();
            if (result.status === 200) {
                this.currentAnnouncements = result.data.data || [];
                this.renderAnnouncements();
            }
        } catch (error) {
        }
    }

    renderAnnouncements() {
        // Override in subclasses for custom rendering
        // This is a placeholder for pages that use the base class
    }

    filterByType(e) {
        // Override in subclasses for custom filtering
    }

    cancelForm() {
        window.location.href = '/announcement';
    }

    getTimeAgo(date) {
        const now = new Date();
        const then = new Date(date);
        const seconds = Math.floor((now - then) / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (days > 0) return `${days} day${days > 1 ? 's' : ''} ago`;
        if (hours > 0) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
        if (minutes > 0) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
        return 'just now';
    }

    getToken() {
        // Try to get token from localStorage (check both possible keys)
        let token = localStorage.getItem('auth_token');  // BaseApiClient key
        if (token) {
            return token;
        }

        // Fallback to 'token' key (alternative storage key)
        token = localStorage.getItem('token');
        if (token) {
            return token;
        }

        // Fallback to CSRF token if no API token found
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            return csrfToken;
        }

        return null;
    }

    /**
     * Show a toast notification
     * @param {string} title - Toast title
     * @param {string} message - Toast message
     * @param {string} type - Toast type: 'success', 'error', 'warning', 'info'
     * @param {number} timeout - Auto-hide timeout in milliseconds
     */
    showToast(title = '', message = '', type = 'info', timeout = 3500) {
        if (window.ToastNotification && window.ToastNotification.show) {
            window.ToastNotification.show(title, message, type, timeout);
        } else {
            // Fallback to alert if ToastNotification is not available
            alert(`${title}: ${message}`);
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.announcementManager = new AnnouncementManager();
});

