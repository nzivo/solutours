<template lang="">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img
      class="mx-auto h-10 w-auto"
      src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
      alt="Your Company"
    />
    <h2
      class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
    >
      Register your account
    </h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" @submit="register">
      <div>
        <label
          for="name"
          class="block text-sm font-medium leading-6 text-gray-900"
          >Your name</label
        >
        <div class="mt-2">
          <input
            id="name"
            name="name"
            type="text"
            autocomplete="name"
            required=""
            v-model="user.name"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
          />
        </div>
      </div>
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
        <div class="flex items-center justify-between">
          <label
            for="password_confirmation"
            class="block text-sm font-medium leading-6 text-gray-900"
            >Confirm Password</label
          >
        </div>
        <div class="mt-2">
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="current-password"
            required=""
            v-model="user.password_confirmation"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
          />
        </div>
      </div>

      <div>
        <button
          type="submit"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          Sign up
        </button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm text-gray-500">
      Already Registered?
      {{ " " }}
      <router-link
        :to="{ name: 'login' }"
        class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
        >Log in to your account</router-link
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
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    });

    const loading = ref(false);
    const errorMsg = ref("");

    const register = (ev) => {
      ev.preventDefault();

      loading.value = true;
      store
        .dispatch("register", user.value)
        .then(() => {
          loading.value = false;
          router.push({
            name: "home",
          });
        })
        .catch((err) => {
          loading.value = false;
          errorMsg.value = err.response?.data?.error || "Registration failed";
          toast("Error, try again", {
            theme: "auto",
            type: "error",
            position: "bottom-center",
          });
        });
    };

    return {
      user,
      register,
      loading,
      errorMsg,
    };
  },
};
</script>
