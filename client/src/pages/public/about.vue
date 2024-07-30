<template>
    <Page class="w-full h-full px-5 bg-slate-100">
        <!-- Title -->
        <div class="flex flex-row w-full justify-center my-10">
            <span
                class="text-center text-green-600 font-sans xs:text-3xl sm:text-4xl md:text-5xl p-7 font-semibold shadow-xl rounded-2xl mx-5 uppercase">
                <TIcon name="info" size="lg" class="mx-2"></TIcon>About Smoke-Free Baguio
            </span>
        </div>


            <!-- Sections - md-lg -->
            <div v-for="section in sections" class="hidden md:flex md:flex-row border my-5 rounded-lg bg-slate-200">
                <div :class="section.images ? 'flex flex-col justify-center items-center text-center py-5  w-1/2 h-[80vh] gap-5' :'flex flex-col justify-center items-center text-center  w-full h-[80vh] gap-5'">
                    <span class="font-sans text-5xl font-semibold mt-5">{{ section.title }}</span>
                    <span class="font-sans text-2xl font-light mx-10 overflow-auto" v-html="section.content"></span>
                </div>
                <template v-if="section.images">
                    <div v-if="section.images.length != 0" class="flex flex-col justify-center items-center w-1/2 h-[80vh]">
                        <Splide :options="{ rewind: true, arrows:section.images.length != 1 }">
                            <SplideSlide v-for="image in section.images" class="w-full h-full">
                                <img :src="image.thumbnails.large" alt="Sample 1" class="object-contain h-[75vh] w-full">
                            </SplideSlide>
                        </Splide>
                    </div>
                    <div v-else class="flex flex-col justify-center items-center w-1/2 h-[80vh]">
                        <img src="@/assets/smokebaguio.png" alt="Sample 1" class="object-contain h-[75vh] w-full">
                        <!-- <span class="font-sans text-slate-600 text-lg">(No Images)</span> -->
                    </div>
                </template>
                <template v-else class="w-full">
                    <div class="flex flex-col justify-center items-center w-full h-[80vh]">
                        <img src="@/assets/smokebaguio.png" alt="Sample 1" class="object-contain h-[75vh] w-full">
                        <!-- <span class="font-sans text-slate-600 text-lg ">(No Images)</span> -->
                    </div>
                </template>
                 
            </div>

            <!-- for sm -->
            <div v-for="section in sections" class="flex flex-col md:hidden border rounded-lg bg-slate-200">
                <div class="flex flex-col justify-center items-center">
                    <span class="font-sans text-3xl font-semibold mx-2 mt-5">{{ section.title }}</span>
                    <span class="font-sans text-lg font-light mx-2 overflow-auto" v-html="section.content"></span>
                </div>
                
                <template v-if="section.images" :class="section.images.length == 0 ? 'hidden' : ''">
                    <div v-if="section.images.length != 0" class="flex flex-col justify-center items-center">
                        <Splide :options="{ rewind: true, arrows:section.images.length != 1 }">
                            <SplideSlide v-for="image in section.images" class="w-full h-24">
                                <img :src="image.thumbnails.medium" alt="Sample 1" class="object-contain sm:h-32 h-24 w-full">
                            </SplideSlide>
                        </Splide>
                    </div> 
                </template>
                <div v-else class="hidden"></div>
                
            </div>

    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css/sea-green';

export default defineComponent({
    name: 'About',
    setup() {

    },

    mounted() {
        this.getSections()

    },

    components: {
        Splide,
        SplideSlide,
    },

    data() {
        return {
            componentOptions: {

            },

            sections: [],
            gallery: [],



        }
    },
    methods: {
        getSections() {
            this.$api.get('/public/about')
                .then((response) => {
                    this.sections = response.data.data
                })
        },




    }


})



</script>







