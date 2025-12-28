import { useAuthStore } from '@/stores/auth';

export function requireAuth(to, from, next) {
  const authStore = useAuthStore();
  
  if (to.matched.some(record => record.meta.requiresAuth) && !authStore.isAuthenticated) {
    return next({ name: 'login' });
  }

  next();
}