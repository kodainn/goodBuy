<script setup>
import { Link } from "@inertiajs/vue3";
import Button from "./Button.vue";
import NavLink from "@/Components/NavLink.vue";

const props = defineProps({
    loginUser: Object,
});
</script>

<template>
    <v-app-bar :color="'#993300'">
        <v-container class="mx-auto d-flex align-center justify-center">
            <NavLink
                :href="route('postlist.index')"
                :active="route().current('postlist.index')"
            >
                投稿一覧
            </NavLink>
            <NavLink
                :href="route('contact.index')"
                :active="route().current('contact.index')"
            >
                お問い合わせ
            </NavLink>
            <NavLink
                :href="route('profile.index')"
                :active="route().current('profile.index')"
            >
                プロフィール
            </NavLink>
            <v-spacer></v-spacer>
            <template v-if="props.loginUser">
                <Link as="button" :href="route('logout')" method="post">
                    <Button
                        name="ログアウト"
                        width="100"
                        height="40"
                        color="#FFF"
                    ></Button>
                </Link>
            </template>
            <template v-else>
                <v-col offset="7">
                    <Link as="button" :href="route('login')" method="get">
                        <Button
                            name="ログイン"
                            width="100"
                            height="40"
                            color="#FFF"
                        ></Button>
                    </Link>
                </v-col>
                <v-col>
                    <Link as="button" :href="route('register')" method="get">
                        <Button
                            name="新規登録"
                            width="100"
                            height="40"
                            color="#FFF"
                        ></Button>
                    </Link>
                </v-col>
            </template>
        </v-container>
    </v-app-bar>
</template>
