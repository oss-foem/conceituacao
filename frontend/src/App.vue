<script setup>
import { RouterView } from 'vue-router';
import { useLoading } from '@/composables/useLoading';

const { isLoading } = useLoading();
</script>

<template>
  <RouterView />

  <div
    v-if="isLoading"
    class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 backdrop-blur-sm transition-opacity duration-300"
  >
    <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
    </svg>
  </div>
</template>
