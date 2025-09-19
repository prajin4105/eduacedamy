import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue'; // ✅ import your App.vue
import axios from 'axios';

// Set up axios defaults
axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const storedToken = localStorage.getItem('token');
if (storedToken) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
}

// Create Pinia
const pinia = createPinia();

// Create and mount Vue app
const app = createApp(App); // ✅ mount App.vue
app.use(pinia);
app.use(router);
app.config.globalProperties.$http = axios;

app.mount('#app');
