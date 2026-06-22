<template>
    <div class="toast" :class="{ show: visible }" role="status" aria-live="polite" id="toast">{{ text }}</div>
</template>

<script setup lang="ts">
defineProps<{
    text: string;
    visible: boolean;
}>();
</script>
