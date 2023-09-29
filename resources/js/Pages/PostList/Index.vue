<script setup>
import CommonHeader from "@/Components/CommonHeader.vue";
import PullDown from "@/Components/PullDown.vue";
import { reactive } from "vue";
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiHeart } from '@mdi/js';
import { mdiHeartOutline } from "@mdi/js";
import Button from "@/Components/Button.vue";
import { ref } from "vue";
import { computed } from "vue";

defineProps({
    loginStatus: Boolean,
    typeDiv: Array,
});

const heart = mdiHeart;
const heartOutline = mdiHeartOutline;

const form = reactive({
    searchGenre: null,
});

const goodStack = ref([]);

const addGoodStack = (i) => {
    if(goodStack.value.indexOf(i) < 0) {
        goodStack.value.push(i)
    }
}

const subGoodStack = (i) => {
    const indexToRemove = goodStack.value.indexOf(i);
    if(indexToRemove !== -1) {
        goodStack.value.splice(indexToRemove, 1);
    }
}

const displayGoodStack = computed(() => goodStack.value);
</script>

<template>
    <v-app>
        <v-container>
            <CommonHeader :loginStatus="loginStatus"></CommonHeader>
            <v-main>
                <v-container>
                    <v-row>
                        <v-col offset="0" cols="4">
                            <PullDown
                                :typeDiv="typeDiv"
                                name="ジャンル"
                                v-model="form.searchGenre"
                            >
                            </PullDown>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col offset="0" cols="3" v-for="i in 10">
                            <v-card
                                class="mx-auto my-12"
                                max-width="374"
                            >
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
                                    src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
                                ></v-img>
                                <v-card-text>
                                    <div class="text-h6">
                                        Small plates, salads & sandwiches - an
                                        intimate setting with 12 indoor seats
                                        plus patio seating.
                                    </div>
                                </v-card-text>
                                <v-card-actions>
                                    <v-row>
                                        <v-col>
                                            <Button
                                                variant="text"
                                                name="詳細を見る"
                                                color="blue"
                                            >
                                            </Button>
                                        </v-col>
                                        <v-col offset="3">
                                            <template v-if="loginStatus">
                                                <template v-if="displayGoodStack.indexOf(i) === -1">
                                                    <svg-icon type="mdi" :path="heartOutline" @click="addGoodStack(i)"></svg-icon>255
                                                </template>
                                                <template v-else>
                                                    <svg-icon type="mdi" :path="heart" style="color: red;" @click="subGoodStack(i)"></svg-icon>256
                                                </template>
                                            </template>
                                        </v-col>
                                    </v-row>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-main>
        </v-container>
    </v-app>
</template>
