<script setup>
import CommonHeader from "@/Components/CommonHeader.vue";
import SvgIcon from "@jamescoyle/vue-icon";
import axios from "axios";
import Icon from "@/Components/Icon.vue";
import Button from "@/Components/Button.vue";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { mdiHeart } from "@mdi/js";
import { mdiHeartOutline } from "@mdi/js";
import { mdiMessageOutline } from '@mdi/js';

const props = defineProps({
    post: Object,
    loginUser: Object,
});

const heart = mdiHeart;
const heartOutline = mdiHeartOutline;
const messageOutline = mdiMessageOutline;

const dialog = ref(false);
const notifications = ref(false);
const sound = ref(false);
const widgets = ref(false);

const goodFrontFlg = ref(false);
const sendCreateGood = async(post_uuid) => {
    await axios.post('/postlist/good/' + post_uuid)
    .then(res => {
        if(res.status === 200) {
            goodFrontFlg.value = true;
            props.post['goods_count']++;
        }
    });
}
const sendDeleteGood = async(post_uuid) => {
    await axios.delete('/postlist/good/' + post_uuid)
    .then(res => {
        if(res.status === 200) {
            goodFrontFlg.value = false;
            props.post['goods_count']--;
            let index;
            if((index = props.post['goods'].findIndex(item => item.user_uuid === props.loginUser['user_uuid'])) >= 0) {
                props.post['goods'][index]['user_uuid'] = '';
            }
        }
    });
}

const currentImagePage = ref(1);
const PagePrev = (p) => {
    p.onClick();
    if (currentImagePage.value > 1) {
        currentImagePage.value--;
    } else {
        currentImagePage.value = props.post["images"].length;
    }
};
const pageNext = (p) => {
    p.onClick();
    if (currentImagePage.value < props.post["images"].length) {
        currentImagePage.value++;
    } else {
        currentImagePage.value = 1;
    }
};
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginUser="props.loginUser"></CommonHeader>
            <v-main>
                <v-row>
                    <v-col offset="1" cols="10">
                        {{ currentImagePage + "/" + props.post["images"].length }}
                        <v-carousel height="350" show-arrows hide-delimiters>
                            <template v-slot:prev="{ props }">
                                <v-btn
                                    variant="elevated"
                                    color="success"
                                    @click="PagePrev(props)"
                                    >前</v-btn
                                >
                            </template>
                            <template v-slot:next="{ props }">
                                <v-btn
                                    variant="elevated"
                                    color="info"
                                    @click="pageNext(props)"
                                    >次</v-btn
                                >
                            </template>
                            <template v-for="image of props.post['images']">
                                <v-carousel-item
                                    :src="image['post_image_path']"
                                    cover
                                ></v-carousel-item>
                            </template>
                        </v-carousel>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="text-h5">
                            タイトル
                        </div>
                    </v-col>
                    <v-spacer></v-spacer>
                    <v-col cols="1">
                        <template v-if="props.loginUser">
                            <template v-if="(props.post['goods'] && props.post['goods'].some(item => item.user_uuid === props.loginUser['user_uuid'])) || goodFrontFlg">
                                <svg-icon
                                    type="mdi"
                                    :path="heart"
                                    style="color: red"
                                    @click="sendDeleteGood(props.post['post_uuid'])"
                                ></svg-icon
                                >{{ props.post['goods_count'] }}
                            </template>
                            <template v-else>
                                <svg-icon
                                    type="mdi"
                                    :path="heartOutline"
                                    @click="sendCreateGood(props.post['post_uuid'])"
                                ></svg-icon
                                >{{ props.post['goods_count'] }}
                            </template>
                        </template>
                    </v-col>
                    <v-col cols="1">
                        <svg-icon
                            type="mdi"
                            :path="messageOutline"
                        >
                        </svg-icon>
                        <v-dialog
                            v-model="dialog"
                            fullscreen
                            :scrim="false"
                            transition="dialog-bottom-transition"
                        >
                        <template v-slot:activator="{ props }">
                            <v-btn
                                color="primary"
                                dark
                                v-bind="props"
                            >
                                Open Dialog
                            </v-btn>
                        </template>
                        <v-card>
                            <v-toolbar
                                dark
                                color="primary"
                            >
                                <v-btn
                                    icon
                                    dark
                                    @click="dialog = false"
                                >
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                                <v-toolbar-title>Settings</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-btn
                                        variant="text"
                                        @click="dialog = false"
                                    >
                                        Save
                                    </v-btn>
                                </v-toolbar-items>
                                </v-toolbar>
                                <v-list
                                    lines="two"
                                    subheader
                                >
                                    <v-list-subheader>User Controls</v-list-subheader>
                                    <v-list-item title="Content filtering" subtitle="Set the content filtering level to restrict apps that can be downloaded"></v-list-item>
                                    <v-list-item title="Password" subtitle="Require password for purchase or use password to restrict purchase"></v-list-item>
                                </v-list>
                                <v-divider></v-divider>
                                <v-list
                                    lines="two"
                                    subheader
                                >
                                    <v-list-subheader>General</v-list-subheader>
                                    <v-list-item title="Notifications" subtitle="Notify me about updates to apps or games that I downloaded">
                                        <template v-slot:prepend>
                                            <v-checkbox v-model="notifications"></v-checkbox>
                                        </template>
                                    </v-list-item>
                                    <v-list-item title="Sound" subtitle="Auto-update apps at any time. Data charges may apply">
                                        <template v-slot:prepend>
                                            <v-checkbox v-model="sound"></v-checkbox>
                                        </template>
                                    </v-list-item>
                                    <v-list-item title="Auto-add widgets" subtitle="Automatically add home screen widgets">
                                        <template v-slot:prepend>
                                            <v-checkbox v-model="widgets"></v-checkbox>
                                        </template>
                                    </v-list-item>
                                </v-list>
                            </v-card>
                        </v-dialog>
                    </v-col>
                </v-row>
                <hr>
                <v-row>
                    <v-col>
                        <div class="text-h5">
                            {{ props.post['title'] }}
                        </div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="text-h5">
                            レビュー
                        </div>
                    </v-col>
                </v-row>
                <hr>
                <v-row>
                    <v-col>
                        <div class="text-h5">
                            {{ props.post['review'] }}
                        </div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="text-h5">
                            投稿者
                        </div>
                    </v-col>
                </v-row>
                <hr>
                <v-row>
                    <v-col>
                        <Link :href="route('profile.show', {user_uuid: props.post['user_uuid']})" class="custom-link">
                            <div class="flex">
                                <Icon :path="props.post['user']['icon_path']"></Icon>
                                <div class="text-h5">
                                    {{ props.post['user']['nick_name'] }}
                                </div>
                            </div>
                        </Link>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <a href="javascript:history.back();">
                            <Button
                                name="戻る"
                                color="blue"
                            >
                            </Button>
                        </a>
                    </v-col>
                </v-row>
            </v-main>
        </v-container>
    </v-app>
</template>

<style scoped>
.flex {
    display: flex;
}

.custom-link {
    text-decoration: none;
    color: black;
}
</style>