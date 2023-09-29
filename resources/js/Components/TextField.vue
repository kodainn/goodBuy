<script setup>
defineProps({
    label: String,
    width: String,
    height: String
});


</script>

<template>
    <v-text-field
        density="compact"
        variant="outlined"
        :label="label"
        :width="width"
        :height="height"
        @input="$emit('update:modelValue', $event.target.value)"
    ></v-text-field>
</template>
