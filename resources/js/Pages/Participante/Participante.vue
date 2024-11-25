<script setup>

import FormularioParticipante from "@/Components/FormularioParticipante.vue";
import FormularioParticipantePreguntas from "@/Components/FormularioParticipantePreguntas.vue";

const { persona, haveToComplete, dataToInformacion } = defineProps({
  persona: Object,
  haveToComplete: Boolean,
  dataToInformacion: Object
});
</script>

<template>
  <header class="bg-white dark:bg-gray-800 shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Informacion</h2>
    </div>
  </header>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <FormularioParticipante :persona="persona" />
        </div>
      </div>
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm mt-10 sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <FormularioParticipantePreguntas :haveData="haveToComplete" :data="dataToInformacion"
                                           :persona="persona" />
        </div>
      </div>
    </div>
  </div>
</template>
