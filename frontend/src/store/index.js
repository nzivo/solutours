import { createStore } from "vuex";
import axiosClient from "../axios";

const TOKEN_EXPIRY_TIME = 59 * 60 * 1000 * 24; //

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem("TOKEN"),
      tokenTimestamp: sessionStorage.getItem("TOKEN_TIMESTAMP"),
    },
    destinations: { loading: false, data: [] },
    tours: { loading: false, data: [] },
    myBookings: { loading: false, data: [] },
    allBookings: { loading: false, data: [] },
    allTickets: { loading: false, data: [] },
    allUsers: { loading: false, data: [] },
    myTicket: { loading: false, data: [] },
    booking: null,
  },
  getters: {
    isTokenExpired(state) {
      if (!state.user.token || !state.user.tokenTimestamp) return true;
      const now = new Date().getTime();
      return now - state.user.tokenTimestamp > TOKEN_EXPIRY_TIME;
    },
  },
  actions: {
    register({ commit, getters }, user) {
      if (getters.isTokenExpired) {
        commit("logout");
      }
      return axiosClient.post("/register", user).then(({ data }) => {
        commit("setUser", data.user);
        commit("setToken", data.access_token);
        return data;
      });
    },
    login({ commit, getters }, user) {
      if (getters.isTokenExpired) {
        commit("logout");
      }
      return axiosClient.post("/login", user).then(({ data }) => {
        commit("setUser", data.user);
        commit("setToken", data.access_token);
        return data;
      });
    },
    logout({ commit }) {
      return axiosClient.post("/logout").then((response) => {
        commit("logout");
        return response;
      });
    },
    getMyBookings({ commit }) {
      commit("setMyBookingsLoading", true);
      return axiosClient.get("/my-bookings").then((res) => {
        commit("setMyBookingsLoading", false);
        commit("setMyBookings", res.data);
        return res;
      });
    },
    getAllBookings({ commit }) {
      commit("setAllBookingsLoading", true);
      return axiosClient.get("/bookings").then((res) => {
        commit("setAllBookingsLoading", false);
        commit("setAllBookings", res.data);
        return res;
      });
    },
    getAllTickets({ commit }) {
      commit("setAllTicketsLoading", true);
      return axiosClient.get("/get-all-tickets").then((res) => {
        commit("setAllTicketsLoading", false);
        commit("setAllTickets", res.data);
        console.log(res);
        return res;
      });
    },
    getAllUsers({ commit }) {
      commit("setAllUsersLoading", true);
      return axiosClient.get("/users").then((res) => {
        commit("setAllUsersLoading", false);
        commit("setAllUsers", res.data);
        console.log(res);
        return res;
      });
    },
    getDestinations({ commit }) {
      commit("setDestinationsLoading", true);
      return axiosClient.get("/destinations").then((res) => {
        commit("setDestinationsLoading", false);
        commit("setDestinations", res.data);
        return res;
      });
    },
    getTours({ commit }) {
      commit("setToursLoading", true);
      return axiosClient.get("/tours").then((res) => {
        commit("setToursLoading", false);
        commit("setTours", res.data);
        return res;
      });
    },

    bookTour({ commit }, tourId) {
      return axiosClient
        .post("/book-tour", { tour_id: tourId })
        .then(({ data }) => {
          commit("setBooking", data.booking_id);
          return data;
        });
    },
    confirmBooking({ commit }, bookingId) {
      return axiosClient.post(`/confirm-booking/${bookingId}`).then((res) => {
        // Optionally, update the booking status in the state here
        commit("updateBookingStatus", bookingId);
        return res;
      });
    },
    generateTicket({ commit }, bookingId) {
      console.log(bookingId);
      return axiosClient
        .post("/generate-ticket", { booking_id: bookingId })
        .then((res) => {
          commit("addTicket", bookingId);
          return res;
        });
    },
    ticketDetails({ commit }, bookingId) {
      console.log(bookingId);
      commit("setMyTicketLoading", true);
      return axiosClient.get(`/view-ticket/${bookingId}`).then((res) => {
        commit("setMyTicketLoading", false);
        commit("setMyTicket", res.data);
        return res;
      });
    },
    // update user role action
    updateUserRole({ commit }, { userId, role }) {
      return axiosClient.put(`/users/${userId}`, { role }).then((res) => {
        commit("updateUserRoleInState", { userId, role });
        return res;
      });
    },
  },
  mutations: {
    logout: (state) => {
      state.user.token = null;
      state.user.data = {};
      state.user.tokenTimestamp = null;
      sessionStorage.removeItem("TOKEN");
      sessionStorage.removeItem("TOKEN_TIMESTAMP");
    },

    setUser: (state, user) => {
      state.user.data = user;
    },

    setToken: (state, token) => {
      state.user.token = token;
      state.user.tokenTimestamp = new Date().getTime();
      sessionStorage.setItem("TOKEN", token);
      sessionStorage.setItem("TOKEN_TIMESTAMP", state.user.tokenTimestamp);
    },

    // Mutations for My Bookings
    setMyBookingsLoading: (state, loading) => {
      state.myBookings.loading = loading;
    },

    setMyBookings: (state, myBookings) => {
      state.myBookings = myBookings;
    },

    // Mutations for All Bookings
    setAllBookingsLoading: (state, loading) => {
      state.allBookings.loading = loading;
    },

    setAllBookings: (state, allBookings) => {
      state.allBookings = allBookings;
    },

    // Mutations for All Tickets
    setAllTicketsLoading: (state, loading) => {
      state.allTickets.loading = loading;
    },

    setAllTickets: (state, allTickets) => {
      state.allTickets = allTickets;
    },

    // Mutations for All Users
    setAllUsersLoading: (state, loading) => {
      state.allUsers.loading = loading;
    },

    setAllUsers: (state, allUsers) => {
      state.allUsers = allUsers;
    },

    // Mutations for destinations
    setDestinationsLoading: (state, loading) => {
      state.destinations.loading = loading;
    },

    setDestinations: (state, destinations) => {
      state.destinations = destinations;
    },

    // Mutations for tours
    setToursLoading: (state, loading) => {
      state.tours.loading = loading;
    },

    setTours: (state, tours) => {
      state.tours = tours;
    },

    setBooking: (state, bookingId) => {
      state.booking = bookingId;
    },
    updateBookingStatus: (state, bookingId) => {
      const booking = state.myBookings.find((b) => b.id === bookingId);
      // if (booking) {
      //   booking.status = status;
      // }
    },
    addTicket: (state, bookingId) => {
      const booking = state.myBookings.find((b) => b.id === bookingId);
      // if (booking) {
      //   booking.tickets.push(ticket);
      // }
    },
    // Mutations for destinations
    setMyTicketLoading: (state, loading) => {
      state.myTicket.loading = loading;
    },

    setMyTicket: (state, myTicket) => {
      state.myTicket = myTicket;
    },

    // update user role mutation
    updateUserRoleInState(state, { userId, role }) {
      const user = state.allUsers.find((u) => u.id === userId);
      if (user) {
        user.role = role;
      }
    },
  },
  modules: {},
});

export default store;
