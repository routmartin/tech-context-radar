<template>
    <button class="source-card" type="button" :aria-pressed="active" @click="$emit('select', source)">
        <span class="panel-head">
            <strong>{{ source.name }}</strong>
            <span class="category-badge">{{ source.category.name }}</span>
        </span>
        <span class="meta">{{ source.trustLevel }} trust · {{ source.updatesToday }} updates · {{ source.lastScanned }}</span>
        <span class="muted">Status: {{ source.status }}</span>
    </button>
</template>

<script setup lang="ts">
import type { Source } from '../types';

defineProps<{
    source: Source;
    active: boolean;
}>();

defineEmits<{ select: [source: Source] }>();
</script>
