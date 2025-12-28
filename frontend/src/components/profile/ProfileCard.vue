<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 flex items-center justify-between">
      <slot name="header"></slot>
    </div>

    <div class="min-h-[720px]">
      <div class="flex justify-end items-center px-1 py-2">
        <button 
          @click="openModal"
          class="px-4 py-2 bg-indigo-700 hover:bg-indigo-500 rounded-md text-sm text-white flex items-center gap-2"
        >
          <h1>Adicionar Perfil</h1>
        </button>
      </div>

      <slot></slot>

      <ProfileAddOrEditModal 
        v-if="showAddProfileModal"
        @close="closeModal"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProfileAddOrEditModal from './ProfileAddOrEditModal.vue'

const showAddProfileModal = ref(false)

const openModal = () => {
  showAddProfileModal.value = true
}

const closeModal = () => {
  showAddProfileModal.value = false
}
</script>
