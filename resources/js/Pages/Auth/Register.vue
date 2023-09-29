<script setup>
import TextField from "@/Components/TextField.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "@/Components/Button.vue";
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
    console.log(form)
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
                        <div v-if="errors.user_id" style="color: red;">{{ errors.user_id }}</div>
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
                        <div v-if="errors.email" style="color: red;">{{ errors.email }}</div>
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
                        <div v-if="errors.password" style="color: red;">{{ errors.password }}</div>
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
