<template>
    <label class="panel-head">
        <span>{{ label }}</span>
        <ToggleSwitch :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" />
    </label>
</template>

<script setup lang="ts">
import ToggleSwitch from './ToggleSwitch.vue';

defineProps<{
    label: string;
    modelValue: boolean;
}>();

defineEmits<{ 'update:modelValue': [value: boolean] }>();
</script>
