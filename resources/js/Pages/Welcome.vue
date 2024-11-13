<script setup>
import {ref} from 'vue'
import { format } from 'date-fns'
import * as yup from 'yup'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import {Head, useForm, usePage } from '@inertiajs/vue3'
import Message from "@/Components/Shared/Message.vue";
import MessageError from "@/Components/Shared/MessageError.vue";

const page = usePage();

const props = defineProps({
    fechaLimite: {
        type: String,
        required: true
    }
});

const flow = ref(['year', 'month', 'calendar']);
const fechaLimite = new Date(props.fechaLimite);

const form = useForm({
    dui: null,
    email: null,
    birthdate: null,
    phone: null
});

const errors = ref({});

const schema = yup.object().shape({
    dui: yup.string()
        .required('El número de identificación ingresado no es válido. Por favor, verifica y vuelve a intentarlo.'),
    email: yup.string()
        .email('La dirección de correo electrónico proporcionada no cumple con el formato requerido. Por favor, corrígela e intenta nuevamente')
        .required('El campo correo electronico es obligatorio, por favor ingresa la información solicitada.'),
    birthdate: yup.date()
        .required('El campo fecha de nacimiento es obligatorio, por favor ingresa la información solicitada.'),
    phone: yup.string()
        .matches(/^(2|7|6)[0-9]{3}-[0-9]{4}$/, 'El número de teléfono ingresado no es válido. Por favor, verifica y vuelve a intentarlo.')
        .required('El campo numero de telefono es obligatorio, por favor ingresa la información solicitada.')
});

const submitForm = async () => {
    try {
        errors.value = {};
        await schema.validateSync(form.data(), {abortEarly: false});
        form.birthdate = formatDate(form.birthdate);
        await form.post(route('persona.store'), {
            preserveState: true,
            onError: (errors) => {
                console.log('Error al enviar el formulario', errors);
            }
        });
    } catch (validationError) {
        validationError.inner.forEach(error => {
            errors.value[error.path] = error.message;
        });
    }
}

const formatDate = (date) => {
  return format(date, 'yyyy-MM-dd');
}
</script>

<template>
    <Head title="Registro"/>
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
        <form @submit.prevent="submitForm" method="POST" class="mx-auto mt-10 max-w-xl sm:mt-10" autocomplete="off">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="company" class="block text-sm/6 font-semibold text-gray-900">DUI</label>
                    <mask-input mask="########-#" v-model="form.dui"
                                placeholder="Digite su numero de DUI"
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                    />
                    <message-error :message="errors.dui" v-if="errors.dui"/>
                </div>
                <div class="sm:col-span-2">
                    <label for="company" class="block text-sm/6 font-semibold text-gray-900">Correo electronico</label>
                    <input placeholder="Correo electronico"
                           type="email"
                           v-model="form.email"
                           class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
                    <message-error :message="errors.email" v-if="errors.email"/>
                </div>
                <div class="sm:col-span-2">
                    <label for="company" class="block text-sm/6 font-semibold text-gray-900">Fecha de nacimiento</label>
                    <vue-date-picker  :flow="flow" :max-date="fechaLimite" v-model="form.birthdate" :enable-time-picker="false" placeholder="Fecha de nacimiento" />
                    <message-error  :message="errors.birthdate" v-if="errors.birthdate"/>
                </div>
                <div class="sm:col-span-2">
                    <label for="company" class="block text-sm/6 font-semibold text-gray-900">Numero de telefono</label>
                    <mask-input mask="####-####" v-model="form.phone"
                                placeholder="Digite su numero de telefono"
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                    />
                    <message-error :message="errors.phone" v-if="errors.phone"/>
                </div>
            </div>
            <div class="mt-10">
                <button type="submit"
                        class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</template>

<style>
</style>
