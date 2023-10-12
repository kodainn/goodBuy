<script setup>
import { ref } from 'vue';

const props = defineProps({
    typeDivKv: Object,
    name: String,
    width: String,
    height: String
});

const typeDivItems = ref([]);

for(const [typeDetailDiv, typeDetailName] of Object.entries(props.typeDivKv)) {
    typeDivItems.value.push({
        'typeName': typeDetailName,
        'typeValue': typeDetailDiv
    });
}

</script>

<template>
    <v-select
        :label="name"
        :items="typeDivItems"
        :style="`width: ${width}px; height: ${height}px`"
        item-title="typeName"
        item-value="typeValue"
        @change="$emit('update:modelValue', $event.target.value)"
        variant="outlined"
    ></v-select>
</template>