<template>
    <section class="hero-copy">
        <span class="eyebrow">Today's AI brief</span>
        <h1 id="page-title">AI and tech updates worth your attention</h1>
        <p class="lead">A short daily radar of the important AI and developer shifts, ranked by impact and stripped of noise.</p>

        <div class="hero-actions" aria-label="Primary actions">
            <Link class="primary-action" href="/today">
                Open Today's Radar
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6"></path>
                </svg>
            </Link>
        </div>

        <div class="value-points" aria-label="Key benefits">
            <span class="value-chip">AI and tech trend detection</span>
            <span class="value-chip">Developer impact summaries</span>
            <span class="value-chip">Actionable context, not headlines</span>
        </div>

        <div class="micro-metrics" aria-label="Radar summary">
            <div class="metric"><span class="metric-label">Daily briefing</span><strong class="metric-value">{{ briefing.readingTime }}m</strong></div>
            <div class="metric"><span class="metric-label">Priority signals</span><strong class="metric-value">{{ briefing.prioritySignals }}</strong></div>
            <div class="metric"><span class="metric-label">Noise filtered</span><strong class="metric-value">{{ briefing.lowImpactFiltered }}</strong></div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Briefing } from '../../types';

defineProps<{
    briefing: Briefing;
}>();
</script>
