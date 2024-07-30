<template>
    <Page class="p-5">
        <!--Label-->
        <div class="flex flex-col text-center">
            <span class="font-sans font-bold text-3xl text-green-600">Images</span>
        </div>
        <!--Images-->
        <div v-if="gallery" class="flex flex-row pt-2">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-2 gap-y-2 w-full">
                <div class="flex w-full h-full rounded-2xl justify-center" v-for="image in gallery">
                    <img :src="image.thumbnails.medium" class="rounded-2xl hover:scale-110">
                </div>
            </div>
        </div>
        <div v-else class="flex flex-row w-full h-full justify-center pt-2">
            <span class="font-sans">(No submitted image)</span>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'ViewReportImage',
    setup(){},
    mounted(){
        this.getImage()
    },
    props:{
        report:{
            type:Object,
            default:null,
        }
    },
    data(){
        return{
            componentOptions: {},
            gallery: []
        }
    },
    methods:{
        getImage(){
            this.gallery = this.report.image
        },
    }
})
</script>
