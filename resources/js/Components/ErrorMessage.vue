<script setup>
defineProps({
    errorMessage: String
});
</script>

<template>
    <div v-if="errorMessage" style="color: red;">{{ errorMessage }}</div>
</template>