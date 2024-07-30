<template>
    <Page class="flex flex-col px-1 md:px-6 lg:px-12 py-3">
        <!-- modal -->
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ReminderContent :content="remind"/>
          
        </Modal>

         <Modal :show="componentOptions.submitModal" @close="closeModal">
           
            <SubmitForm v-if="componentOptions.mode == 'Submit'" @cancel="closeModal"  :forms="forms"/>
        </Modal>


        <!--label xs-->
        <div class="flex flex-row sm:hidden">
            <span class="px-2 font-sans text-2xl font-bold">
                <TIcon name="meeting_room" size="md">
            </TIcon>Enforcers Room</span>
        </div>

        <!--label md-->
        <div class="hidden sm:flex">
            <span class="px-2 font-sans text-5xl font-bold">
                <TIcon name="meeting_room" size="xl">
            </TIcon>Enforcers Room</span>
        </div>


        <!--Search-->
        <div class="flex flex-col sm:flex-row items-start justify-start sm:justify-between px-2 py-3 gap-3 w-full">
            <div class="flex flex-row w-full">
                <input 
                @input="debounceInput"
                type="text" 
                id="default-input"
                class="font-sans p-2 rounded-full sm:w-1/2"
                placeholder="Search Forms">
            </div>
            <div class="flex flex-row gap-1 w-full sm:gap-3 justify-end">
                <div class="flex flex-row">  
                    <a target="_blank" href="https://drive.google.com/drive/folders/18MW4pTuua480W9K7ckZvLadrMP4SM3w4" class="font-sans font-semibold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-xs sm:text-base text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">GDrive Forms</a>
                </div>
                <div class="flex flex-row">
                    <span @click="submitModal('Submit')" class="font-sans font-semibold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-xs sm:text-base text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit Form</span>
                </div>
            </div>
        </div>
       


        <!-- table md-->
        <div class="hidden sm:flex">
            <table class="w-full text-gray-500 dark:text-gray-400">
                    <thead class="text-lg font-semibold font-sans uppercase border-b border-slate-800 text-slate-800">
                        <th class="text-start pl-2 text-slate-800">Form Name</th>
                        <th class="text-center text-slate-800">File Size</th>
                        <th class="text-center text-slate-800">File Type</th>
                        <th class="text-center text-slate-800">Actions</th>
                    </thead>
                    <tbody>

                        <tr v-for="form in forms"
                            class="bg-white border-b cursor-pointer dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            
                            <th scope="row"
                                class="flex items-start justify-start py-4 px-6 font-medium font-sans text-gray-900 whitespace-nowrap dark:text-white w-[20rem]">
                                {{ form.name }}
                            </th>


                            <td class="py-4 font-sans items-center justify-center text-center ">
                                {{(form.size/(1024*1024)).toFixed(2)}} MB

                            </td>


                            <td class="py-4 font-sans items-center justify-center text-center">
                                {{ form.ext }}
                            </td>

                            <td class="py-4 font-sans items-center justify-center text-center  ">

                                <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white cursor-pointer">
                                    <TIcon @click="downloadForm(form.id,form.name)" name="file_download" size="md" class="cursor-pointer hover:scale-110"></TIcon>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>

         <!-- table sm -->
         <div class="flex flex-col px-2 bg-white sm:hidden">
            <div class="flex flex-row justify-between items-center border-b border-slate-800 p-2">
                <span class="font-sans text-gray-700 font-bold">Form Name</span>
                <span class="font-sans text-gray-700 font-bold">Action</span>
            </div>

           <div v-for="form in forms" class="flex flex-row justify-between items-center border-t border-slate-600 p-2">
                <span class="font-sans text-gray-700">{{ form.name }}</span>
                <TIcon @click="downloadForm(form.id,form.name)" name="file_download" size="md" class="cursor-pointer hover:scale-110"></TIcon>
           </div>
        </div>

        <!--reminder header-->
        <div class="flex flex-row py-2 px-1 justify-start sm:justify-center">
            <TIcon name="emergency" size="md"></TIcon>
            <span class="font-sans text-3xl font-semibold">Reminder for Enforcers</span>
            <TIcon name="emergency" size="md"></TIcon>
        </div>

        <!-- reminders -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-6" style="cursor: auto;">
            <div v-for="item in reminders" @click="showModal(item)"
                class="p-8 bg-gray-100 rounded-lg hover:scale-110 cursor-pointer flex flex-col items-center justify-center">
                <!-- icon  -->
                <div class="mb-5 font-sans">
                    <TIcon :name="item.icon" size="xl" class="text-slate-800"></TIcon>
                </div>

                <p class="text-lg font-normal mb-2 font-sans text-center">
                    {{ item.title }}
                </p>
            </div>

        </div>

    </Page>
</template>

<script>
import { defineComponent } from 'vue';

import imageCompression from 'browser-image-compression';
import TFileDrop from '@/components/Forms/TFileDrop.vue';
import { Uploader } from '@/scripts';
import { notify } from 'notiwind';
import SubmitForm from './submitForm.vue';
import _ from 'lodash'

// Modals
import ReminderContent from '@/pages/public/EnforcersRoom/reminderContent.vue';

export default defineComponent({
    name: 'EnforcersRoom',
    setup() { },
    components: {
        ReminderContent,
        SubmitForm
    },

    emits:['cancel','done'],

    mounted(){
        this.getReminders()
        this.getForms()
    },


    data() {
        return {
            componentOptions: {
                showModal: false,
                submitModal: false,
                mode: ''
            },
            
            reminders: [],
            file: [],
            reminders:[],
            remind:[],
            forms:[],
            searchOptions:{
                term: ''
            },
        }
    },
    methods: {
        showModal(reminder) {
            this.componentOptions.showModal = true
            this.remind=reminder
           
        },

        submitModal(mode){
            this.componentOptions.submitModal = true
            this.componentOptions.mode = mode
        },
        closeModal() {
            this.componentOptions.showModal = false
            this.componentOptions.submitModal = false
            this.componentOptions.mode = ''

        },

        getReminders(){
            this.$api.get('/public/enforcement/get-reminders')
            .then((response)=>{
                this.reminders = response.data.data
               
            })
        },

        getForms(term = '') {
            this.$api.get('/admin/enforcer-room/get-uploaded-forms',{
                params:{
                    term:term
                }
            })
            .then((response) => {
                
                this.forms = response.data.forms
            })
        },

        downloadForm(hash,name){
           
            this.$api.get('/public/enforcer-room/download-form',{
                params:{
                    id:hash
                },
                responseType:'blob'
            })
            .then((response)=>{
               
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', name);
                document.body.appendChild(link);
                link.click();
            })
        },

        submitForm(){
           

            let uploader = new Uploader({
                axios: this.$axios,
                url: "/public/enforcer-room/submit-file", //from api
                chunkSize: 1024 * 1024 * 2, // 2MB
            });

            return uploader
            .upload(this.file[0].file, '', null)

            .then((response) => {
             
                this.file=[]
            })

            .finally(()=>{
                // this.closeInnerLoading()
                notify({group:"main",title:"File Uploaded Successfully",type:"positive"},"3000") 
                // this.$emit('done')
                // this.cancel()
            })
            .catch((err) => {
               
                return null;
            });


        },
        debounceInput: _.debounce(function(e){
            this.searchOptions.offset = 0;
            this.searchOptions.term = e.target.value;
            this.getForms(this.searchOptions.term)
        },500)


    }
})
</script>
