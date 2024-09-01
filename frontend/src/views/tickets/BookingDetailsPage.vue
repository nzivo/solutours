<template>
  <div class="bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold tracking-tight text-center text-gray-900">
      Confirm Booking
    </h1>
    <div class="flex items-center justify-center mt-2">
      <p>Your booking with ID: {{ bookingId }} is being processed.</p>
    </div>
    <div class="flex items-center justify-center mt-4">
      <button
        class="w-[160px] px-4 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600"
        @click="confirmBooking(bookingId)"
      >
        Confirm Booking
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from "vue-router";
import { useStore } from "vuex";
import { ref } from "vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

// Access the route parameters and Vuex store
const route = useRoute();
const router = useRouter();
const store = useStore();
const bookingId = ref(route.params.bookingId); // Getting bookingId from the route params

function confirmBooking(bookingId) {
  store
    .dispatch("confirmBooking", bookingId)
    .then(() => store.dispatch("generateTicket", bookingId))
    .then((response) => {
      console.log(response);
      console.log("Booking confirmed, redirecting to ticket details page.");
      router.push({ name: "ticketDetails", params: { id: bookingId } });
    })
    .catch((error) => {
      toast("Error occurred confirming booking", {
        theme: "auto",
        type: "error",
        position: "bottom-center",
      });
    });
}
</script>
