import { defineStore } from 'pinia';
import { useApi } from '@/composables/useApi';
import { useToast } from '@/composables/useToast';
import { useLoading } from '@/composables/useLoading';

export const useProfileStore = defineStore('profiles', {
  state: () => ({
    profiles: [],
    profile: null
  }),

  actions: {
    async fetchProfiles(params = {}) {
      const { api } = useApi();
      const { notifyError } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.get('profiles', { params });
        this.profiles = response.data.data;
      } catch (error) {
        notifyError('Erro ao buscar perfis: ' + (error.response?.data?.message || error.message));
        this.profiles = [];
      } finally {
        loading.stop();
      }
    },

    async fetchProfile(id) {
      const { api } = useApi();
      const { notifyError } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.get(`profiles/${id}`);
        this.profile = response.data.data;

        return this.profile;
      } catch (error) {
        notifyError('Erro ao buscar perfil: ' + (error.response?.data?.message || error.message));
        this.profile = null;
      } finally {
        loading.stop();
      }
    },

    async createProfile(payload) {
      const { api } = useApi();
      const { notifyError, notifySuccess } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.post('profiles', payload);
        notifySuccess('Perfil criado com sucesso!');
        await this.fetchProfiles();
        return response.data.data;
      } catch (error) {
        notifyError('Erro ao criar perfil: ' + (error.response?.data?.message || error.message));
        throw error;
      } finally {
        loading.stop();
      }
    },

    async updateProfile(id, payload) {
      const { api } = useApi();
      const { notifyError, notifySuccess } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        const response = await api.put(`profiles/${id}`, payload);
        notifySuccess('Perfil atualizado com sucesso!');
        await this.fetchProfiles();
        return response.data.data;
      } catch (error) {
        notifyError('Erro ao atualizar perfil: ' + (error.response?.data?.message || error.message));
        throw error;
      } finally {
        loading.stop();
      }
    },

    async deleteProfile(id) {
      const { api } = useApi();
      const { notifyError, notifySuccess } = useToast();
      const loading = useLoading();

      loading.start();

      try {
        await api.delete(`profiles/${id}`);
        notifySuccess('Perfil deletado com sucesso!');
        await this.fetchProfiles();
      } catch (error) {
        notifyError('Erro ao deletar perfil: ' + (error.response?.data?.message || error.message));
      } finally {
        loading.stop();
      }
    },
  },
});
