/**
 * Badge Congratulation Modal Component
 * Displays a celebratory modal with confetti when a user earns a badge
 * Shows badge icon, name, and description with confetti animation
 */

class BadgeCongratulationModal {
  constructor() {
    this.modal = null;
    this.confettiCanvas = null;
    this.confettiParticles = [];
    this.animationId = null;
    this.isAnimating = false;
  }

  /**
   * Initialize the modal
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
    if (document.getElementById('badgeCongratulationModal')) {
      return;
    }

    const modalHTML = `
      <div id="badgeCongratulationModal" class="badge-congratulation-modal" style="display: none;">
        <canvas id="confettiCanvas" class="confetti-canvas"></canvas>
        <div class="badge-modal-overlay"></div>
        <div class="badge-modal-content">
          <button class="badge-modal-close" aria-label="Close">&times;</button>
          <div class="badge-modal-body">
            <div class="badge-celebration-icon">🎉</div>
            <h2 class="badge-modal-title">Congratulations!</h2>
            <div class="badge-display-container">
              <div class="badge-icon-large" id="badgeIcon">🏆</div>
              <img id="badgeImage" class="badge-icon-image" style="display: none;" alt="Badge Icon" />
            </div>
            <h3 class="badge-name" id="badgeName">Badge Name</h3>
            <p class="badge-description" id="badgeDescription">Badge description goes here</p>
            <div class="badge-points-info">
              <span class="points-label">Points Earned:</span>
              <span class="points-value" id="badgePoints">0</span>
            </div>
            <button class="badge-modal-btn-close">Awesome!</button>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    this.modal = document.getElementById('badgeCongratulationModal');
    this.confettiCanvas = document.getElementById('confettiCanvas');
    this.setupEventListeners();
  }

  /**
   * Setup event listeners
   */
  setupEventListeners() {
    const closeBtn = this.modal.querySelector('.badge-modal-close');
    const awesomeBtn = this.modal.querySelector('.badge-modal-btn-close');

    if (closeBtn) {
      closeBtn.addEventListener('click', () => this.hide());
    }

    if (awesomeBtn) {
      awesomeBtn.addEventListener('click', () => this.hide());
    }

    // Close on overlay click
    const overlay = this.modal.querySelector('.badge-modal-overlay');
    if (overlay) {
      overlay.addEventListener('click', () => this.hide());
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.modal.style.display !== 'none') {
        this.hide();
      }
    });
  }

  /**
   * Show the modal with badge information
   * @param {Object} badge - Badge object with name, icon, description, points, icon_path (optional)
   */
  show(badge) {
    if (!this.modal) {
      this.createModalHTML();
    }

    // Update badge information
    const badgeIcon = this.modal.querySelector('#badgeIcon');
    const badgeImage = this.modal.querySelector('#badgeImage');
    const badgeName = this.modal.querySelector('#badgeName');
    const badgeDescription = this.modal.querySelector('#badgeDescription');
    const badgePoints = this.modal.querySelector('#badgePoints');

    // Handle badge icon - support both emoji and image paths
    if (badge.icon_path) {
      // Show image-based badge
      if (badgeImage) {
        badgeImage.src = badge.icon_path;
        badgeImage.style.display = 'block';
      }
      if (badgeIcon) {
        badgeIcon.style.display = 'none';
      }
    } else {
      // Show emoji badge
      if (badgeIcon) {
        badgeIcon.textContent = badge.icon || '🏆';
        badgeIcon.style.display = 'block';
      }
      if (badgeImage) {
        badgeImage.style.display = 'none';
      }
    }

    if (badgeName) {
      badgeName.textContent = badge.name || 'Badge Earned';
    }
    if (badgeDescription) {
      badgeDescription.textContent = badge.description || '';
    }
    if (badgePoints) {
      badgePoints.textContent = badge.points || 0;
    }

    // Show modal
    this.modal.style.display = 'flex';
    this.modal.offsetHeight; // Trigger reflow for animation

    // Start confetti animation
    this.startConfetti();
  }

  /**
   * Hide the modal
   */
  hide() {
    if (this.modal) {
      this.modal.style.display = 'none';
      this.stopConfetti();
    }
  }

  /**
   * Start confetti animation
   */
  startConfetti() {
    if (this.isAnimating) return;

    this.isAnimating = true;
    this.confettiParticles = [];

    // Setup canvas
    const rect = this.modal.getBoundingClientRect();
    this.confettiCanvas.width = rect.width;
    this.confettiCanvas.height = rect.height;

    // Create confetti particles
    for (let i = 0; i < 50; i++) {
      this.confettiParticles.push(this.createConfettiParticle());
    }

    // Start animation loop
    this.animateConfetti();
  }

  /**
   * Create a single confetti particle
   * Uses Kokokah theme colors for confetti
   */
  createConfettiParticle() {
    // Kokokah theme colors: primary, accent, success, warning
    const colors = [
      '#004a53', // Primary teal
      '#2b6870', // Primary hover
      '#ff6b35', // Accent orange
      '#ffa366', // Accent light
      '#16b265', // Success green
      '#fdaf22', // Warning yellow
      '#ecfdff', // Light background
      '#35527a'  // Link hover
    ];
    return {
      x: Math.random() * this.confettiCanvas.width,
      y: -10,
      vx: (Math.random() - 0.5) * 8,
      vy: Math.random() * 5 + 3,
      size: Math.random() * 8 + 4,
      color: colors[Math.floor(Math.random() * colors.length)],
      rotation: Math.random() * Math.PI * 2,
      rotationSpeed: (Math.random() - 0.5) * 0.2,
      opacity: 1
    };
  }

  /**
   * Animate confetti particles with fade-out effect
   */
  animateConfetti() {
    const ctx = this.confettiCanvas.getContext('2d');
    ctx.clearRect(0, 0, this.confettiCanvas.width, this.confettiCanvas.height);

    let activeParticles = 0;

    this.confettiParticles.forEach((particle) => {
      // Update position
      particle.x += particle.vx;
      particle.y += particle.vy;
      particle.vy += 0.1; // Gravity
      particle.rotation += particle.rotationSpeed;

      // Fade out as particle falls
      const fadeStart = this.confettiCanvas.height * 0.7;
      if (particle.y > fadeStart) {
        particle.opacity = Math.max(0, 1 - (particle.y - fadeStart) / (this.confettiCanvas.height - fadeStart));
      }

      // Draw particle with opacity
      if (particle.y < this.confettiCanvas.height && particle.opacity > 0) {
        ctx.save();
        ctx.globalAlpha = particle.opacity;
        ctx.translate(particle.x, particle.y);
        ctx.rotate(particle.rotation);
        ctx.fillStyle = particle.color;
        ctx.fillRect(-particle.size / 2, -particle.size / 2, particle.size, particle.size);
        ctx.restore();
        activeParticles++;
      }
    });

    if (activeParticles > 0) {
      this.animationId = requestAnimationFrame(() => this.animateConfetti());
    } else {
      this.stopConfetti();
    }
  }

  /**
   * Stop confetti animation
   */
  stopConfetti() {
    if (this.animationId) {
      cancelAnimationFrame(this.animationId);
      this.animationId = null;
    }
    this.isAnimating = false;
    this.confettiParticles = [];
  }
}

// Create global instance
window.BadgeCongratulationModal = new BadgeCongratulationModal();

