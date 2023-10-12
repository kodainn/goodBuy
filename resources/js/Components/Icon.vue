<script setup>

const props = defineProps({
    path: String
});

</script>

<template>
    <v-avatar style="background-color: black;">
        <v-img v-if="props.path" :src="props.path"></v-img>
        <v-img v-else src="/img/initIcon.jpg"></v-img>
    </v-avatar>
</template>