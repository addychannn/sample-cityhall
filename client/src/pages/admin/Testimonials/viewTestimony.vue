<template>
    <Page>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <DeleteTestimonyRecord :testimony="componentOptions.selectedItem" v-if="componentOptions.mode == 'delete'" @cancel="closeModal" @done="refreshPage"/>
        </Modal>
        <div class="flex flex-col">
            <div class="md:p-10 md:pb-2">
                <div class="flex flex-col w-full">
                    <!--img carousel-->
                        <div class="flex flex-col w-full  p-4 md:p-0">
                            <div v-if="gallery" class="flex flex-col w-full h-full">
                                <Splide :options="{ rewind: true,autoplay:true, interval:2000, arrows: gallery.length != 1 }" class="flex flex-col w-full h-full">
                                    <SplideSlide v-for="image in gallery" class="flex flex-col rounded-2xl w-full h-full items-center">
                                        <img :src="image.thumbnails.medium" class="hover:scale-110 h-[550px] rounded-2xl w-full sm:w-fit scale-110 sm:scale-100">
                                    </SplideSlide>
                                </Splide>
                            </div>
                        </div>
                        
                        <div 
                            class="flex w-full h-[10rem] items-center justify-center bg-slate-200 rounded-2xl"
                            v-if="gallery == null">
                            <span class="font-sans"> (No Image/s Available)</span>
                        </div>
                    
                    <!--Title and date-->
                    <div class="flex flex-col p-4 w-full items-center justify-center">
                        <!--Title-->
                        <div class="flex text-slate-800 flex-col items-center justify-center text-center">
                            <span class="font-sans text-5xl font-semibold flex flex-col items-center justify-center text-center uppercase">
                                {{ testimonyData.title }}
                            </span>
                        </div>
                        <!--Date-->
                        <div class="flex flex-row items-center justify-center">
                            <div class="flex flex-col">
                                <TIcon name="schedule" size="md"></TIcon>
    
                            </div>
                            <span class=" font-sans text-lg text-center pl-2">
                                {{ testimonyData.date}}
                            </span>            
                        </div>
                    </div>

                    <!-- message -->
                    <div id="content" class="flex flex-col items-center justify-center px-8">
                        <span 
                            type="text" 
                            class="font-sans text-black w-full" 
                            placeholder=""
                            v-html="testimonyData.body"
                        >
                        </span>
                    </div>
                </div>
            </div>

            <!--actions-->
            <div v-if="$guard.can(['testimony_edit','testimony_delete'])" class="flex flex-row gap-2 pb-10 md:pl-10">
                <!--delete btn-->
                <div class="md:w-[10rem]" @click="showModal('delete',testimony)">
                    <div
                        class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-red-500 hover:to-red-600 shadow-xl ">
                        <span class="font-sans">Delete
                        </span>
                    </div>
                </div>
                <!--edit btn-->
                <div class="md:w-[10rem]">
                    <router-link :to="{ name: 'testimony-edit',testimony}">
                    <div
                        class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                        <span class="font-sans">Edit
                        </span>
                    </div>
                </router-link>
                </div>
            </div>

        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import DeleteTestimonyRecord from './DeleteTestimony.vue';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

export default defineComponent({
    name: 'ViewTestimony',
    setup() { },
    components: {
        DeleteTestimonyRecord,
        Splide,
        SplideSlide
    },
    mounted(){
        this.getTestimony()
    },
    data() {
        return {
            componentOptions: {
                showModal: false,
                mode: '',
                selectedItem: null
            },
            testimonyData:{
                title: '',
                date: '',
                body: '',
            },
            testimony: null,
            gallery: []
        }
    },
    methods: {
        getTestimony(){
            this.$api.get(`/testimony/${this.$route.params.hash}`)
            .then((response)=>{
                this.testimony = response.data.testimony
                
                this.testimonyData.title = this.testimony.title
                this.testimonyData.date = this.testimony.date
                this.testimonyData.body = this.testimony.body

                this.gallery = response.data.testimony.image
            })
        },
        showModal(mode,item=null) {
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            if(item != null){
                this.componentOptions.selectedItem = item
            }
        },
        closeModal() {
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''

        },
        refreshPage(){
            this.closeModal()
            this.$router.push({ name: 'AdminTestimonials'})
        }
    }

})
</script>
