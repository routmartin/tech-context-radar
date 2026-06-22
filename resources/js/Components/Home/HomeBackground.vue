<template>
    <div class="hls-backdrop" aria-hidden="true">
        <video
            ref="heroVideo"
            id="hero-video"
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
            poster="mqg3fas9-screenone.webp"
            @error="$emit('video-error')"
        >
            <source src="https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8" type="application/vnd.apple.mpegurl" />
        </video>
    </div>
    <div class="video-fallback" aria-hidden="true"></div>
    <div class="shade" aria-hidden="true"></div>
    <div class="central-glow" aria-hidden="true"></div>
    <div class="grid-field" aria-hidden="true"></div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

defineEmits<{
    'video-error': [];
}>();

const heroVideo = ref<HTMLVideoElement | null>(null);

defineExpose({
    heroVideo,
});
</script>
