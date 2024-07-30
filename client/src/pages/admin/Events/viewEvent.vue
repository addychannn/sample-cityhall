<template>
    <Page  class="w-full h-full p-5">
        
        <div class="flex flex-col pl-4">
            <div class="flex flex-col sm:p-5 p-2  sm:mx-2 md:mx-5 lg:mx-20 gap-5">
                    <div class="block items-center justify-center rounded-3xl  py-3">
                        <div class="flex flex-row items-center justify-center">
                            <div class="flex   pr-2 text-slate-800 p-2">
                              
                                <span
                                    class="font-sans sm:text-4xl text-5xl font-bold font  items-center justify-center">{{form.event_title}}</span>

                            </div>
                            
                        </div>
                        <div class="flex flex-row items-center justify-center">
                            <div class="flex-row flex gap-4 items-center justify-center p-2 pb-4 ">
                                <div>
                                    <span class="font-sans font-semibold">Start date:</span>
                                    <span class="font-sans pl-2">{{form.start_date}}</span>
                                </div>
                                <span class="flex items-center justify-center font-sans">-</span>
                                <div>
                                    <span class="font-sans font-semibold">End date:</span>
                                    <span class="font-sans pl-2">{{form.end_date}}</span>
                                </div>
                                
                        
                              
                            </div>


                        </div>

                        <form>

                            <span>

                                  <!--img carousel-->
                    <div class="flex flex-col w-full items-center">
                        <div v-if="galleryLength !=0" class="flex flex-col w-full">
                            <Splide :options="{ rewind: true,autoplay:true, interval:2000, arrows: galleryLength != 1 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                <SplideSlide v-for="image in gallery" class="flex flex-col items-center rounded-2xl w-full">
                                        <img :src="image.thumbnails.medium" class="xl:h-[600px] sm:h-[500px] hover:scale-110 center"/>
                                </SplideSlide>
                            </Splide>
                        </div>
                        <div 
                            class="flex w-full h-[20rem] items-center justify-center bg-slate-200 rounded-2xl"
                            v-if="galleryLength ==0">
                                <span class="font-sans"> (No Image/s Available)</span>
                        </div>
                    </div>
                               
                                <div class="flex flex-row items-center justify-center p-4">

                                    <!-- <textarea v-model="form.description" type="text" name="description" id="" disabled
                                    class="text-lg font-sans p-5 text-slate rounded-2xl w-[65rem] h-[20rem] bg-slate-100"></textarea> -->
                                    <p
                                        data-aos="fade-down-left"
                                        data-aos-duration="1500"
                                        v-html="form.description"
                                    ></p>
                                </div>

                            </span>
                            <div v-if="$guard.can(['event_edit'])" class="flex flex-col items-end justify-end">
                                <div class="flex flex-col items-end justify-end p-2 w-[15rem]">
                                    <router-link :to="{ name: 'EditEvent' }">
                                        <div
                                            class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
        
                                            <span class="font-sans">Edit Event
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
import EditEvent from './editEvent.vue';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

export default defineComponent({
    name: 'ViewEvent',
    $emits: ['done'],
    setup() { },
    components: {
        EditEvent,
        Splide,
        SplideSlide
    },

    async mounted() {

        await this.getevent()

    },

    props:{
        type: Object,
        default: null,
    },

    data() {
        return {
            componentOptions: {
                message:'',
            },

            form: {
                event_title: '',
                description: '',
                start_date: '',
                end_date: '',

            },

            eventList: null,
            gallery: [],
            galleryLength: 0,


        }
    },

    methods: {
        async getevent() {
            this.$api.get(`admin/events/${this.$route.params.hash}`)
                .then((response) => {
                    this.event = response.data.event
                    this.form.event_title = this.event.event_title
                    this.form.description = this.event.description
                    this.form.start_date = this.event.start_date
                    this.form.end_date = this.event.end_date
                   
                    this.gallery = this.event.image

                if(this.gallery){
                    this.galleryLength = this.gallery.length
                }
                })
        },


        submit() {
            let changes = this.getChanges(this.form)
            
            this.$api.patch('/event/edit', changes)
                .then((response) => {
                    this.$emit('done');
                })

        },
        getChanges(form) {
            let changes = {}
            changes['id'] = this.event.hash
            if (form.event_title != this.event.event_title) {
                changes['event_title'] = form.event_title
            }
            if (form.description != this.event.description) {
                changes['description'] = form.description
            }
            if (form.start_date != this.event.start_date) {
                changes['start_date'] = form.start_date
            }
            if (form.end_date != this.event.end_date) {
                changes['end_date'] = form.end_date
            }

            return changes;
        },





    }


})
</script>
