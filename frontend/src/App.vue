<script setup>
import { RouterLink, RouterView } from "vue-router";
import NavBar from "@/components/NavBar.vue";
</script>

<template>
  <NavBar />

  <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mt-10">
    <RouterView />
  </div>
</template>
