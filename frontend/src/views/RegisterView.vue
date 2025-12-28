<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-6">
      <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Criar Conta</h2>

      <Form @submit="handleRegister" :validation-schema="schema" v-slot="{ errors, isSubmitting }">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
          <Field
            name="name"
            type="text"
            placeholder="Seu nome"
            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
            :class="{ 'border-red-500': errors.name }"
          />
          <ErrorMessage name="name" class="text-red-500 text-xs mt-1" />
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <Field
            name="email"
            type="email"
            placeholder="seu@email.com"
            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
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
            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
            :class="{ 'border-red-500': errors.password }"
          />
          <ErrorMessage name="password" class="text-red-500 text-xs mt-1" />
        </div>

        <button
          type="submit"
          :disabled="isSubmitting"
          class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition"
        >
          {{ isSubmitting ? 'Criando conta...' : 'Registrar' }}
        </button>
      </Form>

      <div class="text-sm text-center mt-4 text-gray-600">
        Já tem uma conta?
        <router-link to="/login" class="text-indigo-600 hover:underline font-medium">Entrar</router-link>
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
const { notifySuccess, notifyError } = useToast();

const schema = yup.object({
  name: yup.string().required('Nome é obrigatório'),
  email: yup.string().email('Email inválido').required('Email é obrigatório'),
  password: yup.string().min(6, 'Mínimo de 6 caracteres').required('Senha é obrigatória'),
})

const handleRegister = async (credentials, { setErrors }) => {
  try {
    await authStore.register(credentials);
    notifySuccess('Cadastro realizado com sucesso! Faça login para continuar.');
    router.push({ name: 'login' });
  } catch (error) {
    if (typeof error === 'string') {
      setErrors({ email: error });
    }

    notifyError(error || 'Erro ao registrar. Tente novamente mais tarde.');
    console.error('Register error:', error);
  }
};

</script>
