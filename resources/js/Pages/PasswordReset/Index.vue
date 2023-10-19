<script setup>
import TextField from "@/Components/TextField.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "@/Components/Button.vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";
import { reactive, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    errors: Object,
});

const dialog = ref(false);

const form = reactive({
    email: null,
});

const sendResetMail = () => {
    dialog.value = true;
    router.post(route("password_reset.send"), form);
};

watch(() => props.errors, (newErrors) => {
    if (newErrors && Object.keys(newErrors).length > 0) {
        dialog.value = false;
    }
});
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
                    <h1>パスワード再設定メールを送る</h1>
                </v-col>
            </v-row>
            <form @submit.prevent="sendResetMail">
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
                            label="メールアドレス"
                            v-model="form.email"
                            autocomplete
                        />
                        <ErrorMessage
                            :errorMessage="errors.email"
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
                            :disabled="dialog"
                            type="submit"
                            name="送信"
                            width="1000"
                            backgroundColor="#993300"
                            color="#FFF"
                        ></Button>
                        <v-dialog
                            v-model="dialog"
                            :scrim="false"
                            persistent
                            width="auto"
                        >
                            <v-card color="#993300">
                                <v-card-text>
                                    送信中...
                                    <v-progress-linear
                                        indeterminate
                                        color="white"
                                        class="mb-0"
                                    ></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </v-col>
                </v-row>
            </form>
        </v-container>
    </v-app>
</template>
