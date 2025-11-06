<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="mt-2 text-gray-600">Manage your account information and settings</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
      </div>

      <!-- Profile Form -->
      <div v-else class="bg-white shadow rounded-lg">
        <form @submit.prevent="updateProfile" class="p-6 space-y-6">
          <!-- Profile Picture Section -->
          <div class="flex items-center space-x-6 pb-6 border-b border-gray-200">
            <div class="flex-shrink-0">
              <div class="relative">
                <img
                  v-if="profilePicturePreview || (profile && profile.profile_picture)"
                  :src="profilePicturePreview || getProfilePictureUrl(profile.profile_picture)"
                  alt="Profile Picture"
                  class="h-24 w-24 rounded-full object-cover border-2 border-gray-300"
                />
                <div
                  v-else
                  class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center border-2 border-gray-300"
                >
                  <span class="text-3xl font-semibold text-indigo-600">
                    {{ profile?.name?.charAt(0).toUpperCase() || 'U' }}
                  </span>
                </div>
                <label
                  for="profile-picture"
                  class="absolute bottom-0 right-0 bg-indigo-600 text-white rounded-full p-2 cursor-pointer hover:bg-indigo-700 transition"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <input
                    id="profile-picture"
                    type="file"
                    accept="image/*"
                    @change="handleFileChange"
                    class="hidden"
                  />
                </label>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-lg font-medium text-gray-900">{{ profile?.name || 'User' }}</h3>
              <p class="text-sm text-gray-500">{{ profile?.email }}</p>
              <p v-if="profile?.role" class="text-xs text-indigo-600 mt-1 capitalize">{{ profile.role }}</p>
            </div>
          </div>

          <!-- Personal Information -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Full Name <span class="text-red-500">*</span>
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                :class="{ 'border-red-300': errors.name }"
              />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email Address <span class="text-red-500">*</span>
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                :class="{ 'border-red-300': errors.email }"
              />
              <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
            </div>

            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                Phone Number
              </label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                :class="{ 'border-red-300': errors.phone }"
                placeholder="+1234567890"
              />
              <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone[0] }}</p>
            </div>

            <div>
              <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                Date of Birth
              </label>
              <input
                id="date_of_birth"
                v-model="form.date_of_birth"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                :class="{ 'border-red-300': errors.date_of_birth }"
              />
              <p v-if="errors.date_of_birth" class="mt-1 text-sm text-red-600">{{ errors.date_of_birth[0] }}</p>
            </div>
          </div>

          <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
              Address
            </label>
            <input
              id="address"
              v-model="form.address"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              :class="{ 'border-red-300': errors.address }"
              placeholder="Your address"
            />
            <p v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address[0] }}</p>
          </div>

          <div>
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
              Bio
            </label>
            <textarea
              id="bio"
              v-model="form.bio"
              rows="4"
              maxlength="1000"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              :class="{ 'border-red-300': errors.bio }"
              placeholder="Tell us about yourself..."
            ></textarea>
            <p v-if="errors.bio" class="mt-1 text-sm text-red-600">{{ errors.bio[0] }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ form.bio?.length || 0 }}/1000 characters</p>
          </div>

          <!-- Password Change Section -->
          <div class="pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
            <p class="text-sm text-gray-500 mb-4">Leave blank if you don't want to change your password</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                  New Password
                </label>
                <input
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-300': errors.password }"
                />
                <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
              </div>

              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                  Confirm New Password
                </label>
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                />
              </div>
            </div>
          </div>

          <!-- Success/Error Messages -->
          <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-md p-4">
            <p class="text-sm text-green-800">{{ successMessage }}</p>
          </div>

          <div v-if="errorMessage" class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-800">{{ errorMessage }}</p>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end space-x-4 pt-4">
            <button
              type="button"
              @click="resetForm"
              class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Reset
            </button>
            <button
              type="submit"
              :disabled="submitting"
              class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="submitting" class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
              </span>
              <span v-else>Save Changes</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const profile = ref(null);
const loading = ref(true);
const submitting = ref(false);
const profilePicturePreview = ref(null);
const profilePictureFile = ref(null);
const successMessage = ref('');
const errorMessage = ref('');
const errors = ref({});

const form = reactive({
  name: '',
  email: '',
  phone: '',
  bio: '',
  date_of_birth: '',
  address: '',
  password: '',
  password_confirmation: '',
});

const getProfilePictureUrl = (path) => {
  if (!path) return null;
  // If it's already a full URL, return it
  if (path.startsWith('http')) return path;
  // Otherwise, construct the URL
  return `/storage/${path}`;
};

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    if (file.size > 2048 * 1024) {
      errorMessage.value = 'Profile picture must be less than 2MB';
      return;
    }
    profilePictureFile.value = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      profilePicturePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const loadProfile = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/profile');
    profile.value = response.data;
    
    // Populate form with profile data
    form.name = profile.value.name || '';
    form.email = profile.value.email || '';
    form.phone = profile.value.phone || '';
    form.bio = profile.value.bio || '';
    form.date_of_birth = profile.value.date_of_birth || '';
    form.address = profile.value.address || '';
    
    // Update auth store user
    authStore.setAuth(profile.value, authStore.token);
  } catch (error) {
    console.error('Error loading profile:', error);
    errorMessage.value = 'Failed to load profile. Please try again.';
  } finally {
    loading.value = false;
  }
};

const updateProfile = async () => {
  submitting.value = true;
  errors.value = {};
  successMessage.value = '';
  errorMessage.value = '';

  try {
    const formData = new FormData();
    formData.append('name', form.name);
    formData.append('email', form.email);
    if (form.phone) formData.append('phone', form.phone);
    if (form.bio) formData.append('bio', form.bio);
    if (form.date_of_birth) formData.append('date_of_birth', form.date_of_birth);
    if (form.address) formData.append('address', form.address);
    if (profilePictureFile.value) {
      formData.append('profile_picture', profilePictureFile.value);
    }
    // Only send password if it's provided and not empty
    if (form.password && form.password.trim()) {
      formData.append('password', form.password);
      formData.append('password_confirmation', form.password_confirmation || '');
    }

    const response = await axios.post('/profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    successMessage.value = response.data.message || 'Profile updated successfully!';
    profile.value = response.data.user;
    
    // Update auth store
    authStore.setAuth(response.data.user, authStore.token);
    
    // Reset password fields
    form.password = '';
    form.password_confirmation = '';
    profilePictureFile.value = null;
    
    // Clear preview if no existing picture
    if (!profile.value.profile_picture) {
      profilePicturePreview.value = null;
    }
    
    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to update profile. Please try again.';
    }
  } finally {
    submitting.value = false;
  }
};

const resetForm = () => {
  if (profile.value) {
    form.name = profile.value.name || '';
    form.email = profile.value.email || '';
    form.phone = profile.value.phone || '';
    form.bio = profile.value.bio || '';
    form.date_of_birth = profile.value.date_of_birth || '';
    form.address = profile.value.address || '';
    form.password = '';
    form.password_confirmation = '';
    profilePictureFile.value = null;
    profilePicturePreview.value = null;
    errors.value = {};
    successMessage.value = '';
    errorMessage.value = '';
  }
};

onMounted(() => {
  loadProfile();
});
</script>

