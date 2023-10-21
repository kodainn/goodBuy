<script setup>
import CommonHeader from "@/Components/CommonHeader.vue";

const props = defineProps({
    loginUser: Object,
});
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginUser="loginUser"></CommonHeader>
            <v-main>
                <v-container>
                    <v-row>
                <v-col
                    offset-sm="3"
                    offset-md="3"
                    offset-lg="3"
                    cols="12"
                    sm="8"
                    md="6"
                    lg="6"
                    align="center"
                    style="margin-bottom: 1%"
                >
                    <h1>お問い合わせが完了しました。</h1>
                </v-col>
            </v-row>
                </v-container>
            </v-main>
        </v-container>
    </v-app>
</template>
