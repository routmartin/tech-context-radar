<template>
    <nav class="bottom-nav" aria-label="Main navigation">
        <HomeBottomNavLink href="/today" current label="Today">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="7"></circle><path d="M12 12 17 8M12 5v3M19 12h-3"></path></svg>
        </HomeBottomNavLink>
        <HomeBottomNavLink href="/saved" label="Saved">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M7 4h10a1 1 0 0 1 1 1v15l-6-3-6 3V5a1 1 0 0 1 1-1Z"></path></svg>
        </HomeBottomNavLink>
        <HomeBottomNavLink href="/sources" label="Sources">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6.5h9M4 12h16M4 17.5h11"></path><path d="M17 6.5h3"></path></svg>
        </HomeBottomNavLink>
        <HomeBottomNavLink href="/settings" label="Settings">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"></path><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.05.05a2 2 0 0 1-2.83 2.83l-.05-.05A1.7 1.7 0 0 0 15 19.36a1.7 1.7 0 0 0-1 1.55V21a2 2 0 0 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.05.05a2 2 0 0 1-2.83-2.83l.05-.05A1.7 1.7 0 0 0 4.64 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 0 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.05-.05a2 2 0 0 1 2.83-2.83l.05.05A1.7 1.7 0 0 0 9 4.64a1.7 1.7 0 0 0 1-1.55V3a2 2 0 0 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.05-.05a2 2 0 0 1 2.83 2.83l-.05.05A1.7 1.7 0 0 0 19.36 9a1.7 1.7 0 0 0 1.55 1H21a2 2 0 0 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15Z"></path></svg>
        </HomeBottomNavLink>
    </nav>
</template>

<script setup lang="ts">
import HomeBottomNavLink from './HomeBottomNavLink.vue';
</script>
