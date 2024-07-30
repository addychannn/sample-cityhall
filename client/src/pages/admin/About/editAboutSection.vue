<template>
     <Page class="w-full h-full p-5">
        <div class="flex flex-col px-5 py-3 gap-5">

            <span class="font-sans font-bold text-2xl md:text-4xl lg:text-4xl">Update Section</span>

            <div class="flex flex-col">
                <span class="font-sans font-semibold text-xl md:text-3x lg:text-3xl ml-5">Title:</span>
                <input v-model="form.title" class="font-sans text-lg font-light p-3 rounded-lg " type="text" name="title" placeholder="Title/Name of Section">
            </div>
            <div class="flex flex-col  justify-start items-start  pt-4 ">
                <span class="sm:text-xl text-md font-sans mt-1">Content:</span>
                <div class="h-[20rem] w-full  overflow-hidden">
                    <QuillEditor v-model:content="form.content" content-type="html" theme="snow"
                        class="w-full h-full border text-lg bg-white" :toolbar="toolbarOptions" required
                        placeholder="(required)" />
                </div>
            </div>
    
            
            <div class="flex flex-col border  gap-5 rounded-xl">
                <span class="font-sans font-semibold text-2xl md:text-3xl lg:text-3xl ml-5 mt-5">Uploaded Image/s:</span>
                <div class="flex flex-col md:grid md:grid-cols-3 lg:grid lg:grid-cols-3 gap-2 px-2">
                    <div v-for="image in images" class="flex flex-col  justify-center items-center">
                        <div class="flex flex-col h-full">
                            <img :src="image.thumbnails.medium" alt="images" class="relative w-full h-full rounded-2xl md:h-[13rem] md:w-[13rem] xl:h-[18rem] xl:w-[18rem] 2xl:h-[20rem] 2xl:w-[20rem]" >
                            <input 
                                type="checkbox" 
                                :value="image.id" 
                                v-model="componentOptions.images.deleteImages"
                                class="absolute z-10 rounded-3xl"
                                v-if="componentOptions.deleteCheckboxes.show"
                            >
                        </div>
                    </div>
                </div>

                <!-- Show if Add More Images is Clicked -->
                <div v-if="componentOptions.filedrop.show" class="px-2">
                    <TFileDrop v-model="componentOptions.images.newImages" multiple accept=".jpeg,.png,.jpg" class="p-10"></TFileDrop>
                </div>

                <div class="flex flex-col md:flex-row m-5 gap-5">
                    <span v-if="componentOptions.imageActionButtons.showAdd" @click="showFileDrop" v-text="componentOptions.filedrop.addMoreButtonText" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl"></span>
                    <span v-if="componentOptions.imageActionButtons.showDeleteButton && imagesLength != 0" @click="showDeleteCheckboxes"  v-text="componentOptions.deleteCheckboxes.deleteImagesButtonText"  class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl"></span>
                    <span v-if="componentOptions.imageActionButtons.showSaveButton" v-text="componentOptions.imageActionButtons.addOrDeleteText" @click="addOrDelete(componentOptions.imageActionButtons.addOrDeleteText)" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl"></span>
                </div>
            </div>

            <div class="flex flex-col md:flex-row border-t border-slate-900 pt-2 gap-5 ml-5">
                <span @click="saveChanges" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Save Changes</span>
                <span @click="backToAboutPage" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Back To About Page</span>
            </div>
            
        </div>

        <TInnerLoading :is-full-screen="true" :text="componentOptions.innerLoading.text"
            :active="componentOptions.innerLoading.show"></TInnerLoading>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import imageCompression from 'browser-image-compression';
import {notify} from 'notiwind';
import TInnerLoading from '@/components/TInnerLoading.vue';

export default defineComponent({
    name: 'EditAboutSection',

    setup(){},

    components:{
        QuillEditor,
        TInnerLoading
    },

    props:{
        hash:{
            type:String,
            default:''
        }
    },

    mounted(){
        this.getSection(this.hash)
    },

    data(){
        return{
            componentOptions: {
                images:{
                    newImages:[],
                    deleteImages:[]
                },

                filedrop:{
                    show:false,
                    addMoreButtonText:'Add More Image/s'
                },

                imageActionButtons:{
                    showAdd:true,
                    showDeleteButton:true,
                    showSaveButton:false,
                    addOrDeleteText:""
                },

                deleteCheckboxes:{
                    show:false,
                    deleteImagesButtonText:'Delete Image/s'
                },
                innerLoading: {
                    show: false,
                    text: ''
                }
                
            },
            form:{
                title:'',
                content:''
            },

            images:[],
            imagesLength:0
            
            
        }
    },

    methods:{
        getSection(hash){
            this.$api.get('/admin/about/section',{params:{hash:hash}})
                .then((response)=>{
                    this.form.title = response.data.data.title
                    this.form.content = response.data.data.content

                    if(response.data.gallery){
                        this.images = response.data.gallery
                        this.imagesLength = this.images.length
                    }

                   
                })    
        },

        saveChanges(){
            this.innerLoading('Saving Changes, Please Wait...')
            this.$api.patch('/admin/about/update',{
                    hash:this.hash,
                    title:this.form.title,
                    content:this.form.content
            })
                .then((response)=>{
                    this.getSection(this.hash)
                    this.$router.push({ name: 'AdminAbout'})
                    this.closeInnerLoading()
                    notify({group:"main",title:"Section Details Updated Successfuly",type:"positive"},"3000")
                })
        },

        showFileDrop(){
            this.componentOptions.filedrop.show = !this.componentOptions.filedrop.show

            this.componentOptions.imageActionButtons.showDeleteButton = !this.componentOptions.imageActionButtons.showDeleteButton

            this.componentOptions.imageActionButtons.showSaveButton = !this.componentOptions.imageActionButtons.showSaveButton

            if(this.componentOptions.filedrop.show==true){
                this.componentOptions.filedrop.addMoreButtonText='Cancel'
                this.componentOptions.imageActionButtons.addOrDeleteText ='Add'
            }
            else{
                this.componentOptions.filedrop.addMoreButtonText='Add More Image/s'
            }
        },
        showDeleteCheckboxes(){
            this.componentOptions.deleteCheckboxes.show = !this.componentOptions.deleteCheckboxes.show

            this.componentOptions.imageActionButtons.showAdd = !this.componentOptions.imageActionButtons.showAdd

            this.componentOptions.imageActionButtons.showSaveButton = !this.componentOptions.imageActionButtons.showSaveButton

            if(this.componentOptions.deleteCheckboxes.show==true){
                this.componentOptions.deleteCheckboxes.deleteImagesButtonText='Cancel'
                this.componentOptions.imageActionButtons.addOrDeleteText ='Delete'
            }
            else{
                this.componentOptions.deleteCheckboxes.deleteImagesButtonText='Delete Image/s'
            }
           
        },

        async uploadImage(hash, images){
            this.innerLoading('Adding Images, Please Wait...')
            for(let i = 0; i < images.length; i++){
               
                let formData = await this.compressAndPrepImage(hash, images[i])
                await this.$api.post('/admin/about/upload-photo', formData)
                    .then((response)=>{
                        this.getSection(hash)
                    })
                    
            }
            this.closeInnerLoading()
            notify({group:"main",title:"New Images Added to Section",type:"positive"},"3000")
            
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

       async addOrDelete(method){
            if(method=='Add'){

                if(this.componentOptions.images.newImages.length != 0){
                    this.addNewImages(this.hash,this.componentOptions.images.newImages)
                }
                else{
                    notify({group:"main",title:"Select image/s to upload",type:"negative"},"3000")
                }
                
              
            }
            if(method=='Delete'){
                if(this.componentOptions.images.deleteImages.length !=0){
                    this.deleteSelectedImages(this.componentOptions.images.deleteImages)
                }
                else{
                    notify({group:"main",title:"Select image/s to delete",type:"negative"},"3000")
                }
                
            }
        },

        addNewImages(hash,images){
            this.uploadImage(hash,images)
            this.componentOptions.images.newImages=[]
            this.showFileDrop()
           
            
        },

        deleteSelectedImages(images){
            this.innerLoading('Deleting Images, Please Wait...')
            this.$api.delete('/admin/about/delete-images',{
                params:{
                    hash:images
                }
            })
            .then((response)=>{
                this.componentOptions.images.deleteImages=[]
                this.showDeleteCheckboxes()
                this.getSection(this.hash)
                this.closeInnerLoading()
                notify({group:"main",title:"Images Deleted Successfuly",type:"positive"},"3000")
            })
        },

        backToAboutPage(){
            this.$router.push({ name: 'AdminAbout'})
        },

        innerLoading(text) {
            this.componentOptions.innerLoading.show = true
            this.componentOptions.innerLoading.text = text
        },

        closeInnerLoading() {
            this.componentOptions.innerLoading.show = false
        }

       
    }
})
</script>
