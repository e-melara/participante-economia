<template>
    <div class="container w-full">
    <div class="grid grid-cols-4 gap-4">
      <article class="col-span-full">
        <InputLabel for="nombre" value="Nombres: " />
        <TextInput
          id="nombre"
          type="text"
          class="mt-1 block w-full"
          v-model="persona.nombres"
          required
          disabled
        />
      </article>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
const {persona} = defineProps({
  persona: Object,
});
</script>