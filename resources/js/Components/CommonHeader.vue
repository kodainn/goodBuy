<script setup>
import { Link } from "@inertiajs/vue3";
import Button from "./Button.vue";
import NavLink from "@/Components/NavLink.vue";
import { ref } from "vue";
import { watch } from "vue";

const props = defineProps({
    loginUser: Object,
});

const menuOpen = ref(false);

const toggleMenu = () => {
      menuOpen.value = !menuOpen.value;
}

const innerWidth = ref(window.innerWidth);
</script>

<template>
    <v-app-bar :color="'#993300'">
        <v-btn v-if="innerWidth < 960" @click="toggleMenu">メニュー</v-btn>
        <v-container v-if="innerWidth >= 960" class="mx-auto d-flex align-center justify-center">
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
            <template v-if="props.loginUser">
                <v-col offset-md="7" offset-lg="7">
                    <Link as="button" :href="route('logout')" method="post">
                        <Button
                            name="ログアウト"
                            width="100"
                            height="40"
                            color="#FFF"
                        ></Button>
                    </Link>
                </v-col>
            </template>
            <template v-else>
                <v-col offset-sm="3" offset-md="7" offset-lg="7">
                    <Link as="button" :href="route('login')">
                        <Button
                            name="ログイン"
                            width="100"
                            height="40"
                            color="#FFF"
                        ></Button>
                    </Link>
                </v-col>
                <v-col>
                    <Link as="button" :href="route('register')">
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
    <v-navigation-drawer v-if="innerWidth < 960" v-model="menuOpen" app right temporary>
            <v-list>
                <v-list-item>
                    <v-list-item-title>
                        <NavLink
                            :href="route('postlist.index')"
                        >
                            投稿一覧
                        </NavLink>
                    </v-list-item-title>
                </v-list-item>
                <v-list-item>
                    <v-list-item-title>
                        <NavLink
                            :href="route('contact.index')"
                        >
                            お問い合わせ
                        </NavLink>
                    </v-list-item-title>
                </v-list-item>
                <v-list-item>
                    <v-list-item-title>
                        <NavLink
                            :href="route('profile.index')"
                        >
                            プロフィール
                        </NavLink>
                    </v-list-item-title>
                </v-list-item>
                <hr>
                <template v-if="props.loginUser">
                    <v-list-item>
                        <v-list-item-title>
                            <NavLink
                                :href="route('logout')"
                                method="post"
                            >
                                ログアウト
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                </template>
                <template v-else>
                    <v-list-item>
                        <v-list-item-title>
                            <NavLink
                                :href="route('login')"
                            >
                                ログイン
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-title>
                            <NavLink
                                :href="route('register')"
                            >
                                新規登録
                            </NavLink>
                        </v-list-item-title>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>
</template>

<style>
/* メニューのスライドトランジション */
.v-navigation-drawer-enter-active,
.v-navigation-drawer-leave-active {
  transition: transform 0.3s;
}

.v-navigation-drawer-enter,
.v-navigation-drawer-leave-to {
  transform: translateX(0);
}
</style>
