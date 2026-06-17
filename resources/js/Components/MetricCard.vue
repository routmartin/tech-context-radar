<template>
    <div class="metric-card">
        <span class="meta">{{ label }}</span>
        <strong class="metric-value">{{ value }}</strong>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    label: string;
    value: string | number;
}>();
</script>
