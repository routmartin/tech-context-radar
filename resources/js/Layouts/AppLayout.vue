<template>
    <div class="tcr-shell">
        <header class="tcr-header">
            <div class="tcr-wrap tcr-header-inner">
                <BrandMark />
                <TopNavigation />
                <div class="signal-actions">
                    <span class="meta">{{ page.props.auth.user?.name }}</span>
                    <Link class="secondary-btn" href="/logout" method="post" as="button">Log out</Link>
                </div>
            </div>
        </header>
        <main class="tcr-wrap" style="padding: 34px 0 54px">
            <slot />
        </main>
        <MobileBottomNavigation />
        <Toast :message="toastMessage" />
    </div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import BrandMark from '../Components/BrandMark.vue';
import MobileBottomNavigation from '../Components/MobileBottomNavigation.vue';
import Toast from '../Components/Toast.vue';
import TopNavigation from '../Components/TopNavigation.vue';
import type { SharedPageProps } from '../types';

const page = usePage<SharedPageProps>();
const toastMessage = computed(() => page.props.flash?.toast ?? null);
</script>
