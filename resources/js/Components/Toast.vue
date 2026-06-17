<template>
    <div v-if="message" class="toast" role="status">
        {{ message }}
    </div>
</template>

<script setup lang="ts">
defineProps<{ message?: string | null }>();
</script>
