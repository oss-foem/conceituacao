import { defineStore } from 'pinia';
import { useApi } from '@/composables/useApi';
import { useToast } from '@/composables/useToast';
import { useLoading } from '@/composables/useLoading';

export const useUserStore = defineStore('users', {
  state: () => ({
    users: [],
    user: null,
  }),

  actions: {
    async fetchUsers(params = {}) {
      const { api } = useApi();
      const { notifyError } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.get('users', { params });
        this.users = response.data.data;
      } catch (error) {
        notifyError('Erro ao buscar usuários: ' + (error.response?.data?.message || error.message));
        this.users = [];
      } finally {
        loading.stop();
      }
    },

    async fetchUser(id) {
      const { api } = useApi();
      const { notifyError } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.get(`users/${id}`);
        this.user = response.data.data;

        return this.user;
      } catch (error) {
        notifyError('Erro ao buscar usuário: ' + (error.response?.data?.message || error.message));
        this.user = null;
      } finally {
        loading.stop();
      }
    },

    async createUser(payload) {
      const { api } = useApi();
      const { notifyError, notifySuccess } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.post('users', payload);
        notifySuccess('Usuário criado com sucesso!');
        await this.fetchUsers();
        return response.data.data;
      } catch (error) {
        notifyError('Erro ao criar usuário: ' + (error.response?.data?.message || error.message));
        throw error;
      } finally {
        loading.stop();
      }
    },

    async updateUser(id, payload) {
      const { api } = useApi();
      const { notifyError, notifySuccess } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.put(`users/${id}`, payload);
        notifySuccess('Usuário atualizado com sucesso!');
        await this.fetchUsers();
        return response.data.data;
      } catch (error) {
        notifyError('Erro ao atualizar usuário: ' + (error.response?.data?.message || error.message));
        throw error;
      } finally {
        loading.stop();
      }
    },

    async deleteUser(id) {
      const { api } = useApi();
      const { notifyError, notifySuccess } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        await api.delete(`users/${id}`);
        notifySuccess('Usuário deletado com sucesso!');
        await this.fetchUsers();
      } catch (error) {
        notifyError('Erro ao deletar usuário: ' + (error.response?.data?.message || error.message));
      } finally {
        loading.stop();
      }
    },

    async attachProfiles(userId, profiles) {
      const { api } = useApi();
      const { notifySuccess, notifyError } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        await api.post(`users/${userId}/profiles/attach`, { profiles });
        notifySuccess('Perfis associados com sucesso.');
        await this.getUserProfiles(userId);
      } catch (error) {
        notifyError('Erro ao associar perfis: ' + (error.response?.data?.message || error.message));
        throw error;
      } finally {
        loading.stop();
      }
    },

    async detachProfiles(userId, profiles) {
      const { api } = useApi();
      const { notifySuccess, notifyError } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        await api.post(`users/${userId}/profiles/detach`, { profiles });
        notifySuccess('Perfis desassociados com sucesso.');
        await this.getUserProfiles(userId);
      } catch (error) {
        notifyError('Erro ao desassociar perfis: ' + (error.response?.data?.message || error.message));
        throw error;
      } finally {
        loading.stop();
      }
    },

    async getUserProfiles(userId) {
      const { api } = useApi();
      const { notifyError } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.get(`users/${userId}/profiles`, { params: { userId } });
        if (this.user && this.user.id === userId) {
          this.user.profiles = response.data.data;
        }

      } catch (error) {
        notifyError('Erro ao buscar perfis do usuário: ' + (error.response?.data?.message || error.message));
        this.userProfiles = [];
      } finally {
        loading.stop();
      }
    },
  },
});
