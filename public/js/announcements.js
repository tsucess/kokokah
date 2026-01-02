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

        switch(priority) {
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
            alert('Please enter an announcement title');
            return;
        }
        if (!descInput?.value) {
            alert('Please enter a description');
            return;
        }

        // Get authentication token
        const token = this.getToken();
        if (!token) {
            alert('Authentication required. Please log in again.');
            window.location.href = '/login';
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

        console.log('Submitting announcement:', data);
        console.log('Using token:', token.substring(0, 20) + '...');

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
                alert(`Announcement ${status} successfully!`);
                window.location.href = '/announcement';
            } else {
                console.error('API Error:', result);
                if (response.status === 401) {
                    alert('Authentication failed. Please log in again.');
                    window.location.href = '/login';
                } else if (response.status === 403) {
                    alert('You do not have permission to create announcements.');
                } else if (response.status === 422) {
                    // Validation error - show detailed error messages
                    let errorMessage = 'Validation failed:\n\n';
                    if (result.errors) {
                        for (const [field, messages] of Object.entries(result.errors)) {
                            errorMessage += `${field}: ${messages.join(', ')}\n`;
                        }
                    } else {
                        errorMessage += result.message || 'Please check your form data';
                    }
                    console.error('Validation errors:', result.errors);
                    alert(errorMessage);
                } else {
                    alert(`Error: ${result.message || 'Failed to save announcement'}`);
                }
            }
        } catch (error) {
            console.error('Error submitting announcement:', error);
            alert('Error submitting announcement. Please try again.');
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
            console.error('Error loading announcements:', error);
        }
    }

    renderAnnouncements() {
        const container = document.querySelector('.notification-container');
        if (!container) return;

        container.innerHTML = this.currentAnnouncements.map(announcement => `
            <div class='d-flex flex-column notification-container'>
                <div class="d-flex gap-2 justify-content-between align-items-start">
                    <div class="d-flex flex-column" style="gap: 14px;">
                        <div class="d-flex gap-5 align-items-center">
                            <h5 class="fw-semibold notification-title">${announcement.title}</h5>
                            <div class="rounded-pill d-flex justify-content-center align-items-center notification-label">
                                <i class="fa-solid fa-circle-info"></i>${announcement.priority}
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center fw-semibold notification-category">${announcement.type}</div>
                    </div>
                    <button class="button" onclick="announcementManager.deleteAnnouncement(${announcement.id})">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                </div>
                <div class="d-flex gap-1 align-items-center">
                    <i class="fa-solid fa-clock"></i>
                    <span class="notification-date">${this.getTimeAgo(announcement.created_at)}</span>
                </div>
                <p class="notification-text">${announcement.description}</p>
            </div>
        `).join('');
    }

    filterByType(e) {
        const type = e.target.textContent.split('(')[0].trim();
        // Filter logic here
    }

    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return 'just now';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes}m ago`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours}h ago`;
        const days = Math.floor(hours / 24);
        if (days < 7) return `${days}d ago`;
        const weeks = Math.floor(days / 7);
        if (weeks < 4) return `${weeks}w ago`;

        return date.toLocaleDateString();
    }

    async deleteAnnouncement(id) {
        if (!confirm('Are you sure you want to delete this announcement?')) return;

        try {
            const response = await fetch(`${this.apiBaseUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${this.getToken()}`
                }
            });

            if (response.ok) {
                alert('Announcement deleted successfully');
                this.loadAnnouncements();
            }
        } catch (error) {
            console.error('Error:', error);
        }
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
            console.log('Token found in localStorage (auth_token):', token.substring(0, 20) + '...');
            return token;
        }

        // Fallback to 'token' key (alternative storage key)
        token = localStorage.getItem('token');
        if (token) {
            console.log('Token found in localStorage (token):', token.substring(0, 20) + '...');
            return token;
        }

        // Fallback to CSRF token if no API token found
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            console.log('Using CSRF token as fallback');
            return csrfToken;
        }

        console.warn('No authentication token found in localStorage or meta tags!');
        console.warn('Available localStorage keys:', Object.keys(localStorage));
        return null;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.announcementManager = new AnnouncementManager();
});

