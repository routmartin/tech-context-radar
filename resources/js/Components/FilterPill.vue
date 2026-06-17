<template>
    <button class="filter-pill" type="button" :aria-pressed="active" @click="$emit('click')">
        {{ label }}
    </button>
</template>

<script setup lang="ts">
defineProps<{
    label: string;
    active: boolean;
}>();

defineEmits<{ click: [] }>();
</script>
