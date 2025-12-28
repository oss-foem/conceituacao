<template>
    <div class="fixed inset-0 z-10 bg-gray-500/40 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96">
        <h3 class="text-lg font-semibold text-gray-700">Confirmar Exclusão</h3>
        <p class="mt-2 text-gray-500">Tem certeza que deseja excluir o cliente {{ client.name }}?</p>
        <div class="mt-4 flex justify-end space-x-4">
          <button 
            id="cancel-button"
            @click="$emit('cancel')"
            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-sm text-gray-700"
          >
            Cancelar
          </button>
          <button 
            id="confirm-button"
            @click="$emit('confirm')"
            class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md text-sm text-white"
          >
            Excluir
          </button>
        </div>
        </div>
    </div>
</template>

<script setup>
  defineProps({
    client: {
        type: Object,
        required: true
    }
  });

  defineEmits(['cancel', 'confirm']);
</script>