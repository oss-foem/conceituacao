<template>
  <div>
    <table class="min-w-full divide-y divide-gray-200">
      <UserTableHeader />
      <tbody class="bg-white">
        <template v-if="userStore.users.length > 0">
          <UserTableRow 
            v-for="user in userStore.users" 
            :key="user.id" 
            :user="user"
            @show-details="showUserDetails"
            @edit="openEditModal"
            @delete="openDeleteModal"
          />
        </template>
        <template v-else>
          <tr>
            <td colspan="5" class="px-6 py-50 text-center">
              <div class="flex flex-col items-center justify-center">
                <div class="bg-gray-100 rounded-full p-6 mb-4">
                  <User class="h-40 w-40"/>
                </div>
                <p class="text-gray-500 mb-4">Não há usuários</p>
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>

    <UserDetailsModal 
      v-if="isUserModalOpen" 
      :user="selectedUser"
      @close="closeUserModal"
    />

    <UserAddOrEditModal 
      v-if="isEditModalOpen"
      :user="userToEdit"
      @close="closeEditModal"
    />

    <DeleteConfirmationModal 
      v-if="isDeleteModalOpen" 
      :client="userToDelete"
      @cancel="cancelDelete"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { User } from 'lucide-vue-next';
import { useUserStore } from '@/stores/user';

import UserTableHeader from './UserTableHeader.vue';
import UserTableRow from './UserTableRow.vue';
import UserDetailsModal from './UserDetailsModal.vue';
import UserAddOrEditModal from './UserAddOrEditModal.vue';
import DeleteConfirmationModal from './DeleteConfirmationModal.vue';

const userStore = useUserStore();

const isUserModalOpen = ref(false);
const selectedUser = ref(null);

const isEditModalOpen = ref(false);
const userToEdit = ref(null);

const isDeleteModalOpen = ref(false);
const userToDelete = ref(null);

onMounted(() => {
  userStore.fetchUsers();
});

const showUserDetails = async (id) => {
  const user = await userStore.fetchUser(id);
  selectedUser.value = user;
  isUserModalOpen.value = true;
};

const closeUserModal = () => {
  isUserModalOpen.value = false;
  selectedUser.value = null;
};

const openEditModal = async (id) => {
  const user = await userStore.fetchUser(id);
  userToEdit.value = user;
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  isEditModalOpen.value = false;
  userToEdit.value = null;
};

const openDeleteModal = async (id) => {
  const user = await userStore.fetchUser(id);
  userToDelete.value = user;
  isDeleteModalOpen.value = true;
};

const cancelDelete = () => {
  isDeleteModalOpen.value = false;
  userToDelete.value = null;
};

const confirmDelete = async () => {
  await userStore.deleteUser(userToDelete.value.id);
  isDeleteModalOpen.value = false;
  userToDelete.value = null;
};
</script>