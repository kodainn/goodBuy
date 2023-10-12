<script setup>
defineProps({
    label: String,
});


</script>

<template>
    <v-textarea
        density="compact"
        variant="outlined"
        :label="label"
        @input="$emit('update:modelValue', $event.target.value)"
    ></v-textarea>
</template>
