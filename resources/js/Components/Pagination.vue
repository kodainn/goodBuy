<script setup>
import Button from './Button.vue';
import { ref } from "vue";
import { Link } from '@inertiajs/vue3';

defineProps({
    genre: {
        type: Number,
        default: 0
    },
    currentPage: Number,
    maxPage: Number
});

const innerWidth = ref(window.innerWidth);
</script>

<template>
    <v-container>
        <v-row v-if="innerWidth >= 900">
            <v-col
                cols="1"
                md="1"
                lg="1"
            >
                <template v-if="currentPage > 1 && maxPage > 0">
                    <Link v-if="genre === 0" :href="route('postlist.paginate', {'page': currentPage - 1})">
                        <Button
                            name="<"
                        >
                        </Button>
                    </Link>
                    <Link v-else :href="route('postlist.search.paginate', {'page': currentPage - 1, 'genre': genre})">
                        <Button
                            name="<"
                        >
                        </Button>
                    </Link>
                </template>
            </v-col>
            <template v-if="maxPage > 1">
                <v-col
                    v-for="page in maxPage"
                    cols="1"
                    md="1"
                    lg="1"
                >
                    <Link v-if="genre === 0" :href="route('postlist.paginate', {'page': page})">
                        <Button
                            :name="String(page)"
                            :class="{'active-button': page === currentPage}"
                        >
                        </Button>
                    </Link>
                    <Link v-else :href="route('postlist.search.paginate', {'page': page, 'genre': genre})">
                        <Button
                            :name="String(page)"
                            :class="{'active-button': page === currentPage}"
                        >
                        </Button>
                    </Link>
                </v-col>
                <v-col
                    cols="1"
                    md="1"
                    lg="1"
                >
                    <template v-if="currentPage < maxPage">
                        <Link v-if="genre === 0" :href="route('postlist.paginate', {'page': currentPage + 1})">
                            <Button
                                name=">"
                            >
                            </Button>
                        </Link>
                        <Link v-else :href="route('postlist.search.paginate', {'page': currentPage + 1, 'genre': genre})">
                            <Button
                                name=">"
                            >
                            </Button>
                        </Link>
                    </template>
                </v-col>
            </template>
        </v-row>
        <v-row v-else>
            <v-col>
                <template v-if="currentPage > 1 && maxPage > 0">
                    <Link v-if="genre === 0" :href="route('postlist.paginate', {'page': currentPage - 1})">
                        <Button
                            name="<前のページへ"
                        >
                        </Button>
                    </Link>
                    <Link v-else :href="route('postlist.search.paginate', {'page': currentPage - 1, 'genre': genre})">
                        <Button
                            name="<前のページへ"
                        >
                        </Button>
                    </Link>
                </template>
                <template v-if="currentPage < maxPage">
                    <Link v-if="genre === 0" :href="route('postlist.paginate', {'page': currentPage + 1})">
                        <Button
                            name="次のページ>"
                        >
                        </Button>
                    </Link>
                    <Link v-else :href="route('postlist.search.paginate', {'page': currentPage + 1, 'genre': genre})">
                        <Button
                            name="次のページ>"
                        >
                        </Button>
                    </Link>
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.active-button {
    background-color: blue;
    color: white;
}

</style>