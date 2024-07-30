    <template>
    <Page>
        <!-- large screen -->
        <div class="hidden lg:flex flex-col md:pl-4">
            <div class="md:p-10 md:pb-2">
                <div class="flex flex-col items-start justify-start ">
                    <div class="flex justify-start">
                        <div class="flex flex-row  items-center justify-center xl:w-full lg:w-full  p-4">
                            <div class=" xl:w-full lg:w-full  text-5xl font-sans text-center font-extrabold text-slate rounded-2xl text-slate-800">
                               {{ form.articleTitle }}
                            </div>
                        </div>
                      
                            

                    </div>
                    <div class="flex flex-row  pl-8 text-slate-800 p-2">
                        <div class="flex flex-col">
                            <TIcon name="schedule" size="md"></TIcon>

                        </div>

                        <div class="flex flex-col">
                            <span
                                class="font-sans text-md font-semibold flex flex-col items-center justify-center">{{form.created_at}}</span>

                        </div>

                    </div>
                </div>

                <div class="flex-col grid grid-cols-2 ">
                   
                   
                    
                    <!--desc-->
                    <div>
                        
                        <div class="flex flex-row  justify-center xl:w-full lg:w-full p-8">

                            <p data-aos="fade-down-left" data-aos-duration="1500" v-html="form.description"></p>
                        </div>

                        
                    </div>
                     <!--img carousel-->
                     <div>
                        <div class="flex flex-col w-full  justify-center">
                            <div v-if="gallery" class="flex flex-col xl:w-full lg:w-full ">
                                <template v-if="gallery.length == 0">
                                    <div 
                                        class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl">
                                        <span class="font-sans"> (No Image/s Available)</span>
                                    </div>
                                </template>
                                <template v-else>
                                    <Splide :options="{ rewind: true,autoplay:true, interval:2000, arrows:gallery.length != 1 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                        <SplideSlide v-for="image in gallery" class="flex flex-col items-center justify-center rounded-2xl xl:w-full lg:w-full ">
                                                <img :src="image.thumbnails.medium" class="h-[650px] hover:scale-110 center rounded-2xl "/>
                                        </SplideSlide>
                                    </Splide>
                                </template>
                                
                            </div>
                            <div 
                                class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl"
                                v-if="gallery == null">
                                <span class="font-sans"> (No Image/s Available)</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- small screen -->
        <div class="flex lg:hidden flex-col md:pl-4">
            <div class="md:p-10 md:pb-2">
                <div class="flex flex-col items-center  justify-center   ">
                    <div class="flex justify-center">
                        <div class="flex flex-row  items-center justify-center xl:w-full lg:w-full  p-4">
                            <div class=" xl:w-full lg:w-full  text-5xl font-sans text-center font-extrabold text-slate rounded-2xl text-slate-800">
                               {{ form.articleTitle }}
                            </div>
                        </div>
                      
                            

                    </div>
                    <div class="flex flex-row   text-slate-800 p-2">
                        <div class="flex flex-col">
                            <TIcon name="schedule" size="md"></TIcon>

                        </div>

                        <div class="flex flex-col">
                            <span
                                class="font-sans text-md font-semibold flex flex-col items-center justify-center">{{form.created_at}}</span>

                        </div>

                    </div>
                </div>

                <div class="flex-row grid grid-rows-2 ">
                      <!--img carousel-->
                      <div>
                        <div class="flex flex-col w-full  justify-center">
                            <div v-if="gallery" class="flex flex-col xl:w-full lg:w-full ">
                                <template v-if="gallery.length != 0">
                                    <Splide :options="{ rewind: true,autoplay:true, interval:2000, arrows:gallery.length != 1 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                        <SplideSlide v-for="image in gallery" class="flex flex-col items-center justify-center rounded-2xl xl:w-full lg:w-full ">
                                                <img :src="image.thumbnails.medium" class="h-[350px] hover:scale-110 center rounded-2xl "/>
                                        </SplideSlide>
                                    </Splide>
                                </template>
                                <template v-else>
                                    <div 
                                        class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl"
                                        v-if="gallery == null">
                                        <span class="font-sans"> (No Image/s Available)</span>
                                    </div>
                                </template>
                            </div>
                            <div 
                                class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl"
                                v-if="gallery == null">
                                <span class="font-sans"> (No Image/s Available)</span>
                            </div>
                        </div>
                          
                    <!--desc-->
                    <div>
                        
                        <div class="flex flex-row  justify-center xl:w-full lg:w-full p-8">

                            <p data-aos="fade-down-left" data-aos-duration="1500" v-html="form.description"></p>
                        </div>

                        
                    </div>
                  
                    </div>
                    
                   
                  
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
    name: 'PublicViewArticle',
    $emits: ['done'],
    components: {
        QuillEditor,
        Splide,
        SplideSlide
    },
    mounted() {

        this.getArticle()

    },
    setup() { },

    data() {
        return {
            componentOptions: {},
            form: {
                articleTitle: '',
                description: '',
                created_at: '',
            },
            article: null,
            gallery: [],
        }
    },
    methods: {
        getArticle() {
            this.$api.get(`/articles/${this.$route.params.hash}`)
                .then((response) => {
                    this.article = response.data.article
                    this.form.articleTitle = this.article.articleTitle
                    this.form.description = this.article.description
                    this.form.created_at = this.article.created_at

                    this.gallery = response.data.gallery
                })
        },
    },
})
</script>
