<template>
    <div class="content-stack">
        <SignalCard v-for="signal in signals" :key="signal.id" :signal="signal" @save="$emit('save', $event)" />
    </div>
</template>

<script setup lang="ts">
import SignalCard from './SignalCard.vue';
import type { Signal } from '../types';

defineProps<{ signals: Signal[] }>();
defineEmits<{ save: [signal: Signal] }>();
</script>
