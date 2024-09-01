import { createRouter, createWebHistory } from "vue-router";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Home from "../views/Home.vue";
import DefaultLayout from "../layouts/DefaultLayout.vue";
import store from "../store";
import AuthLayout from "../layouts/AuthLayout.vue";
import Destinations from "../views/Destinations.vue";
import Tours from "../views/Tours.vue";
import BookingDetailsPage from "../views/tickets/BookingDetailsPage.vue";
import DownloadTicketPage from "../views/tickets/ViewTicketPage.vue";
import Bookings from "../views/Bookings.vue";

const routes = [
  {
    path: "/tours",
    name: "tours",
    redirect: "/tours",
    meta: { requiresAuth: true },
    component: DefaultLayout,
    children: [
      {
        path: "/tours",
        name: "tours",
        component: Tours,
      },
      {
        path: "/bookings",
        name: "bookings",
        component: Bookings,
      },
      {
        path: "/destinations",
        name: "destinations",
        component: Destinations,
      },
      {
        path: "/booking/:tourId/:bookingId",
        component: BookingDetailsPage,
        props: true,
      },
      {
        path: "/ticket/:id",
        name: "ticketDetails",
        component: DownloadTicketPage,
        props: true,
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
        path: "/",
        name: "home",
        component: Home,
      },
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
    next({ name: "tours" });
  } else {
    next();
  }
});

export default router;
