/**
 * Sidebar Manager
 * Dynamically renders sidebar menu items based on user role from localStorage
 */

class SidebarManager {
  static init() {
    this.renderSidebarMenu();
    this.initSidebarToggle();
  }

  static renderSidebarMenu() {
    const user = this.getUserFromStorage();
    if (!user) {
      console.warn('No user data found in localStorage');
      return;
    }

    const sidebarNav = document.getElementById('sidebarNav');
    if (!sidebarNav) return;

    // Clear existing menu items (keep dashboard)
    const dashboardLink = sidebarNav.querySelector('#dashboardLink');
    sidebarNav.innerHTML = '';
    if (dashboardLink) {
      sidebarNav.appendChild(dashboardLink);
    }

    // Add menu items based on role
    const menuHTML = this.getMenuItemsForRole(user.role);
    sidebarNav.insertAdjacentHTML('beforeend', menuHTML);

    // Show/hide settings link based on role
    const settingsLink = document.getElementById('settingsLink');
    if (settingsLink) {
      settingsLink.style.display = user.role === 'superadmin' ? 'block' : 'none';
    }
  }

  static getMenuItemsForRole(role) {
    let html = '';

    // Users Management (Admin+)
    if (['admin', 'superadmin'].includes(role)) {
      html += this.getUsersManagementMenu(role);
    }

    // Subject Management (Instructor+)
    if (['instructor', 'admin', 'superadmin'].includes(role)) {
      html += this.getCourseManagementMenu(role);
    }

    // Transactions (Admin+)
    if (['admin', 'superadmin'].includes(role)) {
      html += `
        <a class="nav-item-link d-flex align-items-center gap-3" href="/transactions" role="button">
          <i class="fa-solid fa-credit-card nav-icon"></i><span>Transactions</span>
        </a>
      `;
    }

    // Reports & Analytics (Instructor+)
    if (['instructor', 'admin', 'superadmin'].includes(role)) {
      html += `
        <a class="nav-item-link d-flex align-items-center gap-3" href="/report">
          <i class="fa-solid fa-chart-line nav-icon"></i><span>Reports & Analytics</span>
        </a>
      `;
    }

    // Communication (Admin+)
    if (['admin', 'superadmin'].includes(role)) {
      html += this.getCommunicationMenu();
    }

    return html;
  }

  static getUsersManagementMenu(role) {
    return `
      <a class="nav-item-link d-flex align-items-center justify-content-between nav-parent"
        data-bs-toggle="collapse" href="#usersMenu">
        <span class="d-flex align-items-center gap-3">
          <i class="fa-solid fa-users nav-icon"></i>
          <span>Users Management</span>
        </span>
        <i class="fa-solid fa-chevron-down chevron-icon"></i>
      </a>
      <div class="collapse ps-5" id="usersMenu">
        <div class="d-flex flex-column gap-2">
          <a class="nav-item-link d-block nav-child" href="/users">All Users</a>
          <a class="nav-item-link d-block nav-child" href="/students">Students</a>
          <a class="nav-item-link d-block nav-child" href="/instructors">Instructors</a>
          <a class="nav-item-link d-block nav-child" href="/adduser">Add Users</a>
          <a class="nav-item-link d-block nav-child" href="/useractivity">Users Activity Log</a>
        </div>
      </div>
    `;
  }

  static getCourseManagementMenu(role) {
    let html = `
      <a class="nav-item-link d-flex align-items-center justify-content-between nav-parent"
        data-bs-toggle="collapse" href="#subjectsMenu" role="button">
        <span class="d-flex align-items-center gap-3">
          <i class="fa-solid fa-book-open nav-icon"></i>
          <span>Course Management</span>
        </span>
        <i class="fa-solid fa-chevron-down chevron-icon"></i>
      </a>
      <div class="collapse ps-5" id="subjectsMenu">
        <div class="d-flex flex-column gap-2">
          <a class="nav-item-link d-block nav-child" href="/subjects">All Courses</a>
          <a class="nav-item-link d-block nav-child" href="/createsubject">Create New Course</a>
    `;

    // Admin and Superadmin items
    if (['admin', 'superadmin'].includes(role)) {
      html += `
          <a class="nav-item-link d-block nav-child" href="/categories">Course Categories</a>
          <a class="nav-item-link d-block nav-child" href="/curriculum-categories">Curriculum Categories</a>
          <a class="nav-item-link d-block nav-child" href="/levels">Levels & Classes</a>
          <a class="nav-item-link d-block nav-child" href="/terms">Academic Terms</a>
      `;
    }

    html += `
          <a class="nav-item-link d-block nav-child" href="/rating">Course Reviews & Rating</a>
        </div>
      </div>
    `;

    return html;
  }

  static getCommunicationMenu() {
    return `
      <a class="nav-item-link d-flex align-items-center justify-content-between nav-parent"
        data-bs-toggle="collapse" href="#communicationMenu" role="button">
        <span class="d-flex align-items-center gap-3">
          <i class="fa-solid fa-comments nav-icon"></i>
          <span>Communication</span>
        </span>
        <i class="fa-solid fa-chevron-down chevron-icon"></i>
      </a>
      <div class="collapse ps-5" id="communicationMenu">
        <div class="d-flex flex-column gap-2">
          <a class="nav-item-link d-block nav-child" href="/announcement">Announcements & Notifications</a>
          <a class="nav-item-link d-block nav-child" href="/createannouncement">Create Announcement</a>
          <a class="nav-item-link d-block nav-child" href="/feedback">Feedback</a>
        </div>
      </div>
    `;
  }

  static getUserFromStorage() {
    const userStr = localStorage.getItem('auth_user');
    if (!userStr) return null;
    try {
      return JSON.parse(userStr);
    } catch (e) {
      console.error('Failed to parse user data:', e);
      return null;
    }
  }

  static initSidebarToggle() {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (!hamburger || !sidebar) return;

    hamburger.addEventListener('click', () => {
      sidebar.classList.toggle('active');
      overlay?.classList.toggle('active');
    });

    overlay?.addEventListener('click', () => {
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
    });
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  SidebarManager.init();
});

