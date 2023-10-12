<script setup>
import { router } from "@inertiajs/vue3";
import CommonHeader from "@/Components/CommonHeader.vue";
import Button from "@/Components/Button.vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";
import { reactive } from "vue";

const props = defineProps({
    loginUser: Object,
});
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginUser="props.loginUser"></CommonHeader>
            <v-main>
                <v-container>
                    お問い合わせ完了
                </v-container>
            </v-main>
        </v-container>
    </v-app>
</template>
