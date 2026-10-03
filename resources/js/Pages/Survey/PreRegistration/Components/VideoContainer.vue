<script setup>
const emit = defineEmits(['video-ended']);

function handleVideoEnded() {
    emit('video-ended');
}
</script>

<template>
    <div class="mb-6">
        <video autoplay controls playsinline @ended="handleVideoEnded"
            class="h-[calc(100vh-50px)] !sm:w-full object-cover lg:max-w-3xl mx-auto border-4 border-bull-blue shadow-[8px_8px_0px_0px_rgba(0,39,49,1)]">
            <source src="/Video/PreRegister/preregistervideo.webm" type="video/mp4"
           >
            <source src="/Video/PreRegister/preregistervideo.webm" type="video/webm">
            Tu navegador no soporta el formato de video.
        </video>
    </div>
</template>