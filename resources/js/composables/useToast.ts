import { ref } from 'vue';

export function useToast() {
    const message = ref<string | null>(null);
    let timer: number | undefined;

    function showToast(nextMessage: string) {
        message.value = nextMessage;
        window.clearTimeout(timer);
        timer = window.setTimeout(() => {
            message.value = null;
        }, 3200);
    }

    return { message, showToast };
}
