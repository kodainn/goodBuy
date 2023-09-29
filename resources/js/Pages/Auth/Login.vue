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
    password: "",
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <v-app>
        <v-container>
            <v-row style="margin: 5%;">
                <v-col offset="2" cols="4">
                    <ApplicationLogo></ApplicationLogo>
                </v-col>
            </v-row>
            <v-row>
                <v-col align="center" style="margin-bottom: 1%;">
                    <h1>GOODSSHARESにログイン</h1>
                </v-col>
            </v-row>
            <form @submit.prevent="submit">
                <v-row>
                    <v-col offset="4" cols="4" style="height: 100px;">
                        <TextField
                            label="ユーザーID"
                            v-model="form.user_id"
                            autocomplete
                        />
                        <div v-if="errors.user_id" style="color: red;">{{ errors.user_id }}</div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col offset="4" cols="4" style="height: 100px;">
                        <TextField
                            type="password"
                            label="パスワード"
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
                            name="goodsharesにログインする"
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
