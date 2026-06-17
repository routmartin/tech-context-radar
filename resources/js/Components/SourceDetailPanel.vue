<template>
    <GlassPanel>
        <div class="content-stack">
            <div>
                <p class="eyebrow">Source detail</p>
                <h2 class="section-title" style="margin-top: 12px">{{ source.name }}</h2>
                <p class="muted">{{ source.trustLevel }} trust · {{ source.noiseFiltered }} low-impact updates filtered</p>
            </div>
            <div class="content-stack" style="gap: 10px">
                <p class="meta">Recent detected signals</p>
                <Link v-for="signal in source.recentSignals" :key="signal.slug" class="source-card" :href="signal.url">
                    <strong>{{ signal.title }}</strong>
                    <span class="meta">{{ signal.priorityScore }} priority</span>
                </Link>
                <p v-if="source.recentSignals.length === 0" class="muted">No priority signals from this source today.</p>
            </div>
        </div>
    </GlassPanel>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GlassPanel from './GlassPanel.vue';
import type { Source } from '../types';

defineProps<{ source: Source }>();
</script>
