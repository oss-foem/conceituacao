<template>
  <tr class="hover:bg-gray-50 group">
    <td @click="$emit('show-details', profile.id)" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 cursor-pointer">
      <div class="flex items-center space-x-2">
        <LucideShield class="h-5 w-5 text-gray-400" />
        <span>{{ profile.name }}</span>
      </div>
    </td>

    <td @click="$emit('show-details', profile.id)" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 cursor-pointer">
    </td>

    <td class="pl-6 py-4 whitespace-nowrap text-sm text-gray-500">
      <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity gap-4">
        <button 
          class="flex items-center gap-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded"
          @click.stop="$emit('edit', profile.id)"
        >
          <LucideEdit class="h-4 w-4" />
          Editar
        </button>
        <button 
          class="flex items-center gap-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded"
          @click.stop="$emit('delete', profile.id)"
        >
          <LucideTrash2 class="h-4 w-4" />
          Excluir
        </button>
      </div>
    </td>
  </tr>
</template>

<script setup>
import { LucideShield, LucideEdit, LucideTrash2 } from 'lucide-vue-next';

defineProps({
  profile: {
    type: Object,
    required: true
  }
});

defineEmits(['show-details', 'edit', 'delete']);
</script>
