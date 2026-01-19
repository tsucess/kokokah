/**
 * Points to Wallet Conversion Component
 * Handles UI and logic for converting points to wallet balance
 */

class PointsConversionComponent {
  constructor() {
    this.CONVERSION_RATIO = 10; // 10 points = 1 wallet unit
    this.MIN_POINTS = 10;
    this.conversionModal = null;
    this.conversionHistoryModal = null;
  }

  /**
   * Initialize the component
   */
  init() {
    console.log('Initializing PointsConversionComponent...');
    try {
      this.createModals();
      this.setupEventListeners();
      console.log('PointsConversionComponent initialized successfully');
    } catch (error) {
      console.error('Error initializing PointsConversionComponent:', error);
    }
  }

  /**
   * Create the conversion modals
   */
  createModals() {
    // Create conversion modal if it doesn't exist
    if (!document.getElementById('pointsConversionModal')) {
      this.createConversionModal();
    }
    
    // Create history modal if it doesn't exist
    if (!document.getElementById('conversionHistoryModal')) {
      this.createHistoryModal();
    }
  }

  /**
   * Create the main conversion modal
   */
  createConversionModal() {
    try {
      const modalHTML = `
        <div class="modal fade" id="pointsConversionModal" data-bs-keyboard="false" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header d-flex justify-content-between align-items-center">
                <h1 class="modal-title">Convert Points to Wallet</h1>
                <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                  <i class="fa-regular fa-circle-xmark"></i>
                </button>
              </div>
              <form class="modal-form-container" id="conversionForm">
                <div class="modal-form">
                  <div class="modal-form-input-border">
                    <label class="modal-label">Your Points</label>
                    <div style="padding: 8px 0; color: #004a53; font-weight: 600; font-size: 16px;">
                      <strong id="userPointsDisplay">0</strong> points available
                    </div>
                  </div>

                  <div class="modal-form-input-border">
                    <label for="conversionPoints" class="modal-label">Points to Convert</label>
                    <input type="number" class="modal-input" id="conversionPoints"
                           placeholder="Enter points (multiple of 10)" min="10" step="10" required />
                    <small style="color: #8E8E93; font-size: 12px; margin-top: 4px;">Minimum: 10 points | Must be multiple of 10</small>
                  </div>

                  <div class="modal-form-input-border">
                    <label class="modal-label">You will receive</label>
                    <div style="padding: 8px 0; color: #004a53; font-weight: 600; font-size: 16px;">
                      <strong id="walletAmountDisplay">₦0.00</strong> in wallet balance
                    </div>
                  </div>

                  <div id="conversionError" class="alert alert-danger d-none" style="margin-top: 12px;"></div>
                </div>
                <div class="d-flex gap-2">
                  <button type="button" class="btn addmoney-btn" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn modal-form-btn" id="convertPointsBtn">Convert Points</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      `;

      document.body.insertAdjacentHTML('beforeend', modalHTML);

      // Check if Bootstrap is available
      if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap not loaded');
        return;
      }

      const modalElement = document.getElementById('pointsConversionModal');
      if (!modalElement) {
        console.error('Modal element not found after insertion');
        return;
      }

      this.conversionModal = new bootstrap.Modal(modalElement);
      console.log('Conversion modal created successfully');
    } catch (error) {
      console.error('Error creating conversion modal:', error);
    }
  }

  /**
   * Create the conversion history modal
   */
  createHistoryModal() {
    try {
      const modalHTML = `
        <div class="modal fade" id="conversionHistoryModal" data-bs-keyboard="false" tabindex="-1">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header d-flex justify-content-between align-items-center">
                <h1 class="modal-title">Conversion History</h1>
                <button type="button" class="modal-header-btn" data-bs-dismiss="modal" aria-label="Close">
                  <i class="fa-regular fa-circle-xmark"></i>
                </button>
              </div>
              <div class="modal-form-container">
                <div id="conversionHistoryList" style="padding: 20px 0;"></div>
              </div>
            </div>
          </div>
        </div>
      `;

      document.body.insertAdjacentHTML('beforeend', modalHTML);

      // Check if Bootstrap is available
      if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap not loaded');
        return;
      }

      const modalElement = document.getElementById('conversionHistoryModal');
      if (!modalElement) {
        console.error('History modal element not found after insertion');
        return;
      }

      this.conversionHistoryModal = new bootstrap.Modal(modalElement);
      console.log('History modal created successfully');
    } catch (error) {
      console.error('Error creating history modal:', error);
    }
  }

  /**
   * Setup event listeners
   */
  setupEventListeners() {
    const self = this;

    // Points input change - calculate wallet amount
    document.addEventListener('input', (e) => {
      if (e.target.id === 'conversionPoints') {
        self.updateWalletAmount();
      }
    });

    // Convert button click
    document.addEventListener('click', (e) => {
      if (e.target.id === 'convertPointsBtn') {
        self.handleConversion();
      }
      if (e.target.id === 'convertPointsOpenBtn') {
        console.log('Convert Points button clicked');
        self.openConversionModal();
      }
      if (e.target.id === 'viewConversionHistoryBtn') {
        self.openHistoryModal();
      }
    });
  }

  /**
   * Update wallet amount display based on points input
   */
  updateWalletAmount() {
    const pointsInput = document.getElementById('conversionPoints');
    const points = parseInt(pointsInput.value) || 0;
    const walletAmount = points / this.CONVERSION_RATIO;
    
    const display = document.getElementById('walletAmountDisplay');
    if (display) {
      display.textContent = `₦${walletAmount.toFixed(2)}`;
    }
  }

  /**
   * Open the conversion modal
   */
  async openConversionModal() {
    try {
      console.log('Opening conversion modal...');

      // Check if modal exists
      if (!this.conversionModal) {
        console.error('Conversion modal not initialized');
        alert('Modal not initialized. Please refresh the page.');
        return;
      }

      // Load user's current points
      await this.loadUserPoints();
      this.clearForm();

      console.log('Showing modal...');
      this.conversionModal.show();
      console.log('Modal shown successfully');
    } catch (error) {
      console.error('Error opening conversion modal:', error);
      alert('Error opening conversion modal: ' + error.message);
    }
  }

  /**
   * Load user's current points
   */
  async loadUserPoints() {
    try {
      console.log('Loading user points...');
      const response = await PointsAndBadgesApiClient.getUserPoints();
      console.log('Points response:', response);

      if (response.success && response.data) {
        const userPoints = response.data.points || 0;
        const display = document.getElementById('userPointsDisplay');
        if (display) {
          display.textContent = userPoints;
          console.log('Points loaded:', userPoints);
        }
      } else {
        console.warn('Failed to load points:', response.message);
      }
    } catch (error) {
      console.error('Error loading user points:', error);
    }
  }

  /**
   * Handle points conversion
   */
  async handleConversion() {
    const pointsInput = document.getElementById('conversionPoints');
    const points = parseInt(pointsInput.value);
    const errorDiv = document.getElementById('conversionError');

    // Clear previous errors
    errorDiv.classList.add('d-none');
    errorDiv.textContent = '';

    // Validate input
    if (!points || points < this.MIN_POINTS) {
      this.showError(`Minimum ${this.MIN_POINTS} points required`);
      return;
    }

    if (points % this.CONVERSION_RATIO !== 0) {
      this.showError(`Points must be a multiple of ${this.CONVERSION_RATIO}`);
      return;
    }

    try {
      const convertBtn = document.getElementById('convertPointsBtn');
      convertBtn.disabled = true;
      convertBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Converting...';

      const response = await WalletApiClient.convertPoints(points);

      if (response.success) {
        NotificationHelper.success('Points converted successfully!');
        this.conversionModal.hide();
        
        // Reload wallet data
        await this.loadUserPoints();
        
        // Reload conversion history
        await this.loadConversionHistory();
      } else {
        this.showError(response.message || 'Conversion failed');
      }
    } catch (error) {
      this.showError(error.message || 'An error occurred during conversion');
    } finally {
      const convertBtn = document.getElementById('convertPointsBtn');
      convertBtn.disabled = false;
      convertBtn.innerHTML = 'Convert Points';
    }
  }

  /**
   * Open conversion history modal
   */
  async openHistoryModal() {
    await this.loadConversionHistory();
    this.conversionHistoryModal.show();
  }

  /**
   * Load and display conversion history
   */
  async loadConversionHistory() {
    try {
      const response = await WalletApiClient.getConversionHistory(20);
      const historyList = document.getElementById('conversionHistoryList');

      if (response.success && response.data.conversions.length > 0) {
        const html = response.data.conversions.map(conversion => `
          <div class="card mb-2">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <small class="text-muted">Points Converted</small>
                  <p class="mb-0"><strong>${conversion.points_converted}</strong></p>
                </div>
                <div class="col-md-6">
                  <small class="text-muted">Wallet Amount</small>
                  <p class="mb-0"><strong>₦${parseFloat(conversion.wallet_amount).toFixed(2)}</strong></p>
                </div>
              </div>
              <small class="text-muted">
                ${new Date(conversion.created_at).toLocaleDateString()} 
                ${new Date(conversion.created_at).toLocaleTimeString()}
              </small>
            </div>
          </div>
        `).join('');
        
        historyList.innerHTML = html;
      } else {
        historyList.innerHTML = '<p class="text-muted">No conversions yet</p>';
      }
    } catch (error) {
      console.error('Error loading conversion history:', error);
    }
  }

  /**
   * Show error message
   */
  showError(message) {
    const errorDiv = document.getElementById('conversionError');
    if (errorDiv) {
      errorDiv.textContent = message;
      errorDiv.classList.remove('d-none');
    }
  }

  /**
   * Clear the conversion form
   */
  clearForm() {
    const pointsInput = document.getElementById('conversionPoints');
    if (pointsInput) {
      pointsInput.value = '';
      this.updateWalletAmount();
    }
  }
}

// Initialize when DOM is ready
function initializePointsConversion() {
  console.log('Initializing Points Conversion Component...');
  if (!window.pointsConversion) {
    window.pointsConversion = new PointsConversionComponent();
    window.pointsConversion.init();
    console.log('Points Conversion Component initialized');
  }
}

// Check if DOM is already loaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializePointsConversion);
} else {
  // DOM is already loaded
  initializePointsConversion();
}

