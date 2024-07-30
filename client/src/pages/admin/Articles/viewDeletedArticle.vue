<template>
    <Page>

        <div class="flex flex-col px-5">
            <div class="p-6">
                <div class="block items-center justify-center rounded-3xl  py-3">
                    <div class="flex flex-row items-start justify-start">
                        <div class="flex   pr-2 text-slate-800 p-2">
                            <TIcon name="calendar_month" size="xl" class="pt-1"></TIcon>
                            <span class="font-sans text-5xl font-semibold flex flex-col items-center justify-center">VIEW DELETED ARTICLE</span>

                        </div>
                    </div>

                    <form>

                        <div v-for="article in articleList">
                            <!--img carousel-->
                            <div class="flex flex-col w-full items-center">
                                <div v-if="article.image" class="flex flex-col w-full">
                                    <Splide :options="{ rewind: true,autoplay:true, interval:2000 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                        <SplideSlide v-for="image in article.image" class="flex flex-col items-center rounded-2xl w-full">
                                                <img :src="image.thumbnails.medium" class="xl:h-[360px] sm:h-[250px] hover:scale-110 center"/>
                                        </SplideSlide>
                                    </Splide>
                                </div>
                                <div 
                                    class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl"
                                    v-if="!article.image">
                                        <span class="font-sans"> (No Image/s Available)</span>
                                </div>
                            </div>


                            <div class="flex flex-row p-5 items-center justify-center">

                                <span class="text-base font-sans text-center text-slate rounded-2xl bg-slate-100">
                                    {{ article.articleTitle }}
                                </span>

                            </div>
                            <div class="flex flex-row items-center justify-center">
                                <div class="font-sans flex-col gap-4 items-center justify-center">

                                    <span class="font-sans items-center justify-center">
                                        {{ article.created_at }}
                                    </span>


                                </div>


                            </div>
                            <div class="flex flex-row items-center justify-center p-2">

                                <!-- <textarea v-model="form.description" type="text" name="description" id="" disabled
                                    class="text-lg font-sans p-5 text-slate rounded-2xl w-[65rem] h-[20rem] bg-slate-100"></textarea> -->
                                <p data-aos="fade-down-left" data-aos-duration="1500" v-html="article.description"></p>
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
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

export default defineComponent({
    name: 'ViewDeletedArticle',
    setup() { },
    components: {
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
            componentOptions: {
                message: '',
            },

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
            
            this.$api.get(`/admin/articles/ListOfDeletedArticles/${this.$route.params.hash}`)
                .then((response) => {
                   
                    this.articleList = response.data.article
                  
                    if(this.gallery){
                    this.galleryLength = this.gallery.length
                }
                })
        },








    }

})
</script>
