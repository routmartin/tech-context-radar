<template>
    <div class="app-shell">
        <header class="topbar">
            <Link class="brand" href="/today" aria-label="Tech Context Radar home">
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

            <div class="nav-wrap">
                <nav class="nav-menu" id="site-menu" aria-label="Primary navigation">
                    <Link class="nav-link" href="/today" :aria-current="current === 'today' ? 'page' : undefined">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="7"></circle>
                                <path d="M12 12 17 8M12 5v3M19 12h-3"></path>
                            </svg>
                        </span>
                        <span class="nav-copy">
                            <span class="nav-primary">Today</span>
                            <span class="nav-secondary">{{ todayCount }} signals now</span>
                        </span>
                        <span class="nav-chevron" aria-hidden="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </span>
                    </Link>
                    <Link class="nav-link" href="/saved" :aria-current="current === 'saved' ? 'page' : undefined">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M7 4h10a1 1 0 0 1 1 1v15l-6-3-6 3V5a1 1 0 0 1 1-1Z"></path>
                            </svg>
                        </span>
                        <span class="nav-copy">
                            <span class="nav-primary">Saved</span>
                            <span class="nav-secondary">Knowledge stack</span>
                        </span>
                        <span class="nav-chevron" aria-hidden="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </span>
                    </Link>
                    <Link class="nav-link" href="/sources" :aria-current="current === 'sources' ? 'page' : undefined">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M4 6.5h9M4 12h16M4 17.5h11"></path>
                                <path d="M17 6.5h3"></path>
                            </svg>
                        </span>
                        <span class="nav-copy">
                            <span class="nav-primary">Sources</span>
                            <span class="nav-secondary">Curated inputs</span>
                        </span>
                        <span class="nav-chevron" aria-hidden="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </span>
                    </Link>
                    <Link class="nav-link" href="/settings" :aria-current="current === 'settings' ? 'page' : undefined">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"></path>
                                <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.05.05a2 2 0 0 1-2.83 2.83l-.05-.05A1.7 1.7 0 0 0 15 19.36a1.7 1.7 0 0 0-1 1.55V21a2 2 0 0 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.05.05a2 2 0 0 1-2.83-2.83l.05-.05A1.7 1.7 0 0 0 4.64 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 0 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.05-.05a2 2 0 0 1 2.83-2.83l.05.05A1.7 1.7 0 0 0 9 4.64a1.7 1.7 0 0 0 1-1.55V3a2 2 0 0 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.05-.05a2 2 0 0 1 2.83 2.83l-.05.05A1.7 1.7 0 0 0 19.36 9a1.7 1.7 0 0 0 1.55 1H21a2 2 0 0 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15Z"></path>
                            </svg>
                        </span>
                        <span class="nav-copy">
                            <span class="nav-primary">Settings</span>
                            <span class="nav-secondary">Tune interests</span>
                        </span>
                        <span class="nav-chevron" aria-hidden="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </span>
                    </Link>
                </nav>
                <button
                    id="menu-toggle"
                    class="menu-button"
                    type="button"
                    aria-controls="site-menu"
                    :aria-expanded="menuOpen"
                    @click="menuOpen = !menuOpen"
                >
                    <span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span>Menu
                </button>
            </div>
        </header>

        <div
            id="drawer-scrim"
            class="drawer-scrim"
            :class="{ 'is-visible': menuOpen }"
            aria-hidden="true"
            @click="menuOpen = false"
        ></div>

        <main class="page" :class="{ 'menu-open': menuOpen }">
            <slot :notify="notify"></slot>
        </main>

        <nav class="bottom-nav" aria-label="Main navigation">
            <Link class="bottom-nav-link" href="/today" :aria-current="current === 'today' ? 'page' : undefined">
                <span class="bottom-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="7"></circle>
                        <path d="M12 12 17 8M12 5v3M19 12h-3"></path>
                    </svg>
                </span>
                <span class="bottom-nav-label">Today</span>
            </Link>
            <Link class="bottom-nav-link" href="/saved" :aria-current="current === 'saved' ? 'page' : undefined">
                <span class="bottom-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M7 4h10a1 1 0 0 1 1 1v15l-6-3-6 3V5a1 1 0 0 1 1-1Z"></path>
                    </svg>
                </span>
                <span class="bottom-nav-label">Saved</span>
            </Link>
            <Link class="bottom-nav-link" href="/sources" :aria-current="current === 'sources' ? 'page' : undefined">
                <span class="bottom-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 6.5h9M4 12h16M4 17.5h11"></path>
                        <path d="M17 6.5h3"></path>
                    </svg>
                </span>
                <span class="bottom-nav-label">Sources</span>
            </Link>
            <Link class="bottom-nav-link" href="/settings" :aria-current="current === 'settings' ? 'page' : undefined">
                <span class="bottom-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"></path>
                        <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.05.05a2 2 0 0 1-2.83 2.83l-.05-.05A1.7 1.7 0 0 0 15 19.36a1.7 1.7 0 0 0-1 1.55V21a2 2 0 0 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.05.05a2 2 0 0 1-2.83-2.83l.05-.05A1.7 1.7 0 0 0 4.64 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 0 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.05-.05a2 2 0 0 1 2.83-2.83l.05.05A1.7 1.7 0 0 0 9 4.64a1.7 1.7 0 0 0 1-1.55V3a2 2 0 0 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.05-.05a2 2 0 0 1 2.83 2.83l-.05.05A1.7 1.7 0 0 0 19.36 9a1.7 1.7 0 0 0 1.55 1H21a2 2 0 0 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15Z"></path>
                    </svg>
                </span>
                <span class="bottom-nav-label">Settings</span>
            </Link>
        </nav>

        <div id="toast" class="toast" :class="{ show: toastVisible }" role="status" aria-live="polite">{{ toastText }}</div>
    </div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import type { SharedPageProps } from '../types';

const props = defineProps<{
    current: 'today' | 'saved' | 'sources' | 'settings';
    todayCount?: number;
}>();

const page = usePage<SharedPageProps>();
const menuOpen = ref(false);
const toastText = ref('');
const toastVisible = ref(false);
let toastTimer: number | null = null;

function notify(message: string) {
    if (!message) return;
    toastText.value = message;
    toastVisible.value = true;
    if (toastTimer) {
        window.clearTimeout(toastTimer);
    }
    toastTimer = window.setTimeout(() => {
        toastVisible.value = false;
    }, 2400);
}

watch(
    () => page.props.flash?.toast,
    (message) => {
        if (typeof message === 'string' && message.length > 0) {
            notify(message);
        }
    },
    { immediate: true },
);

function handleEscape(event: KeyboardEvent) {
    if (event.key === 'Escape') {
        menuOpen.value = false;
    }
}

watch(menuOpen, (open) => {
    document.body.classList.toggle('menu-open', open);
});

onMounted(() => {
    document.addEventListener('keydown', handleEscape);
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', handleEscape);
    document.body.classList.remove('menu-open');
    if (toastTimer) {
        window.clearTimeout(toastTimer);
    }
});
</script>
