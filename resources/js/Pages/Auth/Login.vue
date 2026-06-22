<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <Head title="Log in" />

    <GuestLayout labelled-by="login-title">
        <template #story>
            <Link class="brand" href="/" aria-label="Tech Context Radar home">
                <span class="brand-mark" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="7"></circle>
                        <path d="M12 12 18 7M12 5v2M19 12h-2"></path>
                    </svg>
                </span>
                <span class="brand-text">
                    <span class="brand-title">Tech Context Radar</span>
                    <span class="brand-subtitle">AI brief for busy builders</span>
                </span>
            </Link>

            <div class="auth-copy">
                <span class="eyebrow">Daily intelligence workspace</span>
                <h1>Return to your radar.</h1>
                <p>
                    Pick up the latest AI and developer signals with context, confidence, and actions ready to scan.
                </p>
            </div>

            <div class="auth-metrics" aria-label="Radar summary">
                <div class="auth-metric">
                    <span>Brief length</span>
                    <strong>10m</strong>
                </div>
                <div class="auth-metric">
                    <span>Noise filtered</span>
                    <strong>39</strong>
                </div>
                <div class="auth-metric">
                    <span>Focus</span>
                    <strong>AI</strong>
                </div>
            </div>
        </template>

        <div class="auth-card-head">
            <span class="panel-kicker">Secure access</span>
            <h2 id="login-title">Log in</h2>
            <p>Use your account to open today's radar, saved intelligence, and source settings.</p>
        </div>

        <div v-if="status" class="auth-status" role="status">
            {{ status }}
        </div>

        <form class="auth-form" @submit.prevent="submit">
            <label class="field-row" for="email">
                <span class="field-label">Email</span>
                <input
                    id="email"
                    v-model="form.email"
                    class="field-input"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                />
                <InputError :message="form.errors.email" />
            </label>

            <label class="field-row" for="password">
                <span class="field-label">Password</span>
                <input
                    id="password"
                    v-model="form.password"
                    class="field-input"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Your password"
                />
                <InputError :message="form.errors.password" />
            </label>

            <div class="auth-options">
                <label class="remember-control">
                    <input v-model="form.remember" type="checkbox" name="remember" />
                    <span aria-hidden="true"></span>
                    Remember me
                </label>

                <Link v-if="canResetPassword" :href="route('password.request')" class="auth-link">
                    Forgot password?
                </Link>
            </div>

            <button class="primary-action auth-submit" type="submit" :disabled="form.processing">
                <span>{{ form.processing ? 'Opening radar...' : 'Log in' }}</span>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6"></path>
                </svg>
            </button>
        </form>

        <div class="auth-foot">
            <span>New workspace?</span>
            <Link href="/register" class="auth-link">Create an account</Link>
        </div>
    </GuestLayout>
</template>
