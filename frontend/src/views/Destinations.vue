<template lang="">
  <header class="bg-gray-100 shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-center text-gray-900">
        Destinations
      </h1>
    </div>
  </header>
  <main class="bg-gray-100 min-h-screen">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
        <div
          v-for="destination in destinations"
          :key="destination.id"
          class="flex flex-col py-4 px-6 shadow-md rounded-md bg-white hover:bg-indigo-50 h-[150px]"
        >
          <h4 class="mt-4 text-lg font-bold text-indigo-500 flex items-center">
            <!-- Map Pin Icon -->
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 mr-2"
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
            <!-- Destination Name -->
            {{ destination.name }}
          </h4>

          <div
            v-html="destination.description"
            class="overflow-hidden flex-1 text-gray-500"
          ></div>
        </div>
      </div>
    </div>
  </main>
</template>
<script setup>
import store from "../store";
import { computed } from "vue";

const destinations = computed(() => store.state.destinations);

store.dispatch("getDestinations");
</script>
