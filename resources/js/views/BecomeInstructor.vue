<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Become an Instructor</h1>
        <p class="text-lg text-gray-600">
          Share your knowledge and expertise with thousands of students
        </p>
      </div>

      <!-- Application Status Card (if user has existing application) -->
      <div v-if="existingApplication" class="mb-6 bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Application Status</h3>
            <p class="text-sm text-gray-600 mt-1">
              Your application is currently:
              <span :class="{
                'text-yellow-600 font-semibold': existingApplication.status === 'pending',
                'text-green-600 font-semibold': existingApplication.status === 'approved',
                'text-red-600 font-semibold': existingApplication.status === 'rejected',
                'text-gray-600 font-semibold': existingApplication.status === 'suspended',
              }">
                {{ existingApplication.status.toUpperCase() }}
              </span>
            </p>
            <p v-if="existingApplication.rejection_reason" class="text-sm text-red-600 mt-2">
              <strong>Reason:</strong> {{ existingApplication.rejection_reason }}
            </p>
            <p v-if="existingApplication.document_type" class="text-sm text-gray-600 mt-2">
              <strong>Document Type:</strong> {{ existingApplication.document_type }}
            </p>
          </div>
        </div>
      </div>

      <!-- Application Form -->
      <div class="bg-white shadow-xl rounded-2xl p-8">
        <form @submit.prevent="submitApplication" class="space-y-6">
          <!-- Bio -->
          <div>
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
              Bio <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.bio"
              id="bio"
              name="bio"
              rows="6"
              required
              maxlength="2000"
              placeholder="Tell us about yourself, your experience, and why you want to become an instructor..."
              class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 resize-none"
            ></textarea>
            <p class="text-xs text-gray-500 mt-1">{{ form.bio.length }}/2000 characters</p>
            <p v-if="errors.bio" class="text-red-600 text-sm mt-1">{{ errors.bio }}</p>
          </div>

          <!-- Portfolio URL -->
          <div>
            <label for="portfolio_url" class="block text-sm font-medium text-gray-700 mb-2">
              Portfolio URL (Optional)
            </label>
            <input
              v-model="form.portfolio_url"
              id="portfolio_url"
              name="portfolio_url"
              type="url"
              placeholder="https://yourportfolio.com"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
            />
            <p v-if="errors.portfolio_url" class="text-red-600 text-sm mt-1">{{ errors.portfolio_url }}</p>
          </div>

          <!-- Document Type (NEW) -->
          <div>
            <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">
              Document Type <span class="text-red-500">*</span>
            </label>
            <select
              id="document_type"
              v-model="form.document_type"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
            >
              <option value="" disabled>Select document type</option>
              <option value="resume">Resume / CV</option>
              <option value="portfolio">Portfolio</option>
              <option value="id">Government ID</option> 
              <option value="certificate">Certificate</option>
              <option value="other">Other</option>
            </select>
            <p v-if="errors.document_type" class="text-red-600 text-sm mt-1">{{ errors.document_type }}</p>
          </div>

          <!-- Document Upload -->
          <div>
            <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
              Supporting Document <span class="text-red-500">*</span>
            </label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
              <div class="space-y-1 text-center">
                <svg
                  class="mx-auto h-12 w-12 text-gray-400"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 48 48"
                  aria-hidden="true"
                >
                  <path
                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
                <div class="flex text-sm text-gray-600">
                  <label
                    for="document"
                    class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500"
                  >
                    <span>Upload a file</span>
                    <input
                      id="document"
                      name="document"
                      type="file"
                      accept=".pdf,.jpg,.jpeg,.png"
                      required
                      @change="handleFileChange"
                      class="sr-only"
                    />
                  </label>
                  <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">
                  PDF, JPG, PNG up to 5MB
                </p>
                <p v-if="selectedFile" class="text-sm text-gray-700 mt-2">
                  Selected: {{ selectedFile.name }}
                </p>
              </div>
            </div>
            <p v-if="errors.document" class="text-red-600 text-sm mt-1">{{ errors.document }}</p>
          </div>

          <!-- Submit Button -->
          <div class="pt-4">
            <button
              type="submit"
              :disabled="loading || (existingApplication && existingApplication.status === 'approved')"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <span v-if="loading" class="animate-spin mr-2">&#9696;</span>
              <span v-else>{{ existingApplication && existingApplication.status === 'approved' ? 'Application Approved' : 'Submit Application' }}</span>
            </button>
          </div>

          <!-- Success/Error Messages -->
          <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ successMessage }}
          </div>
          <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ errorMessage }}
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import axios from 'axios';

export default {
  name: 'BecomeInstructor',
  setup() {
    const router = useRouter();
    const auth = useAuthStore();

    const form = ref({
      bio: '',
      portfolio_url: '',
      document_type: '', // <-- added
    });

    const selectedFile = ref(null);
    const loading = ref(false);
    const errors = ref({});
    const successMessage = ref('');
    const errorMessage = ref('');
    const existingApplication = ref(null);

    const isAuthenticated = computed(() => auth.isAuthenticated);
    const user = computed(() => auth.user);

    // Check if user needs to login
    onMounted(() => {
      if (!isAuthenticated.value) {
        router.push('/login');
        return;
      }

      // Check for existing application
      checkExistingApplication();
    });

    const checkExistingApplication = async () => {
      try {
        // The instructor_status is already in the user object from login
        if (user.value?.instructor_status) {
          // Fetch full application details if needed (optional)
          // For now, we'll use the status + any known document_type from user (if available)
          existingApplication.value = {
            status: user.value.instructor_status,
            rejection_reason: user.value.instructor_rejection_reason || null,
            document_type: user.value.instructor_document_type || null,
          };
        }
      } catch (error) {
        console.error('Error checking existing application:', error);
      }
    };

    const handleFileChange = (event) => {
      const file = event.target.files[0];
      if (file) {
        // Validate file type
        const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
          errors.value.document = 'File must be PDF, JPG, or PNG';
          selectedFile.value = null;
          return;
        }

        // Validate file size (5MB = 5 * 1024 * 1024 bytes)
        if (file.size > 5 * 1024 * 1024) {
          errors.value.document = 'File size must be less than 5MB';
          selectedFile.value = null;
          return;
        }

        selectedFile.value = file;
        errors.value.document = '';
      }
    };

    const submitApplication = async () => {
      // client-side validations
      errors.value = {};
      successMessage.value = '';
      errorMessage.value = '';

      if (!form.value.bio || !form.value.bio.trim()) {
        errors.value.bio = 'Bio is required.';
      }
      if (!form.value.document_type) {
        errors.value.document_type = 'Please select a document type.';
      }
      if (!selectedFile.value) {
        errors.value.document = 'Please select a document.';
      }

      if (Object.keys(errors.value).length > 0) {
        return;
      }

      loading.value = true;
      try {
        const formData = new FormData();
        formData.append('bio', form.value.bio);
        if (form.value.portfolio_url) {
          formData.append('portfolio_url', form.value.portfolio_url);
        }
        formData.append('document_type', form.value.document_type); // <-- added
        formData.append('document', selectedFile.value);

        const response = await axios.post('/instructor/apply', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        if (response.data.success) {
          successMessage.value = 'Application submitted successfully! We will review it soon.';

          // Update user's instructor_status & document_type in auth store if available
          if (auth.user) {
            auth.user.instructor_status = 'pending';
            auth.user.instructor_document_type = form.value.document_type;
          }

          // Reset form
          form.value = {
            bio: '',
            portfolio_url: '',
            document_type: '',
          };
          selectedFile.value = null;

          // Refresh user data (if your store provides such a method)
          if (auth.checkAuth) {
            await auth.checkAuth();
          }
        } else {
          errorMessage.value = response.data.message || 'Failed to submit application.';
        }
      } catch (error) {
        // Capture validation errors from server if present
        if (error.response?.data?.errors) {
          errors.value = error.response.data.errors;
        } else {
          errorMessage.value = error.response?.data?.message || 'Failed to submit application. Please try again.';
        }
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      selectedFile,
      loading,
      errors,
      successMessage,
      errorMessage,
      existingApplication,
      isAuthenticated,
      user,
      handleFileChange,
      submitApplication,
    };
  },
};
</script>

<style scoped>
/* Additional styles if needed */
</style>
