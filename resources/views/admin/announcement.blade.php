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
                    <a href="/createannouncement"
                        class="d-flex gap-1 align-items-center py-2 px-3 fs-6 announcement-btn ms-auto"><i
                            class="fa-solid fa-plus"></i> Create New Announcement</a>

                </div>

                <div class="d-flex flex-column " style="gap: 75px;">
                    <div class="row rounded-pill p-2 tab" id="announcementTabs">
                        <div class=" rounded-pill d-flex justify-content-center gap-1 py-2 align-items-center col tab-text bg-light"
                            data-filter="all">
                            <i class="fa-solid fa-bell"></i> All (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1  py-2 align-items-center col tab-text"
                            data-filter="Exams">
                            <i class="fa-solid fa-bell"></i> Exams (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1  py-2 align-items-center col tab-text"
                            data-filter="Events">
                            <i class="fa-solid fa-bell"></i> Events (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1  py-2 align-items-center col tab-text"
                            data-filter="Alert">
                            <i class="fa-solid fa-bell"></i> Alert (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1  py-2 align-items-center col tab-text"
                            data-filter="General Info">
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

    <script src="{{ asset('js/announcements.js') }}"></script>
    <script>
        // Admin announcement manager - extends base class
        class AdminAnnouncementManager extends AnnouncementManager {
            constructor() {
                super('/api/announcements');
                this.currentFilter = 'all';
                this.allAnnouncements = [];
            }

            init() {
                // Don't call super.init() - we have custom initialization
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

                container.innerHTML = filtered.map(announcement => {
                    // Determine priority badge classes
                    let priorityClasses = 'notification-label';
                    let iconColor = '#000000';

                    switch(announcement.priority) {
                        case 'Urgent':
                            priorityClasses += ' notification-label-urgent';
                            iconColor = '#F56824';
                            break;
                        case 'Warning':
                            priorityClasses += ' notification-label-warning';
                            iconColor = '#FDAF22';
                            break;
                        case 'Info':
                        default:
                            priorityClasses += ' notification-label-info';
                            iconColor = '#000000';
                            break;
                    }

                    return `
                <div class='d-flex flex-column notification-container'>
                    <div class="d-flex gap-2 justify-content-between align-items-start">
                        <div class="d-flex flex-column" style="gap: 14px;">
                            <div class="d-flex gap-5 align-items-center">
                                <h5 class="fw-semibold notification-title">${announcement.title}</h5>
                                <div class="rounded-pill d-flex justify-content-center align-items-center ${priorityClasses}">
                                    <i class="fa-solid fa-circle-info" style="color: ${iconColor};"></i>${announcement.priority}
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-center fw-semibold notification-category">${announcement.type}</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm" type="button" id="dropdownMenu${announcement.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu${announcement.id}">
                                <li><a class="dropdown-item" href="/announcement/${announcement.id}/edit">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="adminManager.deleteAnnouncement(${announcement.id}); return false;">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex gap-1 align-items-center">
                        <i class="fa-solid fa-clock"></i>
                        <span class="notification-date">${this.getTimeAgo(announcement.created_at)}</span>
                    </div>
                    <p class="notification-text">${announcement.description}</p>
                </div>
            `;
                }).join('');
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

                    const result = await response.json();

                    if (response.ok) {
                        alert('Announcement deleted successfully!');
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
        adminManager.init();
    </script>
@endsection
