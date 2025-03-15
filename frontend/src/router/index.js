import { createRouter, createWebHistory } from "vue-router";
import AdminLayout from "../components/layouts/AdminLayout.vue";
import AuthLayout from "../components/layouts/AuthLayout.vue";
import Dashboard from "../views/Dashboard.vue";
import Users from "../views/Users.vue";
import Settings from "../views/Settings.vue";
import Login from "../views/Auth/Login.vue";
import Register from "../views/Auth/Register.vue";
import ForgotPassword from "../views/Auth/Forgot.vue";
import LandingPage from "../views/LandingPage.vue";
import NotFound from "../views/error/404.vue"; 
import List from "../views/List.vue";
import Questions from "../views/Questions.vue";
import Batches from "../views/Batches.vue";
import EnrolledStudentsList from "../views/EnrolledStudents.vue";
import CourseList from "../views/Courselist.vue";
import DepartmentList from "../views/DepartmentList.vue";

const isAuthenticated = () => {
  const token = localStorage.getItem("token");
  return token && token !== "undefined" && token !== "null"; // Prevent invalid tokens
};

const routes = [
  {
    path: "/",
    component: LandingPage,
    meta: { guestOnly: true } 
  },
  {
    path: "/",
    component: AdminLayout,
    children: [
      { path: "dashboard", component: Dashboard, meta: { requiresAuth: true } },
      { path: "users", component: Users, meta: { requiresAuth: false } },
      { path: "questions", component: Questions, meta: { requiresAuth: true } },
      // { path: "results", component: Results, meta: { requiresAuth: false } },
      { path: "batch", component: Batches, meta: { requiresAuth: true } },
      // { path: "no-exams-list", component: NoExamsList, meta: { requiresAuth: false } },
      { path: "course-list", component: CourseList, meta: { requiresAuth: true } },
      { path: "department-list", component: DepartmentList, meta: { requiresAuth: false } },
      { path: "enrolled-students-list", component: EnrolledStudentsList, meta: { requiresAuth: false } },
      // { path: "list", component: List, meta: { requiresAuth: false } },
      // { path: "settings", component: Settings, meta: { requiresAuth: false } },
    ],
  },  
  {
    path: "/",
    component: AuthLayout,
    children: [
      { path: "register", component: Register, meta: { guestOnly: true } },
      { path: "sign-in", component: Login, meta: { guestOnly: true } },
      { path: "forgot-password", component: ForgotPassword, meta: { guestOnly: true } },
    ],
  },
  // 404 Not Found route
  {
    path: "/:pathMatch(.*)*", 
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authRequired = to.meta.requiresAuth;
  const guestOnly = to.meta.guestOnly;
  const isLoggedIn = isAuthenticated();

  if (authRequired && !isLoggedIn) {
    next("/sign-in");
  } else if (guestOnly && isLoggedIn) {
    next("/dashboard");
  } else {
    next();
  }
});

export default router;
