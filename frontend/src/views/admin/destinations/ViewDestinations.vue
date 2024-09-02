<template>
  <header class="bg-gray-100 shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1
        class="text-[24px] font-bold tracking-tight text-center text-gray-900"
      >
        All Destinations
      </h1>
      <button
        @click="toggleForm"
        class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600"
      >
        {{ showForm ? "Hide Form" : "+ Create Destination" }}
      </button>
    </div>
  </header>
  <main class="bg-gray-100 min-h-screen">
    <div
      v-if="showForm"
      class="mx-auto max-w-7xl bg-white rounded-md px-4 py-6 sm:px-6 lg:px-8"
    >
      <form @submit.prevent="createDestination">
        <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 sm:gap-4">
          <div>
            <label
              for="name"
              class="block text-gray-700 font-bold mb-2 text-sm font-medium leading-6 text-gray-900"
              >Name:</label
            >
            <input
              v-model="form.name"
              id="name"
              type="text"
              class="w-full px-4 py-2 border rounded-md block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              required
            />
          </div>
          <div>
            <label
              for="slug"
              class="block text-gray-700 font-bold mb-2 text-sm font-medium leading-6 text-gray-900"
              >Slug:</label
            >
            <input
              v-model="form.slug"
              id="slug"
              type="text"
              class="w-full px-4 py-2 border rounded-md block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              required
            />
          </div>
        </div>
        <div class="mb-4">
          <label
            for="description"
            class="block text-gray-700 font-bold mb-2 text-sm font-medium leading-6 text-gray-900"
            >Description:</label
          >
          <textarea
            v-model="form.description"
            id="description"
            class="w-full px-4 py-2 border rounded-md block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            required
          ></textarea>
        </div>
        <button
          type="submit"
          class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600"
        >
          Create Destination
        </button>
      </form>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <table class="min-w-full bg-white border rounded-md border-gray-200">
        <thead class="bg-indigo-500">
          <tr>
            <th class="py-2 px-4 border-b text-indigo-500">Name</th>
            <th class="py-2 px-4 border-b text-indigo-500">Slug</th>
            <th class="py-2 px-4 border-b text-indigo-500">Description</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="Destination in Destinations" :key="Destination.id">
            <td class="py-2 px-4 border-b">{{ Destination?.name || "N/A" }}</td>
            <td class="py-2 px-4 border-b">{{ Destination?.slug || "N/A" }}</td>
            <td class="py-2 px-4 border-b">
              {{ Destination?.description || "N/A" }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const store = useStore();

const Destinations = computed(() => store.state.destinations);

const form = ref({ name: "", slug: "", description: "" });
const showForm = ref(false);

const toggleForm = () => {
  showForm.value = !showForm.value;
};

const createDestination = () => {
  store
    .dispatch("createDestination", form.value)
    .then(() => {
      toast.success("Destination created successfully!", {
        theme: "auto",
        type: "success",
        position: "bottom-center",
      });
      form.value = { name: "", slug: "", description: "" }; // Reset form
      showForm.value = false; // Hide form
      store.dispatch("getDestinations"); // Reload destinations
    })
    .catch((error) => {
      toast.error("Failed to create destination.", {
        theme: "auto",
        type: "error",
        position: "bottom-center",
      });
      console.error(error);
    });
};

onMounted(() => {
  store.dispatch("getDestinations");
});
</script>

<style scoped>
/* Add any custom styles here */
table {
  width: 100%;
  border-collapse: collapse;
}
th,
td {
  text-align: left;
}
th {
  background-color: #f3f4f6;
}
</style>
