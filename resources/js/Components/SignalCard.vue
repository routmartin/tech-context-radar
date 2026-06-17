<template>
    <article class="signal-card" :class="{ 'is-read': signal.isRead }">
        <div class="signal-topline">
            <div class="content-stack" style="gap: 8px">
                <div class="filter-row">
                    <CategoryPill :label="signal.category.name" />
                    <span class="priority-badge">{{ signal.priorityScore }} priority</span>
                </div>
                <h3 class="signal-title">
                    <Link :href="signal.url">{{ signal.title }}</Link>
                </h3>
                <p class="meta">{{ signal.publishedAt ?? 'Today' }} · {{ signal.readTimeMinutes }} min read · {{ signal.sourceCount }} sources</p>
            </div>
        </div>
        <p class="muted">{{ signal.summary }}</p>
        <div class="two-col">
            <div>
                <p class="meta">Why it matters</p>
                <p>{{ signal.whyItMatters }}</p>
            </div>
            <div>
                <p class="meta">Developer impact</p>
                <p>{{ signal.developerImpact }}</p>
            </div>
        </div>
        <p><strong>Next:</strong> {{ signal.recommendedAction }}</p>
        <div class="signal-actions">
            <button class="secondary-btn" type="button" @click="$emit('save', signal)">
                {{ signal.isSaved ? 'Saved' : 'Save' }}
            </button>
            <Link class="primary-btn" :href="signal.url">Open signal</Link>
        </div>
    </article>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import CategoryPill from './CategoryPill.vue';
import type { Signal } from '../types';

defineProps<{ signal: Signal }>();
defineEmits<{ save: [signal: Signal] }>();
</script>
