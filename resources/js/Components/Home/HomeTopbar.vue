<template>
    <header class="topbar">
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

        <div class="nav-wrap">
            <nav class="nav-menu" id="site-menu" aria-label="Primary navigation">
                <HomeNavLink href="/today" current primary="Today" :secondary="`${prioritySignals} signals now`" @navigate="$emit('close-menu')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="7"></circle>
                        <path d="M12 12 17 8M12 5v3M19 12h-3"></path>
                    </svg>
                </HomeNavLink>
                <HomeNavLink href="/saved" primary="Saved" secondary="Knowledge stack" @navigate="$emit('close-menu')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M7 4h10a1 1 0 0 1 1 1v15l-6-3-6 3V5a1 1 0 0 1 1-1Z"></path>
                    </svg>
                </HomeNavLink>
                <HomeNavLink href="/sources" primary="Sources" secondary="Curated inputs" @navigate="$emit('close-menu')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 6.5h9M4 12h16M4 17.5h11"></path>
                        <path d="M17 6.5h3"></path>
                    </svg>
                </HomeNavLink>
                <HomeNavLink href="/settings" primary="Settings" secondary="Tune interests" @navigate="$emit('close-menu')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"></path>
                        <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.05.05a2 2 0 0 1-2.83 2.83l-.05-.05A1.7 1.7 0 0 0 15 19.36a1.7 1.7 0 0 0-1 1.55V21a2 2 0 0 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.05.05a2 2 0 0 1-2.83-2.83l.05-.05A1.7 1.7 0 0 0 4.64 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 0 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.05-.05a2 2 0 0 1 2.83-2.83l.05.05A1.7 1.7 0 0 0 9 4.64a1.7 1.7 0 0 0 1-1.55V3a2 2 0 0 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.05-.05a2 2 0 0 1 2.83 2.83l-.05.05A1.7 1.7 0 0 0 19.36 9a1.7 1.7 0 0 0 1.55 1H21a2 2 0 0 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15Z"></path>
                    </svg>
                </HomeNavLink>
            </nav>
            <button class="menu-button" id="menu-toggle" type="button" aria-controls="site-menu" :aria-expanded="menuOpen" @click="$emit('toggle-menu')">
                <span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span>
                Menu
            </button>
        </div>
    </header>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import HomeNavLink from './HomeNavLink.vue';

defineProps<{
    menuOpen: boolean;
    prioritySignals: number;
}>();

defineEmits<{
    'toggle-menu': [];
    'close-menu': [];
}>();
</script>
