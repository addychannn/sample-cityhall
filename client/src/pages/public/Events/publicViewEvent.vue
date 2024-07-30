<template>
    <Page>
        <!-- large screen -->
        <div class="hidden lg:flex flex-col md:pl-4">
            <div class="md:p-10 md:pb-2">
                <div class="flex flex-col items-start justify-start ">
                    <div class="flex justify-start">
                        <div class="flex flex-row  items-center justify-center xl:w-full lg:w-full  p-4">
                            <div class=" xl:w-full lg:w-full  text-5xl font-sans text-center font-extrabold text-slate rounded-2xl text-slate-800">
                               {{ form.event_title }}
                            </div>
                        </div>
                      
                            

                    </div>
                    <div class=" xl:w-full lg:w-full  text-md font-sans text-start flex flex-col items-start justify-start text-slate rounded-2xl bg-slate-100">
                        <div class="flex-row font-sans text-start items-start justify-start pl-8 ">
                            <span class="font-sans font-semibold">Start date:</span>
                            <span class="font-sans pl-4">{{form.start_date}}</span>
                        </div>
                        <div class="flex-row font-sans text-start items-start justify-start pl-8 ">
                            <span class="font-sans font-semibold">End date:</span>
                            <span class="font-sans pl-4">{{form.end_date}}</span>
                        </div>
                       
                    </div>
                </div>

                <div class="flex-col grid grid-cols-2 w-full">
                  
                    
                    <!--desc-->


                    <div class="flex flex-row  justify-center xl:w-full lg:w-full p-8">

                        <p data-aos="fade-down-left" data-aos-duration="1500" v-html="form.description"></p>
                    </div>

                      <!--img carousel-->
                      <div class="flex flex-col w-full items-center">
                        <div v-if="gallery" class="flex flex-col xl:w-full lg:w-full ">
                            <template v-if="gallery.length != 0 ">
                                <Splide :options="{ rewind: true,autoplay:true, interval:2000, arrows:gallery.length != 0 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                    <SplideSlide v-for="image in gallery" class="flex flex-col items-center justify-centerrounded-2xl xl:w-full lg:w-full ">
                                            <img :src="image.thumbnails.medium" class="h-[550px] hover:scale-110 rounded-2xl center "/>
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
                <div class="flex flex-col items-start justify-start ">
                    <div class="flex justify-start">
                        <div class="flex flex-row  items-center justify-center xl:w-full lg:w-full  p-4">
                            <div class=" xl:w-full lg:w-full  text-5xl font-sans text-center font-extrabold text-slate rounded-2xl text-slate-800">
                               {{ form.event_title }}
                            </div>
                        </div>
                      
                            

                    </div>
                    <div class=" xl:w-full lg:w-full  text-md font-sans text-start flex flex-col items-start justify-start text-slate rounded-2xl bg-slate-100">
                        <div class="flex-row font-sans text-start items-start justify-start pl-8 ">
                            <span class="font-sans font-semibold">Start date:</span>
                            <span class="font-sans pl-4">{{form.start_date}}</span>
                        </div>
                        <div class="flex-row font-sans text-start items-start justify-start pl-8 ">
                            <span class="font-sans font-semibold">End date:</span>
                            <span class="font-sans pl-4">{{form.end_date}}</span>
                        </div>
                       
                    </div>
                </div>

                <div class="flex-row grid grid-rows-2 w-full">
                      <!--img carousel-->
                      <div class="flex flex-col w-full  items-center">
                        <div v-if="gallery" class="flex flex-col xl:w-full lg:w-full ">
                            <template v-if="gallery.length != 0">
                                <Splide :options="{ rewind: true, autoplay:true, interval:2000, arrows:gallery.length != 0 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                    <SplideSlide v-for="image in gallery" class="flex flex-col items-center justify-centerrounded-2xl xl:w-full lg:w-full ">
                                            <img :src="image.thumbnails.medium" class="h-[350px] hover:scale-110 rounded-2xl center "/>
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

                     <!--desc-->


                     <div class="flex flex-row  xl:w-full lg:w-full p-2">

                        <p data-aos="fade-down-left" data-aos-duration="1500" v-html="form.description"></p>
                    </div>
                    </div>
                    
                   

                    
                </div>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

export default defineComponent({
    name: 'PublicViewEvent',
    $emits: ['done'],
    setup() { },
    components: {
        Splide,
        SplideSlide
    },
    async mounted() {
        await this.getEvent()
    },
    props: {
        type: Object,
        default: null,
    },
    data() {
        return {
            componentOptions: {
                selectedItem: null
            },

            form: {
                event_title: '',
                description: '',
                start_date: '',
                end_date: '',
            },
            eventList: null,
            gallery: [],
        }
    },
    methods: {
        async getEvent() {
            this.$api.get(`/events/${this.$route.params.hash}`)
                .then((response) => {
                    this.event = response.data.event
                    this.form.event_title = this.event.event_title
                    this.form.description = this.event.description
                    this.form.start_date = this.event.start_date
                    this.form.end_date = this.event.end_date
                   
                    this.gallery = this.event.image
                })
        },
    }
})
</script>
