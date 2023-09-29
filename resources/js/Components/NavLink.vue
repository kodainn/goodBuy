<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps(['href', 'active']);

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
        method="get"
    >
        <v-tab>
            <slot />
        </v-tab>
    </Link>
</template>
