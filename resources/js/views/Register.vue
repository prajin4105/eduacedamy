<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row bg-white shadow-2xl rounded-2xl overflow-hidden w-full max-w-4xl">

      <!-- Left Side (Intro Panel) -->
      <div class="hidden md:flex flex-col justify-center items-center bg-indigo-600 text-white w-1/2 p-10">
        <h2 class="text-3xl font-bold mb-4 text-center">Welcome to Our Community</h2>
        <p class="text-sm text-center text-indigo-100">
          Join us today and start exploring amazing features crafted just for you.
        </p>
        <a
          @click.prevent="goto('login')"
          class="mt-6 inline-block border border-white/50 px-6 py-2 rounded-md hover:bg-white hover:text-indigo-700 transition cursor-pointer"
        >
          Sign In Instead
        </a>
      </div>

      <!-- Right Side (Form Panel) -->
      <div class="w-full md:w-1/2 p-8 sm:p-10">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-extrabold text-gray-900">Create Your Account</h2>
          <p class="mt-2 text-sm text-gray-600">
            Or
            <a @click.prevent="goto('login')" class="font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
              sign in to your account
            </a>
          </p>
        </div>

        <form class="space-y-6" @submit.prevent="register">
          <div class="grid grid-cols-1 gap-5">
            <div>
              <label for="name" class="sr-only">Full Name</label>
              <input
                v-model="form.name"
                id="name"
                name="name"
                type="text"
                autocomplete="name"
                placeholder="Full Name"
                class="w-full px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
              />
              <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</p>
            </div>

            <div>
              <label for="email" class="sr-only">Email address</label>
              <input
                v-model="form.email"
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                placeholder="Email Address"
                class="w-full px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
              />
              <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</p>
            </div>

            <div>
              <label for="password" class="sr-only">Password</label>
              <input
                v-model="form.password"
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                placeholder="Password"
                class="w-full px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
              />
              <p v-if="errors.password" class="text-red-600 text-sm mt-1">{{ errors.password }}</p>
            </div>

            <div>
              <label for="password_confirmation" class="sr-only">Confirm Password</label>
              <input
                v-model="form.password_confirmation"
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                placeholder="Confirm Password"
                class="w-full px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
              />
            </div>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="animate-spin mr-2">&#9696;</span>
            Register
          </button>
          

          <div v-if="successMessage" class="text-green-600 text-center mt-3">
            {{ successMessage }}
          </div>
          <div v-if="serverError" class="text-red-600 text-center mt-3">
            {{ serverError }}
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useMaskedNavigation } from '../utils/navigation'

const router = useRouter()
const { goto } = useMaskedNavigation()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const errors = ref({})
const loading = ref(false)
const successMessage = ref('')
const serverError = ref('')

const register = async () => {
  loading.value = true
  errors.value = {}
  successMessage.value = ''
  serverError.value = ''

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/register', form.value)
    successMessage.value = 'Registration successful! Redirecting to login...'
    setTimeout(() => goto('login'), 2000)
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors || {}
    } else {
      serverError.value = err.response?.data?.message || 'Registration failed'
    }
  } finally {
    loading.value = false
  }
}
</script>
