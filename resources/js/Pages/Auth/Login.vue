<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
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

    <main class="auth-screen" aria-labelledby="login-title">
        <div class="auth-bg" aria-hidden="true">
            <div class="auth-grid"></div>
            <div class="auth-core"></div>
        </div>

        <section class="auth-shell">
            <aside class="auth-story" aria-label="Tech Context Radar overview">
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
            </aside>

            <section class="auth-card glass-panel" aria-labelledby="login-title">
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
            </section>
        </section>
    </main>
</template>

<style scoped>
.auth-screen {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
    background: var(--bg);
    color: var(--fg);
}

.auth-bg,
.auth-grid,
.auth-core {
    position: fixed;
    inset: 0;
}

.auth-bg {
    z-index: 0;
    pointer-events: none;
    background:
        radial-gradient(circle at 18% 20%, rgba(92, 255, 141, 0.16), transparent 26%),
        radial-gradient(circle at 78% 18%, rgba(156, 255, 183, 0.09), transparent 22%),
        linear-gradient(130deg, var(--bg), var(--bg-2) 52%, var(--accent-deep));
}

.auth-grid {
    opacity: 0.22;
    background-image:
        linear-gradient(rgba(156, 255, 183, 0.12) 1px, transparent 1px),
        linear-gradient(90deg, rgba(156, 255, 183, 0.12) 1px, transparent 1px);
    background-size: 84px 84px;
    mask-image: radial-gradient(circle at 50% 44%, black, transparent 72%);
}

.auth-core {
    background:
        radial-gradient(circle at 50% 46%, rgba(156, 255, 183, 0.18), transparent 28%),
        linear-gradient(90deg, rgba(0, 0, 0, 0.74), rgba(0, 0, 0, 0.22) 50%, rgba(0, 0, 0, 0.76)),
        linear-gradient(180deg, rgba(0, 0, 0, 0.24), var(--bg));
}

.auth-shell {
    position: relative;
    z-index: 1;
    width: min(1160px, calc(100vw - 36px));
    min-height: 100vh;
    display: grid;
    grid-template-columns: minmax(0, 0.95fr) minmax(380px, 0.62fr);
    align-items: center;
    gap: clamp(28px, 6vw, 86px);
    margin: 0 auto;
    padding: 48px 0;
}

.auth-story {
    display: grid;
    gap: 42px;
}

.brand {
    width: fit-content;
}

.auth-copy {
    max-width: 700px;
}

.auth-copy h1 {
    max-width: 12ch;
    margin: 18px 0 20px;
    font-size: clamp(48px, 6vw, 88px);
    font-weight: 800;
    letter-spacing: -0.034em;
    line-height: 0.98;
}

.auth-copy p {
    max-width: 54ch;
    margin: 0;
    color: var(--fg-2);
    font-size: clamp(17px, 1.45vw, 21px);
    font-weight: 500;
    letter-spacing: -0.004em;
    line-height: 1.58;
}

.auth-metrics {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    max-width: 560px;
}

.auth-metric {
    min-height: 104px;
    padding: 16px;
    border: 1px solid var(--border);
    border-radius: 26px;
    background:
        linear-gradient(180deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.025)),
        rgba(2, 4, 3, 0.28);
    backdrop-filter: blur(20px);
}

.auth-metric span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.078em;
    line-height: 1.3;
    text-transform: uppercase;
}

.auth-metric strong {
    display: block;
    margin-top: 12px;
    color: var(--fg);
    font-size: 31px;
    font-weight: 780;
    letter-spacing: -0.028em;
    line-height: 1;
}

.auth-card {
    position: relative;
    overflow: hidden;
    padding: clamp(22px, 3vw, 34px);
    border-color: var(--border-strong);
    border-radius: 34px;
}

.auth-card::before {
    position: absolute;
    inset: 0;
    content: "";
    pointer-events: none;
    background:
        linear-gradient(120deg, rgba(255, 255, 255, 0.16), transparent 34%),
        radial-gradient(circle at 82% 12%, rgba(156, 255, 183, 0.16), transparent 28%);
}

.auth-card > * {
    position: relative;
    z-index: 1;
}

.auth-card-head h2 {
    margin: 0;
    font-size: clamp(30px, 3vw, 42px);
    font-weight: 760;
    letter-spacing: -0.026em;
    line-height: 1.04;
}

.auth-card-head p {
    margin: 10px 0 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.55;
}

.auth-status {
    margin-top: 18px;
    padding: 12px 14px;
    border: 1px solid var(--border-strong);
    border-radius: 18px;
    background: rgba(156, 255, 183, 0.1);
    color: var(--accent);
    font-size: 13px;
    font-weight: 650;
}

.auth-form {
    display: grid;
    gap: 16px;
    margin-top: 24px;
}

.field-row {
    display: grid;
    gap: 8px;
}

.field-label {
    color: var(--muted);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.field-input {
    width: 100%;
    min-height: 50px;
    padding: 0 15px;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    background: rgba(255, 255, 255, 0.055);
    color: var(--fg);
    font-size: 15px;
    outline: 0;
    transition:
        border-color var(--fast) var(--ease),
        background var(--fast) var(--ease),
        box-shadow var(--fast) var(--ease);
}

.field-input::placeholder {
    color: var(--meta);
}

.field-input:hover,
.field-input:focus {
    border-color: var(--border-strong);
    background: rgba(255, 255, 255, 0.08);
}

.field-input:focus {
    box-shadow: var(--focus-ring);
}

.auth-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
}

.remember-control {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--fg-2);
    font-size: 13px;
    font-weight: 620;
    cursor: pointer;
}

.remember-control input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}

.remember-control span {
    width: 22px;
    height: 22px;
    display: grid;
    place-items: center;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.055);
}

.remember-control span::after {
    width: 10px;
    height: 6px;
    content: "";
    border-bottom: 2px solid var(--accent-on);
    border-left: 2px solid var(--accent-on);
    opacity: 0;
    transform: translateY(-1px) rotate(-45deg);
}

.remember-control input:checked + span {
    border-color: transparent;
    background: var(--accent);
}

.remember-control input:checked + span::after {
    opacity: 1;
}

.auth-link {
    color: var(--accent);
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
}

.auth-link:hover {
    color: var(--accent-strong);
}

.auth-submit {
    width: 100%;
    min-height: 52px;
    margin-top: 4px;
}

.auth-submit:disabled {
    cursor: wait;
    opacity: 0.68;
}

.auth-foot {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid var(--border);
    color: var(--muted);
    font-size: 13px;
}

@media (max-width: 900px) {
    .auth-shell {
        grid-template-columns: 1fr;
        gap: 24px;
        padding: 28px 0;
    }

    .auth-story {
        gap: 24px;
    }

    .auth-copy h1 {
        max-width: 16ch;
        font-size: clamp(38px, 10vw, 56px);
    }

    .auth-metrics {
        grid-template-columns: 1fr;
    }

    .auth-metric {
        min-height: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 15px;
    }

    .auth-metric strong {
        margin-top: 0;
        font-size: 26px;
    }
}

@media (max-width: 520px) {
    .auth-shell {
        width: min(100vw - 28px, 1160px);
        padding: 18px 0;
    }

    .auth-card {
        padding: 20px;
        border-radius: 28px;
    }

    .auth-options,
    .auth-foot {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>
