<template>
  <div class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-4xl mx-auto">
      <div class="flex justify-between items-center px-1 py-2">
        <h1 class="text-xl font-medium text-gray-800 mb-4">Lista de usuarios</h1>
        <button 
          v-if="authStore.isAdmin"
          @click="goToProfiles"
          class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-sm text-gray-700 flex justify-between items-center"
        >
          <h1>Perfis</h1>
          <ArrowRight class="pl-2"/>
        </button>
      </div>

      <UserCard>
        <UserTable />
      </UserCard>
    </div>
  </div>
</template>

<script setup>
  import UserCard from '@/components/user/UserCard.vue'
  import UserTable from '@/components/user/UserTable.vue'
  import { useAuthStore } from '@/stores/auth'
  import { ArrowRight } from 'lucide-vue-next';
  import { useRouter } from 'vue-router'

  const authStore = useAuthStore();
  const router = useRouter()

  const goToProfiles = () => {
    router.push('/profiles')
  }
</script>
  