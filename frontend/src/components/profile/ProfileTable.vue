<template>
  <div>
    <table class="min-w-full divide-y divide-gray-200">
      <ProfileTableHeader />
      <tbody class="bg-white">
        <template v-if="profileStore.profiles.length > 0">
          <ProfileTableRow 
            v-for="profile in profileStore.profiles" 
            :key="profile.id" 
            :profile="profile"
            @show-details="showProfileDetails"
            @edit="openEditModal"
            @delete="openDeleteModal"
          />
        </template>
        <template v-else>
          <tr>
            <td colspan="5" class="px-6 py-50 text-center">
              <div class="flex flex-col items-center justify-center">
                <div class="bg-gray-100 rounded-full p-6 mb-4">
                  <Shield class="h-40 w-40"/>
                </div>
                <p class="text-gray-500 mb-4">Não há perfis</p>
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <ProfileDetailsModal 
      v-if="isProfileModalOpen" 
      :profile="selectedProfile"
      @close="closeProfileModal"
    />

    <ProfileAddOrEditModal 
      v-if="isEditModalOpen"
      :profile="profileToEdit"
      @close="closeEditModal"
    />

    <DeleteConfirmationModal 
      v-if="isDeleteModalOpen" 
      :profile="profileToDelete"
      @cancel="cancelDelete"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Shield } from 'lucide-vue-next';
import { useProfileStore } from '@/stores/profile';

import ProfileTableHeader from './ProfileTableHeader.vue';
import ProfileTableRow from './ProfileTableRow.vue';
import ProfileDetailsModal from './ProfileDetailsModal.vue';
import ProfileAddOrEditModal from './ProfileAddOrEditModal.vue';
import DeleteConfirmationModal from './DeleteConfirmationModal.vue';

const profileStore = useProfileStore();

const isProfileModalOpen = ref(false);
const selectedProfile = ref(null);

const isEditModalOpen = ref(false);
const profileToEdit = ref(null);

const isDeleteModalOpen = ref(false);
const profileToDelete = ref(null);

onMounted(() => {
  profileStore.fetchProfiles();
});

const showProfileDetails = async (id) => {
  const profile = await profileStore.fetchProfile(id);
  selectedProfile.value = profile;
  isProfileModalOpen.value = true;
};

const closeProfileModal = () => {
  isProfileModalOpen.value = false;
  selectedProfile.value = null;
};

const openEditModal = async (id) => {
  const profile = await profileStore.fetchProfile(id);
  profileToEdit.value = profile;
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false;
  profileToEdit.value = null;
};

const openDeleteModal = async (id) => {
  const profile = await profileStore.fetchProfile(id);
  profileToDelete.value = profile;
  isDeleteModalOpen.value = true;
};

const cancelDelete = () => {
  isDeleteModalOpen.value = false;
  profileToDelete.value = null;
};

const confirmDelete = async () => {
  await profileStore.deleteProfile(profileToDelete.value.id);
  isDeleteModalOpen.value = false;
  profileToDelete.value = null;
};
</script>
