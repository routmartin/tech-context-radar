<template>
    <GlassPanel>
        <div class="content-stack" style="text-align: center">
            <h2 class="section-title">{{ title }}</h2>
            <p class="muted">{{ description }}</p>
            <div>
                <Link v-if="href" class="primary-btn" :href="href">{{ action }}</Link>
            </div>
        </div>
    </GlassPanel>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GlassPanel from './GlassPanel.vue';

defineProps<{
    title: string;
    description: string;
    action?: string;
    href?: string;
}>();
</script>
