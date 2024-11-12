<template>
  <div :class="typeAlert" role="alert">
    <span class="block font-bold sm:inline"> {{ getText }}</span>
  </div>
</template>

<script setup>
import { computed } from "vue";
const { props } = defineProps({
  props: {
    type: Object,
    required: true,
  },
});

const typeAlert = computed(() => {
  let className = "border px-4 py-3 relative mt-0 text-center text-stone-50 ";

  if (props.success) {
    className += "bg-blue-500 border-blue-400";
  } else if (props.error) {
    className += "bg-red-500 border-red-400";
  } else {
    className += "bg-amber-500 border-amber-400";
  }

  return className;
});

const getText = computed(() => {
  if (props.success) {
    return props.success;
  } else if (props.error) {
    return  props.error;
  } else {
    return "¡Advertencia!";
  }
});
</script>

<style></style>
