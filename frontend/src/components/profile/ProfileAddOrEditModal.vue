<template>
  <div class="fixed inset-0 bg-gray-500/30 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-xl w-full">
      <div class="flex justify-between items-center mb-2 px-5 pt-5">
        <h2 class="text-xl font-medium text-gray-800">
          {{ profile ? 'Editar Perfil' : 'Adicionar Perfil' }}
        </h2>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
          <XIcon class="h-5 w-5" />
        </button>
      </div>

      <div class="relative flex py-4 items-center">
        <div class="flex-grow border-t border-gray-400/30"></div>
        <div class="flex-grow border-t border-gray-400/30"></div>
      </div>

      <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors, isSubmitting }">
        <div class="px-5 pb-5 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
            <Field
              name="name"
              type="text"
              placeholder="Nome do perfil"
              class="w-full px-3 py-1 border rounded-md bg-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              :class="{ 'border-red-500': errors.name }"
              :value="profile?.name"
            />
            <ErrorMessage name="name" class="text-red-500 text-xs mt-1" />
          </div>

          <div class="flex justify-end gap-2">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700"
            >
              {{ isSubmitting ? 'Salvando...' : 'Salvar' }}
            </button>
          </div>
        </div>
      </Form>
    </div>
  </div>
</template>

<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import { XIcon } from 'lucide-vue-next'
import { useProfileStore } from '@/stores/profile'

const profileStore = useProfileStore()
const emit = defineEmits(['close'])

const props = defineProps({
  profile: Object
})

const schema = yup.object({
  name: yup.string().required('Nome é obrigatório'),
})

const onSubmit = async (values) => {
  try {
    if (props.profile) {
      await profileStore.updateProfile(props.profile.id, values)
    } else {
      await profileStore.createProfile(values)
    }
    emit('close')
  } catch (error) {
  }
}
</script>
