import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Courses from '../views/Courses.vue';
import CourseDetail from '../views/CourseDetail.vue';
import Dashboard from '../views/Dashboard.vue';
import EnhancedDashboard from '../views/EnhancedDashboard.vue';
import StudentDashboard from '../views/StudentDashboard.vue';
import StudentCourse from '../views/StudentCourse.vue';
import StudentVideo from '../views/StudentVideo.vue';
import Login from '../views/Login.vue';
import NotFound from '../views/NotFound.vue';
import axios from 'axios';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/courses',
    name: 'Courses',
    component: Courses
  },
  {
    path: '/courses/:slug',
    name: 'CourseDetail',
    component: CourseDetail,
    props: true
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: EnhancedDashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/old-dashboard',
    name: 'OldDashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/student/dashboard',
    name: 'StudentDashboard',
    component: StudentDashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/course/:slug',
    name: 'StudentCourse',
    component: StudentCourse,
    meta: { requiresAuth: true },
    props: true
  },
  {
    path: '/course/:slug/video/:videoId',
    name: 'StudentVideo',
    component: StudentVideo,
    meta: { requiresAuth: true },
    props: true
  },
  // Not found route (must be last)
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 };
  }
});

// Navigation guard using token auth
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  } else {
    delete axios.defaults.headers.common['Authorization'];
  }

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!token) {
      next({ name: 'Login', query: { redirect: to.fullPath } });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
