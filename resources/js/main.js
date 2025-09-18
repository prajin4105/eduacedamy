import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import './css/app.css';

// ✅ Proper Axios setup
axios.defaults.baseURL = "http://127.0.0.1:8000";
axios.defaults.withCredentials = true;
axios.defaults.headers.common["Accept"] = "application/json";

const app = createApp(App);
app.use(router);
app.mount('#app');
