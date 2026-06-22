<template>
    <Link class="bottom-nav-link" :href="href" :aria-current="current ? 'page' : undefined">
        <span class="bottom-nav-icon" aria-hidden="true">
            <slot />
        </span>
        <span class="bottom-nav-label">{{ label }}</span>
    </Link>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    href: string;
    label: string;
    current?: boolean;
}>();
</script>
