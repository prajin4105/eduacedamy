import axios from 'axios';

class ProgressService {
  constructor() {
    this.listeners = new Map();
    this.updateInterval = null;
    this.isRunning = false;
  }

  /**
   * Start real-time progress updates
   * @param {number} interval - Update interval in milliseconds (default: 30000)
   */
  start(interval = 30000) {
    if (this.isRunning) {
      return;
    }

    this.isRunning = true;
    this.updateInterval = setInterval(() => {
      this.updateProgress();
    }, interval);

    // Initial update
    this.updateProgress();
  }

  /**
   * Stop real-time progress updates
   */
  stop() {
    if (this.updateInterval) {
      clearInterval(this.updateInterval);
      this.updateInterval = null;
    }
    this.isRunning = false;
  }

  /**
   * Update progress for all active listeners
   */
  async updateProgress() {
    try {
      const response = await axios.get('/api/dashboard/progress');
      
      if (response.data.success) {
        this.notifyListeners('progress-updated', response.data.data);
      }
    } catch (error) {
      console.error('Error updating progress:', error);
      this.notifyListeners('progress-error', error);
    }
  }

  /**
   * Mark a video as completed
   * @param {number} courseId - Course ID
   * @param {number} videoId - Video ID
   */
  async markVideoCompleted(courseId, videoId) {
    try {
      console.log('ProgressService: Marking video as completed', { courseId, videoId });
      
      const response = await axios.post(`/api/courses/${courseId}/videos/${videoId}/complete`);
      
      console.log('ProgressService: Video completion response', response.data);
      
      if (response.data.success) {
        this.notifyListeners('video-completed', {
          courseId,
          videoId,
          progress: response.data.data.progress,
          isCompleted: response.data.data.is_course_completed
        });
        
        // Update progress immediately
        this.updateProgress();
      }
      
      return response.data;
    } catch (error) {
      console.error('ProgressService: Error marking video as completed:', error);
      this.notifyListeners('video-completion-error', error);
      throw error;
    }
  }

  /**
   * Update time spent on course
   * @param {number} courseId - Course ID
   * @param {number} seconds - Time spent in seconds
   */
  async updateTimeSpent(courseId, seconds) {
    try {
      const response = await axios.post(`/api/courses/${courseId}/time-spent`, {
        seconds: seconds
      });
      
      if (response.data.success) {
        this.notifyListeners('time-updated', {
          courseId,
          timeSpent: response.data.data.time_spent_seconds,
          formattedTime: response.data.data.formatted_time_spent
        });
      }
      
      return response.data;
    } catch (error) {
      console.error('Error updating time spent:', error);
      throw error;
    }
  }

  /**
   * Get course progress
   * @param {number} courseId - Course ID
   */
  async getCourseProgress(courseId) {
    try {
      const response = await axios.get(`/api/courses/${courseId}/progress`);
      return response.data;
    } catch (error) {
      console.error('Error fetching course progress:', error);
      throw error;
    }
  }

  /**
   * Get course enrollment status
   * @param {number} courseId - Course ID
   */
  async getCourseEnrollmentStatus(courseId) {
    try {
      const response = await axios.get(`/api/courses/${courseId}/enrollment-status`);
      return response.data;
    } catch (error) {
      console.error('Error fetching enrollment status:', error);
      throw error;
    }
  }

  /**
   * Add event listener
   * @param {string} event - Event name
   * @param {Function} callback - Callback function
   */
  addEventListener(event, callback) {
    if (!this.listeners.has(event)) {
      this.listeners.set(event, new Set());
    }
    this.listeners.get(event).add(callback);
  }

  /**
   * Remove event listener
   * @param {string} event - Event name
   * @param {Function} callback - Callback function
   */
  removeEventListener(event, callback) {
    if (this.listeners.has(event)) {
      this.listeners.get(event).delete(callback);
    }
  }

  /**
   * Notify all listeners of an event
   * @param {string} event - Event name
   * @param {*} data - Event data
   */
  notifyListeners(event, data) {
    if (this.listeners.has(event)) {
      this.listeners.get(event).forEach(callback => {
        try {
          callback(data);
        } catch (error) {
          console.error('Error in progress service listener:', error);
        }
      });
    }
  }

  /**
   * Get current progress data
   */
  async getCurrentProgress() {
    try {
      const response = await axios.get('/api/dashboard/progress');
      return response.data.success ? response.data.data : null;
    } catch (error) {
      console.error('Error fetching current progress:', error);
      return null;
    }
  }
}

// Create singleton instance
const progressService = new ProgressService();

export default progressService;
