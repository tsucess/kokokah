/**
 * Inactivity Timeout Manager
 * Automatically logs out users after 30 minutes of inactivity
 */

class InactivityTimeoutManager {
    constructor(options = {}) {
        // Configuration
        this.inactivityTimeout = options.inactivityTimeout || 30 * 60 * 1000; // 30 minutes in milliseconds
        this.warningTimeout = options.warningTimeout || 28 * 60 * 1000; // 2 minutes before logout
        this.checkInterval = options.checkInterval || 60 * 1000; // Check every minute
        
        // State
        this.inactivityTimer = null;
        this.warningTimer = null;
        this.lastActivityTime = Date.now();
        this.isWarningShown = false;
        this.isEnabled = true;
        
        // Events to track
        this.activityEvents = [
            'mousedown',
            'mousemove',
            'keypress',
            'scroll',
            'touchstart',
            'click',
            'focus'
        ];
        
        this.init();
    }
    
    /**
     * Initialize the inactivity timeout manager
     */
    init() {
        this.attachActivityListeners();
        this.startInactivityTimer();
        console.log('Inactivity Timeout Manager initialized - 30 minute timeout enabled');
    }
    
    /**
     * Attach event listeners to track user activity
     */
    attachActivityListeners() {
        this.activityEvents.forEach(event => {
            document.addEventListener(event, () => this.resetInactivityTimer(), true);
        });
    }
    
    /**
     * Reset the inactivity timer on user activity
     */
    resetInactivityTimer() {
        if (!this.isEnabled) return;
        
        this.lastActivityTime = Date.now();
        this.isWarningShown = false;
        
        // Clear existing timers
        if (this.inactivityTimer) clearTimeout(this.inactivityTimer);
        if (this.warningTimer) clearTimeout(this.warningTimer);
        
        // Hide warning modal if visible
        this.hideWarningModal();
        
        // Start new timers
        this.startInactivityTimer();
    }
    
    /**
     * Start the inactivity timer
     */
    startInactivityTimer() {
        // Warning timer - show warning 2 minutes before logout
        this.warningTimer = setTimeout(() => {
            if (this.isEnabled && !this.isWarningShown) {
                this.showWarningModal();
                this.isWarningShown = true;
            }
        }, this.warningTimeout);
        
        // Logout timer - logout after 30 minutes
        this.inactivityTimer = setTimeout(() => {
            if (this.isEnabled) {
                this.performLogout();
            }
        }, this.inactivityTimeout);
    }
    
    /**
     * Show warning modal before logout
     */
    showWarningModal() {
        const modalHTML = `
            <div class="modal fade" id="inactivityWarningModal" tabindex="-1" aria-labelledby="inactivityWarningLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="inactivityWarningLabel">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>Session Timeout Warning
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Your session will expire due to inactivity in <strong id="countdownTimer">2 minutes</strong>.</p>
                            <p class="text-muted small">Click "Stay Logged In" to continue your session.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Stay Logged In</button>
                            <button type="button" class="btn btn-danger" id="logoutNowBtn">Logout Now</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if present
        const existingModal = document.getElementById('inactivityWarningModal');
        if (existingModal) existingModal.remove();
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('inactivityWarningModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
        
        // Add event listeners
        document.getElementById('logoutNowBtn').addEventListener('click', () => {
            this.performLogout();
        });
        
        // Start countdown timer
        this.startCountdownTimer();
    }
    
    /**
     * Start countdown timer in warning modal
     */
    startCountdownTimer() {
        let secondsRemaining = 120; // 2 minutes
        const timerElement = document.getElementById('countdownTimer');
        
        const countdownInterval = setInterval(() => {
            secondsRemaining--;
            
            if (timerElement) {
                const minutes = Math.floor(secondsRemaining / 60);
                const seconds = secondsRemaining % 60;
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
            
            if (secondsRemaining <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }
    
    /**
     * Hide warning modal
     */
    hideWarningModal() {
        const modal = document.getElementById('inactivityWarningModal');
        if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        }
    }
    
    /**
     * Perform logout
     */
    async performLogout() {
        this.isEnabled = false;
        
        try {
            const token = localStorage.getItem('auth_token');
            
            // Call logout API
            const response = await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            // Clear local storage
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            
            // Redirect to login
            window.location.href = '/login';
        } catch (error) {
            console.error('Error during logout:', error);
            // Force redirect even if API call fails
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
    }
    
    /**
     * Disable inactivity timeout
     */
    disable() {
        this.isEnabled = false;
        if (this.inactivityTimer) clearTimeout(this.inactivityTimer);
        if (this.warningTimer) clearTimeout(this.warningTimer);
        this.hideWarningModal();
    }
    
    /**
     * Enable inactivity timeout
     */
    enable() {
        this.isEnabled = true;
        this.resetInactivityTimer();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.inactivityManager = new InactivityTimeoutManager({
        inactivityTimeout: 30 * 60 * 1000, // 30 minutes
        warningTimeout: 28 * 60 * 1000     // Show warning at 28 minutes
    });
});

