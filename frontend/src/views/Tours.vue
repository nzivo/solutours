<template>
  <header class="bg-gray-100 shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1
        class="text-[24px] font-bold tracking-tight text-center text-gray-900"
      >
        Tours
      </h1>
    </div>
  </header>
  <main class="bg-gray-100 min-h-screen">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 md:grid-cols-4">
        <div
          v-for="tour in tours"
          :key="tour.id"
          class="flex flex-col p-2 m-2 rounded-md bg-white h-[480px] md:h-[450px]"
        >
          <!-- Rounded Image -->
          <img
            v-if="tour.image"
            :src="`http://localhost:8000/${tour.image}`"
            :alt="tour.name"
            class="w-full h-[300px] object-cover rounded-md"
          />
          <img
            v-else
            src="/public/vite.svg"
            :alt="tour.name"
            class="w-full h-[300px] object-cover rounded-md"
          />

          <!-- Tour Name -->
          <h4 class="mt-4 text-lg font-bold text-start">{{ tour.name }}</h4>

          <!-- Tour Destination and Slots -->
          <div
            class="flex justify-between items-center mt-2 text-sm text-gray-500"
          >
            <div class="flex items-center">
              <!-- Map Pin Icon -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-indigo-500 mr-1"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 2C8.686 2 6 4.686 6 8c0 3.776 3.333 7.565 5.405 10.177a.6.6 0 00.969 0C14.667 15.565 18 11.776 18 8c0-3.314-2.686-6-6-6zm0 8a2 2 0 110-4 2 2 0 010 4z"
                />
              </svg>
              <!-- Destination -->
              <span v-if="tour.destination">{{ tour.destination.name }}</span>
              <span v-else>No destination</span>
            </div>

            <div class="flex items-center">
              <!-- Ticket Icon -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-indigo-500 mr-1"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M4 12h16m-1 6H5a2 2 0 01-2-2v-8a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2z"
                />
              </svg>
              <!-- Conditional Slot Display -->
              <span v-if="tour.slots === 0">No slot available</span>
              <span v-else-if="tour.slots === 1">1 slot</span>
              <span v-else>{{ tour.slots }} slots</span>
            </div>
          </div>

          <!-- Price -->
          <div>
            <span class="text-[12px] text-gray-500">KES </span>
            <b class="text-indigo-500 text-lg">{{ tour.price }}</b
            ><span class="text-[12px] text-gray-500">/Per Person</span>
          </div>

          <!-- Book Tour Button -->
          <div v-if="tour.slots === 0">
            <button
              disabled
              class="my-4 px-4 py-2 w-full bg-indigo-500 opacity-0.5 text-white rounded self-center cursor-not-allowed"
            >
              Book Tour
            </button>
          </div>
          <div v-else>
            <button
              class="my-4 px-4 py-2 w-full bg-indigo-500 text-white rounded hover:bg-indigo-600 self-center"
              @click="bookTour(tour.id)"
            >
              Book Tour
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { useRouter } from "vue-router";
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();
const router = useRouter();
const tours = computed(() => store.state.tours);

const bookTour = (tourId) => {
  store
    .dispatch("bookTour", tourId)
    .then((response) => {
      const bookingId = response.booking_id; // Assuming the response contains booking_id
      // Redirect to booking details page with both tourId and bookingId
      console.log("Booking successful, redirecting to booking details page.");
      router.push({ path: `/booking/${tourId}/${bookingId}` });
    })
    .catch((error) => {
      console.error("Error during booking process:", error);
      alert("Failed to book tour.");
    });
};

store.dispatch("getTours");
</script>
