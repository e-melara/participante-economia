<script setup>
import { ref, onMounted } from "vue";
import * as yup from "yup";
import { parse, isValid, isAfter, format } from "date-fns";
import "@vuepic/vue-datepicker/dist/main.css";

import { Head, useForm, usePage } from "@inertiajs/vue3";
import Message from "@/Components/Shared/Message.vue";
import MessageError from "@/Components/Shared/MessageError.vue";

import { FwbModal } from 'flowbite-vue'

const page = usePage();
const props = defineProps({
  fechaLimite: {
    type: String,
    required: true
  }
});

const isOpen = ref(false);
const isSuccess = ref(false);
const isDisabled = ref(true);
const remainingTime = ref(60);

const startTimer = () => {
  const interval = setInterval(() => {
    if(remainingTime.value > 0) {
      remainingTime.value -= 1;
    } else {
      clearInterval(interval)
      isDisabled.value = false;
    }
  }, 1000)
}

const fechaLimite = new Date(props.fechaLimite);

const form = useForm({
  dui: null,
  email: null,
  birthdate: null,
  phone: null,
  token: null,
});

const errors = ref({});

const schema = yup.object().shape({
  dui: yup.string()
    .required("El número de identificación ingresado no es válido. Por favor, verifica y vuelve a intentarlo."),
  email: yup.string()
    .email("La dirección de correo electrónico proporcionada no cumple con el formato requerido. Por favor, corrígela e intenta nuevamente")
    .required("El campo correo electronico es obligatorio, por favor ingresa la información solicitada."),
  birthdate: yup.string()
    .required("El campo fecha de nacimiento es obligatorio, por favor ingresa la información solicitada.")
    .matches(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/, "La fecha de nacimiento ingresada no es válida. Por favor, verifica y vuelve a intentarlo.")
    .test("max-date", `La fecha no debe ser mayor a ${format(fechaLimite, 'dd/MM/y')}`, value => {
      if(!value) return false;
      const enteredDate = parse(value, "dd/MM/yyyy", new Date());
      return isValid(enteredDate) && !isAfter(enteredDate, fechaLimite);
    }),
  phone: yup.string()
    .matches(/^(2|7|6)[0-9]{3}-[0-9]{4}$/, "El número de teléfono ingresado no es válido. Por favor, verifica y vuelve a intentarlo.")
    .required("El campo numero de telefono es obligatorio, por favor ingresa la información solicitada.")
});

const converterDate = (dateString = '') => {
  const partes = dateString.split('/');
  return `${partes[2]}-${partes[1]}-${partes[0]}`;
}

const initialTimer = () => {
  isDisabled.value = true;
  remainingTime.value = 60;
  startTimer();
}

const validateToken =  async () => {
  form.birthdate = converterDate(form.birthdate);
  form.post(route("persona.store"), {
    onSuccess: () => {
      handlerCloseIsOpen(false);
      form.reset();
      isSuccess.value = true;
    },
    onError: (responseError) => {
      const { errors: errorsApi } = responseError?.response?.data;
      if(errorsApi) {
        Object.keys(errorsApi).forEach(key => {
          if(errorsApi[key].length > 0) {
            errors.value[key] = errorsApi[key][0];
          }
        });
      }
    }
  });
};

const submitForm = async () => {
  try {
    errors.value = {};
    await schema.validateSync(form.data(), { abortEarly: false });
    await window.axios.post(route("persona.token"), {
      email: form.email,
    }).then(() => {
      isOpen.value = true;
      initialTimer();
    }).catch(responseError => {
      const { errors: errorsApi } = responseError?.response?.data;
      if(errorsApi) {
        Object.keys(errorsApi).forEach(key => {
          if(errorsApi[key].length > 0) {
            errors.value[key] = errorsApi[key][0];
          }
        });
      }
    });
  } catch (validationError) {
    if(validationError?.inner?.length > 0) {
      validationError.inner.forEach(error => {
        errors.value[error.path] = error.message;
      });
    } else {
      console.log(validationError);
    }
  }
};

const handlerCloseIsOpen = (isOpenOrClose = false) => {
  isOpen.value = isOpenOrClose;
};

onMounted(() => {
  startTimer();
})
</script>

<template>
  <Head title="Registro" />

  <fwb-modal v-if="isOpen" @close.prevent="handlerCloseIsOpen()" persistent>
    <template #header>
      <div class="flex items-center text-lg">Gracias por registrarte.</div>
    </template>
    <template #body>
      <message-error class="text-justify mb-6" message="Para poder continuar, es necesario que aceptes la declaración jurada. Esto asegura que la información proporcionada es verídica y permite completar el proceso de manera formal. <br /> Si tienes alguna duda o inquietud, no dudes en contactarnos." v-if="errors.acepto_terminos_condiciones" />
      <div>
        <p class="text-justify font-medium">
          Hemos enviado un código de verificación a tu correo electrónico <span class="font-semibold">{{ form.email }}</span>. <br />
          Por favor, ingresa el código en el formulario de verificación para completar el proceso de validación.
        </p>
      </div>
      <div class="max-w-full mt-8">
        <label for="token" class="block text-sm font-medium text-gray-700">Código de verificación</label>
        <div class="mt-1">
          <input
            type="text"
            id="token"
            name="token"
            maxlength="6"
            v-model="form.token"
            placeholder="Ingresa tu código"
            class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
          />
        </div>
        <p class="mt-2 text-sm text-gray-500">
          Introduce el código de 6 dígitos que enviamos a tu correo electrónico.
        </p>
        <button
          type="button"
          @click.prevent="validateToken()"
          class="mt-4 w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Validar código
        </button>
        <button
          type="button"
          :disabled="isDisabled"
          :class="[isDisabled ? 'bg-gray-300' : 'bg-gray-600']"
          @click.prevent="handlerCloseIsOpen()"
          class="mt-4 w-full px-4 py-2  text-white font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          {{ isDisabled ? `Espera ${remainingTime}` : 'Cerrar' }}
        </button>
      </div>
    </template>
  </fwb-modal>

  <message v-if="page.props.status.show" :props="page.props.status" />
  <div class="isolate bg-white px-6 py-24 sm:py-32 lg:px-8">
    <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]">
      <div
        class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]"
        style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
    </div>
    <div class="mx-auto max-w-2xl text-center">
      <h2 class="text-balance text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Formulario de
        registro</h2>
      <p class="mt-2 text-lg/8 text-gray-600">Descripcion del formulario</p>
    </div>
    <div class="mx-auto mt-16 max-w-xl sm:mt-20">
      <message-error v-for="(error, index) in Object.values(form.errors)" :key="index" :message="error" />
    </div>
    <form @submit.prevent="submitForm" method="POST" class="mx-auto mt-10 max-w-xl sm:mt-10" autocomplete="off" v-if="!isSuccess">
      <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label for="company" class="block text-sm/6 font-semibold text-gray-900">DUI</label>
          <mask-input mask="########-#" v-model="form.dui"
                      placeholder="Digite su numero de DUI"
                      class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
          />
          <message-error :message="errors.dui" v-if="errors.dui" />
        </div>
        <div class="sm:col-span-2">
          <label for="company" class="block text-sm/6 font-semibold text-gray-900">Correo electronico</label>
          <input placeholder="Correo electronico"
                 type="email"
                 v-model="form.email"
                 class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
          <message-error :message="errors.email" v-if="errors.email" />
        </div>
        <div class="sm:col-span-2">
          <label for="company" class="block text-sm/6 font-semibold text-gray-900">Fecha de nacimiento</label>
          <mask-input mask="##/##/####"
                      v-model="form.birthdate"
                      placeholder="Por favor digita la fecha de nacimiento en el formato dd/mm/yyyy"
                      class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
          />
          <message-error :message="errors.birthdate" v-if="errors.birthdate" />
        </div>
        <div class="sm:col-span-2">
          <label for="company" class="block text-sm/6 font-semibold text-gray-900">Numero de telefono</label>
          <mask-input mask="####-####" v-model="form.phone"
                      placeholder="Digite su numero de telefono"
                      class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
          />
          <message-error :message="errors.phone" v-if="errors.phone" />
        </div>
      </div>
      <div class="mt-10">
        <button type="submit"
                class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          Registrar
        </button>
      </div>
    </form>
    <div class="mx-auto mt-10 max-w-xl sm:mt-10" v-else>
      <div class="bg-blue-500 border border-blue-400 text-stone-50 text-justify px-4 py-3 rounded relative mt-2" role="alert">
        <span class="block sm:inline">
          Tu registro ha sido completado con éxito. En breve recibirás un correo electrónico con la información necesaria para continuar con el proceso.
        </span>
      </div>
    </div>
  </div>
</template>

<style>
</style>
