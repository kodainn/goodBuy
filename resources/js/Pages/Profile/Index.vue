<script setup>
import { Link } from "@inertiajs/vue3";
import CommonHeader from "@/Components/CommonHeader.vue";

defineProps({
    loginStatus: Boolean
});
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginStatus="loginStatus"></CommonHeader>
            <v-main>
                <v-container>
                    <v-row>
                        <v-col>
                        </v-col>
                    </v-row>
                </v-container>
            </v-main>
        </v-container>
    </v-app>
</template>
