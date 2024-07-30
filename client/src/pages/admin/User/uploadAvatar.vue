<template>
    <Page>
        <Modal :show="componentOptions.show_modal" @close="closeModal">
            <ConfirmAvatarChange v-if="componentOptions.show_modal && componentOptions.mode == 'choose-existing'" @cancel="closeModal" @proceed="changeAvatar"/>
            <ConfirmUploadAvatar v-if="componentOptions.show_modal && componentOptions.mode == null" @proceed="onSubmit" @cancel="closeModal"/>
        </Modal>
        <div class="flex flex-col p-2 md:p-5 gap-2">
            <!-- header -->
            <div class="flex flex-row items-center md:gap-3 md:p-1 md:m-1">
                <div class="flex flex-col">
                    <TIcon name="account_circle" size="xl"></TIcon>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl md:text-5xl font-sans font-semibold">Update Avatar</span>
                </div>
            </div>

            <div class="flex flex-col bg-slate-200 w-full">
                <!--buttons-->
                <div class="flex flex-row gap-1 w-full ">
                    <div @click="showImageCanvas('upload')" class="flex flex-col w-full bg-slate-500 text-center cursor-pointer hover:bg-slate-600 text-white" :class="componentOptions.openUpload ? 'bg-slate-600' : 'bg-slate-500'">
                        <span class="font-sans hover:text-lg">Upload Image</span>
                    </div>
                    <div @click="showImageCanvas('choose-existing')" class="flex flex-col w-full bg-slate-500 text-center cursor-pointer hover:bg-slate-600 text-white focus:bg-slate-600" :class="componentOptions.openTakePhoto ? 'bg-slate-600' : 'bg-slate-500'">
                        <span class="font-sans hover:text-lg">Choose from Upload</span>
                    </div>
                    <div @click="showImageCanvas('take-photo')" class="flex flex-col w-full bg-slate-500 text-center cursor-pointer hover:bg-slate-600 text-white focus:bg-slate-600" :class="componentOptions.openTakePhoto ? 'bg-slate-600' : 'bg-slate-500'">
                        <span class="font-sans hover:text-lg">Take Photo</span>
                    </div>
                </div>

                <!--image input-->
                <div class="flex flex-col w-full py-2">
                    <div v-if="componentOptions.openUpload" class="flex flex-col w-full items-center gap-2">
                        <div class="flex flex-row justify-center h-40 w-40 md:w-80 md:h-80">
                            <img :src="this.preview ? this.preview : '/src/assets/profile-user.png'" class="w-full h-full rounded-full object-cover">
                        </div>
                        <div class="flex flex-row w-full justify-center">
                            <input type="file" accept=".jpeg,.png,.jpg,.gif" ref="imgUpload" @change="Upload" class="block w-[15rem] lg:w-[20rem] text-sm font-sans text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                aria-describedby="user_avatar_help">
                        </div>
                        
                    </div>
                    <div v-if="componentOptions.openChooseExisting" class="flex flex-col w-full p-2 gap-2">
                        <div class="flex flex-col w-full items-center">
                            <div class="flex flex-row justify-center h-40 w-40 md:w-80 md:h-80">
                                <img :src="image.selected_item ? image.selected_item : '/src/assets/profile-user.png'" class="w-full h-full rounded-full object-cover">
                            </div>
                        </div>
                        <div style="border-top: 1px solid gray;"></div>
                        <div v-if="image_list_length != 0" class="grid grid-cols-3 gap-2">
                            <div class="flex flex-col w-full h-[10rem]" v-for="image in imageList">
                                <img @click="selectAvatar(image.id)" :src="image.thumbnails.small" class="w-full h-full object-cover hover:object-contain cursor-pointer">
                            </div>
                        </div>
                        <div v-else class="flex flex-col w-full items-center">
                            <span class="font-sans text-slate-600 text-center">(No Available Images)</span>
                        </div>
                        
                    </div>
                    <div v-if="componentOptions.openTakePhoto" class="flex flex-col w-full">
                        <CaptureImage :show="componentOptions.openTakePhoto" @picture-captured="storeCaptured($event)" ref="Capture"/>
                    </div>
                    <div v-if="!componentOptions.openUpload && !componentOptions.openTakePhoto && !componentOptions.openChooseExisting" class="flex flex-col w-full text-center text-slate-600 font-sans bg-slate-200">
                        (Choose a mode for updating avatar)
                    </div>
                </div>
            </div>

            <!--Submit button for upload photo and take photo-->
            <div v-if="componentOptions.openTakePhoto || componentOptions.openUpload" class="flex flex-row justify-center gap-2">
                <div class="flex flex-col items-center justify-center pt-4">
                    <span @click="showModal(null)" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
                </div>
                <div @click="cancel" class="flex flex-col items-center justify-center pt-4">
                    <span class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
                </div>
            </div>
            
            <!--Submit button for choose from upload-->
            <div v-if="componentOptions.openChooseExisting" class="flex flex-row justify-center gap-2">
                <div class="flex flex-col items-center justify-center pt-4">
                    <span @click="showModal('choose-existing')" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Change</span>
                </div>
                <div @click="cancel" class="flex flex-col items-center justify-center pt-4">
                    <span class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
                </div>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import imageCompression from 'browser-image-compression';
import { Helpers} from "@/scripts";
import ConfirmAvatarChange from './ConfirmAvatarChange.vue';
import ConfirmUploadAvatar from './ConfirmUploadAvatar.vue';
import CaptureImage from '@/layouts/Partials/Camera.vue';

export default defineComponent({
    name: 'UploadAvatar',
    setup(){},
    components:{
        ConfirmAvatarChange,
        ConfirmUploadAvatar,
        CaptureImage
    },
    props:{
        user:{
            type: String,
            default: ''
        }
    },
    emits:[
        'cancel',
        'done',
        'processing'
    ],
    data(){
        return{
            componentOptions: {
                openUpload: false,
                openTakePhoto: false,
                openChooseExisting: false,               
                show_modal: false,
                mode: ''
            },
            image:{
                selected_item: null,
                selected_id: '',
            },
            file: null,
            preview: null,
            imageList: null,
            image_list_length: 0
        }
    },
    methods:{
        showImageCanvas(mode){
            if(mode == 'upload'){
                this.componentOptions.openUpload = !this.componentOptions.openUpload
                this.componentOptions.openTakePhoto = false
                this.componentOptions.openChooseExisting = false
            }
            if(mode == 'choose-existing'){
                this.componentOptions.openUpload = false
                this.componentOptions.openTakePhoto = false
                this.componentOptions.openChooseExisting = !this.componentOptions.openChooseExisting
                this.getImages()
            }
            if(mode == 'take-photo'){
                this.componentOptions.openUpload = false
                this.componentOptions.openTakePhoto = !this.componentOptions.openTakePhoto
                this.componentOptions.openChooseExisting = false
            }
        },
        async storeCaptured(file){
            this.file = file
            if(this.file){
                this.file = await this.constructFileObject(this.file)
            }
            else{
                this.preview = null
            }  
        },
        async Upload(e){
            this.file = this.$refs.imgUpload.files[0]
            if(this.file){
                this.preview = URL.createObjectURL(this.file)
                this.file = await this.constructFileObject(this.file)
            }
            else{
                this.preview = null
            }   
        },
        async constructFileObject(file){
            return {
                id: Helpers.uniqid(),
                progress: 0,
                rename: file.name,
                file: file,
            };
        },
        async onSubmit(){
            if(this.componentOptions.openTakePhoto){
                this.$refs.Capture.stopCameraStream()
            }
            this.closeModal()
            this.$emit('processing')
            let token = null
            token = this.user
            await this.uploadImage(token, this.file)
        },
        async uploadImage(hash,file){
            let formData = await this.compressAndPrepImage(hash,file)
            await this.$api.post(`/users/avatar/${hash}`,formData)
            .then((response)=>{
                this.$emit('done')
            })
        },
        async compressAndPrepImage(hash, file){
            // Create a form data
            let form = new FormData()
            // Compress Each file in the files variable then append
            // form details.
            let image = await imageCompression(file.file, {
                maxSizeMB: 2,
            })
            form.append('file', image)
            // for identification
            form.append('uid',file.id)
            form.append('timestamp', null)
            // Chunking
            form.append('chunk', 0)
            form.append('name', file.file.name)
            form.append('ext', file.file.name?.split(".").pop())
            form.append('is_last', 1)
            form.append('hash', hash)
            form.append('is_main', false)
            return form
        },
        cancel(){
            if(this.componentOptions.openTakePhoto){
                this.$refs.Capture.stopCameraStream()
            }
            this.$emit('cancel')
        },
        getImages(){
            this.$api.get(`/users/${this.user}`)
            .then((response)=>{
                this.imageList = response.data.data.profile.images 
                this.image_list_length = this.imageList.length
            })
        },
        selectAvatar(item){
            let ctr = 0
            for(ctr=0;ctr<this.imageList.length;ctr++){
                if(this.imageList[ctr].id == item){
                    this.image.selected_id = this.imageList[ctr].id
                    this.image.selected_item = this.imageList[ctr].thumbnails.medium
                }
            }
        },
        changeAvatar(){
            this.$emit('processing')
            this.$api.patch(`/users/avatar/${this.user}`, this.image)
            .then(
                this.closeModal()
            )
            this.$emit('done')
        },
        showModal(mode){
            this.componentOptions.show_modal = true
            this.componentOptions.mode = mode
        },
        closeModal(){
            this.componentOptions.show_modal = false
        }
    }
})
</script>
