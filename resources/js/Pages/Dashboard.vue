<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="typo-h2">
                Dashboard
            </h2>
        </template>

        <div class="profile-page">
            <div class="mx-auto max-w-7xl">
                <div class="profile-card">
                    <div class="typo-body">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
