<template>
    <Home :briefing="briefing" :categories="categories" :signals="signals" />
</template>

<script setup lang="ts">
import Home from './Home.vue';
import type { Briefing, Category, Signal } from '../types';

defineProps<{
    briefing: Briefing;
    categories: Category[];
    signals: Signal[];
}>();
</script>
