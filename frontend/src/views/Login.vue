<template>
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <router-link :to="{ name: 'home' }">
      <img
        class="mx-auto h-10 w-auto"
        src="/public/vite.svg"
        alt="Your Company"
    /></router-link>
    <h2
      class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
    >
      Solutours
    </h2>
    <h4
      class="mt-10 text-center text-[24px] font-bold leading-3 tracking-tight text-gray-900"
    >
      Sign in to your account
    </h4>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" @submit="login">
      <div>
        <label
          for="email"
          class="block text-sm font-medium leading-6 text-gray-900"
          >Email address</label
        >
        <div class="mt-2">
          <input
            id="email"
            name="email"
            type="email"
            autocomplete="email"
            required=""
            v-model="user.email"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
          />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label
            for="password"
            class="block text-sm font-medium leading-6 text-gray-900"
            >Password</label
          >
          <div class="text-sm">
            <a
              href="#"
              class="font-semibold text-indigo-600 hover:text-indigo-500"
              >Forgot password?</a
            >
          </div>
        </div>
        <div class="mt-2">
          <input
            id="password"
            name="password"
            type="password"
            autocomplete="current-password"
            required=""
            v-model="user.password"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
          />
        </div>
      </div>

      <div>
        <button
          type="submit"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          Sign in
        </button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm text-gray-500">
      Not registered?
      {{ " " }}
      <router-link
        :to="{ name: 'register' }"
        class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
        >Create an Account</router-link
      >
    </p>
  </div>
</template>
<script>
import { ref } from "vue";
import store from "../store";
import { useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
  setup() {
    const router = useRouter();
    const user = ref({
      email: "",
      password: "",
    });

    const loading = ref(false);
    const errorMsg = ref("");

    const login = (ev) => {
      ev.preventDefault();

      loading.value = true;
      store
        .dispatch("login", user.value)
        .then(() => {
          loading.value = false;
          router.push({
            name: "home",
          });
        })
        .catch((err) => {
          loading.value = false;
          errorMsg.value = err.response?.data?.error || "Login failed";
          toast("Check your Credentials", {
            theme: "auto",
            type: "error",
            position: "bottom-center",
          });
        });
    };

    return {
      user,
      login,
      loading,
      errorMsg,
    };
  },
};
</script>
