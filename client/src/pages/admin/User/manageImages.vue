<template>
    <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
    <Page>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmAvatarChange v-if="componentOptions.showModal" @cancel="closeModal" @proceed="deleteImage"/>
        </Modal>
        <div class="flex flex-col p-2 md:px-8 md:py-16 lg:px-10 lg:py-10">
            <div class="flex flex-col px-2 items-center justify-center py-2 gap-2 md:gap-5">
                <!--label-->
                <div ref="toTop" class="flex flex-row items-center text-slate-800 p-2 w-full">
                    <div class="flex flex-col">
                        <TIcon name="people" size="xl"></TIcon>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="font-sans text-2xl md:text-3xl md:text-5xl font-semibold flex flex-col items-center justify-center">Manage Images</span>
                    </div>
                </div>
                <!--images-->
                <div class="flex flex-col md:px-10 w-full">
                    <div class="flex flex-col lg:flex-row w-full lg:gap-0 bg-white shadow-md border-solid">
                        <!--preview of image-->
                        <div class="relative flex flex-col w-full lg:w-3/4 items-center justify-center">
                            <div class="flex flex-col w-40 h-40 md:w-96 md:h-96" :class="componentOptions.selected_item ? ' lg:w-full lg:h-[25rem] xl:h-[30rem]' : 'md:w-96 md:h-96 xl:h-[30rem] p-2'">
                                <img :src="componentOptions.selected_item ? selectedImage : '/src/assets/profile-user.png'" class="w-full h-full object-contain" >
                            </div> 
                            <TIcon v-if="componentOptions.selected_item" @click="showModal" name="delete" size="lg" class="absolute z-10 right-1 bottom-1 text-red-500 hover:scale-110 hover:text-red-600 cursor-pointer"/>
                        </div>
                        <!--gallery-->
                        <div class="w-full lg:w-1/4 lg:h-[25rem] xl:h-[30rem] bg-gray-300 p-2 lg:overflow-auto">
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
import { notify } from 'notiwind';
import ConfirmAvatarChange from './ConfirmAvatarChange.vue';

export default defineComponent({
    name: 'manage-images',
    setup(){},
    components:{
        ConfirmAvatarChange
    },
    mounted(){
        this.getUser()
    },
    data(){
        return{
            componentOptions: {
                selected_item: '',
                showModal: false,
                alertMsg: '',
                activeStatus: false
            },
            imageList: [],
            selectedImage: '',
            delete_image:{
                user_id: '',
                image: ''
            },
            deleted: false,
            mainAvatar: ''
            
        }
    },
    methods:{
        getUser(){
            this.$api.get(`/users/${this.$route.params.hash}`)
            .then((response)=>{
                this.imageList = response.data.data.profile.images
                this.user = response.data.data.id
                this.mainAvatar = response.data.data.profile.image.id
                if(this.deleted){
                    this.componentOptions.activeStatus = false
                    notify({group:"main", title:'Image deleted successfully!', type:"positive"},'3000')
                }
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
                this.$refs.toTop.scrollIntoView({ behavior: 'smooth' })
            }
            else{
                this.componentOptions.selected_item = ''
            }
        },
        deleteImage(){
            this.closeModal()
            this.componentOptions.alertMsg = 'Deleting Image'
            this.componentOptions.activeStatus = true
            this.image = this.componentOptions.selected_item
            if(this.mainAvatar != this.componentOptions.selected_item){
                this.$api.delete(`/users/delete-avatar/${this.user}`,{
                    params:{
                        image: this.componentOptions.selected_item
                    }
                })
                .then((response)=>{
                    this.componentOptions.selected_item = ''
                    this.selectedImage = ''
                    this.delete_image.user_id = ''
                    this.delete_image.image = ''
                    this.deleted = true
                    this.getUser()  
                })
            }
            else{
                this.componentOptions.activeStatus = false
                notify({group:"main", title:'Image cannot be deleted!',text:'Image is still in use.', type:"negative"},'5000')
            }
        },
        showModal() {
            this.componentOptions.showModal = true
        },
        closeModal() {
            this.componentOptions.showModal = false
        },
    }
})
</script>
