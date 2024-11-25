<template>
  <authenticated-layout>
    <Head title="Listado de participantes" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Listado de participantes
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <fwb-table hoverable>
              <fwb-table-head>
                <fwb-table-head-cell></fwb-table-head-cell>
                <fwb-table-head-cell>
                  <h3 class="font-semibold">Fecha Nacimiento</h3>
                </fwb-table-head-cell>
                <fwb-table-head-cell>
                  <h3 class="font-semibold">Edad</h3>
                </fwb-table-head-cell>
                <fwb-table-head-cell>
                  <h3 class="font-semibold">Profesion</h3>
                </fwb-table-head-cell>
                <fwb-table-head-cell></fwb-table-head-cell>
                <fwb-table-head-cell></fwb-table-head-cell>
              </fwb-table-head>
              <fwb-table-body>
                <fwb-table-row v-for="persona in personas?.data">
                  <fwb-table-cell class="flex items-center">
                    <img class="w-15 h-10 rounded" :src="persona?.photo_url" :alt="persona?.full_name" />
                    <div class="flex-1 text-left ml-6">
                      <h1 class="font-semibold">{{ capitalizeSentence(persona?.nombres) }}</h1>
                      <h3 class="text-gray-500">{{ capitalizeSentence(persona?.apellidos) }}</h3>
                    </div>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <h3 class="text-black">{{ persona?.fecha_nacimiento }}</h3>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <h3 class="text-black">{{ persona?.edad }}</h3>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <h3 class="text-left text-black">{{ persona?.profesion }}</h3>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <fwb-badge :type="getColorsBadge(persona.status)" size="sm">
                      {{ capitalizeSentence(persona.status) }}
                    </fwb-badge>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <fwb-button class="mr-2" size="sm" color="green">
                      <template #prefix>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-6">
                          <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                          <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                      </template>
                      Ver
                    </fwb-button>
                  </fwb-table-cell>
                </fwb-table-row>
              </fwb-table-body>
            </fwb-table>
          </div>
        </div>
      </div>
    </div>
  </authenticated-layout>
</template>

<script setup>
import {
  FwbTable,
  FwbBadge,
  FwbTableBody,
  FwbTableRow,
  FwbTableCell,
  FwbTableHead,
  FwbButton,
  FwbTableHeadCell,
} from "flowbite-vue";

import { Head } from "@inertiajs/vue3";

import { capitalizeSentence } from "@/utils/functions.js";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const { personas } = defineProps({
  "personas": Object
});

const getColorsBadge = (status = "pendiente") => {
  switch (status) {
    case "aprobado":
      return "green";
    case "rechazado":
      return "pink";
    default:
      return "dark";
  }
};

</script>

<style scoped>
</style>
