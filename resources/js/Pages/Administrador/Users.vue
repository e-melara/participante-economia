<template>
  <authenticated-layout>

  </authenticated-layout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  'personas': Object,
});
</script>

<style scoped>
</style>
