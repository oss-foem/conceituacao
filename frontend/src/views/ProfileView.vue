<template>
  <div class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-4xl mx-auto">
      <div class="flex justify-between items-center px-1 py-2">
        <h1 class="text-xl font-medium text-gray-800 mb-4">Lista de perfis</h1>
        <button 
          @click="goToUsers"
          class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-sm text-gray-700 flex justify-between items-center"
        >
          <h1>Usuários</h1>
          <ArrowRight class="pl-2"/>
        </button>
      </div>

      <ProfileCard>
        <ProfileTable />
      </ProfileCard>
    </div>
  </div>
</template>

<script setup>
import ProfileCard from '@/components/profile/ProfileCard.vue'
import ProfileTable from '@/components/profile/ProfileTable.vue'
import { ArrowRight } from 'lucide-vue-next'
import { useRouter } from 'vue-router'

const router = useRouter()

const goToUsers = () => {
  router.push('/users')
}
</script>
