<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or
          <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
            sign in to your account
          </router-link>
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="register">
        <div class="rounded-md shadow-sm -space-y-px">
          <div class="mb-4">
            <label for="name" class="sr-only">Full Name</label>
            <input
              v-model="form.name"
              id="name"
              name="name"
              type="text"
              autocomplete="name"
              placeholder="Full Name"
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
            />
            <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</p>
          </div>

          <div class="mb-4">
            <label for="email" class="sr-only">Email address</label>
            <input
              v-model="form.email"
              id="email"
              name="email"
              type="email"
              autocomplete="email"
              placeholder="Email address"
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
            />
            <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</p>
          </div>

          <div class="mb-4">
            <label for="password" class="sr-only">Password</label>
            <input
              v-model="form.password"
              id="password"
              name="password"
              type="password"
              autocomplete="new-password"
              placeholder="Password"
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
            />
            <p v-if="errors.password" class="text-red-600 text-sm mt-1">{{ errors.password }}</p>
          </div>

          <div class="mb-4">
            <label for="password_confirmation" class="sr-only">Confirm Password</label>
            <input
              v-model="form.password_confirmation"
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              autocomplete="new-password"
              placeholder="Confirm Password"
              class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="animate-spin mr-2">&#9696;</span>
            Register
          </button>
        </div>

        <div v-if="successMessage" class="text-green-600 text-center mt-2">
          {{ successMessage }}
        </div>
        <div v-if="serverError" class="text-red-600 text-center mt-2">
          {{ serverError }}
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const errors = ref({});
const loading = ref(false);
const successMessage = ref('');
const serverError = ref('');

const register = async () => {
  loading.value = true;
  errors.value = {};
  successMessage.value = '';
  serverError.value = '';

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/register', form.value);

    successMessage.value = 'Registration successful! Redirecting to login...';
    setTimeout(() => {
      router.push('/login');
    }, 2000);
  } catch (err) {
    if (err.response?.status === 422) {
      // Validation errors
      errors.value = err.response.data.errors || {};
    } else {
      serverError.value = err.response?.data?.message || 'Registration failed';
    }
  } finally {
    loading.value = false;
  }
};
</script>

