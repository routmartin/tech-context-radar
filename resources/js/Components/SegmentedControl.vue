<template>
    <div class="filter-row" role="group" :aria-label="label">
        <FilterPill
            v-for="option in options"
            :key="option"
            :label="option"
            :active="option === modelValue"
            @click="$emit('update:modelValue', option)"
        />
    </div>
</template>

<script setup lang="ts">
import FilterPill from './FilterPill.vue';

defineProps<{
    label: string;
    options: string[];
    modelValue: string;
}>();

defineEmits<{ 'update:modelValue': [value: string] }>();
</script>
