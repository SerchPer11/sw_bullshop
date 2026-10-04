import { useSending } from '@/Hooks/useLoading';
import { useForm } from '@inertiajs/vue3';
import { computed, ref, reactive } from 'vue';
import { error422 } from '@/lib/alerts';

export function useSurveyProcessor(props) {
    const survey = props.survey;
    const source = props.source;
    const { setSending } = useSending();
    const initialResponses = {};

    const showModal = ref(false);

    const hasOptions = (options) => {
        if (Array.isArray(options)) return options.length > 0;
        if (typeof options !== 'string') return false;

        try {
            const parsedOptions = JSON.parse(options);
            return Array.isArray(parsedOptions) && parsedOptions.length > 0;
        } catch {
            return false;
        }
    };

    survey.questions.forEach(question => {
        initialResponses[question.id] = question.type === 'repeater'
            || (question.type === 'checkbox' && hasOptions(question.options))
            ? []
            : '';
    });

    const form = useForm({
        survey_id: survey.id,
        source: source,
        responses: initialResponses,
    });

    const submitSurvey = () => {
        form.post(route('preregistration.store'), {
            preserveScroll: true,
            onBefore: () => {
                setSending(true);
            },
            onError: () => {
                error422();
                showModal.value = false;
                setSending(false);
                document.getElementById('surveyHead').scrollIntoView({ behavior: 'smooth' });
            },
            onFinish: () => {
                showModal.value = false;
                document.getElementById('surveyHead').scrollIntoView({ behavior: 'smooth' });
                clearTimeout(timeoutId);
                setSending(false);
            }
        });

        const timeoutId = setTimeout(() => {
            if (form.processing) {
                form.cancel();
            }
        }, 5000);
    };

    const fieldValidity = reactive({});
    const hasAttemptedSubmit = ref(false);

    const hasInvalidFields = computed(() => Object.values(fieldValidity).some(valid => valid === false));
    const hasServerErrors = computed(() => Object.keys(form.errors).length > 0);
    const canSubmit = computed(() => !hasAttemptedSubmit.value || (!hasInvalidFields.value && !hasServerErrors.value));

    const handleFieldValidity = (questionId, valid) => {
        fieldValidity[questionId] = valid;

        if (valid) form.clearErrors(`responses.${questionId}`);
    };

    const handleSubmit = () => {
        hasAttemptedSubmit.value = true;
        submitSurvey();

    };

    const mapQuestionTypeToNeoInput = (dbType) => {
        const map = {
            'dropdown': 'select',
            'multiple_choice': 'radio',
        };
        return map[dbType] || dbType;
    };

    const questionUiConfig = (question) => question.ui_config || {};

    const parseQuestionText = (text) => {
        if (!text) return '';

        return text.replace(/\{\{(.*?)\}\}/g, (match, rawCode) => {
            const code = rawCode.trim();

            const targetQuestion = props.survey.questions?.find(q => q.code == code);

            if (targetQuestion) {
                const answer = form.responses[targetQuestion.id];
                if (answer) return answer;
            }

            return 'tu bulldog';
        });
    };

    return {
        form,
        submitSurvey,
        isSending: computed(() => form.processing),
        submitSurvey,
        showModal,
        canSubmit,
        handleFieldValidity,
        handleSubmit,
        mapQuestionTypeToNeoInput,
        questionUiConfig,
        parseQuestionText,
    };
}