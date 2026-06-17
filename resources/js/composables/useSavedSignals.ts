import { router } from '@inertiajs/vue3';
import type { Signal } from '../types';

export function useSavedSignals() {
    function toggleSaved(signal: Signal) {
        if (signal.isSaved) {
            router.delete(`/signals/${signal.slug}/save`, { preserveScroll: true });
            return;
        }

        router.post(`/signals/${signal.slug}/save`, {}, { preserveScroll: true });
    }

    function markRead(signal: Signal) {
        router.post(`/signals/${signal.slug}/read`, {}, { preserveScroll: true });
    }

    return { toggleSaved, markRead };
}
