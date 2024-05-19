<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    values: {
        type: Object,
        required: true,
    },
    placeholder: {
        type: String,
        default: 'Select an option',
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input">

        <option value="">{{ placeholder }}</option>

        <template v-for="option in values" :key="option.value">
            <option :value="option.value">{{ option.label }}</option>
        </template>
    </select>
</template>
