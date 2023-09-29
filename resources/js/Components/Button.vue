<script setup>
const props = defineProps({
    variant: {
        type: String,
        default: 'outlined'
    },
    name: String,
    width: String,
    height: String,
    backgroundColor: String,
    color: String
});
</script>

<template>
    <v-btn
        :variant="variant"
        :style="`background-color: ${backgroundColor}`"
        :color="color"
        :width="width"
        :height="height"
    >
        {{ name }}
    </v-btn>
</template>