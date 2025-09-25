import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import './css/app.css';

// ✅ Proper Axios setup
axios.defaults.baseURL = "http://127.0.0.1:8000/api";
axios.defaults.headers.common["Accept"] = "application/json";

// Attach Bearer token if exists
const storedToken = localStorage.getItem("auth_token");
if (storedToken) {
  axios.defaults.headers.common["Authorization"] = `Bearer ${storedToken}`;
}

const app = createApp(App);
app.use(router);

// Make axios available as $http
app.config.globalProperties.$http = axios;

app.mount('#app');
