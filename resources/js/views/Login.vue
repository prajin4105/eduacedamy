<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row bg-white shadow-2xl rounded-2xl overflow-hidden w-full max-w-4xl">

      <!-- Left Side (Welcome Panel) -->
      <div class="hidden md:flex flex-col justify-center items-center bg-indigo-600 text-white w-1/2 p-10">
        <h2 class="text-3xl font-bold mb-4 text-center">Welcome Back to EduAcademy</h2>
        <p class="text-sm text-center text-indigo-100">
          Access your account and continue your learning journey.
        </p>
        <router-link
          to="/register"
          class="mt-6 inline-block border border-white/50 px-6 py-2 rounded-md hover:bg-white hover:text-indigo-700 transition"
        >
          Create Account
        </router-link>
      </div>

      <!-- Right Side (Login Form) -->
      <div class="w-full md:w-1/2 p-8 sm:p-10">
        <div class="text-center mb-8">
          <router-link to="/" class="inline-block mb-4">
            <h2 class="text-3xl font-bold text-indigo-600">EduAcademy</h2>
          </router-link>
          <h2 class="text-2xl font-extrabold text-gray-900">Sign in to your account</h2>
        </div>

        <form @submit.prevent="login" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              :class="{ 'border-red-300': errors.email }"
            />
            <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email[0] }}</p>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              :class="{ 'border-red-300': errors.password }"
            />
            <p v-if="errors.password" class="mt-2 text-sm text-red-600">{{ errors.password[0] }}</p>
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center text-sm text-gray-900">
              <input
                id="remember-me"
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
              />
              <span class="ml-2">Remember me</span>
            </label>

            <router-link
              to="/forgot-password"
              class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
            >
              Forgot your password?
            </router-link>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white mr-2"></span>
            {{ loading ? 'Signing in...' : 'Sign in' }}
          </button>

          <div v-if="message" :class="messageType === 'success' ? 'text-green-600' : 'text-red-600'" class="text-sm text-center">
            {{ message }}
          </div>
        </form>

        <!-- Social Login Section -->


        <div class="mt-8 text-center">
          <p class="text-sm text-gray-600">
            Don’t have an account?
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
  remember: false
})

const loading = ref(false)
const errors = ref({})
const message = ref('')
const messageType = ref('success')

const login = async () => {
  try {
    loading.value = true
    errors.value = {}
    message.value = ''

    const response = await axios.post('/login', {
      email: form.value.email,
      password: form.value.password,
      device_name: 'web'
    })

    const token = response.data.token
    localStorage.setItem('auth_token', token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    message.value = 'Login successful! Redirecting...'
    messageType.value = 'success'

    const me = await axios.get('/me')
    localStorage.setItem('user', JSON.stringify(me.data))
    authStore.setAuth(me.data, token)

    setTimeout(async () => {
      const role = me.data.role
      if (role === 'admin') {
        try {
          await axios.post('http://127.0.0.1:8000/login', {
            email: form.value.email,
            password: form.value.password,
            remember: true
          }, {
            withCredentials: true,
            headers: { Accept: 'application/json' }
          })
          window.location.href = '/admin'
        } catch (e) {
          window.location.href = '/admin/login'
        }
      } else if (role === 'instructor') {
        try {
          await axios.post('http://127.0.0.1:8000/login', {
            email: form.value.email,
            password: form.value.password,
            remember: true
          }, {
            withCredentials: true,
            headers: { Accept: 'application/json' }
          })
          window.location.href = '/instructor'
        } catch (e) {
          window.location.href = '/instructor/login'
        }
      } else if (role === 'student') {
        router.replace('/dashboard')
      } else {
        router.replace('/')
      }
    }, 300)
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      message.value = error.response.data.message || 'Validation failed'
    } else {
      message.value = error.response?.data?.message || 'Login failed. Please try again.'
    }
    messageType.value = 'error'
  } finally {
    loading.value = false
  }
}
</script>
