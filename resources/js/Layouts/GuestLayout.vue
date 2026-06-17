<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="auth-shell">
        <div>
            <Link href="/">
                <ApplicationLogo class="auth-logo" />
            </Link>
        </div>

        <div class="auth-card">
            <slot />
        </div>
    </div>
</template>
