<script setup>
import { router } from "@inertiajs/vue3";
import CommonHeader from "@/Components/CommonHeader.vue";
import TextField from "@/Components/TextField.vue";
import TextArea from "@/Components/TextArea.vue";
import Button from "@/Components/Button.vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";
import { reactive } from "vue";

const props = defineProps({
    loginUser: Object,
    errors: Object,
    contact: Object
});


const form = reactive({
    email: props.contact['email'],
    title: props.contact['title'],
    body: props.contact['body']
});

const confFormSend = () => {
    router.post(route('contact.conf'), form);
}
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginUser="props.loginUser"></CommonHeader>
            <v-main>
                <v-container>
                    <form @submit.prevent="confFormSend">
                        <v-row>
                            <v-col offset="2" cols="8">
                                <TextField
                                    label="メールアドレス"
                                    v-model="form.email"
                                >
                                </TextField>
                                <ErrorMessage :errorMessage="errors.email"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset="2" cols="8">
                                <TextField
                                    label="タイトル"
                                    v-model="form.title"
                                >
                                </TextField>
                                <ErrorMessage :errorMessage="errors.title"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset="2" cols="8">
                                <TextArea
                                    label="お問い合わせ内容"
                                    v-model="form.body"
                                >
                                </TextArea>
                                <ErrorMessage :errorMessage="errors.body"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset="2" cols="4">
                                <Button
                                    type="submit"
                                    name="入力内容確認"
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
