<template>
    <label>
        <span class="sr-only">{{ label }}</span>
        <input
            class="search-input"
            type="search"
            :value="modelValue"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        />
    </label>
</template>

<script setup lang="ts">
defineProps<{
    modelValue: string;
    label?: string;
    placeholder?: string;
}>();

defineEmits<{ 'update:modelValue': [value: string] }>();
</script>
