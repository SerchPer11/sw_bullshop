<template>
    <div class="flex flex-col md:flex-row items-center gap-4 mb-4">
        <h2 v-if="props.title" class="text-2xl font-bold text-bull-blue">{{ props.title }}</h2>
        <div class="h-1 md:flex-grow bg-bull-blue/10"></div>
    </div>

</template>

<script setup>
const props = defineProps({
    title: {
        type: String,
        required: false
    },
});
</script>