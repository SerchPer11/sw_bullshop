<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useSurveyProcessor } from '../Composables/useSurveyProcessor.js';
import NeoInput from '@/components/Common/NeoInput.vue';
import SurveyRepeater from '@/components/Survey/Types/QuestionRepeater.vue';
import { Button } from '@/components/ui/button';
import FormTitles from '@/components/Common/FormTitles.vue';
import { HeartHandshake, Mailbox, ArrowRight } from 'lucide-vue-next';
import Separator from '@/components/Common/Separator.vue';
import NeoConfirmModal from '@/components/Common/NeoConfirmModal.vue';
import DOMPurify from 'dompurify';
import VideoContainer from '@/Pages/Survey/PreRegistration/Components/VideoContainer.vue';

const LucideIcons = {
    HeartHandshake,
    Mailbox,
    ArrowRight
};


const surveyHead = ref(null);

function scrollToSurvey() {
    surveyHead.value?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

const props = defineProps({
    survey: {
        type: Object,
        required: true,
    },
    source: {
        type: String,
        required: true,
    },
});

const { form,
    isSending,
    showModal,
    canSubmit,
    handleFieldValidity,
    handleSubmit,
    mapQuestionTypeToNeoInput,
    questionUiConfig,
    parseQuestionText, } = useSurveyProcessor(props);

</script>

<template>

    <Head title="Unete a la familia" />

    <div class="min-h-screen bg-bull-cream py-6 px-4 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-3xl mx-auto">

            <div class="bg-bull-neon border-4 border-bull-blue p-8 mb-8 shadow-[8px_8px_0px_0px_rgba(0,39,49,1)]">
                <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-bull-blue mb-4">
                    {{ survey.title }}
                </h1>
            </div>

            <VideoContainer @video-ended="scrollToSurvey" v-if="!(form.wasSuccessful)" />

            <div id="surveyHead" ref="surveyHead"
                class="bg-bull-neon border-4 border-bull-blue p-8 mb-8 shadow-[8px_8px_0px_0px_rgba(0,39,49,1)]">
                <!-- Renderizamos la descripción de la encuesta con v-html y DOMPurify para sanitizar el contenido -->
                <div v-html="DOMPurify.sanitize(survey.description)"
                    class="text-md text-bull-blue tracking-wide text-justify"></div>
            </div>



            <div v-if="form.wasSuccessful"
                class="text-center py-20 bg-white border-4 border-bull-blue p-6 md:p-10 shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] space-y-10">
                <h2 class="text-5xl font-black text-bull-blue uppercase mb-4">¡Ya eres parte de la familia!</h2>
                <p class="text-xl font-bold">Revisa tu correo, tenemos un regalito para ti.</p>
                <!-- Agregar boton con texto "Síguenos en Instagram" -->
            </div>

            <form v-else @submit.prevent="handleSubmit"
                class="bg-white border-4 border-bull-blue p-6 md:p-10 shadow-[8px_8px_0px_0px_rgba(0,39,49,1)] space-y-10">

                <div class="space-y-6">
                    <div v-for="(question, index) in survey.questions" :key="question.id" class="w-full">
                        <Separator v-if="question.type === 'title' && index > 0" class="my-4" />
                        <FormTitles v-if="question.type === 'title'" :title="question.question"
                            :icon="LucideIcons[question.icon]" />

                        <SurveyRepeater v-else-if="question.type === 'repeater'" :question="question"
                            v-model="form.responses[question.id]" :error="form.errors"
                            :error-prefix="`responses.${question.id}`" :loading="isSending"
                            :plusButtonText="'Agregar bulldog'" />

                        <NeoInput v-else :id="`survey-question-${question.id}`"
                            :type="mapQuestionTypeToNeoInput(question.type)"
                            :label="parseQuestionText(question.question)" :required="question.is_required"
                            :options="question.options" v-model="form.responses[question.id]"
                            :error="form.errors[`responses.${question.id}`]" :placeholder="question.placeholder"
                            :validation="questionUiConfig(question).validation || ''"
                            :formatter="questionUiConfig(question).formatter || ''"
                            :max-selections="questionUiConfig(question).maxSelections || null"
                            :input-attrs="questionUiConfig(question).inputAttrs || {}"
                            @validity-change="valid => handleFieldValidity(question.id, valid)" />

                    </div>
                </div>
                <NeoConfirmModal v-model:open="showModal" title="¿Estás listo para ser parte de BullShop?"
                    description="Una vez enviado, serás parte del club BullShop" confirmText="Sí, quiero unirme"
                    cancelText="Cancelar" variant="success" :loading="isSending" @confirm="handleSubmit">
                    <template #trigger>
                        <Button :disabled="!canSubmit" size="xl" class="w-full text-white" variant="neo-blue"
                            :loading="isSending">
                            ¡QUIERO SER PARTE DE BULLSHOP CLUB!
                            <LucideIcons.ArrowRight />
                        </Button>
                    </template>
                </NeoConfirmModal>
            </form>
        </div>
    </div>

</template>