<script setup>
import { ref } from 'vue';

const props = defineProps({
    typeDiv: Array,
    name: String,
    width: String,
    height: String
});

const typeDivItems = ref([]);

for(let item of props.typeDiv) {
    typeDivItems.value.push({
        'typeName': item['type_detail_name'],
        'typeValue': item['type_detail_div']
    });
}

</script>

<template>
    <v-combobox
        :label="name"
        :items="typeDivItems"
        :style="`width: ${width}px; height: ${height}px`"
        item-title="typeName"
        item-value="typeValue"
        @change="$emit('update:modelValue', $event.target.value)"
        variant="outlined"
    ></v-combobox>
</template>