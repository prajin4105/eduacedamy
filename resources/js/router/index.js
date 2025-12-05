import { createRouter, createWebHistory } from "vue-router";
import NotFound from "../views/NotFound.vue";
import MaskedLoader from "../views/MaskedLoader.vue";
import axios from "axios";

// Global long-mask generator
const generateMask = (length = 20) => {
  return Array.from({ length }, () =>
    Math.random().toString(36)[2] || "x"
  ).join("");
};

const routes = [
  {
    path: "/",
    redirect: () => {
      const mask = generateMask(20);
      return { name: "MaskedPage", params: { mask } };
    }
  },

  {
    path: "/:mask",
    name: "MaskedPage",
    component: MaskedLoader,
    props: true
  },
{
    path: "/login",
    name: "Login",
    component: () => import("../views/Login.vue")
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("../views/Register.vue")
},
{
    path:"/reset-password",
    name:"ResetPassword",
    component: () => import("../views/ForgotPassword.vue")
},
{
    path: "/become-instructor",
    name: "BecomeInstructor",
    component: () => import("../views/BecomeInstructor.vue")
},
  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    component: NotFound
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  }
});

// Auth header
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("auth_token");
  if (token) axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  else delete axios.defaults.headers.common["Authorization"];

  next();
});

export default router;
