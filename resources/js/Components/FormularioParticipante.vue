<template>
    <div class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">      
      <div class="lg:col-span-1">
        <figure v-if="persona.photo" class="flex h-full justify-center">
          <img :src="persona.photo" 
            alt="imagen del usuario" 
            class="h-64 object-scale-down hover:transition-all w-64 w-64 h-full"
          />
        </figure>
      </div>
      <div class="lg:col-span-2">
        <article class="lg:space-y-0 space-y-5 lg:grid lg:grid-cols-4 lg:gap-4">
          <div class="col-span-full">
            <InputLabel for="nombre" value="Nombres: " />
            <TextInput
              id="nombre"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.nombres"
              required
              disabled
            />
          </div>
          <div class="col-span-4">
            <InputLabel for="apellidos" value="Apellidos: " />
            <TextInput
              id="apellidos"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.apellidos"
              required
              disabled
            />
          </div>
          <div class="col-span-2">
            <InputLabel for="fecha_nacimiento" value="Fecha de Nacimiento: " />
            <TextInput
              id="fecha_nacimiento"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.fecha_nacimiento"
              required
              disabled
            />
          </div>
          <div class="col-span-2">
            <InputLabel for="edad" value="Edad: " />
            <TextInput
              id="edad"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.edad"
              required
              disabled
            />
          </div>

          <div class="col-span-2">
            <InputLabel for="departamento_nacimiento" value="Departamento nacimiento: " />
            <TextInput
              id="departamento_nacimiento"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.departamento_nacimiento"
              required
              disabled
            />
          </div>
          <div class="col-span-2">
            <InputLabel for="municipio_nacimiento" value="Municipio nacimiento: " />
            <TextInput
              id="municipio_nacimiento"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.municipio_nacimiento"
              required
              disabled
            />
          </div>

          <div class="col-span-2">
            <InputLabel for="departamento_residencia" value="Departamento residencia: " />
            <TextInput
              id="departamento_residencia"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.departamento_residencia"
              required
              disabled
            />
          </div>
          <div class="col-span-2">
            <InputLabel for="municipio_residencia" value="Municipio residencia: " />
            <TextInput
              id="municipio_residencia"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.municipio_residencia"
              required
              disabled
            />
          </div>

          <div class="col-span-1">
            <InputLabel for="genero" value="Sexo: " />
            <TextInput
              id="genero"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.genero"
              required
              disabled
            />
          </div>
          <div class="col-span-1">
            <InputLabel for="estado_civil" value="Estado civil: " />
            <TextInput
              id="estado_civil"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.estado_civil"
              required
              disabled
            />
          </div>

          <div class="col-span-2">
            <InputLabel for="profesion" value="Profesion: " />
            <TextInput
              id="profesion"
              type="text"
              class="mt-1 block w-full"
              v-model="persona.profesion"
              required
              disabled
            />
          </div>
        </article>
      </div>
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
