<template>
    <div class="flex items-center gap-4 mb-4">
        <h2 v-if="props.title" class="text-2xl font-bold text-bull-blue">{{ props.title }}</h2>
        <div class="h-1 flex-grow bg-bull-blue/10"></div>
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