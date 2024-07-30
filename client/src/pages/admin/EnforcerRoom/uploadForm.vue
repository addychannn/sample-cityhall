<template>
    <Page>
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

    <div class="flex flex-col justify-start items-start px-5 gap-2 pb-3">
        <span class="w-full font-sans  text-start font-light">Name of Form</span>
        <input v-model="form.fileName" type="text" name="Name" required id="" placeholder="Input Form Name"
            class="text-slate rounded w-full bg-slate-200 border-black shadow-sm py-1 px-3 font-sans">
    </div>

    <div class="flex flex-col justify-start items-start px-5 gap-2 pt-3">
        <span class="font-sans font-light text-start">Form File</span>
        <TFileDrop v-model="form.file" accept=".pdf,.docx,.xlsx,.xls" :multiple="false" class="w-full p-10"></TFileDrop>

    </div>

    <div class="flex flex-row justify-center gap-2 pb-4">
        <div class="flex flex-col items-center justify-center pt-4">
            <span @click="submit"
                class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
        </div>

        <div @click="cancel" class="flex flex-col items-center justify-center pt-4">
            <span
                class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
        </div>


    </div>
    <TInnerLoading :is-full-screen="true" :text="componentOptions.innerLoading.text" :active="componentOptions.innerLoading.show"></TInnerLoading>
</Page>
</template>

<script>
import { defineComponent } from 'vue';
import { Uploader } from '@/scripts';
import { Notification, notify } from 'notiwind';
import TInnerLoading from '@/components/TInnerLoading.vue';



export default defineComponent({
    name: 'UploadForm',
    components: {
        TInnerLoading
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
                }
            },
            form:{
                file: [],
                fileName:"",
            }
            
        }
    },
    methods: {


        cancel() {
            this.$emit('cancel')
        },

        submit(){

        
            this.innerLoading('Uploading File')

            let uploader = new Uploader({
                axios: this.$axios,
                url: "/admin/enforcer-room/upload-file", //from api
                chunkSize: 1024 * 1024 * 2, // 2MB
            });

            return uploader
            .upload(this.form.file[0].file, this.form.fileName, null)

            .then((response) => {
              
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

        },

        innerLoading(text){
            this.componentOptions.innerLoading.show=true
            this.componentOptions.innerLoading.text=text
        },

        closeInnerLoading(){
            this.componentOptions.innerLoading.show=false
        },



        

    }
})
</script>
