import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Use API base URL explicitly
window.axios.defaults.baseURL = 'http://127.0.0.1:8000';

// Token auth: do not send cookies for CORS
window.axios.defaults.withCredentials = false;

// Optional XSRF configuration (kept harmless)
window.axios.defaults.xsrfCookieName = 'XSRF-TOKEN';
window.axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN';
