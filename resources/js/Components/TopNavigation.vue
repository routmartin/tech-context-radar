<template>
    <nav class="pill-nav" aria-label="Primary navigation">
        <Link
            v-for="item in items"
            :key="item.href"
            :href="item.href"
            :aria-current="isActive(item.href) ? 'page' : undefined"
        >
            {{ item.label }}
        </Link>
    </nav>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const path = computed(() => page.url.split('?')[0]);
const items = [
    { label: 'Today', href: '/today' },
    { label: 'Saved', href: '/saved' },
    { label: 'Sources', href: '/sources' },
    { label: 'Settings', href: '/settings' },
];

const isActive = (href: string) => path.value === href || (href === '/today' && path.value.startsWith('/signals/'));
</script>
