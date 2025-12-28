<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-6">
      <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Entrar</h2>

      <Form @submit="handleLogin" :validation-schema="schema" v-slot="{ errors, isSubmitting }">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <Field
            name="email"
            type="email"
            placeholder="seu@email.com"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            :class="{ 'border-red-500': errors.email }"
          />
          <ErrorMessage name="email" class="text-red-500 text-xs mt-1" />
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
          <Field
            name="password"
            type="password"
            placeholder="Senha"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            :class="{ 'border-red-500': errors.password }"
          />
          <ErrorMessage name="password" class="text-red-500 text-xs mt-1" />
        </div>

        <button
          type="submit"
          :disabled="isSubmitting"
          class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ isSubmitting ? 'Entrando...' : 'Entrar' }}
        </button>
      </Form>

      <div class="text-sm text-center mt-4 text-gray-600">
        Ainda não tem uma conta?
        <router-link to="/register" class="text-indigo-600 hover:underline font-medium">Criar conta</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { useToast } from '@/composables/useToast';
import * as yup from 'yup'

const authStore = useAuthStore();
const router = useRouter();
const { notifyError } = useToast();

const schema = yup.object({
  email: yup.string().email('Email inválido').required('Email é obrigatório'),
  password: yup.string().required('Senha é obrigatória'),
})

const handleLogin = async (credentials, { setErrors }) => {
  try {
    await authStore.login(credentials);
    router.push({ name: 'users' });
  } catch (error) {
    if (error.response?.status === 401) {
      notifyError('Credenciais inválidas. Verifique seu email e senha.');
    } else {
      notifyError('Erro ao fazer login. Tente novamente mais tarde.');
      console.error('Login error:', error);
    }
  }
};
</script>