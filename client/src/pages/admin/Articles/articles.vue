<template>
    <Page>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <DeleteArticle :article="componentOptions.selectedItem" v-if="componentOptions.mode == 'delete'"
                @cancel="closeModal" @done="refreshTable" />
        </Modal>

        <div class="flex flex-col ">

            <div class="flex-col px-2 md:px-5 lg:px-10 items-center justify-center gap-10 py-1 ">

                <!--header-->
                <div class="flex flex-row md:py-5 justify-between">
                    <!--label-->
                    <div class="flex flex-row text-slate-800 pt-2 items-center">
                        <TIcon name="newspaper" size="xl" class="text-slate-800 md:px-2"></TIcon>
                        <span class="text-xl md:text-3xl lg:text-5xl font-sans text-slate-800 font-semibold">Articles</span>

                    </div>

                    <!--actions-->
                    <div class="flex flex-row items-center lg:pr-4 gap-2">

                        <router-link :to="{ name: 'AddArticle' }">
                            <TIcon v-if="$guard.can(['article_add'])" name="add" size="md"
                                class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md hover:scale-110 hover:bg-green-700">
                            </TIcon>
                        </router-link>

                        <router-link :to="{ name: 'ListOfDeletedArticles' }">
                            <TIcon v-if="$guard.can(['article_list-deleted'])" name="folder_delete" size="md"
                                class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md hover:scale-110 hover:bg-green-700">
                            </TIcon>
                            <TToolTip class="text-center">
                                <span class="font-sans text-center">Deleted Articles</span>
                            </TToolTip>
                        </router-link>
                    </div>

                </div>

                <!--search-->
                <div class=" flex-col p-2 ">
                    <div class="inline-flex gap-4 justify-between">
                        <div class="flex flex-row w-full md:w-[25rem] gap-2">
                            <input @input="debounceInput" type="text" id="default-input"
                                class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search Articles">

                            <input @input="debounceInput" type="date" id="default-input"
                                class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search Articles">
                        </div>



                    </div>




                </div>


                <!-- article card -->
                <div
                    class="items-center justify-center flex-col p-2 grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-for="article in articleList" v-bind:key="article.hash">

                        <!-- sample2 -->
                        <div class="flex flex-col items-center justify-center">
                            <div
                                class="2xl:h-[40rem] xl:h-[40rem] 2lg:h-[35rem] lg:h-[26rem] md:h-[25rem] xs:h-[40rem]  first-letter: max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 items-center justify-between">
                                <div class="flex flex-col items-center justify-center">


                                    <div class="object-cover max-h-full max-w-full">



                                        <div class="object-cover h-full w-full">
                                            <template v-if="article.image">
                                                <img :src="article.image.length != 0 ? article.image[0].thumbnails.medium : '/src/assets/smokebaguio.png'"
                                                    class="w-full h-full  md:h-[10rem] 2xl:h-[25rem] xs:h-[25rem] 2lg:h-[20rem] lg:h-[14rem] object-cover rounded-t-lg">
                                            </template>
                                            <template v-else>
                                                <img src="@/assets/smokebaguio.png"
                                                    class="w-full h-full  md:h-[10rem] 2xl:h-[25rem] 2lg:h-[20rem] object-cover">
                                            </template>

                                        </div>


                                    </div>



                                    <a href="#">
                                        <h5
                                            class=" font-sans text-center p-2 2xl:text-2xl xl:text-2xl lg:text-lg font-bold tracking-tight text-gray-900 dark:text-white w-full  md:line-clamp-1 ">
                                            {{ article.articleTitle }}</h5>
                                    </a>

                                    <div class="flex flex-col items-center justify-center">
                                        <div class=" p-2 w-full text-xs text-center  ">
                                            <span class="font-bold border-b border-white font-sans">
                                                {{ article.created_at }}</span>

                                        </div>
                                    </div>


                                    <div class="p-2">

                                        <div class="flex items-center justify-center">

                                            <p class="2xl:line-clamp-3 2lg:line-clamp-3 lg:line-clamp-2 xs:line-clamp-3 ss:line-clamp-3 px-2"
                                                data-aos="fade-down-left" data-aos-duration="1500"
                                                v-html="article.description"></p>

                                        </div>

                                    </div>
                                </div>


                                <div class="flex flex-col justify-center items-center ">
                                    <div class="flex flex-row justify-center w-28 pr-2 gap-3 lg:p-2.5">
                                        <router-link :to="{ name: 'EditArticle', params: { hash: article.hash } }">
                                            <TIcon v-if="$guard.can(['article_edit'])" name="edit" size="md "
                                                class="pt-1 hover:scale-110 hover:text-blue-800 dark:hover:bg-blue-800 text-blue-600"
                                                :class="$guard.can(['article_edit']) ? 'cursor-pointer' : 'hidden'">
                                            </TIcon>
                                        </router-link>
                                        <router-link :to="{ name: 'ViewArticle', params: { hash: article.hash } }">
                                            <TIcon name="visibility" size="md"
                                                class="pt-1 hover:scale-110 hover:text-black dark:hover:bg-gray-900 text-gray-800 cursor-pointer">
                                            </TIcon>
                                        </router-link>

                                        <TIcon v-if="$guard.can(['article_delete'])" @click="showModal('delete', article)"
                                            name="delete" size="md"
                                            class="pt-1 hover:scale-110 hover:text-red-800 dark:hover:bg-red-800 text-red-600"
                                            :class="$guard.can(['article_delete']) ? 'cursor-pointer' : 'hidden'">
                                        </TIcon>
                                    </div>
                                </div>
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
import ViewArticle from './viewArticle.vue';
import EditArticle from './editArticle.vue';
import DeleteArticle from './deleteArticle.vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import _ from 'lodash';

export default defineComponent({
    name: 'Articles',
    // props:{
    //     type: Object,
    //     default: null,
    // },
    components: {
        ViewArticle,
        EditArticle,
        DeleteArticle,
        QuillEditor
    },


    setup() { },
    mounted() {
        this.getArticles('');
    },
    data() {
        return {

            componentOptions: {

                selectedItem: null,


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
            // gallery:[],

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

                    // this.gallery = this.articleList.image
                    this.loadStatus = 'No Articles Found'
                })

        },
        // MODAL


        showModal(mode, article = null) {

            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            if (article != null) {
                this.componentOptions.selectedItem = article
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

    handleDrop(article) {
        this.fileleave(article)
        article.preventDefault()
        this.componentOptions.isDraggingFile = false
        this.componentOptions.isDropped = true
        const files = Array.from(article.dataTransfer.items).map((item) => {
            if (item.kind !== 'file') return null
            let base = ''
            var reader = new FileReader()
            reader.readAsDataURL(item.getAsFile())
            reader.onload = () => {
                this.files.push(reader.result)
            }
            return base
        }).filter(Boolean);

        files.forEach((i) => this.componentOptions.files.push(i))

    },

    fileEnter(article) {
        article.preventDefault();
        this.componentOptions.isDraggingFile = true
    },
    fileLeave(article) {
        article.preventDefault();
        this.componentOptions.isDraggingFile = false
    }

})
</script>
