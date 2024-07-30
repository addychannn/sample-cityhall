<template>
    <Page>
        <div class="flex flex-col md:pl-4">
            <div class="md:p-10 md:pb-2">
                <div class="flex flex-col w-full" v-for="item in testimonyData">
                    <!--img carousel-->
                    <div class="flex flex-col w-full items-center">
                        <div v-if="item.image" class="flex flex-col w-full">
                            <Splide :options="{ rewind: true,autoplay:true, interval:2000 }"  aria-label="My Favorite Images" class="flex flex-col w-full">
                                <SplideSlide v-for="image in item.image" class="flex flex-col items-center bg-slate-800 rounded-2xl w-full">
                                    <img :src="image.thumbnails.medium" class="h-[250px] hover:scale-110 center"/>
                                </SplideSlide>
                            </Splide>
                        </div>
                        <div 
                            class="flex w-full h-[10rem] items-center justify-center bg-slate-200 rounded-2xl"
                            v-if="item.image == null">
                            <span class="font-sans"> (No Image/s Available)</span>
                        </div>
                    </div>
                    
                    <!--Title and date-->
                    <div class="flex flex-col p-5 w-full items-center justify-center" >
                        <!--Title-->
                        <div class="flex text-slate-800 flex-col items-center justify-center text-center">
                            <span class="font-sans text-5xl font-semibold flex flex-col items-center justify-center text-center uppercase">
                                {{ item.title }}
                            </span>
                        </div>
                        <!--Date-->
                        <div class="flex flex-col items-center justify-center">
                            <span class=" font-sans text-lg text-center ">
                                {{ item.date}}
                            </span>            
                        </div>
                    </div>

                    <!-- message -->
                    <div class="flex flex-col items-center justify-center px-3 xl:px-[20rem] py-6">
                        <p 
                            type="text" 
                            class="font-sans text-black w-full" 
                            placeholder=""
                            v-html="item.body"
                        >
                        </p>
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
    name: 'ViewDeletedTestimony',
    setup(){},
    mounted(){
        this.getDeletedTestimony()
    },
    components: {
        Splide,
        SplideSlide
    },
    data(){
        return{
            componentOptions: {},
            testimonyData: null,
        }
    },
    methods:{
        getDeletedTestimony(){
            this.$api.get(`testimony-deleted/${this.$route.params.hash}`)
            .then((response)=>{
                this.testimonyData = response.data.testimony
            })
        }
    }
})
</script>
