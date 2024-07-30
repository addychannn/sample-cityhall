<template>
    <Page>
        <!-- large screen -->
        <div class="hidden lg:flex flex-col md:pl-4">
            <div class="md:p-10 md:pb-2">
                <div class="flex flex-col items-start justify-start">
                    <div class="flex justify-start">
                        <div class="flex flex-row  items-center justify-center xl:w-full lg:w-full  p-4">
                            <div class=" xl:w-full lg:w-full  text-5xl font-sans text-center font-extrabold text-slate rounded-2xl text-slate-800">
                               {{ testimonyData.title }}
                            </div>
                        </div>
                      
                            

                    </div>
                    <div class="flex flex-row  pl-8 text-slate-800 p-2">
                        <div class="flex flex-col">
                            <TIcon name="schedule" size="md"></TIcon>

                        </div>

                        <div class="flex flex-col">
                            <span
                                class="font-sans text-md font-semibold flex flex-col items-center justify-center">{{testimonyData.date}}</span>

                        </div>

                    </div>
                </div>
                <div class="flex-col grid grid-cols-2">
                       <!-- message -->
                       <div id="content" class="flex flex-col items-center justify-center p-8">
                        <span type="text" disabled class="font-sans text-black w-full" placeholder="" v-html="testimonyData.body">
                        </span>
                    </div>
                    <!--img carousel-->
                    <div class="flex flex-col w-full items-center p-4 md:p-0">
                        <div v-if="gallery" class="flex flex-col w-full">
                            <template v-if="gallery.length != 0">
                                <Splide :options="{ rewind: true,autoplay:true, interval:2000, arrows:gallery.length != 1 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                    <SplideSlide v-for="image in gallery" class="flex flex-col items-center rounded-2xl w-full">
                                            <img :src="image.thumbnails.medium" class="hover:scale-110 h-[550px] rounded-2xl w-full sm:w-fit scale-110 sm:scale-100"/>
                                    </SplideSlide>
                                </Splide>
                            </template>
                            <template v-else>
                                <div 
                                    class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl">
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
                </div>
            </div>
        </div>

        <!-- small screen -->
        <div class="flex lg:hidden flex-col md:pl-4">
            <div class="md:p-10 md:pb-2">
                <div class="flex flex-col items-start justify-start">
                    <div class="flex justify-start">
                        <div class="flex flex-row  items-center justify-center xl:w-full lg:w-full  p-4">
                            <div class=" xl:w-full lg:w-full  text-5xl font-sans text-center font-extrabold text-slate rounded-2xl text-slate-800">
                               {{ testimonyData.title }}
                            </div>
                        </div>
                      
                            

                    </div>
                    <div class="flex flex-row  pl-8 text-slate-800 p-2">
                        <div class="flex flex-col">
                            <TIcon name="schedule" size="md"></TIcon>

                        </div>

                        <div class="flex flex-col">
                            <span
                                class="font-sans text-md font-semibold flex flex-col items-center justify-center">{{testimonyData.date}}</span>

                        </div>

                    </div>
                </div>
                <div class="flex-col flex">
                      
                    <!--img carousel-->
                    <div class="flex flex-col w-full items-center p-4 md:p-0">
                        <div v-if="gallery" class="flex flex-col w-full">
                            <template v-if="gallery.length != 0">
                                <Splide :options="{ rewind: true,autoplay:true, interval:2000, arrows:gallery.length != 1 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                    <SplideSlide v-for="image in gallery" class="flex flex-col items-center rounded-2xl w-full">
                                            <img :src="image.thumbnails.medium" class="hover:scale-110 h-[550px] rounded-2xl w-full sm:w-fit scale-110 sm:scale-100"/>
                                    </SplideSlide>
                                </Splide>
                            </template>
                            <template v-else>
                                <div 
                                    class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl">
                                    <span class="font-sans"> (No Image/s Available)</span>
                                </div>
                            </template>
                            
                        </div>
                        <div 
                            class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl"
                            v-if="gallery == null">
                            <span class="font-sans"> (No Image/s Available)</span>
                        </div>
                         <!-- message -->
                       <div id="content" class="flex flex-col items-center justify-center p-8">
                        <span type="text" disabled class="font-sans text-black w-full" placeholder="" v-html="testimonyData.body">
                        </span>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

export default defineComponent({
    name: 'TestimonyContent',
    setup(){},
    components:{
        Splide,
        SplideSlide
    },
    mounted(){
        this.getTestimony()
    },
    data(){
        return{
            componentOptions: {},
            testimonyData:{
                title: '',
                date: '',
                body: '',
            },
            testimony: null,
            gallery: []
        }
    },
    methods:{
        getTestimony(){
            this.$api.get(`/testimony-public/${this.$route.params.hash}`)
            .then((response)=>{
                this.testimony = response.data.testimony
                
                this.testimonyData.title = this.testimony.title
                this.testimonyData.date = this.testimony.date
                this.testimonyData.body = this.testimony.body
                
                this.gallery = response.data.testimony.image
            })
        }
    }
})
</script>
