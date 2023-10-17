<script setup>
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import CommonHeader from "@/Components/CommonHeader.vue";
import Button from "@/Components/Button.vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";
import { reactive } from "vue";

const props = defineProps({
    loginUser: Object,
    inputs: Object,
    errors: Object
});

console.log(props.inputs);

const form = reactive({
    email: props.inputs['email'],
    title: props.inputs['title'],
    body: props.inputs['body']
});


const compSendForm = () => {
    router.post(route('contact.comp'), form);
}
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginUser="props.loginUser"></CommonHeader>
            <v-main>
                <v-container>
                    <form @submit.prevent="compSendForm">
                        <v-row>
                            <v-col offset="2" cols="8">
                                <h4>メールアドレス:</h4>
                                <p>{{ props.inputs['email'] }}</p>
                                <ErrorMessage :errorMessage="errors.email"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset="2" cols="8">
                                <h4>タイトル:</h4>
                                <p>{{ props.inputs['title'] }}</p>
                                <ErrorMessage :errorMessage="errors.title"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset="2" cols="8">
                                <h4>お問い合わせ内容:</h4>
                                <p>{{ props.inputs['body'] }}</p>
                                <ErrorMessage :errorMessage="errors.body"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset="2" cols="1">
                                <Link :href="route('contact.index')">
                                    <Button
                                        type="submit"
                                        name="戻る"
                                        width="1000"
                                        backgroundColor="#993300"
                                        color="#FFF"
                                    ></Button>
                                </Link>
                            </v-col>
                            <v-col cols="3">
                                <Button
                                    type="submit"
                                    name="お問い合わせ送信"
                                    width="1000"
                                    backgroundColor="#993300"
                                    color="#FFF"
                                ></Button>
                            </v-col>
                        </v-row>
                    </form>
                </v-container>
            </v-main>
        </v-container>
    </v-app>
</template>
