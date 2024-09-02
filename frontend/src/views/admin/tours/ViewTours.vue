<template>
  <header class="bg-gray-100 shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1
        class="text-[24px] font-bold tracking-tight text-center text-gray-900"
      >
        All Tours
      </h1>

      <button
        @click="toggleForm"
        class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600"
      >
        {{ showForm ? "Hide Form" : "+ Create Tour" }}
      </button>
    </div>
  </header>
  <main class="bg-gray-100 min-h-screen">
    <div
      v-if="showForm"
      class="mx-auto max-w-7xl bg-white rounded-md px-4 py-6 sm:px-6 lg:px-8"
    >
      <form @submit.prevent="createTour">
        <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 sm:gap-4">
          <div>
            <label
              for="destination_id"
              class="block text-gray-700 font-bold mb-2 text-sm font-medium leading-6 text-gray-900"
              >Destination:</label
            >
            <select
              v-model="form.destination_id"
              id="destination_id"
              class="w-full px-4 py-2 border rounded-md block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              required
            >
              <option
                v-for="destination in Destinations"
                :key="destination.id"
                :value="destination.id"
              >
                {{ destination.name }}
              </option>
            </select>
          </div>
          <div>
            <label
              for="name"
              class="block text-gray-700 font-bold mb-2 text-sm font-medium leading-6 text-gray-900"
              >Tour Name:</label
            >
            <input
              v-model="form.name"
              id="name"
              type="text"
              class="w-full px-4 py-2 border rounded-md block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              required
            />
          </div>
        </div>
        <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 sm:gap-4">
          <div>
            <label
              for="price"
              class="block text-gray-700 font-bold mb-2 text-sm font-medium leading-6 text-gray-900"
              >Price:</label
            >
            <input
              v-model="form.price"
              id="price"
              type="number"
              min="1"
              class="w-full px-4 py-2 border rounded-md block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              required
            />
          </div>
          <div>
            <label
              for="slots"
              class="block text-gray-700 font-bold mb-2 text-sm font-medium leading-6 text-gray-900"
              >slots:</label
            >
            <input
              v-model="form.slots"
              id="slots"
              type="number"
              min="1"
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

        <div class="mb-4">
          <label
            for="image"
            class="block text-gray-700 font-bold mb-2 text-sm font-medium leading-6 text-gray-900"
            >Tour Image:</label
          >
          <div
            class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
          >
            <img
              v-if="form.image_url"
              :src="form.image_url"
              :alt="form.name"
              class="w-48 h-64 object-cover"
            />
            <svg
              v-else
              class="mx-auto h-12 w-12 text-gray-300"
              viewBox="0 0 24 24"
              fill="currentColor"
              aria-hidden="true"
            >
              <path
                fill-rule="evenodd"
                d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                clip-rule="evenodd"
              />
            </svg>
          </div>

          <input
            @change="onFileChange"
            id="image"
            type="file"
            class="w-full px-4 py-2 border rounded-md block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            required
          />
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
            <th class="py-2 px-4 border-b text-indigo-500">Poster</th>
            <th class="py-2 px-4 border-b text-indigo-500">Name</th>
            <th class="py-2 px-4 border-b text-indigo-500">Description</th>
            <th class="py-2 px-4 border-b text-indigo-500">Price</th>
            <th class="py-2 px-4 border-b text-indigo-500">Slots</th>
            <th class="py-2 px-4 border-b text-indigo-500">Destination</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="Tour in Tours" :key="Tour.id">
            <td class="py-2 px-4 border-b">
              <img
                v-if="Tour.image"
                :src="`http://localhost:8000/${Tour.image}`"
                :alt="Tour.name"
                class="w-[30px] h-[30px] object-cover rounded-full"
              />
              <img
                v-else
                src="/public/vite.svg"
                :alt="Tour.name"
                class="w-[30px] h-[30px] object-cover rounded-full"
              />
            </td>
            <td class="py-2 px-4 border-b">
              <!-- Display the first ticket number or 'No ticket' if tickets are empty -->
              {{ Tour?.name || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">
              {{ Tour?.description || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">KES {{ Tour?.price || "N/A" }}</td>
            <td class="py-2 px-4 border-b">
              {{ Tour?.slots || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">
              {{ Tour?.destination?.name || "N/A" }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script setup>
import { useRouter } from "vue-router";
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const store = useStore();
const router = useRouter();

const Tours = computed(() => store.state.tours);
const Destinations = computed(() => store.state.destinations);
const form = ref({
  destination_id: "",
  name: "",
  price: "",
  description: "",
  slots: "",
  image: null,
});
const showForm = ref(false);

const toggleForm = () => {
  showForm.value = !showForm.value;
};

const onFileChange = (e) => {
  form.value.image = e.target.files[0];

  const reader = new FileReader();
  reader.onload = () => {
    form.value.image = reader.result;

    form.value.image_url = reader.result;
  };
  reader.readAsDataURL(form.value.image);
};

const createTour = () => {
  const formData = new FormData();
  formData.append("destination_id", form.value.destination_id);
  formData.append("name", form.value.name);
  formData.append("price", form.value.price);
  formData.append("description", form.value.description);
  formData.append("slots", form.value.slots);
  formData.append("image", form.value.image);

  store
    .dispatch("createTour", formData)
    .then(() => {
      toast.success("Tour created successfully!");
      form.value = {
        destination_id: "",
        name: "",
        price: "",
        description: "",
        slots: "",
        image: null,
      }; // Reset form
      showForm.value = false; // Hide form
      store.dispatch("getTours"); // Reload tours
    })
    .catch((error) => {
      toast.error("Failed to create tour.");
      console.error(error);
    });
};

onMounted(() => {
  store.dispatch("getTours");
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
