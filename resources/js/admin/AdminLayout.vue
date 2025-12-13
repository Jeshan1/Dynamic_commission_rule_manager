<template>
    <div class="flex h-screen">
    
      <SideBar />
  
      <div class="flex-1 flex flex-col">
       
        <TopBar />
  
        <div class="fixed top-[4.3rem] left-[250px] right-0">
          <router-view></router-view>
        </div>
      </div>
    </div>
</template>

<script setup>

import SideBar from '../admin/SideBar.vue';
import TopBar from '../admin/TopBar.vue';
</script>