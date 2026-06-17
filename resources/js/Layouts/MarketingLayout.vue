<template>
    <div class="tcr-shell">
        <header class="tcr-header">
            <div class="tcr-wrap tcr-header-inner">
                <BrandMark />
                <div class="signal-actions">
                    <Link class="secondary-btn" href="/login">Log in</Link>
                    <Link class="primary-btn" href="/today">View Today’s Radar</Link>
                </div>
            </div>
        </header>
        <main>
            <slot />
        </main>
        <Toast :message="toastMessage" />
    </div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import BrandMark from '../Components/BrandMark.vue';
import Toast from '../Components/Toast.vue';
import type { SharedPageProps } from '../types';

const page = usePage<SharedPageProps>();
const toastMessage = computed(() => page.props.flash?.toast ?? null);
</script>
