<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <router-link to="/" class="flex justify-center">
        <h2 class="text-3xl font-bold text-indigo-600">EduAcademy</h2>
      </router-link>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Sign in to your account
      </h2>

    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form @submit.prevent="login" class="space-y-6">
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
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.email }"
              />
              <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email[0] }}</p>
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Password
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="form.password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.password }"
              />
              <p v-if="errors.password" class="mt-2 text-sm text-red-600">{{ errors.password[0] }}</p>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember-me"
                v-model="form.remember"
                name="remember-me"
                type="checkbox"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                Remember me
              </label>
            </div>

            <div class="text-sm">
              <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                Forgot your password?
              </a>
            </div>
          </div>

          <div>
            <button
              type="submit"
              :disabled="loading"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mr-2"></span>
              {{ loading ? 'Signing in...' : 'Sign in' }}
            </button>
          </div>

          <div v-if="message" :class="messageType === 'success' ? 'text-green-600' : 'text-red-600'" class="text-sm text-center">
            {{ message }}
          </div>
        </form>

        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300" />
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-gray-500">Or continue with</span>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-3 gap-3">
            <div>
              <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Sign in with Facebook</span>
                <i class="fab fa-facebook-f text-blue-600"></i>
              </a>
            </div>

            <div>
              <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Sign in with Twitter</span>
                <i class="fab fa-twitter text-blue-400"></i>
              </a>
            </div>

            <div>
              <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Sign in with GitHub</span>
                <i class="fab fa-github text-gray-900"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Don't have an account?
            <router-link to="/register" class="font-medium text-indigo-600 hover:text-indigo-500">
              Sign up here
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
  email: '',
  password: '',
  remember: false
});

const loading = ref(false);
const errors = ref({});
const message = ref('');
const messageType = ref('success');

const login = async () => {
  try {
    loading.value = true;
    errors.value = {};
    message.value = '';

    const response = await axios.post('/login', {
      email: form.value.email,
      password: form.value.password,
      device_name: 'web'
    });

    const token = response.data.token;
    localStorage.setItem('auth_token', token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    message.value = 'Login successful! Redirecting...';
    messageType.value = 'success';

    const me = await axios.get('/me');

    // Store user data in localStorage
    localStorage.setItem('user', JSON.stringify(me.data));

    // Update auth store with user data and token
    authStore.setAuth(me.data, token);

    setTimeout(() => {
      const role = me.data.role;
      if (role === 'admin') {
        window.location.href = '/admin';
      } else if (role === 'instructor') {
        window.location.href = '/instructor';
      } else if (role === 'student') {
        router.replace('/dashboard');
      } else {
        router.replace('/');
      }
    }, 300);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
      message.value = error.response.data.message || 'Validation failed';
    } else {
      message.value = error.response?.data?.message || 'Login failed. Please try again.';
    }
    messageType.value = 'error';
  } finally {
    loading.value = false;
  }
};
</script>
