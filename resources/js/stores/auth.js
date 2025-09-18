import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import axios from 'axios';
import router from '@/router';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(JSON.parse(localStorage.getItem('user')));
  const token = ref(localStorage.getItem('auth_token'));
  const isAuthenticated = computed(() => !!token.value);

  const setAuth = (userData, authToken) => {
    user.value = userData;
    token.value = authToken;
    
    // Store user data and token in localStorage
    localStorage.setItem('user', JSON.stringify(userData));
    localStorage.setItem('auth_token', authToken);
    
    // Set the default Authorization header for axios
    axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
  };

  const clearAuth = () => {
    user.value = null;
    token.value = null;
    
    // Remove user data and token from localStorage
    localStorage.removeItem('user');
    localStorage.removeItem('auth_token');
    
    // Remove Authorization header
    delete axios.defaults.headers.common['Authorization'];
  };

  const login = async (credentials) => {
    try {
      const response = await axios.post('/login', credentials);
      const { user, token } = response.data;
      
      setAuth(user, token);
      return { success: true };
    } catch (error) {
      console.error('Login failed:', error);
      return { 
        success: false, 
        error: error.response?.data?.message || 'Login failed. Please try again.' 
      };
    }
  };

  const register = async (userData) => {
    try {
      const response = await axios.post('/register', userData);
      const { user, token } = response.data;
      
      setAuth(user, token);
      return { success: true };
    } catch (error) {
      console.error('Registration failed:', error);
      return { 
        success: false, 
        errors: error.response?.data?.errors || {},
        message: error.response?.data?.message || 'Registration failed. Please try again.'
      };
    }
  };

  const logout = async () => {
    try {
      await axios.post('/logout');
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      clearAuth();
      router.push('/login');
    }
  };

  const checkAuth = async () => {
    if (!token.value) return false;
    
    try {
      const response = await axios.get('/api/user');
      user.value = response.data;
      localStorage.setItem('user', JSON.stringify(response.data));
      return true;
    } catch (error) {
      clearAuth();
      return false;
    }
  };

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout,
    checkAuth,
    setAuth,
    clearAuth
  };
});
