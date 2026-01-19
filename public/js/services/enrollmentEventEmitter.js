/**
 * Enrollment Event Emitter
 * Wraps EnrollmentApiClient to emit events on successful course completion
 */

class EnrollmentEventEmitter {
  static initialized = false;
  static originalCompleteEnrollment = null;

  /**
   * Initialize enrollment event emitter
   */
  static init() {
    if (this.initialized) return;
    this.initialized = true;

    // Store original method
    if (window.EnrollmentApiClient && window.EnrollmentApiClient.completeEnrollment) {
      this.originalCompleteEnrollment = window.EnrollmentApiClient.completeEnrollment;

      // Override completeEnrollment to emit events
      window.EnrollmentApiClient.completeEnrollment = async (enrollmentId) => {
        try {
          const response = await EnrollmentEventEmitter.originalCompleteEnrollment.call(
            window.EnrollmentApiClient,
            enrollmentId
          );

          // If successful, emit event
          if (response.success && window.DataRefreshService) {
            await DataRefreshService.emit(DataRefreshService.EVENTS.COURSE_COMPLETED, {
              enrollment_id: enrollmentId,
              user_points: response.data?.user_points,
              points_awarded: response.data?.points_awarded,
              certificate: response.data?.certificate
            });
          }

          return response;
        } catch (error) {
          throw error;
        }
      };
    }
  }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  EnrollmentEventEmitter.init();
});

