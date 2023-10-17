<script setup>
import CommonHeader from "@/Components/CommonHeader.vue";
import SvgIcon from "@jamescoyle/vue-icon";
import axios from "axios";
import TextField from "@/Components/TextField.vue";
import Icon from "@/Components/Icon.vue";
import Button from "@/Components/Button.vue";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { mdiHeart } from "@mdi/js";
import { mdiHeartOutline } from "@mdi/js";
import { mdiMessageOutline } from '@mdi/js';
import { formatDateTimeJp } from "@/format";

const props = defineProps({
    loginUser: Object,
    post: Object,
    message: Object,
    backUrl: String
});

const heart = mdiHeart;
const heartOutline = mdiHeartOutline;
const messageOutline = mdiMessageOutline;

const setFrontPost = ref(props.post);
const setFrontMessage = ref(props.message);

const dialog = ref(false);

const goodFrontFlg = ref(false);
const sendCreateGood = async(post_uuid) => {
    await axios.post('/postlist/good/' + post_uuid)
    .then(res => {
        if(res.status === 200) {
            goodFrontFlg.value = true;
            setFrontPost.value['goods_count']++;
        }
    });
}

const sendDeleteGood = async(post_uuid) => {
    await axios.delete('/postlist/good/' + post_uuid)
    .then(res => {
        if(res.status === 200) {
            goodFrontFlg.value = false;
            setFrontPost.value['goods_count']--;
            let index;
            if((index = setFrontPost.value['goods'].findIndex(item => item.user_uuid === props.loginUser['user_uuid'])) >= 0) {
                setFrontPost.value['goods'][index]['user_uuid'] = '';
            }
        }
    });
}

const inputMessage = ref('');
const sendCreateMessage = async(post_uuid) => {
    await axios.post('/postlist/message/' + post_uuid, { inputMessage: inputMessage.value })
    .then(res => {
        if(res.status === 200) {
            setFrontMessage.value = res.data;
            setFrontPost.value['messages_count'] += 1;
            inputMessage.value = '';
        }
    });
}

const currentImagePage = ref(1);
const PagePrev = (p) => {
    p.onClick();
    if (currentImagePage.value > 1) {
        currentImagePage.value--;
    } else {
        currentImagePage.value = setFrontPost.value["images"].length;
    }
};
const pageNext = (p) => {
    p.onClick();
    if (currentImagePage.value < setFrontPost.value["images"].length) {
        currentImagePage.value++;
    } else {
        currentImagePage.value = 1;
    }
};
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginUser="loginUser"></CommonHeader>
            <v-main>
                <v-row>
                    <v-col offset="1" cols="10">
                        {{ currentImagePage + "/" + setFrontPost["images"].length }}
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
                            <template v-for="image of setFrontPost['images']">
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
                        <template v-if="loginUser">
                            <template v-if="(setFrontPost['goods'] && setFrontPost['goods'].some(item => item.user_uuid === loginUser['user_uuid'])) || goodFrontFlg">
                                <svg-icon
                                    type="mdi"
                                    :path="heart"
                                    style="color: red"
                                    @click="sendDeleteGood(setFrontPost['post_uuid'])"
                                ></svg-icon
                                >{{ setFrontPost['goods_count'] }}
                            </template>
                            <template v-else>
                                <svg-icon
                                    type="mdi"
                                    :path="heartOutline"
                                    @click="sendCreateGood(setFrontPost['post_uuid'])"
                                ></svg-icon
                                >{{ post['goods_count'] }}
                            </template>
                        </template>
                    </v-col>
                    <v-col cols="1">
                        <v-dialog
                            v-model="dialog"
                            fullscreen
                            :scrim="false"
                            transition="dialog-bottom-transition"
                        >
                            <template v-slot:activator="{ props }">
                                <svg-icon
                                    v-bind="props"
                                    type="mdi"
                                    :path="messageOutline"
                                ></svg-icon>
                                {{ setFrontPost['messages_count'] }}
                            </template>
                            <v-card>
                                <v-toolbar
                                    dark
                                    color="#993300"
                                >
                                    <v-btn
                                        icon
                                        dark
                                        @click="dialog = false"
                                    >
                                        閉じる
                                    </v-btn>
                                </v-toolbar>
                                <v-card-text>
                                    <v-container>
                                        <v-row v-for="message of setFrontMessage">
                                            <v-col offset="2" cols="8">
                                                <div style="display: flex;">
                                                    <Icon :path="message['user']['icon_path']" size="60"></Icon>
                                                    <div>{{ message['user']['nick_name'] }} : </div>
                                                    <div>{{ formatDateTimeJp(message['created_at']) }}</div>
                                                </div>
                                                <v-card :text="message['message']" variant="tonal"></v-card>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-footer v-if="loginUser" class="custom-footer">
                                    <v-row>
                                        <v-col offset="1" cols="8">
                                            <TextField
                                                label="メッセージ"
                                                v-model="inputMessage"
                                            >
                                            </TextField>
                                        </v-col>
                                        <v-col cols="2">
                                            <Button
                                                :disabled="inputMessage ? false : true"
                                                name="送信"
                                                @click="sendCreateMessage(setFrontPost['post_uuid'])"
                                                :style="{ height: '67%' }"
                                            >
                                            </Button>
                                        </v-col>
                                    </v-row>
                                </v-footer>
                            </v-card>
                        </v-dialog>
                    </v-col>
                </v-row>
                <hr>
                <v-row>
                    <v-col>
                        <div class="text-h5">
                            {{ setFrontPost['title'] }}
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
                            {{ setFrontPost['review'] }}
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
                        <Link :href="route('profile.show', {user_uuid: setFrontPost['user_uuid']})" class="custom-link">
                            <div class="flex">
                                <Icon :path="setFrontPost['user']['icon_path']"></Icon>
                                <div class="text-h5">
                                    {{ setFrontPost['user']['nick_name'] }}
                                </div>
                            </div>
                        </Link>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <Link :href="backUrl">
                            <Button
                                name="戻る"
                                color="blue"
                            >
                            </Button>
                        </Link>
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

.custom-footer {
    position: sticky;
    bottom: 0;
    max-height: 15%;
    background-color: white; /* フッターの背景色を設定してください */
    z-index: 1; /* フッターが他の要素の上に表示されるようにz-indexを設定します */
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2); /* フッターの下部に影を追加します */
}
</style>