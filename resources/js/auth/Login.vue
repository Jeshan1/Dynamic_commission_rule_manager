<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
  
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">
          Login to Your Account
        </h2>
  
        <input
          v-model="email"
          type="email"
          placeholder="Enter email"
          class="w-full mb-4 px-4 py-2 border rounded-lg"
        />
  
        <input
          v-model="password"
          type="password"
          placeholder="Enter password"
          class="w-full mb-4 px-4 py-2 border rounded-lg"
        />
  
        <button
          @click="submitLogin"
          class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700"
        >
          Login
        </button>
  
        <p v-if="error" class="mt-4 text-red-600">{{ error }}</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from "vue";
  import { useStore } from "vuex";
  import { useRouter } from "vue-router";
  import Swal from "sweetalert2";
  
  const store = useStore();
  const router = useRouter();
  
  const email = ref("");
  const password = ref("");
  
  const error = computed(() => store.state.error);
  
  async function submitLogin() {
  
    const res =await store.dispatch("login", { email: email.value, password: password.value });

    if(res.success){
      router.push("/admin");
    }
    else{
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: res.message,
      });
    }
  
  }
  </script>
  