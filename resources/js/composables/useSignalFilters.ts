import { computed, ref } from 'vue';
import type { Signal } from '../types';

export function useSignalFilters(signals: () => Signal[]) {
    const activeCategory = ref('All');
    const search = ref('');

    const filteredSignals = computed(() => {
        const query = search.value.trim().toLowerCase();

        return signals().filter((signal) => {
            const matchesCategory = activeCategory.value === 'All' || signal.category.name === activeCategory.value;
            const matchesSearch =
                query.length === 0 ||
                signal.title.toLowerCase().includes(query) ||
                signal.summary.toLowerCase().includes(query) ||
                signal.developerImpact.toLowerCase().includes(query);

            return matchesCategory && matchesSearch;
        });
    });

    return { activeCategory, search, filteredSignals };
}
