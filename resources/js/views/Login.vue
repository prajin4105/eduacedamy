<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row bg-white shadow-2xl rounded-2xl overflow-hidden w-full max-w-4xl">

      <!-- Left Side (Welcome Panel) -->
      <div class="hidden md:flex flex-col justify-center items-center bg-indigo-600 text-white w-1/2 p-10">
        <h2 class="text-3xl font-bold mb-4 text-center">Welcome Back to EduAcademy</h2>
        <p class="text-sm text-center text-indigo-100">
          Access your account and continue your learning journey.
        </p>
        <RouterLink
          to="/register"
          class="mt-6 inline-block border border-white/50 px-6 py-2 rounded-md hover:bg-white hover:text-indigo-700 transition cursor-pointer"
        >
          Create Account
        </RouterLink>
      </div>

      <!-- Right Side (Login Form) -->
      <div class="w-full md:w-1/2 p-8 sm:p-10">
        <div class="text-center mb-8">
          <a @click.prevent="goto('home')" class="inline-block mb-4 cursor-pointer">
            <h2 class="text-3xl font-bold text-indigo-600">EduAcademy</h2>
          </a>
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

            <RouterLink
              to="/reset-password"
              class="text-sm font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer"
            >
              Forgot your password?
            </RouterLink>
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

        <div class="mt-8 text-center">
          <p class="text-sm text-gray-600">
            Don't have an account?
            <RouterLink to="/register" class="font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
              Sign up here
            </RouterLink>
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
import { useMaskedNavigation } from '../utils/navigation'

const router = useRouter()
const authStore = useAuthStore()
const { goto } = useMaskedNavigation()

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

    // Step 1: Login and get token
    const response = await axios.post('/login', {
      email: form.value.email,
      password: form.value.password,
      device_name: 'web'
    })

    console.log('Full response:', response.data)

    // Extract token from response - check if it's wrapped in data property
    const token = response.data.data?.token || response.data.token
    const userData = response.data.data?.user || response.data.user

    if (!token) {
      throw new Error('No token received from server')
    }

    console.log('Token received:', token ? 'Yes' : 'No')

    // Step 2: Set token in localStorage AND axios headers BEFORE making /me request
    localStorage.setItem('auth_token', token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

    // Step 3: If user data is already in login response, use it; otherwise fetch from /me
    let me
    if (userData) {
      me = { data: userData }
      console.log('User data from login response:', userData)
    } else {
      // Fetch user data with the token properly set
      me = await axios.get('/me', {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      })
      console.log('User data from /me endpoint:', me.data)
    }

    // Step 4: Store user data
    localStorage.setItem('user', JSON.stringify(me.data))
    authStore.setAuth(me.data, token)

    message.value = 'Login successful! Redirecting...'
    messageType.value = 'success'

    // Step 5: Role-based redirection
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
          console.error('Admin login error:', e)
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
          console.error('Instructor login error:', e)
          window.location.href = '/instructor/login'
        }
      } else if (role === 'student') {
        goto('dashboard')
      } else {
        goto('home')
      }
    }, 300)
  } catch (error) {
    console.error('Login error:', error)

    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      message.value = error.response.data.message || 'Validation failed'
    } else if (error.response?.status === 401) {
      message.value = 'Invalid email or password'
    } else {
      message.value = error.response?.data?.message || 'Login failed. Please try again.'
    }
    messageType.value = 'error'
  } finally {
    loading.value = false
  }
}
</script>
