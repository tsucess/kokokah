@extends('layouts.dashboardtemp')

@section('content')
<main class="subjects-main">
 <section class="d-flex flex-column gap-3 p-4">
        <div class="d-flex align-items-center gap-3">
            <a href="/announcement" class="d-flex align-items-center gap-2 back-arrow">
                <i class="fa-solid fa-arrow-left" style="color: #000000;"></i>
                Back to Notifications & Announcements
            </a>
        </div>
        <header class="d-flex justify-content-between">
            <div class="d-flex flex-column gap-2">
                <h1>Edit Announcement</h1>
                <p class="announcement-subtitle">Update the announcement details below.</p>
            </div>
            <div class="d-flex gap-3 align-items-center justify-content-center w-50">
                <button class="cancel-btn announment-btn">Cancel</button>
                <button class="update-btn announment-btn">Update</button>
                <button class="status-btn announment-btn" id="statusBtn">Publish</button>
            </div>
        </header>
        <section class="container-fluid">
            <div class="row g-4">
                <section class="col col-12 col-lg-7 d-flex flex-column gap-5">
                    <div class="d-flex flex-column gap-5">
                        <div class="d-flex flex-column input-container">
                            <label for="title" class="label">Announcement Title</label>
                            <input type="text" name="title" id="title" placeholder="Enter announcement title" class="input-text">
                        </div>
                        <div class="d-flex flex-column input-container">
                            <label for="type" class="label">Select Announcement Type *</label>
                            <select name="type" id="type">
                                <option value="Exams">Exams</option>
                                <option value="Events">Events</option>
                                <option value="Alert">Alert</option>
                                <option value="General Info">General Info</option>
                            </select>
                        </div>
                    </div>
                    <div class="priority-container d-flex flex-column">
                        <h6 class="priority-title">Priority</h6>
                        <div class="d-flex gap-3 align-items-center">
                            <div class="badge d-flex justify-content-center align-items-center preview-card-badge active" data-priority="Info">
                                <i class="fa-solid fa-circle-info" style="color: #000000;"></i>Info
                            </div>
                            <div class="badge d-flex justify-content-center align-items-center preview-card-badge" data-priority="Urgent">
                                <i class="fa-solid fa-circle-info" style="color: #F56824;"></i>Urgent
                            </div>
                            <div class="badge d-flex justify-content-center align-items-center preview-card-badge" data-priority="Warning">
                                <i class="fa-solid fa-circle-info" style="color: #FDAF22;"></i>Warning
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-5">
                        <div class="d-flex flex-column input-container">
                            <label for="audience" class="label">Audience</label>
                            <select name="audience" id="audience">
                                <option value="All students">All students</option>
                                <option value="Specific class">Specific class</option>
                                <option value="Specific level">Specific level</option>
                            </select>
                        </div>
                        <div class="d-flex flex-column input-container">
                            <label for="scheduled_at" class="label">Date & Time (optional)</label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="input-text">
                        </div>
                        <div class="d-flex flex-column textarea-container">
                            <label for="description" class="label">Description</label>
                            <textarea name="description" id="description" placeholder="Write announcement details here...." class="textarea-text"></textarea>
                        </div>
                    </div>

                </section>
                <article class="col col-12 col-lg-5 d-flex flex-column preview-container">
                    <h5 class="preview-title">Preview</h5>
                    <div class="preview-card">
                        <div class="d-flex align-items-center gap-3">
                            <h6 class="preview-card-title" id="previewTitle">Mid-term Examination Schedule Released</h6>
                            <div class="badge d-flex justify-content-center align-items-center preview-card-badge" id="previewBadge">
                                <i class="fa-solid fa-circle-info" style="color: #000000;"></i><span id="previewPriority">Info</span>
                            </div>
                        </div>
                        <p class="preview-card-text" id="previewDescription">The mid-term examination schedule for all grades has been published. Students can check their exam dates and timings on the student portal. Please ensure you're well-prepared.</p>
                    </div>
                </article>
            </div>
        </section>
    </section>
 </main>

<script src="{{ asset('js/announcements.js') }}"></script>
<script>
    class EditAnnouncementManager {
        constructor() {
            this.announcementId = '{{ $announcementId }}';
            this.apiBaseUrl = '/api/announcements';
            this.currentStatus = null; // Will be set when announcement is loaded
            this.init();
        }

        async init() {
            try {
                // Load announcement data
                await this.loadAnnouncement();

                // Setup event listeners
                this.setupEventListeners();
            } catch (error) {
                console.error('Error initializing edit form:', error);
                this.showToast('Error', 'Error loading announcement. Please try again.', 'error');
                setTimeout(() => {
                    window.location.href = '/announcement';
                }, 1500);
            }
        }

        async loadAnnouncement() {
            console.log('Loading announcement:', this.announcementId);
            const response = await fetch(`${this.apiBaseUrl}/${this.announcementId}`, {
                headers: {
                    'Authorization': `Bearer ${this.getToken()}`
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load announcement');
            }

            const data = await response.json();
            console.log('Loaded announcement data:', data);
            const announcement = data.data;

            // Populate form fields
            document.getElementById('title').value = announcement.title || '';
            document.getElementById('type').value = announcement.type || 'Exams';
            document.getElementById('audience').value = announcement.audience || 'All students';
            document.getElementById('description').value = announcement.description || '';

            // Set priority badge
            const priority = announcement.priority || 'Info';
            document.querySelectorAll('.badge.preview-card-badge').forEach(badge => {
                badge.classList.remove('active', 'urgent-badge', 'warning-badge');
                if (badge.dataset.priority === priority) {
                    badge.classList.add('active');
                    if (priority === 'Urgent') badge.classList.add('urgent-badge');
                    if (priority === 'Warning') badge.classList.add('warning-badge');
                }
            });

            // Set scheduled_at if exists
            if (announcement.scheduled_at) {
                const dateTime = new Date(announcement.scheduled_at);
                const localDateTime = dateTime.toISOString().slice(0, 16);
                document.getElementById('scheduled_at').value = localDateTime;
            }

            // Store current status and update button text
            this.currentStatus = announcement.status;
            this.updateStatusButton();

            // Update preview
            this.updatePreview();
        }

        setupEventListeners() {
            // Title input
            document.getElementById('title').addEventListener('input', () => this.updatePreview());

            // Type select
            document.getElementById('type').addEventListener('change', () => this.updatePreview());

            // Priority badges
            document.querySelectorAll('.badge.preview-card-badge').forEach(badge => {
                badge.addEventListener('click', (e) => {
                    document.querySelectorAll('.badge.preview-card-badge').forEach(b => {
                        b.classList.remove('active', 'urgent-badge', 'warning-badge');
                    });
                    badge.classList.add('active');
                    if (badge.dataset.priority === 'Urgent') badge.classList.add('urgent-badge');
                    if (badge.dataset.priority === 'Warning') badge.classList.add('warning-badge');
                    this.updatePreview();
                });
            });

            // Description textarea
            document.getElementById('description').addEventListener('input', () => this.updatePreview());

            // Cancel button
            document.querySelector('.cancel-btn').addEventListener('click', () => {
                window.location.href = '/announcement';
            });

            // Update button - only updates data, keeps current status
            document.querySelector('.update-btn').addEventListener('click', () => {
                this.submitForm(this.currentStatus, 'update');
            });

            // Status button - toggles between publish and draft
            document.querySelector('.status-btn').addEventListener('click', () => {
                const newStatus = this.currentStatus === 'draft' ? 'published' : 'draft';
                const actionType = newStatus === 'published' ? 'publish' : 'draft';
                this.submitForm(newStatus, actionType);
            });
        }

        updateStatusButton() {
            const statusBtn = document.getElementById('statusBtn');
            if (this.currentStatus === 'draft') {
                statusBtn.textContent = 'Publish';
            } else if (this.currentStatus === 'published') {
                statusBtn.textContent = 'Save as Draft';
            }
        }

        updatePreview() {
            const title = document.getElementById('title').value || 'Announcement Title';
            const description = document.getElementById('description').value || 'Announcement description will appear here...';
            const priority = document.querySelector('.badge.preview-card-badge.active')?.dataset.priority || 'Info';

            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewDescription').textContent = description;
            document.getElementById('previewPriority').textContent = priority;

            // Update badge styling
            const badge = document.getElementById('previewBadge');
            badge.classList.remove('urgent-badge', 'warning-badge');
            if (priority === 'Urgent') badge.classList.add('urgent-badge');
            if (priority === 'Warning') badge.classList.add('warning-badge');
        }

        async submitForm(status, actionType = 'update') {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const type = document.getElementById('type').value;
            const priority = document.querySelector('.badge.preview-card-badge.active')?.dataset.priority || 'Info';
            const audience = document.getElementById('audience').value;
            const scheduled_at_input = document.getElementById('scheduled_at').value;

            console.log('Form values before validation:');
            console.log('- title:', title);
            console.log('- description:', description);
            console.log('- type:', type);
            console.log('- priority:', priority);
            console.log('- audience:', audience);
            console.log('- scheduled_at_input:', scheduled_at_input);
            console.log('- actionType:', actionType);

            // Validation
            if (!title) {
                alert('Please enter a title');
                return;
            }
            if (!description) {
                alert('Please enter a description');
                return;
            }

            // Format scheduled_at to Y-m-d H:i:s format
            let scheduled_at = null;
            if (scheduled_at_input) {
                // Convert from datetime-local format (YYYY-MM-DDTHH:mm) to Y-m-d H:i:s
                const dateTime = new Date(scheduled_at_input);
                const year = dateTime.getFullYear();
                const month = String(dateTime.getMonth() + 1).padStart(2, '0');
                const day = String(dateTime.getDate()).padStart(2, '0');
                const hours = String(dateTime.getHours()).padStart(2, '0');
                const minutes = String(dateTime.getMinutes()).padStart(2, '0');
                const seconds = '00';
                scheduled_at = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }

            try {
                const requestBody = {
                    title,
                    description,
                    type,
                    priority,
                    audience,
                    audience_value: null,
                    scheduled_at,
                    status
                };

                console.log('Sending request to:', `${this.apiBaseUrl}/${this.announcementId}`);
                console.log('Request body:', requestBody);
                console.log('Token:', this.getToken());

                const response = await fetch(`${this.apiBaseUrl}/${this.announcementId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.getToken()}`
                    },
                    body: JSON.stringify(requestBody)
                });

                if (!response.ok) {
                    const error = await response.json();
                    console.log('API Error Response:', error); // Debug log
                    let errorMessage = error.message || 'Failed to update announcement';

                    // If there are validation errors, format them
                    if (error.errors) {
                        const errorList = Object.entries(error.errors)
                            .map(([field, messages]) => {
                                const msgs = Array.isArray(messages) ? messages : [messages];
                                return `${field}: ${msgs.join(', ')}`;
                            })
                            .join('\n');
                        errorMessage = `Validation errors:\n${errorList}`;
                    }

                    throw new Error(errorMessage);
                }

                // Update current status
                this.currentStatus = status;
                this.updateStatusButton();

                // Show different success message based on action type
                let successMessage = 'Announcement updated successfully!';
                if (actionType === 'publish') {
                    successMessage = 'Announcement published successfully!';
                } else if (actionType === 'draft') {
                    successMessage = 'Announcement saved as draft successfully!';
                }

                this.showToast('Success', successMessage, 'success');
                setTimeout(() => {
                    window.location.href = '/announcement';
                }, 1500);
            } catch (error) {
                console.error('Error updating announcement:', error);
                this.showToast('Error', 'Error updating announcement: ' + error.message, 'error');
            }
        }

        getToken() {
            // Try to get auth token from localStorage first (for API authentication)
            let token = localStorage.getItem('auth_token');
            if (token) {
                console.log('Using auth_token from localStorage');
                return token;
            }

            // Fallback to CSRF token if no auth token found
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                console.log('Using CSRF token as fallback');
                return csrfToken;
            }

            console.warn('No authentication token found');
            return '';
        }

        /**
         * Show a toast notification
         */
        showToast(title = '', message = '', type = 'info', timeout = 3500) {
            if (window.ToastNotification && window.ToastNotification.show) {
                window.ToastNotification.show(title, message, type, timeout);
            } else {
                console.warn('ToastNotification not available, falling back to alert');
                alert(`${title}: ${message}`);
            }
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        new EditAnnouncementManager();
    });
</script>
@endsection
