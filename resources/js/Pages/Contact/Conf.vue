<script setup>
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import CommonHeader from "@/Components/CommonHeader.vue";
import Button from "@/Components/Button.vue";
import ErrorMessage from "@/Components/ErrorMessage.vue";
import { reactive, ref, watch } from "vue";

const props = defineProps({
    loginUser: Object,
    inputs: Object,
    errors: Object
});

const dialog = ref(false);

const form = reactive({
    email: props.inputs['email'],
    title: props.inputs['title'],
    body: props.inputs['body']
});

const compSendForm = () => {
    dialog.value = true;
    router.post(route('contact.comp'), form);

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
            <CommonHeader :loginUser="props.loginUser"></CommonHeader>
            <v-main>
                <v-container>
                    <form @submit.prevent="compSendForm">
                        <v-row>
                            <v-col offset="2" cols="8">
                                <h4>メールアドレス:</h4>
                                <p>{{ inputs['email'] }}</p>
                                <ErrorMessage :errorMessage="errors.email"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset="2" cols="8">
                                <h4>タイトル:</h4>
                                <p>{{ inputs['title'] }}</p>
                                <ErrorMessage :errorMessage="errors.title"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset="2" cols="8">
                                <h4>お問い合わせ内容:</h4>
                                <p>{{ inputs['body'] }}</p>
                                <ErrorMessage :errorMessage="errors.body"></ErrorMessage>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col offset-md="2" offset-lg="2" cols="12" sm="12" md="1" lg="1">
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
                            <v-col cols="12" sm="12" md="3" lg="3">
                                <Button
                                    :disabled="dialog"
                                    type="submit"
                                    name="お問い合わせ送信"
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
                                    <v-card
                                        color="#993300"
                                    >
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
            </v-main>
        </v-container>
    </v-app>
</template>
