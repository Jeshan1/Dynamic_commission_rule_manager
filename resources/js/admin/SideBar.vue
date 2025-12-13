<template>
    <nav class="bg-[#f7f7f8] h-screen fixed top-0 left-0 min-w-[250px] py-6 px-4 font-sans">
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Admin Panel</h1>
      </div>
  
      <div class="overflow-auto py-6 h-full">
        <ul class="space-y-2">
          <li v-if="isAdmin">
            <router-link to="/commission-management" class="flex items-center px-4 py-3 rounded hover:bg-white text-black hover:text-blue-600 transition-all">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 64 64" width="64" height="64">
                <path d="M24 44c-2 0-4-1-4-3s2-5 4-5 4 2 4 4-2 4-4 4z" stroke-width="2"/>
                <path d="M32 44h-8v-8h8z" stroke-width="2"/>
                <path d="M20 44h-4v-4h4z" stroke-width="2"/>
                <path d="M28 44v-8h4v-4h-8v4h4z" stroke-width="2"/>
                <circle cx="48" cy="16" r="12" stroke="currentColor" stroke-width="2"/>
                <text x="43" y="20" font-family="Arial" font-size="12" fill="currentColor">%</text>
              </svg>
              <span>Commission Module</span>
            </router-link>
          </li>
          <li>
            <div @click="handleLogout" class="flex items-center px-4 py-3 rounded hover:bg-red-50 text-black hover:text-red-600 cursor-pointer transition-all">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 24 24" width="24" height="24">
                <path d="M10 17L15 12M15 12L10 7M15 12H3M15 12V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Logout</span>
            </div>
          </li>
  
        </ul>
      </div>
    </nav>
  </template>
  
  <script setup>
  import { useStore } from 'vuex'
  import { useRouter } from 'vue-router'
  
  const store = useStore()
  const router = useRouter()
  
  const isAdmin = store.getters.isAdmin
  
  const handleLogout = async () => {
    await store.dispatch('logout')
    router.push('/login')
  }
  </script>