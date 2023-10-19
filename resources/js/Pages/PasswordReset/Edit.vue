<script setup>
import TextField from "@/Components/TextField.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "@/Components/Button.vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    userToken: Object,
    errors: Object
});


const form = reactive({
    resetToken: props.userToken['reset_password_access_key'],
    password: null
});

const sendResetPassForm = () => {
    router.post(route("password_reset.update"), form);
};
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
                    <h1>パスワード再設定</h1>
                </v-col>
            </v-row>
            <form @submit.prevent="sendResetPassForm">
                <v-row>
                    <v-col
                        offset-sm="3"
                        offset-md="3"
                        offset-lg="3"
                        cols="12"
                        sm="8"
                        md="6"
                        lg="6"
                        style="height: 100px"
                    >
                        <TextField
                            label="新しいパスワード"
                            v-model="form.password"
                            autocomplete
                        />
                        <ErrorMessage
                            :errorMessage="errors.password"
                        ></ErrorMessage>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col
                        offset-sm="3"
                        offset-md="3"
                        offset-lg="3"
                        cols="12"
                        sm="4"
                        md="4"
                        lg="4"
                    >
                        <Button
                            type="submit"
                            name="パスワードを変更"
                            width="1000"
                            backgroundColor="#993300"
                            color="#FFF"
                        ></Button>
                    </v-col>
                </v-row>
            </form>
        </v-container>
    </v-app>
</template>
