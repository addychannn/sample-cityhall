<template>
    <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
    <Page class="p-5 w-full">
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <DeleteTestimonyImage :testimony="deleteFiles" @cancel="closeModal" @done="refreshPage" @processing="showLoader" @failed="deleteFailed"/>
        </Modal>
        <div class="flex flex-col">
            
            <!--Label-->
            <div class="flex flex-row items-start justify-start">
                <div class="flex   pr-2 text-slate-800 p-2">
                    <TIcon name="edit" size="xl" class="pt-1"></TIcon>
                    <span class="font-sans text-5xl font-semibold flex flex-col items-center justify-center">
                        EDIT TESTIMONY</span>

                </div>
            </div>


            <!--Testimony Title-->
            <div class="flex flex-col gap-3 justify-start items-start">
                <span class="sm:text-xl text-md font-sans mt-1">Title:</span>
                <input v-model="form.title" class="rounded-md  w-full h-10 font-sans p-2  "
                    type="text" name="title" placeholder="Event Title">
            </div>

            <!--Testimony-->
            <div class="flex flex-col  justify-start items-start  pt-4 ">
                <span class="sm:text-xl text-md font-sans mt-1">Testimony</span>
                <div class="h-[20rem] w-full  overflow-hidden">
                    <QuillEditor v-model:content="form.body" content-type="html" theme="snow"
                        class="w-full h-full border text-lg bg-white" :toolbar="toolbarOptions"
                        placeholder="(required)" required/>
                </div>
            </div>

           <!--Images-->
           <div class="flex flex-col pt-8">
            <!--Images-->
            <label for="TestimonyContent" class="font-sans font-semibold pb-3 text-start text-2xl">Uploaded Image/s:</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-2 px-4 pb-5  flex-col w-full">
                <div class="flex flex-col w-full h-full rounded-2xl items-center" v-for="image in files">
                    <div class="h-full flex flex-col">
                        <img :src="image.thumbnails.medium" 
                        class="relative w-full h-full rounded-2xl md:h-[13rem] md:w-[13rem] xl:h-[18rem] xl:w-[18rem] 2xl:h-[20rem] 2xl:w-[20rem]"
                        :class="componentOptions.deleteImage ? 'hover:scale-100' : 'hover:scale-110'">
                        <input 
                            type="checkbox" 
                            :value="image.id" 
                            v-model="deleteFiles"
                            class="absolute z-10 rounded-3xl"
                            v-if="componentOptions.deleteImage"
                        > 
                    </div>  
                    
                </div>
                
                <div class="flex flex-col w-full h-full rounded-2xl items-center" v-if="componentOptions.message == 'no img'">
                    <span class="font-sans"> (No Image/s Available)</span>
                </div>
            </div>
            
            <!--Image Upload-->
            <div 
                class="flex flex-row w-full h-full px-4 pb-4"
                v-if="componentOptions.addImage"
            >
                <TFileDrop 
                    v-model="newFiles" 
                    multiple
                    accept=".jpeg,.png,.jpg,.gif"
                    class="p-2 text-xs md:p-10 md:text-sm w-full">
                </TFileDrop>
            </div>

            <!--Image Upload btn-->
            <div 
                class="flex flex-row w-full h-full px-4 gap-2"
                v-if="componentOptions.addImage"
            >
                <div
                    class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-red-500 hover:to-red-600 shadow-xl"
                    @click="addImage"
                >
                    <span class="font-sans">Add</span>
                </div>
                <div
                    class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-red-500 hover:to-red-600 shadow-xl"
                    @click="restoreImageButton"
                >
                    <span class="font-sans">Cancel</span>
                </div>
            </div>

            <!--Image Delete-->
            <div 
                class="flex flex-row w-full h-full px-4 gap-2"
                v-if="componentOptions.deleteImage"
            >
                <div
                    class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-red-500 hover:to-red-600 shadow-xl"
                    @click="deleteImage"  
                >
                    <span class="font-sans">Delete</span>
                </div>
                <div
                    class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-red-500 hover:to-red-600 shadow-xl"
                    @click="restoreImageButton"
                >
                    <span class="font-sans">Cancel</span>
                </div>
            </div>

            <!--Actions-->
            <div class="flex flex-row gap-2 p-2" v-if="!componentOptions.addImage&&!componentOptions.deleteImage">
                <template v-if="files">
                    <div
                        class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl text-xs md:text-base"
                        v-if="files.length != 0"
                        @click="showDelete"
                    >
                        <span class="font-sans">Delete Image</span>
                    </div>
                </template>
                <div
                    class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl text-xs md:text-base"
                    @click="showUpload"    
                >
                    <span class="font-sans">Add Image</span>
                </div>
            </div>
        </div>

            <!--submit and cancel btn-->
            <div class="flex flex-row justify-center gap-2">
                <div class="flex flex-col items-center justify-center pt-4">
                    <span @click="onSubmit" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
                </div>
                <div class="flex flex-col items-center justify-center pt-4">
                    <span @click="onCancel" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
                </div>
            </div>
        </div>
        
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { notify } from 'notiwind';
import TFileDrop from '@/components/Forms/TFileDrop.vue';
import ReportFileDrop from '@/components/Forms/ReportFileDrop.vue';
import imageCompression from 'browser-image-compression';
import DeleteTestimonyImage from '@/pages/admin/Testimonials/DeleteTestimonyImage.vue'

export default defineComponent({
    name: 'EditTestimony',
    setup(){},
    components:{
        QuillEditor,
        TFileDrop,
        ReportFileDrop,
        DeleteTestimonyImage
    },
    emits:['done'],
    mounted(){
        this.getTestimonies()
    },
    data(){
        return{
            componentOptions: {
                message:'',
                addImage: false,
                deleteImage: false,
                showModal: false,
                alertMsg: '',
                activeStatus:false,
                hasNewData: false,
                successMsg: '',
                hasChanges:false
            },
            form:{
                title: '',
                body: ''
            },
            testimony: null,
            files:[],
            newFiles:[],
            deleteFiles:[],
            toolbarOptions: [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                [{ 'color': [] }],          // dropdown with defaults from theme
                [{ 'align': [] }],
                ['clean']                                         // remove formatting button
            ]
        }
    },
    methods:{
        getTestimonies(){
            this.$api.get(`/testimony/${this.$route.params.hash}`)
            .then((response)=>{
                this.testimony = response.data.testimony
                this.form.title = this.testimony.title
                this.form.body = this.testimony.body
                this.files = response.data.testimony.image
                this.componentOptions.message = response.data.message
                this.componentOptions.activeStatus = false
                if(this.componentOptions.hasNewData){
                    this.notify(this.componentOptions.successMsg)
                }
            })
            
        },
        onSubmit(){
            let changes = this.getChanges(this.form)
            this.$api.patch('/testimony/update',changes)
            .then((response)=>{
                if(this.hasChanges == true){
                    notify({group:"main", title:"Testimony Updated", type:"positive"},'3000')
                }
                else{
                    notify({group:"main", title:"No updates made", type:"positive"},'3000')
                }
                this.$router.push({ name: 'AdminTestimonials'})
            })
            
        },
        getChanges(form){
            let changes = {}
            changes['id'] = this.testimony.hash
            if(form.title != this.testimony.title){
                this.componentOptions.hasChanges = true
                changes['title'] = form.title
            }
            else{
                changes['title'] = form.title
            }
            if(form.body != this.testimony.body){
                this.componentOptions.hasChanges = true
                changes['body'] = form.body
            }
            else{
                changes['body'] = form.body
            }
            return changes;
        },
        showUpload(){
            this.componentOptions.addImage = !this.componentOptions.addImage
            this.componentOptions.deleteImage = false
        },
        showDelete(){
            this.componentOptions.deleteImage = !this.componentOptions.deleteImage
            this.componentOptions.addImage = false
        },
        restoreImageButton(){
            this.componentOptions.addImage = false
            this.componentOptions.deleteImage = false
            this.newFiles = []
            this.deleteFiles = []
        },
        onCancel(){
            this.$router.push({ name: 'AdminTestimonials'})
        },
        async addImage(){
            let token = null
            token = this.testimony.hash
            if(this.newFiles.length != 0){
                this.componentOptions.alertMsg = 'Adding images,please wait'
                this.componentOptions.activeStatus = true
                await this.uploadImage(token, this.newFiles)
            }
            else{
                notify({group:"main", title:"Select image/s to upload", type:"negative"},'3000')
            }
        },
        async uploadImage(hash,files){
            for(let i=0; i<files.length; i++){
                let formData = await this.compressAndPrepImage(hash,files[i])
                await this.$api.post('/testimony/create-photo',formData)
            }
            this.restoreImageButton()
            this.getTestimonies()
            this.componentOptions.successMsg = 'Image/s added successfully'
            this.componentOptions.hasNewData = true
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
            form.append('uuid',file.id)
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
        notify(message){
            notify({group:"main", title:message, type:"positive"},'3000')
            this.componentOptions.hasNewData = false   
        },
        deleteImage(){
            this.componentOptions.showModal = true
        },
        closeModal(){
            this.componentOptions.showModal = false
            this.deleteFiles = []
        },
        deleteFailed(){
            this.closeModal()
            notify({group:"main", title:"Select image/s to delete", type:"negative"},'3000')
        },
        refreshPage(){
            this.componentOptions.showModal =false
            this.getTestimonies()
            this.deleteFiles = []
            this.toDelete = ''
            this.restoreImageButton()
            this.componentOptions.successMsg = 'Image/s deleted successfully'
            this.componentOptions.hasNewData = true
        },
        showLoader(){
            this.componentOptions.alertMsg = 'Deleting photo/s, please wait'
            this.componentOptions.activeStatus = true
        }
    }
})
</script>
