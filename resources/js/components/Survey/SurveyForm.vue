<template>
  <div v-for="question in survey.questions" :key="question.id" class="mb-8">
    
    <SurveyRepeater 
      v-if="question.type === 'repeater'"
      :question="question"
      v-model="form.responses[question.id]"
      :error="form.errors[`responses.${question.id}`]"
    />

    <NeoInput
      v-else
      :type="mapQuestionTypeToNeoInput(question.type)"
      :label="question.question"
      :required="question.is_required"
      :options="question.options"
      v-model="form.responses[question.id]"
      :error="form.errors[`responses.${question.id}`]"
    />

  </div>
</template>

<script setup>
import NeoInput from '@/components/Common/NeoInput.vue';
import SurveyRepeater from './SurveyRepeater.vue';

// ... (configuración del form)

const mapQuestionTypeToNeoInput = (dbType) => {
    if (dbType === 'dropdown') return 'select';
    return dbType; 
};
</script>