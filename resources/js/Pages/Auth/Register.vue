<script setup>
import TextField from "@/Components/TextField.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "@/Components/Button.vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";
import { useForm } from "@inertiajs/vue3";

defineProps({
    errors: Object
});

const form = useForm({
    user_id: "",
    email: "",
    password: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <v-app>
        <v-container>
            <v-row style="margin: 5%;">
                <v-col>
                    <ApplicationLogo></ApplicationLogo>
                </v-col>
            </v-row>
            <v-row>
                <v-col align="center" style="margin-bottom: 1%;">
                    <h1>GOODSSHARESへようこそ</h1>
                </v-col>
                </v-row>
            <form @submit.prevent="submit">
                <v-row>
                    <v-col offset="4" cols="4" style="height: 100px;">
                        <TextField
                            id="user_id"
                            label="ユーザーID"
                            width="400"
                            height="30"
                            v-model="form.user_id"
                            autocomplete
                        />
                        <ErrorMessage :errorMessage="errors.user_id"></ErrorMessage>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col offset="4" cols="4" style="height: 100px;">
                        <TextField
                            id="email"
                            label="メールアドレス"
                            width="400"
                            height="30"
                            v-model="form.email"
                            autocomplete
                        />
                        <ErrorMessage :errorMessage="errors.email"></ErrorMessage>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col offset="4" cols="4" style="height: 100px;">
                        <TextField
                            type="password"
                            id="password"
                            label="パスワード"
                            width="400"
                            height="30"
                            v-model="form.password"
                            autocomplete
                        />
                        <ErrorMessage :errorMessage="errors.password"></ErrorMessage>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col offset="4" cols="4">
                        <Button
                            type="submit"
                            name="goodsharesに登録する"
                            width="1000"
                            height="40"
                            backgroundColor="#993300"
                            color="#FFF"
                        ></Button>
                    </v-col>
                </v-row>
            </form>
        </v-container>
    </v-app>
</template>
