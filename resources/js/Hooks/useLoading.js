import { ref } from "vue";

export const useLoading = () => {
    const isLoading = ref(false);

    const setLoading = (value) => {
        isLoading.value = value;
    };

    return {
        isLoading,
        setLoading
    }
};

export const useSending = () => {
    const isSending = ref(false);

    const setSending = (value) => {
        isSending.value = value;
    };

    return {
        isSending,
        setSending
    }
}