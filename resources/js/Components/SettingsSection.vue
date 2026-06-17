<template>
    <GlassPanel>
        <div class="content-stack">
            <div>
                <h2 class="section-title">{{ title }}</h2>
                <p v-if="description" class="muted">{{ description }}</p>
            </div>
            <slot />
        </div>
    </GlassPanel>
</template>

<script setup lang="ts">
import GlassPanel from './GlassPanel.vue';

defineProps<{
    title: string;
    description?: string;
}>();
</script>
