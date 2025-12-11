/**
 * Confirmation Modal Utility
 * Provides a reusable confirmation modal for delete and other confirmation actions
 * Replaces browser's confirm() with a styled modal dialog
 */

class ConfirmationModal {
  constructor() {
    this.modalElement = null;
    this.titleElement = null;
    this.messageElement = null;
    this.confirmBtn = null;
    this.cancelBtn = null;
    this.bootstrapModal = null;
    this.resolveCallback = null;
    this.init();
  }

  /**
   * Initialize the confirmation modal
   */
  init() {
    this.createModalHTML();
    this.setupEventListeners();
  }

  /**
   * Create the modal HTML structure
   */
  createModalHTML() {
    // Check if modal already exists
    if (document.getElementById('confirmationModal')) {
      this.modalElement = document.getElementById('confirmationModal');
      this.titleElement = document.getElementById('confirmationModalTitle');
      this.messageElement = document.getElementById('confirmationModalMessage');
      this.confirmBtn = document.getElementById('confirmationModalConfirmBtn');
      this.cancelBtn = document.getElementById('confirmationModalCancelBtn');
      return;
    }

    const modalHTML = `
      <div class="modal fade" id="confirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
          <div class="modal-content" style="border-radius: 8px; max-height: 300px; display: flex; flex-direction: column;">
            <div class="modal-header" style="border-bottom: 1px solid #e8e8e8; padding: 1rem;">
              <h1 class="modal-title" id="confirmationModalTitle" style="font-size: 1.1rem; font-weight: 600; color: #000;">Confirm Action</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1rem; min-height: auto;">
              <p id="confirmationModalMessage" style="margin: 0; color: #333; font-size: 0.95rem;">Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e8e8e8; padding: 1rem; gap: 0.5rem;">
              <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal" id="confirmationModalCancelBtn" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;">Cancel</button>
              <button type="button" class="btn" id="confirmationModalConfirmBtn" style="padding: 0.5rem 1.5rem; font-size: 0.9rem; background-color: #dc3545; color: white; border: none; border-radius: 4px; font-weight: 500;">Confirm</button>
            </div>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    this.modalElement = document.getElementById('confirmationModal');
    this.titleElement = document.getElementById('confirmationModalTitle');
    this.messageElement = document.getElementById('confirmationModalMessage');
    this.confirmBtn = document.getElementById('confirmationModalConfirmBtn');
    this.cancelBtn = document.getElementById('confirmationModalCancelBtn');
    this.bootstrapModal = new bootstrap.Modal(this.modalElement);
  }

  /**
   * Setup event listeners
   */
  setupEventListeners() {
    if (this.confirmBtn) {
      this.confirmBtn.addEventListener('click', () => {
        if (this.resolveCallback) {
          this.resolveCallback(true);
        }
        this.bootstrapModal.hide();
      });
    }

    if (this.cancelBtn) {
      this.cancelBtn.addEventListener('click', () => {
        if (this.resolveCallback) {
          this.resolveCallback(false);
        }
        this.bootstrapModal.hide();
      });
    }

    // Handle modal close button
    if (this.modalElement) {
      this.modalElement.addEventListener('hidden.bs.modal', () => {
        if (this.resolveCallback) {
          this.resolveCallback(false);
        }
      });
    }
  }

  /**
   * Show confirmation modal
   * @param {string} title - Modal title
   * @param {string} message - Confirmation message
   * @param {string} confirmText - Confirm button text (default: "Confirm")
   * @param {string} cancelText - Cancel button text (default: "Cancel")
   * @returns {Promise<boolean>} - Resolves to true if confirmed, false if cancelled
   */
  show(title = 'Confirm Action', message = 'Are you sure?', confirmText = 'Confirm', cancelText = 'Cancel') {
    return new Promise((resolve) => {
      this.resolveCallback = resolve;

      // Update modal content
      if (this.titleElement) {
        this.titleElement.textContent = title;
      }
      if (this.messageElement) {
        this.messageElement.textContent = message;
      }
      if (this.confirmBtn) {
        this.confirmBtn.textContent = confirmText;
      }
      if (this.cancelBtn) {
        this.cancelBtn.textContent = cancelText;
      }

      // Show modal
      if (this.bootstrapModal) {
        this.bootstrapModal.show();
      }
    });
  }

  /**
   * Show delete confirmation modal
   * @param {string} itemName - Name of item being deleted
   * @returns {Promise<boolean>}
   */
  showDeleteConfirmation(itemName = 'this item') {
    return this.show(
      'Delete Confirmation',
      `Are you sure you want to delete ${itemName}? This action cannot be undone.`,
      'Delete',
      'Cancel'
    );
  }

  /**
   * Show logout confirmation modal
   * @returns {Promise<boolean>}
   */
  showLogoutConfirmation() {
    return this.show(
      'Logout Confirmation',
      'Are you sure you want to logout?',
      'Logout',
      'Cancel'
    );
  }

  /**
   * Show account deletion confirmation modal
   * @returns {Promise<boolean>}
   */
  showAccountDeletionConfirmation() {
    return this.show(
      'Delete Account',
      'Are you sure you want to delete your account? This action cannot be undone. All your data will be permanently deleted.',
      'Delete Account',
      'Cancel'
    );
  }
}

// Initialize confirmation modal when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    window.confirmationModal = new ConfirmationModal();
  });
} else {
  window.confirmationModal = new ConfirmationModal();
}

