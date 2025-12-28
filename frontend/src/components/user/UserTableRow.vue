<template>
  <tr class="hover:bg-gray-50 group">
    <td @click="$emit('show-details', user.id)" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 cursor-pointer">
      <div class="flex items-center space-x-2">
        <LucideUser class="h-5 w-5 text-gray-400" />
        <span>{{ user.name }}</span>
      </div>
    </td>

    <td @click="$emit('show-details', user.id)" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 cursor-pointer">
      {{ user.email }}
    </td>

    <td class="pl-6 py-4 whitespace-nowrap text-sm text-gray-500">
      <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity gap-4">
        <button 
          class="flex items-center gap-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded disabled:bg-blue-300 disabled:cursor-not-allowed"
          :disabled="!authStore.isAdmin"
          @click.stop="$emit('edit', user.id)"
        >
          <LucideEdit class="h-4 w-4" />
          Editar
        </button>
        <button 
          class="flex items-center gap-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded disabled:bg-blue-300 disabled:cursor-not-allowed"
          :disabled="!authStore.isAdmin"
          @click.stop="$emit('delete', user.id)"
        >
          <LucideTrash2 class="h-4 w-4" />
          Excluir
        </button>
      </div>
    </td>
  </tr>
</template>

<script setup>
import { LucideUser, LucideEdit, LucideTrash2 } from 'lucide-vue-next';
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore();

defineProps({
  user: {
    type: Object,
    required: true
  }
});

defineEmits(['show-details', 'edit', 'delete']);
</script>
