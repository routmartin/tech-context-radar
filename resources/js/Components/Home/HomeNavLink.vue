<template>
    <Link class="nav-link" :href="href" :aria-current="current ? 'page' : undefined" @click="$emit('navigate')">
        <span class="nav-icon" aria-hidden="true">
            <slot />
        </span>
        <span class="nav-copy"><span class="nav-primary">{{ primary }}</span><span class="nav-secondary">{{ secondary }}</span></span>
        <span class="nav-chevron" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m9 18 6-6-6-6"></path></svg></span>
    </Link>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    href: string;
    primary: string;
    secondary: string;
    current?: boolean;
}>();

defineEmits<{
    navigate: [];
}>();
</script>
