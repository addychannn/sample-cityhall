<template>
    <Page class="w-full h-full p-5">

        <div class="flex flex-col pl-4">
            <div class="flex flex-col sm:p-5 p-2  sm:mx-2 md:mx-5 lg:mx-20 gap-5">
                <div class="block items-center justify-center rounded-3xl  py-3">
                    <div class="flex flex-row items-center justify-center">
                        <div class="flex   pr-2 text-slate-800 p-2">
                          
                            <span
                                class="font-sans sm:text-4xl text-5xl font-bold font  items-center justify-center">{{form.articleTitle}}</span>

                        </div>
                        
                        
                    </div>

                    <div class="flex flex-row items-center justify-center">
                        <div class="flex-row flex gap-4 items-center justify-center p-2 pb-4 ">
                            <div class="flex flex-col">
                                <TIcon name="schedule" size="md"></TIcon>
    
                            </div>
                            <div>
                                
                                <span class="font-sans ">{{form.created_at}}</span>
                            </div>

                        </div>


                    </div>

                    <form>

                        <span>

                            <!--img carousel-->
                            <div class="flex flex-col w-full items-center">
                                <div v-if="galleryLength !=0" class="flex flex-col w-full">
                                    <Splide :options="{ rewind: true, autoplay: true, interval: 2000, arrows: galleryLength != 1 }"
                                        aria-label="My Favorite Images" class="flex flex-col w-full">
                                        <SplideSlide v-for="image in gallery"
                                            class="flex flex-col items-center  rounded-2xl">
                                            <img :src="image.thumbnails.medium"
                                                class="h-[600px] rounded-2xl hover:scale-110 center" />
                                        </SplideSlide>
                                    </Splide>
                                </div>
                                <div 
                                class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl"
                                v-if="galleryLength==0">
                                    <span class="font-sans"> (No Image/s Available)</span>
                            </div>
                            </div>
                            <div class="flex flex-row items-center justify-center p-4">


                                <p data-aos="fade-down-left" data-aos-duration="1500" v-html="form.description"></p>
                            </div>

                        </span>
                        <div v-if="$guard.can(['article_edit'])" class="flex flex-col items-end justify-end">
                            <div class="flex flex-col items-end justify-end p-2 w-[15rem]">
                                <router-link :to="{ name: 'EditArticle' }">
                                    <div
                                        class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                                        <span class="font-sans">Edit Article
                                        </span>

                                    </div>
                                </router-link>


                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>


    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import EditArticle from './editArticle.vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

export default defineComponent({
    name: 'ViewArticle',
    $emits: ['done'],
    setup() { },
    components: {
        EditArticle,
        QuillEditor,
        Splide,
        SplideSlide
    },

    async mounted() {

        await this.getArticle()

    },

    props: {
        type: Object,
        default: null,
    },

    data() {
        return {
            componentOptions: {},

            form: {
                articleTitle: '',
                description: '',
                created_at: '',

            },

            articleList: null,

            gallery: [],

            galleryLength: 0,


        }
    },

    methods: {
        async getArticle() {

            this.$api.get(`admin/articles/${this.$route.params.hash}`)
                .then((response) => {
                    this.article = response.data.article
                    this.form.articleTitle = this.article.articleTitle
                    this.form.description = this.article.description
                    this.form.created_at = this.article.created_at

                   
                    this.gallery = response.data.gallery
                if(this.gallery){
                    this.galleryLength = this.gallery.length
                }
                })
        },


        submit() {
            let changes = this.getChanges(this.form)
            
            this.$api.patch('/article/edit', changes)
                .then((response) => {
                    this.$emit('done');
                })

        },
        getChanges(form) {
            let changes = {}
            changes['id'] = this.article.hash
            if (form.articleTitle != this.article.articleTitle) {
                changes['articleTitle'] = form.articleTitle
            }
            if (form.description != this.article.description) {
                changes['description'] = form.description
            }
            if (form.created_at != this.article.created_at) {
                changes['created_at'] = form.created_at
            }

            return changes;
        },





    }


})
</script>
