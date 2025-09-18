import './bootstrap';
import { createApp, h } from 'vue';
import { RouterView } from 'vue-router';
import { createPinia } from 'pinia';
import router from './router';
import axios from 'axios';

// Set up axios defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;

// Attach bearer token if present
const storedToken = localStorage.getItem('auth_token');
if (storedToken) {
	axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
}

// Preload Sanctum CSRF cookie once on boot (non-blocking)
axios.get('/sanctum/csrf-cookie').catch(() => {});

// Create Pinia instance
const pinia = createPinia();

// Create Vue app
const app = createApp({
    data() {
        return {
            user: window.Laravel.user,
            isAuthenticated: window.Laravel.isAuthenticated
        }
    },
    provide() {
        return {
            user: this.user,
            isAuthenticated: this.isAuthenticated
        }
    },
    render() {
        return h(RouterView);
    }
});

app.use(pinia);
app.use(router);
app.config.globalProperties.$http = axios;
app.mount('#app');
