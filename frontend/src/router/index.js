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
import ViewBookings from "../views/admin/bookings/ViewBookings.vue";
import ViewDestinations from "../views/admin/destinations/ViewDestinations.vue";
import ViewTickets from "../views/admin/tickets/ViewTickets.vue";
import ViewTours from "../views/admin/tours/ViewTours.vue";
import ViewUsers from "../views/admin/users/ViewUsers.vue";
import AdminLayout from "../layouts/AdminLayout.vue";

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
    path: "/admin-dashboard",
    name: "adminDashboard",
    component: AdminLayout, // Assume this is your admin dashboard component
    redirect: "/admin-destinations",
    meta: { requiresAuth: true },
    children: [
      {
        path: "/admin-tours",
        name: "adminTours",
        component: ViewTours,
      },
      {
        path: "/admin-bookings",
        name: "adminBookings",
        component: ViewBookings,
      },
      {
        path: "/admin-tickets",
        name: "adminTickets",
        component: ViewTickets,
      },
      {
        path: "/admin-destinations",
        name: "adminDestinations",
        component: ViewDestinations,
      },
      {
        path: "/admin-users",
        name: "adminUsers",
        component: ViewUsers,
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
  const isAuthenticated = !!store.state.user.token;
  const isAdmin = store.state.user.role === "admin";

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: "login" });
  } else if (to.meta.isGuest && isAuthenticated) {
    if (isAdmin) {
      next({ name: "adminDashboard" });
    } else {
      next({ name: "tours" });
    }
  } else if (to.meta.isAdmin && !isAdmin) {
    next({ name: "tours" });
  } else {
    next();
  }
});

export default router;
