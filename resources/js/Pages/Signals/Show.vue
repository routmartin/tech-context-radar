<template>
    <Head :title="signal.title" />
    <RadarAppShell current="today" :today-count="relatedSignals.length + 1" v-slot="{ notify }">
        <Link class="back-link" href="/today">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"></path></svg>
            Back to Today
        </Link>

        <section class="screen-grid detail-layout">
            <div class="stack">
                <article class="glass-panel signal-header">
                    <div class="badge-row" aria-label="Signal metadata">
                        <span class="brief-badge">{{ signal.category.name }}</span>
                        <span class="brief-badge is-priority">{{ priorityLabel }}</span>
                        <span class="brief-badge">{{ signal.readTimeMinutes }} min read</span>
                    </div>
                    <span class="eyebrow">Priority signal</span>
                    <h1 id="signal-title" class="detail-title">{{ signal.title }}</h1>
                    <p class="summary-block">{{ signal.summary }}</p>
                </article>

                <section class="glass-panel brief-section" aria-labelledby="why-title">
                    <div>
                        <span class="section-icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 3v18M3 12h18"></path><circle cx="12" cy="12" r="7"></circle></svg></span>
                        <h2 id="why-title">Why it matters</h2>
                    </div>
                    <p>{{ signal.whyItMatters }}</p>
                </section>

                <section class="glass-panel brief-section" aria-labelledby="impact-title">
                    <div>
                        <span class="section-icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 18V6M10 18v-7M16 18v-4M22 18H2"></path></svg></span>
                        <h2 id="impact-title">Developer impact</h2>
                    </div>
                    <ul class="brief-list">
                        <li v-for="item in developerImpactPoints" :key="item">{{ item }}</li>
                    </ul>
                </section>

                <section class="glass-panel brief-section" aria-labelledby="changed-title">
                    <div>
                        <span class="section-icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 7h12M6 12h12M6 17h7"></path></svg></span>
                        <h2 id="changed-title">What changed</h2>
                    </div>
                    <ul class="brief-list">
                        <li v-for="item in whatChangedPoints" :key="item">{{ item }}</li>
                    </ul>
                </section>

                <section class="glass-panel brief-section" aria-labelledby="next-title">
                    <div>
                        <span class="section-icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 12h14M13 6l6 6-6 6"></path></svg></span>
                        <h2 id="next-title">What to do next</h2>
                    </div>
                    <p>{{ signal.recommendedAction }}</p>
                </section>

                <section class="glass-panel brief-section" aria-labelledby="care-title">
                    <div>
                        <span class="section-icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-8 0v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                        <h2 id="care-title">Who should care</h2>
                    </div>
                    <div class="care-grid">
                        <div class="care-card"><strong>Solo builders</strong><span>Useful for compressing repetitive project setup and implementation chores.</span></div>
                        <div class="care-card"><strong>Team leads</strong><span>Worth testing for code review prep, migration work, and operational cleanup.</span></div>
                        <div class="care-card"><strong>Tooling teams</strong><span>A signal that developer workflows are moving toward persistent AI workspaces.</span></div>
                    </div>
                </section>
            </div>

            <aside class="glass-panel side-panel" aria-label="Signal actions and references">
                <span class="panel-kicker">Radar score</span>
                <h2>{{ signal.priorityScore }} / 100</h2>
                <p>High confidence because the signal has product relevance, developer workflow impact, and near-term testability.</p>

                <div class="confidence-meter" :aria-label="`Confidence ${signal.priorityScore} percent`">
                    <span class="metric-label">Confidence</span>
                    <div class="meter-track"><div class="meter-fill" :style="{ width: `${signal.priorityScore}%` }"></div></div>
                </div>

                <div class="action-panel" style="margin-top: 18px;">
                    <button class="primary-action" type="button" @click="toggleSaved(notify)">
                        {{ signal.isSaved ? 'Remove saved' : 'Save' }}
                    </button>
                    <button class="secondary-action" type="button" @click="markRead(notify)">
                        {{ signal.isRead ? 'Read' : 'Mark as read' }}
                    </button>
                    <button class="secondary-action" type="button" @click="shareSignal(notify)">Share</button>
                </div>

                <div class="source-list" aria-label="Source references">
                    <span class="panel-kicker" style="margin-top: 18px;">Source references</span>
                    <div class="source-ref">
                        <div>
                            <strong>{{ signal.source?.name ?? 'Primary source' }}</strong>
                            <span>{{ signal.source?.trustLevel ?? 'Trusted source' }} · {{ signal.publishedAt ?? signal.publishedDate ?? 'recently' }}</span>
                        </div>
                        <Link href="/sources">Open</Link>
                    </div>
                    <div class="source-ref">
                        <div>
                            <strong>Additional coverage</strong>
                            <span>{{ signal.sourceCount }} sources scanned for this brief</span>
                        </div>
                        <Link href="/sources">Open</Link>
                    </div>
                </div>

                <div class="related-list" aria-label="Related signals">
                    <span class="panel-kicker" style="margin-top: 18px;">Related signals</span>
                    <Link v-for="related in relatedSignals" :key="related.id" class="related-card" :href="related.url">
                        <strong>{{ related.title }}</strong>
                        <span>{{ related.summary }}</span>
                    </Link>
                </div>
            </aside>
        </section>
    </RadarAppShell>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import RadarAppShell from '../../Components/RadarAppShell.vue';
import type { Signal } from '../../types';

const props = defineProps<{
    signal: Signal;
    relatedSignals: Signal[];
}>();

const priorityLabel = computed(() => (props.signal.priorityScore >= 85 ? 'High priority' : 'Watch closely'));
const developerImpactPoints = computed(() => toBulletPoints(props.signal.developerImpact));
const whatChangedPoints = computed(() => [
    props.signal.summary,
    `Priority score: ${props.signal.priorityScore}.`,
    `${props.signal.sourceCount} sources contributed context to this brief.`,
]);

function toBulletPoints(value: string) {
    return value
        .split(/(?<=[.!?])\s+/)
        .map((item) => item.trim())
        .filter(Boolean);
}

function toggleSaved(notify: (message: string) => void) {
    if (props.signal.isSaved) {
        router.delete(`/signals/${props.signal.slug}/save`, {
            preserveScroll: true,
            onSuccess: () => notify('Signal removed from your knowledge stack.'),
        });

        return;
    }

    router.post(`/signals/${props.signal.slug}/save`, {}, {
        preserveScroll: true,
        onSuccess: () => notify('Signal saved to your knowledge stack.'),
    });
}

function markRead(notify: (message: string) => void) {
    router.post(`/signals/${props.signal.slug}/read`, {}, {
        preserveScroll: true,
        onSuccess: () => notify('Signal marked as read.'),
    });
}

async function shareSignal(notify: (message: string) => void) {
    const url = `${window.location.origin}${props.signal.url}`;

    try {
        if (navigator.share) {
            await navigator.share({ title: props.signal.title, url });
            notify('Share sheet opened.');
            return;
        }

        if (navigator.clipboard) {
            await navigator.clipboard.writeText(url);
            notify('Signal link copied.');
            return;
        }
    } catch {
        //
    }

    notify('Signal ready to share.');
}
</script>
