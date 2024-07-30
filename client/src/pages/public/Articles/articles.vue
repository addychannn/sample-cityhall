<template>
    <Page>

        <div class="flex flex-col">

            <div class="flex-col lg:px-10 xs:p-2 items-center justify-center gap-10 lg:py-10 sm:px-8">
                <div class="flex flex-row items-start justify-between ">

                    <div class="flex flex-row  pr-2 text-slate-800 p-2">
                        <div class="flex flex-col">
                            <TIcon name="newspaper" size="xl"></TIcon>

                        </div>

                        <div class="flex flex-col">
                            <span
                                class="font-sans text-5xl font-semibold flex flex-col items-center justify-center">Articles</span>

                        </div>

                    </div>




                </div>
                <div class="flex flex-col p-2">

                    <!--search bar-->
                <div class="flex flex-row w-full lg:w-[25rem] gap-2 ">
                    <input 
                        @input="debounceInput"
                        type="text" 
                        id="default-input"
                        class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search Articles">

                    
                        <input 
                        @input="debounceInput"
                        type="date" 
                        id="default-input"
                        class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search Articles">


                </div> 
                </div>


                <!-- article card -->
                <div class="flex-col p-2 grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-for="article in articleList" v-bind:key="article.hash">

                        <!-- sample1 -->
                        <div
                            class=" max-h-sm first-letter: max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <a href="#">
                                <div class="object-cover max-h-full max-w-full">

                                    <!-- <div class="absolute flex flex-col items-end justify-end p-2 ">
                                        <span class=" p-2 font-bold font-sans text-xl bg-green-600 text-white float-right fd-cl group-hover:opacity-25 rounded-2xl shadow-md">
                                            {{ article.created_at }}</span>
                                     
                                     </div> -->
                                    <div class="object-cover h-full md:h-[20rem] w-full">
                                        <template v-if="article.image">
                                            <img :src="article.image.length !=0 ? article.image[0].thumbnails.medium:'/src/assets/smokebaguio.png'" class="w-full h-full rounded-t-lg">
                                        </template>
                                        <template v-else>
                                            <img src="@/assets/smokebaguio.png" class="w-full h-full">
                                        </template>

                                    </div>
                                </div>


                            </a>
                            <div class="p-5">
                                <a href="#">
                                    <h5
                                        class="text-center font-sans mb-2 text-2xl font-bold tracking-tight text-green-600 dark:text-white line-clamp-1">
                                        {{ article.articleTitle }}</h5>
                                </a>

                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class=" w-full text-xs text-center  ">
                                        <span class="font-bold border-b border-white font-sans">
                                            {{article.created_at}}</span>
    
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <p class="line-clamp-3" data-aos="fade-down-left" data-aos-duration="1500"
                                        v-html="article.description"></p>
                                </div>
                                <!-- buttons -->
                                <router-link :to="{ name: 'PublicViewArticle', params: { hash: article.hash } }">
                                    <div class="flex justify-end">
                                        <div
                                            class="flex w-28 cursor-pointer pr-2 hover:scale-110 hover:text-green-800 dark:hover:bg-green-800 text-green-600">
                                            <span class="font-sans text-md">Read more</span>
                                            <TIcon name="arrow_forward" size="sm" class="pt-1"></TIcon>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                    </div>

                </div>

                <div v-if="searchOptions.total == 0" class="p-8 text-center">
                    <span class="font-sans text-xl text-slate-700">({{ loadStatus }})</span>
                </div>
                <!--Pagination-->
                <div class="flex flex-col items-center pt-3">
                    <Pagination v-model="searchOptions.page" v-model:limit="searchOptions.limit"
                        :total="searchOptions.total" v-model:offset="searchOptions.offset"
                        @update:totalPage="(count) => { pages = count }" @paginate="getArticles(searchOptions.term)"
                        direction-nav boundary-nav />
                </div>

            </div>

        </div>



    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import PublicViewArticle from './publicViewArticle.vue';
import _ from 'lodash';

export default defineComponent({
    name: 'publicArticles',
    props: {
        value: Object,
    },
    setup() { },
    components: {
        PublicViewArticle
    },
    mounted() {
        this.getArticles('');
    },
    data() {
        return {
            componentOptions: {
                selectecItem: null,
            },
            searchOptions: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 4,
                offset: 0,
            },
            articleList: null,
            loadStatus: ''
        }
    },
    methods: {
        getArticles(term = '') {
            this.loadStatus = 'Fetching Data'
           
            this.$api.get('/articles/list', {
                params: {
                    term: term,
                    offset: this.searchOptions.offset,
                    limit: this.searchOptions.limit,
                }

            })
                .then((response) => {
                   
                    this.articleList = response.data.article
                    this.searchOptions.total = response.data.total
                    this.articleList.created_at = response.data.date
                    
                    this.loadStatus = 'No Article Found'
                })

        },
        // MODAL


        showModal(mode, item = null) {
           
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            if (item != null) {
                this.componentOptions.selectedItem = item
            }
        },
        closeModal() {
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''

        },
        refreshTable() {
            this.closeModal();
            this.getArticles();
        },


        debounceInput: _.debounce(function (e) {
            this.searchOptions.offset = 0;
            this.searchOptions.term = e.target.value;
            this.getArticles(this.searchOptions.term)
        }, 500)


    },
})
</script>
