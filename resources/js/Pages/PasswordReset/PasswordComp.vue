<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <v-app>
        <v-container>
            <v-row style="margin: 5%">
                <v-col
                    offset-sm="3"
                    offset-md="3"
                    offset-lg="3"
                    cols="8"
                    sm="8"
                    md="6"
                    lg="6"
                >
                    <ApplicationLogo></ApplicationLogo>
                </v-col>
            </v-row>
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
                    <h1>パスワードの更新を完了しました。</h1>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>