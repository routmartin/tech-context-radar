<template>
    <Head title="Sources" />
    <RadarAppShell current="sources" :today-count="summary.prioritySignals">
        <section class="page-hero sources-hero">
            <div>
                <span class="eyebrow">Source transparency</span>
                <h1 id="page-title">Sources</h1>
                <p class="lead">Trusted technical sources filtered into useful developer context.</p>
            </div>
            <aside class="hero-summary" aria-label="Source health summary">
                <span class="panel-kicker">Source health</span>
                <h2>{{ summary.sourcesScanned }} streams scanned. {{ summary.prioritySignals }} priority signals surfaced.</h2>
                <div class="health-grid">
                    <div class="health-cell"><span class="metric-label">Sources scanned today</span><strong class="metric-value">{{ summary.sourcesScanned }}</strong></div>
                    <div class="health-cell"><span class="metric-label">Updates collected</span><strong class="metric-value">{{ summary.updatesCollected }}</strong></div>
                    <div class="health-cell"><span class="metric-label">Low-impact filtered</span><strong class="metric-value">{{ summary.lowImpactFiltered }}</strong></div>
                    <div class="health-cell"><span class="metric-label">Priority signals</span><strong class="metric-value">{{ summary.prioritySignals }}</strong></div>
                </div>
            </aside>
        </section>

        <section class="screen-grid sources-layout">
            <div class="stack">
                <div class="glass-panel source-panel">
                    <div class="panel-head">
                        <div>
                            <span class="panel-kicker">Curated inputs</span>
                            <h2>Developer sources ranked for relevance, not volume.</h2>
                        </div>
                    </div>

                    <div class="source-filter-bar" role="group" aria-label="Filter sources by category">
                        <button class="filter-pill" type="button" :aria-pressed="activeCategory === 'all'" @click="activeCategory = 'all'">All</button>
                        <button
                            v-for="category in categories"
                            :key="category.id"
                            class="filter-pill"
                            type="button"
                            :aria-pressed="activeCategory === category.name"
                            @click="activeCategory = category.name"
                        >
                            {{ category.name }}
                        </button>
                    </div>

                    <div id="source-library" class="stack" aria-live="polite">
                        <section v-for="group in groupedSources" :key="group.category" class="category-section">
                            <div class="category-label"><h3>{{ group.title }}</h3><span>{{ group.sources.length }} sources</span></div>
                            <div class="stack">
                                <article
                                    v-for="source in group.sources"
                                    :key="source.id"
                                    class="source-card"
                                    :class="{ 'is-selected': selectedSource?.id === source.id }"
                                    @click="selectedSource = source"
                                >
                                    <div class="source-top">
                                        <div class="source-name">
                                            <span class="trust-pill">{{ source.trustLevel }}</span>
                                            <strong>{{ source.name }}</strong>
                                        </div>
                                        <span class="source-score">{{ sourceScore(source) }}</span>
                                    </div>
                                    <div class="source-scan-detail">
                                        <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 6v6l4 2"></path><circle cx="12" cy="12" r="10"></circle></svg>{{ source.updatesToday }} updates today</span>
                                        <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>Scanned {{ source.lastScanned ?? 'recently' }}</span>
                                        <span><span class="status-dot" :class="statusClass(source.status)"></span>{{ source.status }}</span>
                                    </div>
                                </article>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <aside class="glass-panel side-panel source-panel" aria-label="Source detail preview">
                <span class="panel-kicker">Source detail</span>
                <div class="source-panel-detail">
                    <div v-if="!selectedSource" class="source-select-default">
                        <strong>Select a source</strong>
                        <span>Click any source card to inspect its recent signals, trust metadata, and noise filter log.</span>
                    </div>
                    <template v-else>
                        <div class="source-detail-header">
                            <div>
                                <h3>{{ selectedSource.name }}</h3>
                                <span class="trust-pill" style="margin-top: 8px;">{{ selectedSource.trustLevel }}</span>
                            </div>
                            <span class="source-score">{{ sourceScore(selectedSource) }}</span>
                        </div>

                        <div class="source-detail-meta">
                            <div class="meta-row"><span class="meta-label">Category</span><span class="meta-value">{{ selectedSource.category.name }}</span></div>
                            <div class="meta-row"><span class="meta-label">Last scanned</span><span class="meta-value">{{ selectedSource.lastScanned ?? 'recently' }}</span></div>
                            <div class="meta-row"><span class="meta-label">Status</span><span class="meta-value">{{ selectedSource.status }}</span></div>
                            <div class="meta-row"><span class="meta-label">Noise filtered</span><span class="meta-value">{{ selectedSource.noiseFiltered }}</span></div>
                        </div>

                        <div class="signals-preview">
                            <h4>Recent signals</h4>
                            <div v-for="signal in selectedSource.recentSignals" :key="signal.slug" class="signal-preview-item">
                                <div class="spi-top">
                                    <strong>{{ signal.title }}</strong>
                                    <span>{{ signal.priorityScore }}</span>
                                </div>
                                <p>Priority signal surfaced from this source.</p>
                            </div>
                        </div>

                        <div class="noise-list">
                            <h4>Noise filter log</h4>
                            <div class="noise-entry"><span>Low-priority updates removed</span><span>{{ selectedSource.noiseFiltered }} filtered</span></div>
                            <div class="noise-entry"><span>Recent source activity</span><span>{{ selectedSource.updatesToday }} updates</span></div>
                        </div>
                    </template>
                </div>
            </aside>
        </section>
    </RadarAppShell>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import RadarAppShell from '../Components/RadarAppShell.vue';
import type { Category, Source } from '../types';

const props = defineProps<{
    categories: Category[];
    sources: Source[];
    summary: {
        sourcesScanned: number;
        updatesCollected: number;
        lowImpactFiltered: number;
        prioritySignals: number;
    };
}>();

const activeCategory = ref('all');
const selectedSource = ref<Source | null>(props.sources[0] ?? null);
const filteredSources = computed(() =>
    props.sources.filter((source) => activeCategory.value === 'all' || source.category.name === activeCategory.value),
);

const groupedSources = computed(() =>
    props.categories
        .map((category) => ({
            category: category.name,
            title: category.name === 'DevTools' ? 'Developer Tools' : category.name === 'AI' ? 'AI Labs' : category.name,
            sources: filteredSources.value.filter((source) => source.category.name === category.name),
        }))
        .filter((group) => group.sources.length > 0),
);

function statusClass(status: string) {
    if (status.toLowerCase() === 'healthy') return 'is-active';
    if (status.toLowerCase() === 'degraded') return 'is-paused';
    if (status.toLowerCase() === 'active') return 'is-active';
    if (status.toLowerCase() === 'paused') return 'is-paused';

    return 'is-idle';
}

function sourceScore(source: Source) {
    const baseScores: Record<string, number> = {
        primary: 96,
        trusted: 92,
        research: 88,
    };

    const match = Object.keys(baseScores).find((key) => source.trustLevel.toLowerCase().includes(key));

    return match ? baseScores[match] : 90;
}
</script>

<style>
.sources-hero h1 { max-width: 10ch; }
.health-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 18px;
}
.health-cell {
    min-height: 88px;
    padding: 15px;
    border: 1px solid var(--border);
    border-radius: 22px;
    background: rgba(2, 4, 3, 0.34);
    transition: border-color var(--base) var(--ease), background var(--base) var(--ease), transform var(--fast) var(--ease);
}
.health-cell:hover {
    border-color: var(--border-strong);
    background:
        radial-gradient(circle at 18% 12%, rgba(156, 255, 183, 0.105), transparent 30%),
        rgba(2, 4, 3, 0.42);
    transform: translateY(-2px);
}
.health-cell .metric-label {
    display: block;
    margin-bottom: 8px;
    color: var(--muted);
    line-height: 1.3;
}
.health-cell .metric-value {
    display: block;
    font-size: 26px;
    font-weight: 780;
    letter-spacing: -0.028em;
    line-height: 1;
}
.sources-layout { grid-template-columns: minmax(0, 1fr) 380px; }
.source-filter-bar { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
.category-section { margin-bottom: 22px; }
.category-section:last-child { margin-bottom: 0; }
.category-label { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 0 2px; }
.category-label::before {
    width: 8px;
    height: 8px;
    content: "";
    border-radius: 50%;
    background: var(--accent);
    box-shadow: 0 0 18px rgba(92, 255, 141, 0.38);
}
.category-label h3 { margin: 0; font-size: 15px; font-weight: 740; letter-spacing: -0.004em; }
.category-label span { margin-left: auto; color: var(--muted); font-size: 12px; font-weight: 620; }
.source-card {
    position: relative;
    overflow: hidden;
    padding: 18px;
    border: 1px solid var(--border);
    border-radius: 26px;
    background:
        linear-gradient(145deg, rgba(255, 255, 255, 0.09), rgba(255, 255, 255, 0.03) 38%, rgba(156, 255, 183, 0.045)),
        var(--surface);
    transition: transform var(--base) var(--ease), border-color var(--base) var(--ease), background var(--base) var(--ease), opacity var(--base) var(--ease);
    cursor: pointer;
}
.source-card:hover, .source-card.is-selected {
    border-color: var(--border-strong);
    background:
        radial-gradient(circle at 12% 0%, rgba(156, 255, 183, 0.13), transparent 36%),
        linear-gradient(145deg, rgba(156, 255, 183, 0.09), rgba(255, 255, 255, 0.035)),
        rgba(2, 4, 3, 0.48);
    transform: translateY(-2px);
}
.source-card.is-selected { box-shadow: 0 0 0 1.5px var(--accent), 0 12px 40px rgba(92, 255, 141, 0.12); }
.source-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; }
.source-name { display: grid; gap: 4px; min-width: 0; }
.source-name strong { overflow: hidden; color: var(--fg); font-size: 15.5px; font-weight: 720; letter-spacing: -0.006em; line-height: 1.25; text-overflow: ellipsis; white-space: nowrap; }
.source-name .trust-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    width: fit-content;
    color: var(--accent);
    font-size: 10.5px;
    font-weight: 760;
    letter-spacing: 0.078em;
    text-transform: uppercase;
}
.source-name .trust-pill::before {
    width: 6px;
    height: 6px;
    content: "";
    border-radius: 50%;
    background: var(--accent);
    box-shadow: 0 0 14px rgba(92, 255, 141, 0.36);
}
.source-score {
    min-width: 40px;
    height: 40px;
    display: grid;
    place-items: center;
    flex: 0 0 auto;
    border-radius: 50%;
    background:
        radial-gradient(circle, rgba(156, 255, 183, 0.18), transparent 30%),
        rgba(255, 255, 255, 0.06);
    border: 1px solid var(--border);
    color: var(--fg);
    font-family: var(--font-mono);
    font-size: 12px;
    font-weight: 780;
    letter-spacing: -0.01em;
}
.source-scan-detail { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 16px; margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border); }
.source-scan-detail span { display: inline-flex; align-items: center; gap: 6px; color: var(--muted); font-size: 12px; font-weight: 620; }
.source-scan-detail span svg { width: 14px; height: 14px; stroke-width: 1.8; color: var(--meta); }
.status-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; flex: 0 0 auto; }
.status-dot.is-active { background: var(--accent-strong); box-shadow: 0 0 12px rgba(92, 255, 141, 0.42); }
.status-dot.is-idle { background: var(--muted); }
.status-dot.is-paused { background: var(--warn); }
.source-panel { overflow: hidden; }
.source-panel-detail { display: grid; gap: 18px; }
.source-detail-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; }
.source-detail-header h3 { margin: 0; color: var(--fg); font-size: 18px; font-weight: 740; letter-spacing: -0.01em; line-height: 1.2; }
.source-detail-meta { display: grid; gap: 6px; margin-top: 14px; padding: 14px; border: 1px solid var(--border); border-radius: 20px; background: rgba(255, 255, 255, 0.045); }
.source-detail-meta .meta-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.source-detail-meta .meta-row + .meta-row { padding-top: 8px; border-top: 1px solid var(--border); }
.source-detail-meta .meta-label { color: var(--muted); font-size: 12px; }
.source-detail-meta .meta-value { color: var(--fg-2); font-size: 13px; font-weight: 650; }
.signals-preview { display: grid; gap: 10px; }
.signals-preview h4, .noise-list h4 { margin: 0; color: var(--fg); font-size: 14px; font-weight: 720; letter-spacing: -0.004em; }
.signal-preview-item { padding: 13px; border: 1px solid var(--border); border-radius: 18px; background: rgba(2, 4, 3, 0.32); }
.signal-preview-item .spi-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 6px; }
.signal-preview-item .spi-top strong { color: var(--fg); font-size: 13px; font-weight: 690; letter-spacing: -0.004em; }
.signal-preview-item .spi-top span { color: var(--meta); font-family: var(--font-mono); font-size: 11px; font-weight: 600; }
.signal-preview-item p { margin: 0; color: var(--muted); font-size: 12.5px; line-height: 1.45; }
.noise-list { display: grid; gap: 8px; }
.noise-entry { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 12px; border: 1px solid var(--border); border-radius: 16px; background: rgba(255, 255, 255, 0.035); }
.noise-entry span:first-child { color: var(--fg-2); font-size: 12.5px; font-weight: 620; }
.noise-entry span:last-child { color: var(--meta); font-size: 11px; font-weight: 600; white-space: nowrap; }
.source-select-default { display: grid; gap: 12px; padding: 24px; border: 1px dashed var(--border-strong); border-radius: 26px; background: rgba(156, 255, 183, 0.035); text-align: center; }
.source-select-default strong { display: block; color: var(--fg); font-size: 16px; }
.source-select-default span { color: var(--muted); font-size: 13px; line-height: 1.48; }
@media (max-width: 960px) {
    .sources-layout { grid-template-columns: 1fr; }
    .health-grid { grid-template-columns: 1fr 1fr; }
    .health-cell { min-height: auto; display: flex; align-items: center; justify-content: space-between; }
    .health-cell .metric-value { font-size: 22px; }
    .health-cell .metric-label { margin-bottom: 0; }
    .source-detail-header { flex-direction: column; }
    .source-scan-detail { flex-direction: column; align-items: flex-start; }
}
@media (max-width: 420px) {
    .health-grid { grid-template-columns: 1fr; }
}
</style>
