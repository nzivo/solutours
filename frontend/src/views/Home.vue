<template>
  <Disclosure as="nav" class="bg-gray-100" v-slot="{ open }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <!-- SVG Image -->
          <div class="flex-shrink-0">
            <img class="h-8 w-8" src="/public/vite.svg" alt="Your Company" />
          </div>
          <!-- Text Next to Image -->
          <span class="ml-2 text-[26px] font-bold">Solutours</span>
        </div>

        <!-- md links -->
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <router-link
              v-for="item in navigation"
              :key="item.name"
              :to="item.to"
              active-class="bg-gray-900 text-white"
              :class="[
                item.current === item.to.name
                  ? ''
                  : 'text-gray-500 hover:bg-gray-700 hover:text-white',
                'rounded-md px-3 py-2 text-sm font-medium',
              ]"
              >{{ item.name }}</router-link
            >
          </div>
        </div>
      </div>
    </div>

    <DisclosurePanel class="md:hidden">
      <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
        <router-link
          v-for="item in navigation"
          :key="item.name"
          :to="item.to"
          active-class="bg-gray-900 text-white"
          :class="[
            item.current === item.to.name
              ? ''
              : 'text-gray-500hover:bg-gray-700 hover:text-white',
            'block bg-indigo-700  rounded-md px-3 py-2 text-base font-medium',
          ]"
          >{{ item.name }}</router-link
        >
      </div>
      <div class="border-t border-gray-700 pb-3 pt-4">
        <div class="flex items-center px-5">
          <div class="flex-shrink-0">
            <img class="h-10 w-10 rounded-full" src="/public/vite.svg" alt="" />
          </div>
          <div class="ml-3">
            <div class="text-base font-medium leading-none text-white">
              {{ user.name }}
            </div>
            <div class="text-sm font-medium leading-none text-gray-400">
              {{ user.email }}
            </div>
          </div>
          <button
            type="button"
            class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
          >
            <span class="absolute -inset-1.5" />
            <span class="sr-only">View notifications</span>
            <BellIcon class="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
        <div class="mt-3 space-y-1 px-2">
          <DisclosureButton
            v-for="item in userNavigation"
            :key="item.name"
            as="a"
            :href="item.href"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
            >{{ item.name }}</DisclosureButton
          >
        </div>
      </div>
    </DisclosurePanel>
  </Disclosure>

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
            src="https://a0.muscache.com/im/pictures/hosting/Hosting-U3RheVN1cHBseUxpc3Rpbmc6MTE2MjI1MjI0NDQ0MzYzMjM4Mg%3D%3D/original/ae3426d1-fba4-44d4-bed2-690426f25f7a.jpeg?im_w=1440&im_q=highq"
            alt="Tour Image"
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
import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
} from "@headlessui/vue";
import { Bars3Icon, BellIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { useRouter } from "vue-router";
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();
const router = useRouter();
const tours = computed(() => store.state.tours);

const navigation = [
  { name: "login", to: { name: "login" } },
  { name: "register", to: { name: "register" } },
];

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
