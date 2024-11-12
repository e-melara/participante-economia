<script setup>
const props = defineProps({
    message: String
});
</script>

<template>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2" role="alert">
        <strong class="">¡Error!</strong>
        <span class="block font-bold sm:inline"> &empty; {{ props.message }}</span>
    </div>
</template>
