
<template>
  <div class="fixed inset-0 z-10 bg-gray-500/40 overflow-y-auto">
    <div class="absolute top-1/2 left-0 right-0 mx-auto max-w-2xl bg-white rounded-lg shadow-sm transform -translate-y-1/2">
      <div class="flex justify-between items-center px-6 pt-6">
        <div class="flex items-center gap-3">
          <div class="bg-gray-200 rounded-full h-12 w-12 flex items-center justify-center text-gray-500">
            <LucideUser class="h-6 w-6" />
          </div>
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
          <span class="font-medium text-gray-500">Nome:</span> {{ user.name }}
        </div>

        <div>
          <span class="font-medium text-gray-500">Email:</span> {{ user.email }}
        </div>

        <div v-if="user.created_at">
          <span class="font-medium text-gray-500">Criado em:</span>
          {{ formatDate(user.created_at) }}
        </div>

        <div v-if="user.updated_at">
          <span class="font-medium text-gray-500">Última atualização:</span>
          {{ formatDate(user.updated_at) }}
        </div>

        <div v-if="authStore.isAdmin && profileStore.profiles.length" class="space-y-2">
          <span class="font-medium text-gray-500">Perfis:</span>
          <div v-for="profile in profileStore.profiles" :key="profile.id" class="flex items-center gap-2">
            <input
              type="checkbox"
              :id="'profile-' + profile.id"
              :value="profile.id"
              v-model="selectedProfiles"
              :disabled="!authStore.isAdmin"
              @change="onProfileToggle(profile.id)"
            />
            <label :for="'profile-' + profile.id" class="text-gray-700">{{ profile.name }}</label>
          </div>
        </div>
        <div v-else>
          <span class="font-medium text-gray-500">Perfis:</span>
          <div
            v-for="profile in props.user.profiles"
            :key="profile.id"
            class="flex items-center gap-2"
          >
            <div class="text-gray-700">{{ profile.name }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { XIcon, LucideUser } from 'lucide-vue-next';
import { useProfileStore } from '@/stores/profile';
import { useUserStore } from '@/stores/user';
import { useAuthStore } from '@/stores/auth';


const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

defineEmits(['close']);

const profileStore = useProfileStore();
const userStore = useUserStore();
const authStore = useAuthStore();

const selectedProfiles = ref([]);

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

onMounted(async () => {
  if (authStore.isAdmin) {
    await profileStore.fetchProfiles();
  }
  await userStore.getUserProfiles(props.user.id);
  selectedProfiles.value = props.user.profiles.map(p => p.id);
});

const onProfileToggle = async (profileId) => {
  const alreadyHas = props.user.profiles.some(p => p.id === profileId);
  try {
    if (selectedProfiles.value.includes(profileId) && !alreadyHas) {
      await userStore.attachProfiles(props.user.id, [profileId]);
    } else if (!selectedProfiles.value.includes(profileId) && alreadyHas) {
      await userStore.detachProfiles(props.user.id, [profileId]);
    }
  } catch (error) {
  }
};
</script>