<template>
    <button class="toggle" type="button" :aria-pressed="modelValue" @click="$emit('update:modelValue', !modelValue)">
        <span></span>
    </button>
</template>

<script setup lang="ts">
defineProps<{ modelValue: boolean }>();
defineEmits<{ 'update:modelValue': [value: boolean] }>();
</script>
