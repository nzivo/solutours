<template>
  <header class="bg-gray-100 shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1
        class="text-[24px] font-bold tracking-tight text-center text-gray-900"
      >
        All Users
      </h1>
    </div>
  </header>
  <main class="bg-gray-100 min-h-screen">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <table class="min-w-full bg-white border rounded-md border-gray-200">
        <thead class="bg-indigo-500">
          <tr>
            <th class="py-2 px-4 border-b text-indigo-500">Full Name</th>
            <th class="py-2 px-4 border-b text-indigo-500">Email Address</th>
            <th class="py-2 px-4 border-b text-indigo-500">Role Name</th>
            <th class="py-2 px-4 border-b text-indigo-500">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="allUser in allUsers" :key="allUser.id">
            <td class="py-2 px-4 border-b">
              <!-- Display the first ticket number or 'No ticket' if tickets are empty -->
              {{ allUser?.name || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">
              {{ allUser?.email || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">
              <span
                v-if="allUser?.role === 'admin'"
                class="p-1 text-white rounded bg-red-500"
              >
                {{ allUser?.role || "N/A" }}
              </span>
              <span v-else class="p-1 text-white rounded bg-indigo-500">
                {{ allUser?.role || "N/A" }}
              </span>
            </td>
            <td class="py-2 px-4 border-b">
              <button
                v-if="allUser?.role === 'admin'"
                class="w-[160px] px-4 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                @click="updateUserRole(allUser.id, 'user')"
              >
                Revoke Admin
              </button>
              <button
                v-else
                class="w-[160px] px-4 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                @click="updateUserRole(allUser.id, 'admin')"
              >
                Grant Admin
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script setup>
import { useRouter } from "vue-router";
import { computed, onMounted } from "vue";
import { useStore } from "vuex";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const store = useStore();
const router = useRouter();

const allUsers = computed(() => store.state.allUsers);

onMounted(() => {
  store.dispatch("getAllUsers");
});

const updateUserRole = (userId, role) => {
  store
    .dispatch("updateUserRole", { userId, role })
    .then(() => {
      toast.success(`User role updated to ${role}`);
    })
    .catch(() => {
      toast.error("Failed to update user role");
    });
};
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
