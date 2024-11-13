<script setup>
const props = defineProps({
    message: String
});
</script>

<template>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2" role="alert">
        <span class="block sm:inline">{{ props.message }}</span>
    </div>
</template>
