<template>
   
    <div class="flex flex-row  pr-2 text-slate-800 p-2 items-center justify-center">
        <div class="flex flex-col">
            <TIcon name="upload" size="lg"></TIcon>

        </div>

        <div class="flex flex-col">
            <span class="font-sans text-2xl font-semibold flex flex-col items-center justify-center">Upload A Form</span>

        </div>

    </div>
    <div class="flex flex-row items-center justify-center">
        <hr class="my-2 border border-slate-800 w-[10rem] sm:w-[40rem]">
    </div>
    <div class="flex flex-col justify-start mx-5">
        <span class="font-sans text-sm font-semibold">Follow The Steps On Form Submission: </span>
        <span class="font-sans text-sm">1.Download the Form From The List </span>
        <span class="font-sans text-sm">2.Fill Up the Downloaded Form. </span>
        <span class="font-sans text-sm">3.Submit The Form. </span>
    </div>

    <div class="flex flex-row items-center justify-center">
        <hr class="my-2 border border-slate-800 w-[10rem] sm:w-[40rem]">
    </div>

    <div class="flex flex-col justify-start items-start px-5 gap-1 pb-3">
        <span class="w-full font-sans  text-start font-light">Form Name (Required)</span>
        <select v-model="form.fileName" class="w-full border border-slate-500 p-2 font-sans gap-2">
            <option selected value="">Choose a File</option>
            <option v-for="file in forms" :value="file.name" class="uppercase">{{ file.name }}</option>
        </select>
        <p><small v-if="componentOptions.validation.formName.show" class="text-xs font-sans text-red-500 italic">* This Field is Required</small></p>
    </div>

    <div class="flex flex-col justify-start items-start px-5 gap-2 pb-3">
        <span class="w-full font-sans  text-start font-light">Name of Client (Required)</span>
        <input v-model="form.clientName" type="text" name="Name" required id="" placeholder="Input Client Name"
            class="text-slate w-full border-black border shadow-sm py-1 px-3 font-sans">
            <p><small v-if="componentOptions.validation.clientName.show" class="text-xs font-sans text-red-500 italic">* This Field is Required</small></p>
    </div>

    <div class="flex flex-col justify-start items-start px-5 gap-2 pt-3">
        <span class="font-sans font-light text-start">Form File</span>
        <TFileDrop v-model="form.file" accept=".pdf,.docx,.xlsx,.xlx" :multiple="false" class="w-full p-10"></TFileDrop>
        <p><small v-if="componentOptions.validation.file.show" class="text-xs font-sans text-red-500 italic">* This Field is Required</small></p>
    </div>

    <div class="flex flex-row justify-center gap-2 pb-4">
        <div class="flex flex-col items-center justify-center pt-4">
            <span @click="showModal"
                class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
        </div>

        <div @click="cancel" class="flex flex-col items-center justify-center pt-4">
            <span
                class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
        </div>


    </div>
    <TInnerLoading :is-full-screen="true" :text="componentOptions.innerLoading.text" :active="componentOptions.innerLoading.show"></TInnerLoading>
    <Modal :show="componentOptions.showModal" @close="closeModal">
        <ConfirmSubmission v-if="componentOptions.showModal" @proceed="submit" @cancel="closeModal" />
    </Modal>
</template>

<script>
import { defineComponent } from 'vue';
import { Uploader } from '@/scripts';
import { Notification, notify } from 'notiwind';
import TInnerLoading from '@/components/TInnerLoading.vue';
import ConfirmSubmission from '@/pages/public/confirmSubmission.vue'



export default defineComponent({
    name: 'UploadForm',
    components: {
        TInnerLoading,
        ConfirmSubmission
    },

    props:{
        forms:{
            type:Object,
            default:null
        }
    },

    setup() { },
    emits: [
        'cancel',
        'done',
        'close'
    ],
    data() {
        return {
            componentOptions: {
                innerLoading:{
                    show:false,
                    text:''
                },

                validation:{
                    formName:{
                        show:false,
                    },
                    clientName:{
                        show:false,
                    },
                    file:{
                        show:false,
                    },
                },
                showModal: false
            },
            form:{
                file: [],
                fileName:"",
                clientName:""
            }
            
        }
    },
    methods: {


        cancel() {
            this.$emit('cancel')
        },

        submit(){
            this.closeModal()
            if(this.form.fileName && this.form.clientName && this.form.file!=[]){
                if(this.form.fileName==this.form.file[0].file.name){
                    this.innerLoading('Uploading File')

                    let uploader = new Uploader({
                        axios: this.$axios,
                        url: "/public/enforcer-room/submit-file", //from api
                        chunkSize: 1024 * 1024 * 2, // 2MB
                    });

                    return uploader
                    .upload(this.form.file[0].file, this.form.fileName, null)

                    .then((response) => {
                    
                        this.setSubmittedBy(response.data.data.hash)
                        this.form.file=[]
                        this.form.fileName=''
                    })
                    .finally(()=>{
                        this.closeInnerLoading()
                        notify({group:"main",title:"File Uploaded Successfully",type:"positive"},"3000") 
                        this.$emit('done')
                        this.cancel()
                    })
                    .catch((err) => {
                      
                        return null;
                    });
                }
                else{
                    notify({group:"main",title:"Both File Names Should Be The Same",type:"negative"},"3000")
                    this.form.file=[]
                }
            }
            else{
                if(!this.form.fileName){
                    this.componentOptions.validation.formName.show=true
                }

                if(!this.form.clientName){
                    this.componentOptions.validation.clientName.show=true
                }

                if(this.form.file=[]){
                    this.componentOptions.validation.file.show=true
                }  
            }

           
           

        },

        setSubmittedBy(hash){
            this.$api.patch('/public/set-submitted-by',{
                
                    hash:hash,
                    title:this.form.clientName,
                
            })
        },

        innerLoading(text){
            this.componentOptions.innerLoading.show=true
            this.componentOptions.innerLoading.text=text
        },

        closeInnerLoading(){
            this.componentOptions.innerLoading.show=false
        },
        showModal(){
            this.componentOptions.showModal = true
        },
        closeModal(){
            this.componentOptions.showModal = false
        }


        

    }
})
</script>
