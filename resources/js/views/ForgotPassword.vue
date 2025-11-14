<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <a @click.prevent="goto('home')" class="flex justify-center cursor-pointer">
        <h2 class="text-3xl font-bold text-indigo-600">EduAcademy</h2>
      </a>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Reset your password
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Remember your password?
        <a @click.prevent="goto('login')" class="font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
          Sign in
        </a>
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form @submit.prevent="handleSubmit" class="space-y-6">

          <!-- Email Input (Always visible) -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email address
            </label>
            <div class="mt-1">
              <input
                id="email"
                v-model="form.email"
                name="email"
                type="email"
                autocomplete="email"
                required
                :disabled="otpSent"
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                :class="{ 'border-red-300': errors.email }"
                placeholder="Enter your email"
              />
              <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email }}</p>
            </div>
          </div>

          <!-- OTP Input (Visible after email is verified) -->
          <div v-if="otpSent">
            <label for="otp" class="block text-sm font-medium text-gray-700">
              OTP Code
            </label>
            <div class="mt-1">
              <input
                id="otp"
                v-model="form.otp"
                name="otp"
                type="text"
                maxlength="6"
                required
                :disabled="otpVerified"
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                :class="{ 'border-red-300': errors.otp, 'border-green-300': otpVerified }"
                placeholder="Enter 6-digit OTP"
              />
              <p v-if="errors.otp" class="mt-2 text-sm text-red-600">{{ errors.otp }}</p>
              <p v-if="otpVerified" class="mt-2 text-sm text-green-600">✓ OTP verified successfully</p>
            </div>

            <!-- Resend OTP -->
            <div class="mt-2 flex items-center justify-between">
              <button
                v-if="!otpVerified"
                type="button"
                @click="resendOtp"
                :disabled="resendDisabled || loading"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-500 disabled:text-gray-400 disabled:cursor-not-allowed"
              >
                {{ resendDisabled ? `Resend OTP in ${countdown}s` : 'Resend OTP' }}
              </button>
              <span v-if="otpSent && !otpVerified" class="text-xs text-gray-500">
                OTP sent to your email
              </span>
            </div>
          </div>

          <!-- New Password Input (Visible after OTP is verified) -->
          <div v-if="otpVerified">
            <label for="password" class="block text-sm font-medium text-gray-700">
              New Password
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="form.password"
                name="password"
                type="password"
                autocomplete="new-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.password }"
                placeholder="Enter new password"
              />
              <p v-if="errors.password" class="mt-2 text-sm text-red-600">{{ errors.password }}</p>
            </div>
          </div>

          <!-- Confirm Password Input (Visible after OTP is verified) -->
          <div v-if="otpVerified">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
              Confirm Password
            </label>
            <div class="mt-1">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.password_confirmation }"
                placeholder="Confirm new password"
              />
              <p v-if="errors.password_confirmation" class="mt-2 text-sm text-red-600">{{ errors.password_confirmation }}</p>
            </div>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              :disabled="loading"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mr-2"></span>
              {{ getButtonText() }}
            </button>
          </div>

          <!-- Success/Error Messages -->
          <div v-if="message" :class="messageType === 'success' ? 'text-green-600' : 'text-red-600'" class="text-sm text-center">
            {{ message }}
          </div>
        </form>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
          <a @click.prevent="goto('login')" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
            ← Back to login
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useMaskedNavigation } from '../utils/navigation';

const router = useRouter();
const { goto } = useMaskedNavigation();

const form = ref({
  email: '',
  otp: '',
  password: '',
  password_confirmation: ''
});

const loading = ref(false);
const errors = ref({});
const message = ref('');
const messageType = ref('success');

const otpSent = ref(false);
const otpVerified = ref(false);
const resendDisabled = ref(false);
const countdown = ref(60);

let countdownInterval = null;

// Start countdown timer for resend OTP
const startCountdown = () => {
  resendDisabled.value = true;
  countdown.value = 60;

  countdownInterval = setInterval(() => {
    countdown.value--;
    if (countdown.value <= 0) {
      clearInterval(countdownInterval);
      resendDisabled.value = false;
    }
  }, 1000);
};

// Clear countdown on component unmount
onBeforeUnmount(() => {
  if (countdownInterval) {
    clearInterval(countdownInterval);
  }
});

// Get button text based on current step
const getButtonText = () => {
  if (loading.value) {
    return 'Processing...';
  }
  if (!otpSent.value) {
    return 'Send OTP';
  }
  if (!otpVerified.value) {
    return 'Verify OTP';
  }
  return 'Reset Password';
};

// Handle form submission
const handleSubmit = async () => {
  if (!otpSent.value) {
    await sendOtp();
  } else if (!otpVerified.value) {
    await verifyOtp();
  } else {
    await resetPassword();
  }
};

// Step 1: Send OTP to email
const sendOtp = async () => {
  try {
    loading.value = true;
    errors.value = {};
    message.value = '';

    const response = await axios.post('/forgot-password', {
      email: form.value.email
    });

    otpSent.value = true;
    message.value = 'OTP has been sent to your email address';
    messageType.value = 'success';
    startCountdown();
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
      message.value = error.response.data.message || 'Validation failed';
    } else {
      message.value = error.response?.data?.message || 'Failed to send OTP. Please try again.';
    }
    messageType.value = 'error';
  } finally {
    loading.value = false;
  }
};

// Step 2: Verify OTP
const verifyOtp = async () => {
  try {
    loading.value = true;
    errors.value = {};
    message.value = '';

    const response = await axios.post('/verify-otp', {
      email: form.value.email,
      otp: form.value.otp
    });

    otpVerified.value = true;
    message.value = 'OTP verified successfully. Please enter your new password.';
    messageType.value = 'success';
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
      message.value = error.response.data.message || 'Invalid OTP';
    } else {
      message.value = error.response?.data?.message || 'OTP verification failed. Please try again.';
    }
    messageType.value = 'error';
  } finally {
    loading.value = false;
  }
};

// Step 3: Reset Password
const resetPassword = async () => {
  try {
    loading.value = true;
    errors.value = {};
    message.value = '';

    const response = await axios.post('/reset-password', {
      email: form.value.email,
      otp: form.value.otp,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation
    });

    message.value = 'Password reset successful! Redirecting to login...';
    messageType.value = 'success';

    setTimeout(() => {
      goto('login');
    }, 2000);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
      message.value = error.response.data.message || 'Validation failed';
    } else {
      message.value = error.response?.data?.message || 'Password reset failed. Please try again.';
    }
    messageType.value = 'error';
  } finally {
    loading.value = false;
  }
};

// Resend OTP
const resendOtp = async () => {
  try {
    loading.value = true;
    errors.value = {};
    message.value = '';

    const response = await axios.post('/forgot-password', {
      email: form.value.email
    });

    message.value = 'OTP has been resent to your email address';
    messageType.value = 'success';
    startCountdown();
  } catch (error) {
    message.value = error.response?.data?.message || 'Failed to resend OTP. Please try again.';
    messageType.value = 'error';
  } finally {
    loading.value = false;
  }
};
</script>
