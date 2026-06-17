<template>
    <GlassPanel class="radar-panel">
        <div class="content-stack">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Today’s Radar Preview</p>
                    <h2 class="section-title" style="margin-top: 14px">One brief shows what changed, why it matters, and what to do next.</h2>
                </div>
                <span class="priority-badge">{{ briefing.confidenceScore }}% confidence</span>
            </div>
            <div class="metric-grid">
                <MetricCard label="AI filtered the noise" :value="`${briefing.confidenceScore}%`" />
                <MetricCard label="Signals" :value="briefing.prioritySignals" />
                <MetricCard label="Low-impact removed" :value="briefing.lowImpactFiltered" />
            </div>
            <div class="filter-row">
                <CategoryPill v-for="category in categories.slice(0, 6)" :key="category.id" :label="category.name" />
            </div>
            <SignalStack :signals="signals.slice(0, 2)" @save="$emit('save', $event)" />
        </div>
    </GlassPanel>
</template>

<script setup lang="ts">
import CategoryPill from './CategoryPill.vue';
import GlassPanel from './GlassPanel.vue';
import MetricCard from './MetricCard.vue';
import SignalStack from './SignalStack.vue';
import type { Briefing, Category, Signal } from '../types';

defineProps<{
    briefing: Briefing;
    categories: Category[];
    signals: Signal[];
}>();

defineEmits<{ save: [signal: Signal] }>();
</script>
