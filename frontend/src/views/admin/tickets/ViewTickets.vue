<template>
  <header class="bg-gray-100 shadow">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1
        class="text-[24px] font-bold tracking-tight text-center text-gray-900"
      >
        All Tickets
      </h1>
    </div>
  </header>
  <main class="bg-gray-100 min-h-screen">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <table class="min-w-full bg-white border rounded-md border-gray-200">
        <thead class="bg-indigo-500">
          <tr>
            <th class="py-2 px-4 border-b text-indigo-500">User</th>
            <th class="py-2 px-4 border-b text-indigo-500">Ticket Number</th>
            <th class="py-2 px-4 border-b text-indigo-500">Tour Name</th>
            <th class="py-2 px-4 border-b text-indigo-500">Destination</th>
            <th class="py-2 px-4 border-b text-indigo-500">Created At</th>
            <th class="py-2 px-4 border-b text-indigo-500">Status</th>
            <th class="py-2 px-4 border-b text-indigo-500">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="allTicket in allTickets" :key="allTicket.id">
            <td class="py-2 px-4 border-b">
              <!-- Display the first ticket number or 'No ticket' if tickets are empty -->
              {{ allTicket?.booking?.user?.name || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">
              {{ allTicket?.ticket_number || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">
              {{ allTicket?.booking?.tour?.name || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">
              {{ allTicket?.booking?.tour?.destination?.name || "N/A" }}
            </td>
            <td class="py-2 px-4 border-b">
              {{ new Date(allTicket.created_at).toLocaleDateString() }}
            </td>
            <td class="py-2 px-4 border-b">
              <span
                v-if="allTicket?.booking?.status === 'pending'"
                class="p-1 text-white rounded bg-yellow-500"
              >
                {{ allTicket?.booking?.status || "N/A" }}
              </span>
              <span v-else class="p-1 text-white rounded bg-green-500">
                {{ allTicket?.booking?.status || "N/A" }}
              </span>
            </td>
            <td class="py-2 px-4 border-b">
              <button
                v-if="allTicket?.booking?.status === 'confirmed'"
                @click="viewTicket(allTicket.booking_id)"
                class="w-[160px] px-4 py-1 bg-green-500 text-white rounded hover:bg-green-600"
              >
                View Ticket
              </button>
              <button
                v-else
                @click="confirmBooking(allTicket.booking_id)"
                class="w-[160px] px-4 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600"
              >
                Confirm Booking
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

const allTickets = computed(() => store.state.allTickets);

onMounted(() => {
  store.dispatch("getAllTickets");
});

function viewTicket(bookingId) {
  // Navigate to a ticket details page or handle viewing tickets here
  router.push({ name: "ticketDetails", params: { id: bookingId } });
}

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
      console.error("Error confirming booking:", error);
      toast("Error occurred confirming booking", {
        theme: "auto",
        type: "error",
        position: "bottom-center",
      });
    });
}
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
