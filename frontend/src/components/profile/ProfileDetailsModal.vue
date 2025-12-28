<template>
  <div class="fixed inset-0 z-10 bg-gray-500/40 overflow-y-auto">
    <div class="absolute top-1/2 left-0 right-0 mx-auto max-w-2xl bg-white rounded-lg shadow-sm transform -translate-y-1/2">
      <div class="flex justify-between items-center px-6 pt-6">
        <div class="flex items-center gap-3">
          <div class="bg-gray-200 rounded-full h-12 w-12 flex items-center justify-center text-gray-500">
            <LucideShield class="h-6 w-6" />
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Detalhes do Perfil</h2>
        </div>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
          <XIcon class="h-5 w-5" />
        </button>
      </div>

      <div class="relative flex py-4 items-center">
        <div class="flex-grow border-t border-gray-400/30"></div>
        <div class="flex-grow border-t border-gray-400/30"></div>
      </div>

      <div class="space-y-4 px-6 pb-6 text-sm text-gray-800">
        <div>
          <span class="font-medium text-gray-500">Nome:</span> {{ profile.name }}
        </div>

        <div v-if="profile.created_at">
          <span class="font-medium text-gray-500">Criado em:</span>
          {{ formatDate(profile.created_at) }}
        </div>

        <div v-if="profile.updated_at">
          <span class="font-medium text-gray-500">Última atualização:</span>
          {{ formatDate(profile.updated_at) }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { XIcon, LucideShield } from 'lucide-vue-next';

const props = defineProps({
  profile: {
    type: Object,
    required: true,
  },
});

defineEmits(['close']);

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>
