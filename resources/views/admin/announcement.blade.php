@extends('layouts.dashboardtemp')

@section('content')
<main class="subjects-main">

        <section class="d-flex gap-5 flex-column py-4 container px-5">


            <section class=" d-flex flex-column" style="gap: 30px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1>Notifications & Announcements</h1>
                        <p class="text">Stay updated with the latest news and updates from your school/instructor.</p>
                    </div>
                    <a href="/createannouncement" class="d-flex gap-1 align-items-center py-2 px-3 fs-6 announcement-btn ms-auto"><i class="fa-solid fa-plus"></i> Create New Announcement</a>

                </div>

                <div class="d-flex flex-column " style="gap: 75px;">
                    <div class="row rounded-pill p-2 tab" id="announcementTabs">
                        <div class=" rounded-pill d-flex justify-content-center gap-1 py-2 align-items-center col tab-text bg-light" data-filter="all">
                            <i class="fa-solid fa-bell"></i> All (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1  py-2 align-items-center col tab-text" data-filter="Exams">
                            <i class="fa-solid fa-bell"></i> Exams (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1  py-2 align-items-center col tab-text" data-filter="Events">
                            <i class="fa-solid fa-bell"></i> Events (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1  py-2 align-items-center col tab-text" data-filter="Alert">
                            <i class="fa-solid fa-bell"></i> Alert (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1  py-2 align-items-center col tab-text" data-filter="General Info">
                            <i class="fa-solid fa-bell"></i> General Info (<span class="count">0</span>)
                        </div>
                    </div>
                    <div id="announcementsContainer" class="d-flex flex-column" style="gap: 30px;">
                        <!-- Announcements will be loaded here dynamically -->
                        <div class="text-center py-5">
                            <p>Loading announcements...</p>
                        </div>
                    </div>


                </div>
            </section>
        </section>
    </main>

<!-- Edit Announcement Modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAnnouncementLabel">Edit Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAnnouncementForm">
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editType" class="form-label">Type</label>
                            <select class="form-select" id="editType" name="type" required>
                                <option value="">Select Type</option>
                                <option value="Exams">Exams</option>
                                <option value="Events">Events</option>
                                <option value="Alert">Alert</option>
                                <option value="General Info">General Info</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editPriority" class="form-label">Priority</label>
                            <select class="form-select" id="editPriority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="Info">Info</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Warning">Warning</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editAudience" class="form-label">Audience</label>
                            <select class="form-select" id="editAudience" name="audience" required>
                                <option value="">Select Audience</option>
                                <option value="All students">All students</option>
                                <option value="Specific class">Specific class</option>
                                <option value="Specific level">Specific level</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="">Select Status</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editScheduledAt" class="form-label">Schedule Date (Optional)</label>
                        <input type="datetime-local" class="form-control" id="editScheduledAt" name="scheduled_at">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="adminManager.submitEditAnnouncement()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAnnouncementModal" tabindex="-1" aria-labelledby="deleteAnnouncementLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAnnouncementLabel">Delete Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this announcement? This action cannot be undone.</p>
                <p class="text-muted" id="deleteAnnouncementTitle"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="adminManager.confirmDeleteAnnouncement()">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/announcements.js') }}"></script>
<script>
    // Enhanced announcement manager for admin view
    class AdminAnnouncementManager extends AnnouncementManager {
        constructor() {
            super('/api/announcements');
            this.currentFilter = 'all';
            this.allAnnouncements = [];
            this.editingAnnouncementId = null;
            this.deletingAnnouncementId = null;
        }

        init() {
            super.init();
            this.setupTabFilters();
            this.loadAnnouncements();
        }

        setupTabFilters() {
            document.querySelectorAll('[data-filter]').forEach(tab => {
                tab.addEventListener('click', (e) => {
                    document.querySelectorAll('[data-filter]').forEach(t => t.classList.remove('bg-light'));
                    e.currentTarget.classList.add('bg-light');
                    this.currentFilter = e.currentTarget.dataset.filter;
                    this.renderAnnouncements();
                });
            });
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
                    this.allAnnouncements = result.data.data || [];
                    this.updateTabCounts();
                    this.renderAnnouncements();
                }
            } catch (error) {
                console.error('Error loading announcements:', error);
            }
        }

        updateTabCounts() {
            const types = ['Exams', 'Events', 'Alert', 'General Info'];
            const allCount = this.allAnnouncements.length;

            document.querySelector('[data-filter="all"] .count').textContent = allCount;

            types.forEach(type => {
                const count = this.allAnnouncements.filter(a => a.type === type).length;
                document.querySelector(`[data-filter="${type}"] .count`).textContent = count;
            });
        }

        renderAnnouncements() {
            const container = document.getElementById('announcementsContainer');
            let filtered = this.allAnnouncements;

            if (this.currentFilter !== 'all') {
                filtered = this.allAnnouncements.filter(a => a.type === this.currentFilter);
            }

            if (filtered.length === 0) {
                container.innerHTML = '<div class="text-center py-5"><p>No announcements found</p></div>';
                return;
            }

            container.innerHTML = filtered.map(announcement => `
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
                        <div class="dropdown">
                            <button class="button" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="adminManager.editAnnouncement(${announcement.id}); return false;">Edit</a></li>
                                <li><a class="dropdown-item" href="#" onclick="adminManager.deleteAnnouncement(${announcement.id}); return false;">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex gap-1 align-items-center">
                        <i class="fa-solid fa-clock"></i>
                        <span class="notification-date">${this.getTimeAgo(announcement.created_at)}</span>
                    </div>
                    <p class="notification-text">${announcement.description}</p>
                </div>
            `).join('');
        }

        editAnnouncement(id) {
            const announcement = this.allAnnouncements.find(a => a.id === id);
            if (!announcement) {
                alert('Announcement not found');
                return;
            }

            this.editingAnnouncementId = id;

            // Populate form with announcement data
            document.getElementById('editTitle').value = announcement.title;
            document.getElementById('editDescription').value = announcement.description;
            document.getElementById('editType').value = announcement.type;
            document.getElementById('editPriority').value = announcement.priority;
            document.getElementById('editAudience').value = announcement.audience;
            document.getElementById('editStatus').value = announcement.status;

            // Format scheduled_at for datetime-local input
            if (announcement.scheduled_at) {
                const date = new Date(announcement.scheduled_at);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                document.getElementById('editScheduledAt').value = `${year}-${month}-${day}T${hours}:${minutes}`;
            } else {
                document.getElementById('editScheduledAt').value = '';
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editAnnouncementModal'));
            modal.show();
        }

        async submitEditAnnouncement() {
            const form = document.getElementById('editAnnouncementForm');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            // Format scheduled_at if present
            if (data.scheduled_at) {
                const dateObj = new Date(data.scheduled_at);
                const year = dateObj.getFullYear();
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dateObj.getDate()).padStart(2, '0');
                const hours = String(dateObj.getHours()).padStart(2, '0');
                const minutes = String(dateObj.getMinutes()).padStart(2, '0');
                const seconds = '00';
                data.scheduled_at = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            } else {
                data.scheduled_at = null;
            }

            try {
                const response = await fetch(`${this.apiBaseUrl}/${this.editingAnnouncementId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.getToken()}`
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Announcement updated successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('editAnnouncementModal')).hide();
                    this.loadAnnouncements();
                } else {
                    if (response.status === 422) {
                        let errorMessage = 'Validation failed:\n\n';
                        if (result.errors) {
                            for (const [field, messages] of Object.entries(result.errors)) {
                                errorMessage += `${field}: ${messages.join(', ')}\n`;
                            }
                        }
                        alert(errorMessage);
                    } else {
                        alert(`Error: ${result.message || 'Failed to update announcement'}`);
                    }
                }
            } catch (error) {
                console.error('Error updating announcement:', error);
                alert('Error updating announcement. Please try again.');
            }
        }

        deleteAnnouncement(id) {
            const announcement = this.allAnnouncements.find(a => a.id === id);
            if (!announcement) {
                alert('Announcement not found');
                return;
            }

            this.deletingAnnouncementId = id;
            document.getElementById('deleteAnnouncementTitle').textContent = `"${announcement.title}"`;

            const modal = new bootstrap.Modal(document.getElementById('deleteAnnouncementModal'));
            modal.show();
        }

        async confirmDeleteAnnouncement() {
            try {
                const response = await fetch(`${this.apiBaseUrl}/${this.deletingAnnouncementId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${this.getToken()}`
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Announcement deleted successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('deleteAnnouncementModal')).hide();
                    this.loadAnnouncements();
                } else {
                    alert(`Error: ${result.message || 'Failed to delete announcement'}`);
                }
            } catch (error) {
                console.error('Error deleting announcement:', error);
                alert('Error deleting announcement. Please try again.');
            }
        }
    }

    const adminManager = new AdminAnnouncementManager();
</script>
@endsection
