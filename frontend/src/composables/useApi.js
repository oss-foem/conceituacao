import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

export function useApi() {
  const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    timeout: 5000,
  });

  api.interceptors.request.use(
    (config) => {
      const authStore = useAuthStore();
      if (authStore.token) {
        config.headers.Authorization = `Bearer ${authStore.token}`;
      }
      return config;
    },
    (error) => Promise.reject(error)
  );

  api.interceptors.response.use(
    (response) => response,
    (error) => {
      const authStore = useAuthStore();
      const router = useRouter();

      if (error.response?.status === 401) {
        console.warn('Token expirado ou inválido. Realizando logout automático.');
        authStore.clearToken();
        authStore.clearUser();
        router.push({ name: 'login' });
      }

      console.error('Erro na API:', error);
      return Promise.reject(error);
    }
  );

  return { api };
}