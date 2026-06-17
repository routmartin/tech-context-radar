<template>
    <Head title="Saved Intelligence" />
    <RadarAppShell current="saved" :today-count="3" v-slot="{ notify }">
        <section class="page-hero saved-hero">
            <div>
                <span class="eyebrow">Saved intelligence</span>
                <h1 id="page-title">Saved Intelligence</h1>
                <p class="lead">Your personal library of important tech context.</p>
            </div>
            <aside class="hero-summary" aria-label="Saved summary">
                <span class="panel-kicker">Library signal</span>
                <h2>{{ signals.length }} saved briefs. {{ totalReadingTime }} minutes of reading preserved.</h2>
                <div class="summary-grid">
                    <div class="summary-cell"><span class="metric-label">Unread</span><strong class="metric-value">{{ unreadCount }}</strong></div>
                    <div class="summary-cell"><span class="metric-label">Domains</span><strong class="metric-value">{{ activeDomainCount }}</strong></div>
                </div>
            </aside>
        </section>

        <section class="screen-grid saved-screen">
            <div class="stack">
                <div class="glass-panel saved-library-panel">
                    <div class="panel-head saved-panel-head">
                        <div>
                            <span class="panel-kicker">Intelligence library</span>
                            <h2>Saved signals with context, not bookmark clutter.</h2>
                        </div>
                        <Link class="secondary-action" href="/today">View Today’s Radar</Link>
                    </div>

                    <div class="saved-controls" aria-label="Saved intelligence controls">
                        <label class="search-shell">
                            <span class="search-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"></circle><path d="m16.5 16.5 4 4"></path></svg>
                            </span>
                            <span class="sr-only">Search saved intelligence</span>
                            <input
                                id="saved-search"
                                v-model="search"
                                class="search-field"
                                type="search"
                                placeholder="Search saved signals, sources, or impact..."
                                autocomplete="off"
                            />
                        </label>

                        <div class="filter-row" role="group" aria-label="Filter saved intelligence by category">
                            <button class="filter-pill" type="button" :aria-pressed="activeCategory === 'all'" @click="activeCategory = 'all'">All</button>
                            <button
                                v-for="category in displayCategories"
                                :key="category.id"
                                class="filter-pill"
                                type="button"
                                :aria-pressed="activeCategory === category.name"
                                @click="activeCategory = category.name"
                            >
                                {{ category.name }}
                            </button>
                        </div>
                    </div>

                    <div v-if="groupedSignals.length" id="saved-library" class="saved-library" aria-live="polite">
                        <section v-for="group in groupedSignals" :key="group.label" class="saved-group" data-saved-group>
                            <div class="group-heading">
                                <h3>{{ group.label }}</h3>
                                <span>{{ group.description }}</span>
                            </div>

                            <article
                                v-for="signal in group.signals"
                                :key="signal.id"
                                class="saved-card"
                                :data-category="signal.category.name"
                                :data-title="signal.title"
                                :data-summary="signal.summary"
                            >
                                <div class="saved-card-top">
                                    <span class="category-badge">{{ signal.category.name }}</span>
                                    <div class="saved-meta">
                                        <span>{{ savedDateLabel(signal, group.label) }}</span>
                                        <span>{{ signal.readTimeMinutes }} min read</span>
                                    </div>
                                </div>
                                <h3><Link :href="signal.url">{{ signal.title }}</Link></h3>
                                <p>{{ signal.summary }}</p>
                                <div class="why-card">
                                    <strong>Why it matters</strong>
                                    <span>{{ signal.whyItMatters }}</span>
                                </div>
                                <div class="saved-card-actions">
                                    <Link class="secondary-action" :href="signal.url">Open brief</Link>
                                    <button class="remove-action" type="button" @click="removeSignal(signal, notify)">Remove</button>
                                </div>
                            </article>
                        </section>
                    </div>

                    <div v-else id="saved-empty" class="empty-state">
                        <strong>{{ signals.length ? 'No saved signals match this view.' : 'No saved signals yet.' }}</strong>
                        <p>
                            {{ signals.length
                                ? 'Try a different category or search term to recover more context.'
                                : 'Save important updates from Today’s Radar to build your intelligence library.' }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </RadarAppShell>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import RadarAppShell from '../Components/RadarAppShell.vue';
import type { Category, Signal } from '../types';

const props = defineProps<{
    categories: Category[];
    signals: Signal[];
}>();

const search = ref('');
const activeCategory = ref('all');

const displayCategories = computed(() => props.categories.filter((category) => category.name !== 'Blogs'));
const filteredSignals = computed(() => {
    const query = search.value.trim().toLowerCase();

    return props.signals.filter((signal) => {
        const categoryMatch = activeCategory.value === 'all' || signal.category.name === activeCategory.value;
        const searchable = [signal.title, signal.summary, signal.whyItMatters, signal.category.name, signal.source?.name ?? '']
            .join(' ')
            .toLowerCase();

        return categoryMatch && (!query || searchable.includes(query));
    });
});

const groupedSignals = computed(() => [
    { label: 'Recent', description: 'Saved today', signals: filteredSignals.value.slice(0, 2) },
    { label: 'This week', description: 'Useful for current planning', signals: filteredSignals.value.slice(2, 4) },
    { label: 'Older', description: 'Reference context', signals: filteredSignals.value.slice(4) },
].filter((group) => group.signals.length > 0));

const totalReadingTime = computed(() => props.signals.reduce((total, signal) => total + signal.readTimeMinutes, 0));
const unreadCount = computed(() => props.signals.filter((signal) => !signal.isRead).length);
const activeDomainCount = computed(() => new Set(props.signals.map((signal) => signal.category.name)).size);

function savedDateLabel(signal: Signal, groupLabel: string) {
    if (groupLabel === 'Recent') return 'Saved today';
    return `Saved ${signal.publishedDate ?? 'recently'}`;
}

function removeSignal(signal: Signal, notify: (message: string) => void) {
    router.delete(`/signals/${signal.slug}/save`, {
        preserveScroll: true,
        onSuccess: () => notify('Saved signal removed.'),
    });
}
</script>
