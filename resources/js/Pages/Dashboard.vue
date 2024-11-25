<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import { hasRole } from "@/utils/hasRolesUtils.js";
import Participante from "@/Pages/Participante/Participante.vue";

const { persona, haveToComplete, dataToInformacion } = defineProps({
  persona: {
    type: Object,
    default: null
  },
  haveToComplete: {
    type: Boolean,
    default: false
  },
  dataToInformacion: {
    type: Object,
    default: null
  }
});
</script>

<template>
  <Head title="Informacion Personal" />
  <AuthenticatedLayout>
    <section v-if="hasRole(['Administrador'])">
      <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Sistema de aldea</h2>
        </div>
      </header>
    </section>
    <section v-else>
      <Participante :haveToComplete="haveToComplete" :dataToInformacion="dataToInformacion" :persona="persona?.data" />
    </section>
  </AuthenticatedLayout>
</template>
