import { createRouter, createWebHistory } from "vue-router";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Home from "../views/Home.vue";
import DefaultLayout from "../layouts/DefaultLayout.vue";
import store from "../store";
import AuthLayout from "../layouts/AuthLayout.vue";
import Destinations from "../views/Destinations.vue";
import Tours from "../views/Tours.vue";
const routes = [
  {
    path: "/",
    name: "home",
    meta: { requiresAuth: true },
    component: DefaultLayout,
    children: [
      {
        path: "/home",
        name: "home",
        component: Home,
      },
      {
        path: "/tours",
        name: "tours",
        component: Tours,
      },
      {
        path: "/destinations",
        name: "destinations",
        component: Destinations,
      },
    ],
  },
  {
    path: "/auth",
    redirect: "/login",
    name: "auth",
    component: AuthLayout,
    meta: { isGuest: true },
    children: [
      {
        path: "/login",
        name: "login",
        component: Login,
      },
      {
        path: "/register",
        name: "register",
        component: Register,
      },
    ],
  },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    next({ name: "login" });
  } else if (store.state.user.token && to.meta.isGuest) {
    next({ name: "home" });
  } else {
    next();
  }
});

export default router;
