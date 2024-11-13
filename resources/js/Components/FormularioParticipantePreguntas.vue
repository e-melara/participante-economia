<template>
  <div class="container w-full">
    <form @submit.prevent="onSubmit">
      <article class="grid grid-cols-4 gap-4">
        <div class="col-span-2">
          <InputLabel for="ocupacion" value="¿Cuál es su situación ocupacional actualmente?" class="mb-2"/>
          <select
            v-model="form.ocupacion" id="ocupacion"
            class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
          >
            <option value="EMPLEO">Empleado formal (Cotiza en ISSS y/o AFP)</option>
            <option value="DESEMPLEO">Desempleado/Desocupado (Buscan activamente un trabajo)</option>
            <option value="SUBEMPLEO">Sub-empleado/a (Empleo de jornada reducida (menor a 40 horas semanales)</option>
            <option value="EMPRENDEDOR">Trabajador por cuenta propia/emprendedor</option>
          </select>
          <message-error v-if="errors.ocupacion" :message="errors.ocupacion" />
        </div>
        <div class="col-span-2">
          <InputLabel for="nivel_escolaridad" value="¿Cuál es su máximo nivel de escolaridad en el que ha recibido título o diploma?" class="mb-2"/>
          <select
            id="nivel_escolaridad" v-model="form.nivel_escolaridad"
            class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
          >
            <option value="NINGUNO">Ninguno</option>
            <option value="PARVULARIA">Parvularia</option>
            <option value="PRIMARIA">Básica primaria (1°a 5°)</option>
            <option value="SECUNDARIA">Básica secundaria (6°a 9°)</option>
            <option value="MEDIA">Media (Bachiller)</option>
            <option value="SUPERIOR">Superior</option>
          </select>
          <message-error v-if="errors.nivel_escolaridad" :message="errors.nivel_escolaridad" />
        </div>
        <div class="col-span-2">
          <InputLabel for="ingresos" value="¿Cuál es el nivel de ingresos de su hogar (Hogar: debe sumar los ingresos de todas las personas que viven en su casa) ?" class="mb-2"/>
          <TextInput
            id="ingresos"
            v-model="form.ingresos"
            type="number"
            placeholder="Ingresos mensuales en dólares"
            class="mt-1 block w-full"
            required
          />
          <message-error v-if="errors.ingresos" :message="errors.ingresos" />
        </div>
        <div class="col-span-2">
          <InputLabel for="numero_personas" value="¿Cual el número de personas que viven en su casa (Incluir adultos, niños y adultos mayores)?" class="mb-2"/>
          <TextInput
            id="personas"
            v-model="form.numero_personas"
            placeholder="Número de personas"
            type="number"
            class="mt-1 block w-full"
            required
          />
          <message-error v-if="errors.numero_personas" :message="errors.numero_personas" />
        </div>
        <div class="col-span-full">
          <InputLabel for="estudiando" value="¿Actualmente se encuentra estudiando en un centro educativo formal (Escuela, Colegio, Universidad, Centro Escolar, Institutos Tecnológicos, Escuela Superior)?" class="mb-2"/>
          <select
            id="estudiando" v-model="form.estudiando"
            class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
          >
            <option value="SI">SI</option>
            <option value="NO">No</option>
          </select>
          <message-error v-if="errors.estudiando" :message="errors.estudiando" />
        </div>

        <div class="col-span-full">
          <PrimaryButton>
            Guardar
          </PrimaryButton>
        </div>
      </article>
    </form>
  </div>
</template>

<script setup>
import * as yup from 'yup'
import { defineProps, ref, defineEmits } from 'vue'
import { useForm } from '@inertiajs/vue3'


import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import MessageError from "@/Components/Shared/MessageError.vue";

const {persona} = defineProps({
  persona: Object,
});

const form = useForm({
  ocupacion: null,
  nivel_escolaridad: null,
  ingresos: null,
  numero_personas: null,
  estudiando: null
});

const schema = yup.object().shape({
  ocupacion: yup.string().required('Este campo es requerido'),
  nivel_escolaridad: yup.string().required('Este campo es requerido'),
  ingresos: yup.number().required('Este campo es requerido').min(1, 'El valor debe ser mayor a 0'),
  numero_personas: yup.number().required('Este campo es requerido').min(1, 'El valor debe ser mayor a 0'),
  estudiando: yup.string().required('Este campo es requerido'),
});

const errors = ref({});

// actions
const onSubmit = () => {
  try {
    errors.value = {};
    schema.validateSync(form.data(), {abortEarly: false});
    form.data.persona_id = persona.id;
    form.post(route('persona.validar'), {
      onSuccess: () => {
        console.log('success');
      }
    });
  } catch (validationError) {
    console.log(validationError);
    validationError.inner.forEach(error => {
      errors.value[error.path] = error.message;
    });
  }
}
</script>
