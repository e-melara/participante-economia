<template>
    <div class="container w-full">
    <div class="grid grid-cols-3 gap-4">
      <div class="col-span-2">
        <article class="grid grid-cols-4 gap-4">
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
      <div>
        <figure v-if="persona.photo">
          <img :src="persona.photo" alt="imagen del usuario" class="object-contain w-full" />
        </figure>
      </div>
    </div>
  </div>
</template>

<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
const {persona} = defineProps({
  persona: Object,
});
</script>
