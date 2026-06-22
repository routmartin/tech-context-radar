<template>
    <aside class="glass-card brief-dashboard" aria-label="Today's AI intelligence brief">
        <div class="glass-header brief-panel-header">
            <div>
                <span class="glass-kicker">Today's priority stack</span>
                <h2>Five signals ranked by developer impact.</h2>
            </div>
            <div class="status-ring" :aria-label="`Radar confidence ${briefing.confidenceScore} percent`">
                <span>{{ briefing.confidenceScore }}%</span>
            </div>
        </div>

        <div class="brief-progress">
            <div>
                <span>Noise filtering complete</span>
                <strong>{{ briefing.lowImpactFiltered }} low-impact updates removed from your queue</strong>
            </div>
            <span class="mini-score">{{ briefing.prioritySignals }}</span>
        </div>

        <div class="signal-stack">
            <Link
                v-for="(signal, index) in signals"
                :key="signal.id"
                class="signal"
                :class="{ 'is-active': index === 0 }"
                :href="signal.url"
                :aria-label="`Open signal brief: ${signal.title}`"
            >
                <div class="signal-label">
                    <span>{{ signal.category.name }}</span>
                    <span class="signal-time">{{ signal.publishedAt ?? "recently" }}</span>
                </div>
                <h3>{{ signal.title }}</h3>
                <p>Developer impact: {{ signal.developerImpact }}</p>
                <span class="signal-action">
                    {{ index === 0 ? "Open signal brief" : signal.recommendedAction }}
                </span>
            </Link>
        </div>
    </aside>
</template>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import type { Briefing, Category, Signal } from "../../types";

defineProps<{
    briefing: Briefing;
    categories: Category[];
    signals: Signal[];
}>();
</script>
