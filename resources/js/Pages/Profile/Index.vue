<script setup>
import CommonHeader from "@/Components/CommonHeader.vue";
import Icon from "@/Components/Icon.vue";
import SvgIcon from "@jamescoyle/vue-icon";
import Button from '@/Components/Button.vue';
import ErrorMessage from "@/Components/ErrorMessage.vue";
import TextField from "@/Components/TextField.vue";
import TextArea from "@/Components/TextArea.vue";
import { Link, router } from "@inertiajs/vue3";
import { mdiHeart } from "@mdi/js";
import { ref, reactive } from "vue";
import axios from "axios";

const props = defineProps({
    selfProfile: {
        type: Boolean,
        default: false
    },
    loginUser: Object,
    typeGenreDivKv: Object,
    user: Object,
    posts: Object,
    follows: Object,
    allFollows: Object,
    followers: Object,
    counts: Object,
    errors: Object
});

const heart = mdiHeart;

const dialog = ref(false);
const frontUser = ref(props.user);
const frontCounts = ref(props.counts);


const frontPost = ref(props.posts);
const frontPostMoreCount = ref(1);
const morePostRequest = async(user_uuid) => {
    frontPostMoreCount.value++;
    await axios.get('/profile/' + user_uuid + '/morePost/' + frontPostMoreCount.value)
    .then(res => {
        if(res.status === 200) {
            for(const post of res.data) {
                frontPost.value.push(post);
            }
        }
    });
}


const postDelete = async(post_uuid, user_uuid) => {
    let success_flg = ref(false);
    if(confirm('本当に削除しますか?')) {
        await axios.delete('/postlist/' + post_uuid)
        .then(res => {
            if(res.status === 200) {
                success_flg.value = true;
            }
        });

        if(!success_flg.value){return ;}

        await axios.get('/profile/' + user_uuid + '/limitPost/' + frontPostMoreCount.value)
        .then(res => {
            if(res.status === 200) {
                frontPost.value = res.data;
                frontCounts.value['postCount']--;
            }
        })
    }
}


const frontFollowMoreCount = ref(1);
const frontFollowerMoreCount = ref(1);
const followFrontFlg = ref(false);
const frontFollower = ref(props.followers);
const sendCreateFollow = async(user_uuid) => {
    let create_success = ref(false);
    await axios.post('/profile/follow/' + user_uuid)
    .then(res => {
        if(res.status === 200) {
            create_success.value = res.data['create_success'];
        }
    });

    if(!create_success.value){return ;}

    await axios.get('/profile/' + user_uuid + '/limitFollower/' + frontFollowerMoreCount.value)
    .then(res => {
        if(res.status === 200) {
            frontFollower.value = res.data;
            frontCounts.value['followerCount']++;
            followFrontFlg.value = true;
        }
    })
}


const sendDeleteFollow = async(user_uuid) => {
    if(!confirm('フォローを辞めますか?')) {return;}
    let delete_success = ref(false);
    await axios.delete('/profile/follow/' + user_uuid)
    .then(res => {
        if(res.status === 200) {
            delete_success.value = res.data['delete_success'];
        }
    });

    if(!delete_success.value) {return ;}

    await axios.get('/profile/' + user_uuid + '/limitFollower/' + frontFollowerMoreCount.value)
    .then(res => {
        if(res.status === 200) {
            followFrontFlg.value = false;
            frontFollower.value = res.data;
            frontCounts.value['followerCount']--;
            let index;
            if((index = props.allFollows.findIndex(item => item.user_uuid === props.loginUser['user_uuid'])) >= 0) {
                props.allFollows[index]['user_uuid'] = '';
            }
        }
    })
}


const moreFollowerRequest = async(user_uuid) => {
    frontFollowerMoreCount.value++;
    await axios.get('/profile/' + user_uuid + '/moreFollower/' + frontFollowerMoreCount.value)
    .then(res => {
        if(res.status === 200) {
            for(const follower of res.data) {
                frontFollower.value.push(follower);
            }
        }
    })
}


const frontFollow = ref(props.follows);
const moreFollowRequest = async(user_uuid) => {
    frontFollowMoreCount.value++;
    await axios.get('/profile/' + user_uuid + '/moreFollow/' + frontFollowMoreCount.value)
    .then(res => {
        if(res.status === 200) {
            for(const follow of res.data) {
                frontFollow.value.push(follow);
            }
        }
    })
}


const form = reactive({
    nickName: frontUser.value['nick_name'],
    pr: frontUser.value['pr'],
    iconPath: frontUser.value['icon_path'],
    updateIconFlg: false
});


const sendForm = () => {
    router.post(route('profile.update'), form, {
        onSuccess: () => {
            dialog.value = false;
        }
    });
}


const tab = ref(null);
const iconReviewPath = ref('');
const setIconPath = ($event) => {
    if ($event.target.files.length > 0) {
        const file = $event.target.files[0];
        form.iconPath = file;
        form.updateIconFlg = true;
        iconReviewPath.value = URL.createObjectURL(file);
    }
}
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginUser="loginUser"></CommonHeader>
            <v-main>
                <v-container>
                    <v-row justify="space-around">
                        <v-card width="800">
                            <v-card-text>
                                <v-container>
                                    <v-row v-if="!selfProfile && loginUser">
                                        <v-col>
                                            <div class="text-left mb-2">
                                                <Link
                                                    :href="route('profile.index')"
                                                    style="text-decoration: none;"    
                                                >
                                                    {{ "<戻る" }}
                                                </Link>
                                            </div>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <div class="text-center mb-2">
                                                <Icon size="150" :path="user['icon_path']"></Icon>
                                            </div>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col
                                            offset="0"
                                            sm="4"
                                            md="4"
                                            lg="4"
                                        >
                                            <div class="text-h5">
                                                {{ user["nick_name"] }}
                                            </div>
                                        </v-col>
                                        <v-col
                                            offset-sm="5"
                                            offset-md="6"
                                            offset-lg="6"
                                            sm="1"
                                            md="1"
                                            lg="1"
                                            v-if="selfProfile"
                                        >
                                            <v-dialog
                                                v-model="dialog"
                                                transition="dialog-bottom-transition"
                                                width="70%"
                                            >
                                                <template v-slot:activator="{ props }">
                                                    <Button
                                                        rounded
                                                        name="編集"
                                                        color="purple"
                                                        v-bind:="props"
                                                    ></Button>
                                                </template>
                                                <v-card>
                                                    <v-card-title>
                                                        <span class="text-h5">プロフィール編集</span>
                                                    </v-card-title>
                                                    <form @submit.prevent="sendForm" enctype="multipart/form-data">
                                                        <v-card-text>
                                                            <v-container>
                                                                <v-row>
                                                                    <v-col>
                                                                        <div class="text-center mb-2">
                                                                            <Icon
                                                                                v-if="iconReviewPath"
                                                                                size="150"
                                                                                :path="iconReviewPath"
                                                                            ></Icon>
                                                                            <Icon
                                                                                v-else
                                                                                size="150"
                                                                                :path="form.iconPath"
                                                                            ></Icon>
                                                                        </div>
                                                                    </v-col>
                                                                </v-row>
                                                                <v-row>
                                                                    <v-col
                                                                        offset-md="3"
                                                                        offset-lg="3"
                                                                        cols="12"
                                                                        sm="12"
                                                                        md="6"
                                                                        lg="6"
                                                                    >
                                                                        <label for="file-input" class="custom-label-button">アイコンを選ぶ</label>
                                                                        <input
                                                                            hidden
                                                                            id="file-input"
                                                                            type="file"
                                                                            accept="image/*"
                                                                            @change="setIconPath($event)"
                                                                            ref="image"
                                                                        >
                                                                    </v-col>
                                                                </v-row>
                                                                <v-row>
                                                                    <v-col
                                                                        offset-md="3"
                                                                        offset-lg="3"
                                                                        cols="12"
                                                                        sm="12"
                                                                        md="6"
                                                                        lg="6"
                                                                    >
                                                                        <TextField
                                                                            label="ニックネーム"
                                                                            v-model="form.nickName"
                                                                            autocomplete
                                                                        >
                                                                        </TextField>
                                                                        <ErrorMessage :errorMessage="errors.nickName"></ErrorMessage>
                                                                    </v-col>
                                                                </v-row>
                                                                <v-row>
                                                                    <v-col
                                                                        offset-md="3"
                                                                        offset-lg="3"
                                                                        cols="12"
                                                                        sm="12"
                                                                        md="6"
                                                                        lg="6"
                                                                    >
                                                                        <TextArea
                                                                            label="自己PR"
                                                                            v-model="form.pr"
                                                                            autocomplete
                                                                        >
                                                                        </TextArea>
                                                                        <ErrorMessage :errorMessage="errors.pr"></ErrorMessage>
                                                                    </v-col>
                                                                </v-row>
                                                                <v-row>
                                                                <v-col
                                                                    offset-md="3"
                                                                    offset-lg="3"
                                                                    cols="12"
                                                                    sm="12"
                                                                    md="6"
                                                                    lg="6"
                                                                >
                                                                    <Button
                                                                        name="保存する"
                                                                        color="blue"
                                                                        type="submit"
                                                                    >
                                                                    </Button>
                                                                </v-col>
                                                            </v-row>
                                                            </v-container>
                                                        </v-card-text>
                                                    </form>
                                                </v-card>
                                            </v-dialog>
                                        </v-col>
                                        <v-col
                                            v-else-if="!selfProfile && loginUser"
                                            offset-sm="5"
                                            offset-md="6"
                                            offset-lg="6"
                                            sm="1"
                                            md="2"
                                            lg="2"
                                        >
                                            <Button
                                                v-if="(allFollows && allFollows.some(item => item.user_uuid === loginUser['user_uuid'])) || followFrontFlg"
                                                rounded
                                                large
                                                name="フォロー中"
                                                background-color="orchid"
                                                color="white"
                                                @click="sendDeleteFollow(frontUser['user_uuid'])"
                                            ></Button>
                                            <Button
                                                v-else
                                                rounded
                                                name="フォロー"
                                                color="purple"
                                                @click="sendCreateFollow(frontUser['user_uuid'])"
                                            ></Button>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col offset="0">
                                            <div>
                                                {{ user["pr"] }}
                                            </div>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <v-tabs
                                                v-model="tab"
                                                color="deep-purple-accent-4"
                                                align-tabs="center"
                                            >
                                            <v-tab value="postList">投稿一覧({{ frontCounts['postCount'] }})</v-tab>
                                            <v-tab value="followList">フォロワー({{ frontCounts['followerCount'] }})</v-tab>
                                            <v-tab value="followerList">フォロー中({{ frontCounts['followCount'] }})</v-tab>
                                            </v-tabs>
                                            <v-window v-model="tab">
                                                <!-- 投稿一覧 -->
                                                <v-window-item
                                                    key="postList"
                                                    value="postList"
                                                >
                                                    <v-container fluid>
                                                        <v-row>
                                                            <v-col
                                                                offset="0"
                                                                cols="12"
                                                                sm="6"
                                                                md="4"
                                                                lg="3"
                                                                v-for="post of frontPost"
                                                            >
                                                                <v-card class="mx-auto my-12" max-width="374">
                                                                    <template v-slot:loader="{ isActive }">
                                                                        <v-progress-linear
                                                                            :active="isActive"
                                                                            color="deep-purple"
                                                                            height="4"
                                                                            indeterminate
                                                                        ></v-progress-linear>
                                                                    </template>
                                                                    <v-img
                                                                        cover
                                                                        height="250"
                                                                        :src="post['images'][0]['post_image_path']"
                                                                    ></v-img>
                                                                    <v-card-text>
                                                                        <div class="text-h5 text-primary">
                                                                            {{ typeGenreDivKv[post['genre_div']] }}
                                                                        </div>
                                                                        <div class="text-h6">
                                                                            {{ post['title'].length >= 15 ? post['title'].substr(0, 15) + '...' : post['title'] }}
                                                                        </div>
                                                                    </v-card-text>
                                                                    <v-card-actions>
                                                                        <v-row>
                                                                            <v-col>
                                                                                <Link
                                                                                    :href="
                                                                                        route('postlist.show', { post_uuid: post['post_uuid'] })
                                                                                    "
                                                                                >
                                                                                    <Button
                                                                                        variant="text"
                                                                                        name="詳細を見る"
                                                                                        color="blue"
                                                                                    >
                                                                                    </Button>
                                                                                </Link>
                                                                                <Button
                                                                                    v-if="loginUser && loginUser['user_uuid'] === post['user_uuid']"
                                                                                    variant="text"
                                                                                    name="削除"
                                                                                    color="red"
                                                                                    @click="postDelete(post['post_uuid'], post['user_uuid'])"
                                                                                >
                                                                                </Button>
                                                                            </v-col>
                                                                            <v-col offset="1">
                                                                                <template v-if="loginUser">
                                                                                    <svg-icon
                                                                                        type="mdi"
                                                                                        :path="heart"
                                                                                        style="color: red"
                                                                                    ></svg-icon
                                                                                    >{{ post['goods_count'] }}
                                                                                </template>
                                                                            </v-col>
                                                                        </v-row>
                                                                    </v-card-actions>
                                                                </v-card>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <Button
                                                                    :style="{display: frontPost.length === frontCounts['postCount'] && frontCounts['postCount'] <= 50 ? 'none' : ''}"
                                                                    variant="text"                                                         
                                                                    name="もっと見る"
                                                                    @click="morePostRequest(frontPost[0]['user_uuid'])"
                                                                >
                                                                </Button>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-window-item>

                                                <!-- フォロワー -->
                                                <v-window-item
                                                    key="followList"
                                                    value="followList"
                                                >
                                                    <v-container fluid>
                                                        <v-row>
                                                            <v-col
                                                                v-for="follower of frontFollower"
                                                                cols="12"
                                                            >
                                                                <Link class="custom-link" :href="route('profile.show', {user_uuid: follower.users[0]['user_uuid']})">
                                                                    <Icon :path="follower.users[0]['icon_path']"></Icon>
                                                                    {{ follower.users[0]['nick_name'] }}
                                                                </Link>
                                                                <hr>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <Button
                                                                    :style="{display: frontFollower.length === frontCounts['followerCount'] && frontCounts['followerCount'] <= 50 ? 'none' : ''}"
                                                                    variant="text"                                                         
                                                                    name="もっと見る"
                                                                    @click="moreFollowerRequest(user['user_uuid'])"
                                                                >
                                                                </Button>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-window-item>

                                                <!-- フォロー中 -->
                                                <v-window-item
                                                    key="followerList"
                                                    value="followerList"
                                                >
                                                    <v-container fluid>
                                                        <v-row>
                                                            <v-col
                                                                v-for="follow of frontFollow"
                                                                cols="12"
                                                            >
                                                                <Link class="custom-link" :href="route('profile.show', {user_uuid: follow.users[0]['user_uuid']})">
                                                                    <Icon :path="follow.users[0]['icon_path']"></Icon>
                                                                    {{ follow.users[0]['nick_name'] }}
                                                                </Link>
                                                                <hr>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col>
                                                                <Button
                                                                    :style="{display: frontFollow.length === frontCounts['followCount'] && frontCounts['followCount'] <= 50 ? 'none' : ''}"
                                                                    variant="text"                                                         
                                                                    name="もっと見る"
                                                                    @click="moreFollowRequest(user['user_uuid'])"
                                                                >
                                                                </Button>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-window-item>
                                            </v-window>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-row>
                </v-container>
            </v-main>
        </v-container>
    </v-app>
</template>

<style scoped>
.custom-label-button {
    cursor: pointer; /* マウスオーバー時にカーソルをポインターに変更 */
    padding: 10px 20px; /* ボタンのパディング */
    background-color: #007bff; /* ボタンの背景色 */
    color: #fff; /* ボタンのテキスト色 */
    border: none; /* ボタンのボーダーを削除 */
    border-radius: 5px; /* ボタンの角丸 */
    display: inline-block; /* ボタンをインラインブロックに設定 */
}

.custom-label-button:hover {
    background-color: #0056b3; /* マウスオーバー時の背景色 */
}

.custom-link {
    text-decoration: none;
    color: black;
}

</style>
