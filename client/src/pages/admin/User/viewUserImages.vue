<template>
    <Page>
        <div class="flex flex-col p-2 md:px-8 md:py-16 lg:px-10 lg:py-10">
            <div class="flex flex-col px-2 items-center justify-center py-2 gap-2 md:gap-5">
                <!--label-->
                <div class="flex flex-row items-center text-slate-800 p-2 w-full">
                    <div class="flex flex-col">
                        <TIcon name="people" size="xl"></TIcon>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="font-sans text-2xl md:text-5xl font-semibold flex flex-col items-center justify-center">User Images</span>
                    </div>
                </div>
                <!--images-->
                <div class="flex flex-col md:px-10 w-full">
                    <div class="flex flex-col lg:flex-row w-full lg:gap-0 bg-white shadow-md border-solid">
                        <!--preview of image-->
                        <div class="flex flex-col w-full lg:w-3/4 items-center justify-center">
                            <div class="flex flex-col w-40 h-40 md:w-96 md:h-96" :class="componentOptions.selected_item ? ' lg:w-full lg:h-[30rem]' : 'md:w-96 md:h-96 xl:h-[30rem] p-2'">
                                <img :src="componentOptions.selected_item ? selectedImage : '/src/assets/profile-user.png'" class="w-full h-full object-contain" >
                            </div> 
                        </div>
                        <!--gallery-->
                        <div class="w-full lg:w-1/4 bg-gray-300 p-2">
                            <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-3 gap-2   ">
                                <div v-for="image in imageList" class="flex flex-col h-20 bg-slate-500">
                                    <img @click="previewImage(image.id)" :src="image.thumbnails.small" class="w-full h-full grayscale object-cover hover:scale-110 cursor-pointer hover:grayscale-0" :class="componentOptions.selected_item == image.id ? 'grayscale-0' : ''">
                                </div>
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

export default defineComponent({
    name: 'ViewUserImages',
    setup(){},
    mounted(){
        this.getUser()
    },
    data(){
        return{
            componentOptions: {
                selected_item: ''
            },
            imageList: [],
            selectedImage: ''
        }
    },
    methods:{
        getUser(){
            this.$api.get(`/users/${this.$route.params.hash}`)
            .then((response)=>{
                this.imageList = response.data.data.profile.images
            })
        },
        previewImage(item){
            if(this.componentOptions.selected_item != item){
                this.componentOptions.selected_item = item
                let ctr = 0
                for(ctr=0; ctr<this.imageList.length; ctr++){
                    if(this.imageList[ctr].id == this.componentOptions.selected_item){
                        this.selectedImage = this.imageList[ctr].thumbnails.medium
                    }
                }
            }
            else{
                this.componentOptions.selected_item = ''
            }
        }
    }
})
</script>
