<template>

  <fwb-modal v-if="isOpen" @close.prevent="handlerIsOpen(false)" persistent>
    <template #header>
      <div class="flex items-center text-lg">Declaración jurada</div>
    </template>
    <template #body>
      <message-error class="text-justify mb-6" message="Para poder continuar, es necesario que aceptes la declaración jurada. Esto asegura que la información proporcionada es verídica y permite completar el proceso de manera formal. <br /> Si tienes alguna duda o inquietud, no dudes en contactarnos." v-if="errors.acepto_terminos_condiciones" />
      <div>
        <p class="text-gray-700 text mb-4 text-justify text-sm">
          Declaro que la información proporcionada en este formulario es verdadera, completa y precisa, y entiendo que
          cualquier información incorrecta, falsa o engañosa puede resultar en la invalidación de mi solicitud o en
          otras acciones según lo determine la institución. <br /><br />
          Acepto que la organización se reserva el derecho de verificar
          los datos ingresados y de solicitar información adicional, si fuera necesario.
        </p>

        <div class="flex items-center">
          <input v-model="form.acepto_terminos_condiciones" type="checkbox"
                 class="h-4 w-4 text-blue-500 focus:ring-blue-400 border-gray-300 rounded">
          <label for="veracityCheck" class="ml-2 text-gray-700 text-sm text-justify">
            Confirmo que he leído y acepto la declaración de veracidad de la información ingresada.
          </label>
        </div>
      </div>
    </template>

    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="handlerIsOpen(false)" color="alternative">
          Rechazar
        </fwb-button>
        <fwb-button @click="aceptoTerminosCondiciones()" color="green">
          Acepto
        </fwb-button>
      </div>
    </template>
  </fwb-modal>

  <div class="w-full">
    <message :props="props.status" v-if="props.status && props.status.show" class="mb-6" />
    <form @submit.prevent="onSubmit">
      <article class="lg:grid lg:grid-cols-4 lg:gap-4 space-y-4lg:space-y-0">
        <div class="lg:col-span-2">
          <InputLabel for="ocupacion" value="¿Cuál es su situación ocupacional actualmente?" class="mb-2"/>
          <select
            v-model="form.ocupacion" id="ocupacion"
            :disabled="haveData || haveDataSaving"
            class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
          >
            <option value="null">Seleccione una opción</option>
            <option value="EMPLEO">Empleado formal (Cotiza en ISSS y/o AFP)</option>
            <option value="DESEMPLEO">Desempleado/Desocupado (Buscan activamente un trabajo)</option>
            <option value="SUBEMPLEO">Sub-empleado/a (Empleo de jornada reducida (menor a 40 horas semanales)</option>
            <option value="EMPRENDEDOR">Trabajador por cuenta propia/emprendedor</option>
          </select>
          <message-error v-if="errors.ocupacion" :message="errors.ocupacion" />
        </div>
        <div class="lg:col-span-2">
          <InputLabel for="nivel_escolaridad" value="¿Cuál es su máximo nivel de escolaridad en el que ha recibido título o diploma?" class="mb-2"/>
          <select
            id="nivel_escolaridad" v-model="form.nivel_escolaridad" :disabled="haveData || haveDataSaving"
            class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
          >
            <option value="null">Seleccione una opción</option>
            <option value="NINGUNO">Ninguno</option>
            <option value="PARVULARIA">Parvularia</option>
            <option value="PRIMARIA">Básica primaria (1°a 5°)</option>
            <option value="SECUNDARIA">Básica secundaria (6°a 9°)</option>
            <option value="MEDIA">Media (Bachiller)</option>
            <option value="SUPERIOR">Superior</option>
          </select>
          <message-error v-if="errors.nivel_escolaridad" :message="errors.nivel_escolaridad" />
        </div>
        <div class="lg:col-span-2">
          <InputLabel for="ingresos" value="¿Cuál es el nivel de ingresos de su hogar (Hogar: debe sumar los ingresos de todas las personas que viven en su casa) ?" class="mb-2"/>
          <TextInput
            id="ingresos"
            v-model="form.ingresos"
            type="number"
            :disabled="haveData || haveDataSaving"
            placeholder="Ingresos mensuales en dólares"
            class="mt-1 block w-full"
            required
          />
          <message-error v-if="errors.ingresos" :message="errors.ingresos" />
        </div>
        <div class="lg:col-span-2">
          <InputLabel for="numero_personas" value="¿Cual el número de personas que viven en su casa (Incluir adultos, niños y adultos mayores)?" class="mb-2"/>
          <TextInput
            id="personas"
            v-model="form.numero_personas"
            placeholder="Número de personas"
            type="number"
            :disabled="haveData || haveDataSaving"
            class="mt-1 block w-full"
            required
          />
          <message-error v-if="errors.numero_personas" :message="errors.numero_personas" />
        </div>
        <div class="lg:col-span-full">
          <InputLabel for="estudiando" value="¿Actualmente se encuentra estudiando en un centro educativo formal (Escuela, Colegio, Universidad, Centro Escolar, Institutos Tecnológicos, Escuela Superior)?" class="mb-2"/>
          <select
            id="estudiando" v-model="form.estudiando" :disabled="haveData || haveDataSaving"
            class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
          >
            <option value="null">Seleccione una opción</option>
            <option value="SI">SI</option>
            <option value="NO">No</option>
          </select>
          <message-error v-if="errors.estudiando" :message="errors.estudiando" />
        </div>

        <div class="lg:col-span-full">
          <InputLabel for="participa_o_recibe" value="¿Actualmente participa y recibe estipendio en un programa social ejecutado por el gobierno de El Salvador?" class="mb-2"/>
          <select
            id="estudiando" v-model="form.participa_o_recibe" :disabled="haveData || haveDataSaving"
            class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
          >
            <option value="null">Seleccione una opción</option>
            <option value="SI">SI</option>
            <option value="NO">No</option>
          </select>
          <message-error v-if="errors.participa_o_recibe" :message="errors.participa_o_recibe" />
        </div>

        <div class="lg:col-span-full" v-if="!(haveData || haveDataSaving)">
          <PrimaryButton>
            Guardar
          </PrimaryButton>
        </div>
        <div v-else class="lg:col-span-full">
          <message :props="{ success: 'Gracias por enviar su información. La hemos recibido exitosamente y nuestro equipo se comunicará con usted en breve para brindarle más detalles y los próximos pasos a seguir.' }" />
        </div>
      </article>
    </form>
  </div>
</template>

<script setup>
import * as yup from 'yup'
import { defineProps, ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { FwbButton, FwbModal } from 'flowbite-vue'

import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Message from "@/Components/Shared/Message.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import MessageError from "@/Components/Shared/MessageError.vue";

const props = usePage().props;

const isOpen = ref(false);
const haveDataSaving = ref(false);

const {persona, haveData, data} = defineProps({
  persona: Object,
  haveData: Boolean,
  data: Object
});

const form = useForm({
  ocupacion: haveData ? data.respuestas.ocupacion : null,
  nivel_escolaridad: haveData ? data.respuestas.nivel_escolaridad : null,
  ingresos: haveData ? data.respuestas.ingresos : null,
  numero_personas: haveData ? data.respuestas.numero_personas : null,
  estudiando: haveData ? data.respuestas.estudiando : null,
  participa_o_recibe: haveData ? data.respuestas.participa_o_recibe : null,
  acepto_terminos_condiciones: false,
});

const schema = yup.object().shape({
  ocupacion: yup.string().required('Este campo es requerido'),
  nivel_escolaridad: yup.string().required('Este campo es requerido'),
  ingresos: yup.number().required('Este campo es requerido').min(1, 'El valor debe ser mayor a 0'),
  numero_personas: yup.number().required('Este campo es requerido').min(1, 'El valor debe ser mayor a 0'),
  estudiando: yup.string().required('Este campo es requerido'),
  participa_o_recibe: yup.string().required('Este campo es requerido'),
});

const errors = ref({});

// actions
const onSubmit = async () => {
  try {
    errors.value = {};
    await schema.validateSync(form.data(), {abortEarly: false});
    isOpen.value = true;
  } catch (validationError) {
    validationError.inner.forEach(error => {
      errors.value[error.path] = error.message;
    });
  }
}

const aceptoTerminosCondiciones = async () => {
  if(!form.acepto_terminos_condiciones) {
    errors.value['acepto_terminos_condiciones'] = 'Debes aceptar la declaración jurada';
    return;
  }
  try {
    errors.value = {};
    await schema.validateSync(form.data(), {abortEarly: false});
    form.data.persona_id = persona.id;
    form.post(route('persona.validar'), {
      onSuccess: () => {
        haveDataSaving.value = true;
        isOpen.value = false;
      }
    });
  } catch (validationError) {
    validationError.inner.forEach(error => {
      errors.value[error.path] = error.message;
    });
  }
}

const handlerIsOpen = (value) => {
  isOpen.value = value;
}
</script>
