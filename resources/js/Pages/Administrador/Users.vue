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
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" >
          <div class="flex justify-end mt-5 mr-5">
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
              </div>
              <mask-input mask="########-#"
                          autocomplete="off"
                          @keyup.enter="searchParticipante"
                          v-model="pagination.query"
                          class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="Buscar participante por el DUI"
              />
            </div>
          </div>
          <div class="p-6 text-gray-900 dark:text-gray-100" v-if="!isLoading">
            <fwb-table hoverable>
              <fwb-table-head>
                <fwb-table-head-cell class="w-25"></fwb-table-head-cell>
                <fwb-table-head-cell class="w-10 text-center">
                  <h3 class="font-semibold">Fecha Nacimiento</h3>
                </fwb-table-head-cell>
                <fwb-table-head-cell class="w-10 text-center">
                  <h3 class="font-semibold">Edad</h3>
                </fwb-table-head-cell>
                <fwb-table-head-cell class="w-14 text-center">
                  <h3 class="font-semibold">Profesion</h3>
                </fwb-table-head-cell>
                <fwb-table-head-cell class="w-20"></fwb-table-head-cell>
                <fwb-table-head-cell class="w-32"></fwb-table-head-cell>
              </fwb-table-head>
              <fwb-table-body v-for="persona in personas">
                <fwb-table-row>
                  <fwb-table-cell class="flex items-center">
                    <fwb-img
                      class="w-10 h-10 rounded"
                      :alt="persona?.full_name"
                      :src="persona?.photo_url"
                    />
                    <div class="flex-1 text-left ml-6">
                      <h1 class="font-semibold">{{ persona?.dui }}</h1>
                      <h3 class="text-gray-400">{{ capitalizeSentence(persona?.nombres) }}
                        {{ capitalizeSentence(persona?.apellidos) }}</h3>
                    </div>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <h3 class="text-black dark:text-gray-400">{{ persona?.fecha_nacimiento }}</h3>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <h3 class="text-black dark:text-gray-400">{{ persona?.edad }}</h3>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <h3 class="text-left text-black dark:text-gray-400">{{ persona?.profesion }}</h3>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <fwb-badge :type="getColorsBadge(persona.status)" size="sm" class="text-center">
                      {{ capitalizeSentence(persona.status) }}
                    </fwb-badge>
                  </fwb-table-cell>
                  <fwb-table-cell>
                    <fwb-button @click.prevent="onViewPersona(persona)" class="mr-2" size="sm" color="green"
                                v-if="['rechazado', 'aprobado'].includes(persona.status)">
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

            <article class="max-w-full flex justify-center mt-6">
              <fwb-pagination @pageChanged="onChangePage" v-model="pagination.page"
                              :total-pages="pagination.total_pages" :show-labels="false">
                <template #prev-icon>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                  </svg>
                </template>
                <template #next-icon>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                  </svg>
                </template>
              </fwb-pagination>
            </article>
          </div>
          <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-center p-8">
            <fwb-spinner size="12" />
          </div>
        </div>
      </div>
    </div>
  </authenticated-layout>
</template>

<script setup>
import {
  FwbImg,
  FwbTable,
  FwbBadge,
  FwbButton,
  FwbSpinner,
  FwbTableRow,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbPagination,
  FwbTableHeadCell
} from "flowbite-vue";

import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";

import { capitalizeSentence } from "@/utils/functions.js";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const isLoading = ref(true);
const personas = ref([]);
const pagination = ref({
  page: 1,
  total: 8,
  query: "",
  per_page: 3,
  total_pages: 1
});

const onChangePage = async () => {
  await paginations();
};

const paginations = async () => {
  const { page, per_page, query } = pagination.value;
  isLoading.value = true;
  try {
    const { data, meta } = await window.axios.get(route("participantes.pagination"), {
      params: {
        page,
        query,
        per_page
      }
    })
      .then((response) => response?.data?.personas);
    personas.value = data;
    pagination.value = Object.assign(pagination.value, meta, {
      total_pages: Math.ceil(meta.total / meta.per_page)
    });
  } catch (e) {
    console.error(e);
  } finally {
    isLoading.value = false;
  }
};

const searchParticipante = async () => {
  pagination.value = Object.assign(pagination.value, { page: 1 });
  await paginations();
};

const onViewPersona = (persona) => {
  console.log(persona);
};

onMounted(() => {
  paginations();
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
