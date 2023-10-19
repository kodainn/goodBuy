<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps(['href', 'active', 'method']);

const classes = computed(() =>
    props.active
        ? 'border-bottom: solid #FFF;'
        : ''
);
</script>

<template>
    <Link
        as="button"
        :href="href"
        :style="classes"
        :method="method ? method : 'get'"
    >
        <v-tab>
            <slot />
        </v-tab>
    </Link>
</template>
