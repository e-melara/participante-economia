<script setup>
import { Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FormularioParticipante from '@/Components/FormularioParticipante.vue';
import FormularioParticipantePreguntas from '@/Components/FormularioParticipantePreguntas.vue';

const props = defineProps({
    persona: Object,
    haveToComplete: Boolean,
    dataToInformacion: Object,
});
</script>

<template>
    <Head title="Informacion Personal" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Informacion</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <FormularioParticipante :persona="persona?.data" />
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm mt-10 sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <FormularioParticipantePreguntas :haveData="haveToComplete" :data="dataToInformacion" :persona="persona?.data" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
