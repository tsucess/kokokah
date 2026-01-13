@extends('layouts.usertemplate')

@section('content')
<style>
    .tabs{
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px,1fr))
    }
    @media screen and (max-width:768px){
        .tabs{
            border-radius: 30px;
            padding: 20px;
        }
    }
</style>
<main class="subjects-main">

        <section class="d-flex gap-5 flex-column py-4 container px-3 px-lg-4">


            <section class=" d-flex flex-column" style="gap: 30px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1>Notifications & Announcements</h1>
                        <p class="text">Stay updated with the latest news and updates from your school/instructor.</p>
                    </div>
                </div>

                <div class="d-flex flex-column " style="gap: 75px;">
                    <div class="tab tabs" id="announcementTabs">
                        <div class=" rounded-pill d-flex justify-content-center gap-1 py-lg-2 align-items-center col tab-text bg-light" data-filter="all">
                            <i class="fa-solid fa-bell"></i> All (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1 py-lg-2 align-items-center col tab-text" data-filter="Exams">
                            <i class="fa-solid fa-bell"></i> Exams (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1 py-lg-2 align-items-center col tab-text" data-filter="Events">
                            <i class="fa-solid fa-bell"></i> Events (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1 py-lg-2 align-items-center col tab-text" data-filter="Alert">
                            <i class="fa-solid fa-bell"></i> Alert (<span class="count">0</span>)
                        </div>
                        <div class=" rounded-pill d-flex justify-content-center gap-1 py-lg-2 align-items-center col tab-text" data-filter="General Info">
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

<!-- View Announcement Modal -->
<div class="modal fade" id="viewAnnouncementModal" tabindex="-1" aria-labelledby="viewAnnouncementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAnnouncementLabel">Announcement Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="announcementDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/announcements.js') }}"></script>
<script>
    // Student announcement manager (read-only view)
    class StudentAnnouncementManager extends AnnouncementManager {
        constructor() {
            super('/api/announcements');
            this.currentFilter = 'all';
            this.allAnnouncements = [];
        }

        init() {
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
                <div class='d-flex flex-column notification-container' style="cursor: pointer;" onclick="studentManager.viewAnnouncement(${announcement.id})">
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

        viewAnnouncement(id) {
            const announcement = this.allAnnouncements.find(a => a.id === id);
            if (!announcement) {
                alert('Announcement not found');
                return;
            }

            const detailsHtml = `
                <div class="announcement-details">
                    <div class="mb-3">
                        <h4 class="fw-bold">${announcement.title}</h4>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Type:</strong> ${announcement.type}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Priority:</strong> ${announcement.priority}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Audience:</strong> ${announcement.audience}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Posted:</strong> ${this.getTimeAgo(announcement.created_at)}</p>
                        </div>
                    </div>
                    ${announcement.scheduled_at ? `<div class="mb-3"><p><strong>Scheduled:</strong> ${new Date(announcement.scheduled_at).toLocaleString()}</p></div>` : ''}
                    <div class="mb-3">
                        <p><strong>Description:</strong></p>
                        <p>${announcement.description}</p>
                    </div>
                </div>
            `;

            document.getElementById('announcementDetails').innerHTML = detailsHtml;
            const modal = new bootstrap.Modal(document.getElementById('viewAnnouncementModal'));
            modal.show();
        }
    }

    const studentManager = new StudentAnnouncementManager();
</script>
@endsection

