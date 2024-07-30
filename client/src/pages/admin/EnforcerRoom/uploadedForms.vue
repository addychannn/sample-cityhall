<template>
    <Page >
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <DeleteForm :hash="componentOptions.selectedForm"  v-if="componentOptions.mode == 'Delete'"
                @cancel="closeModal" @done="refreshTable" />
            <UploadForm v-if="componentOptions.mode == 'upload'" @cancel="closeModal" @done="refreshTable" />

        </Modal>

        <div class="flex flex-col md:py-4">
            <div class="flex flex-row px-1 md:px-5 lg:px-10 py-1 md:py-10 justify-between">
                <!-- Header -->
                <div class="flex flex-row">
                    <TIcon name="newspaper" size="xl"></TIcon>
                    <span class="font-sans text-xl sm:text-5xl font-semibold flex flex-col items-center justify-center">Smoke-Free Forms</span>
                </div>
                <!-- Icons -->
                <div class="flex flex-row gap-1">
                    <div class="flex flex-col">
                        <TIcon v-if="$guard.can(['enforcer_upload_new_form'])" @click="showModal('upload')" name="add" size="md" class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md first-letter:hover:scale-110 hover:bg-green-700"></TIcon>
                    </div>
                    
                    <router-link :to="{ name: 'ListOfDeletedForms' }">
                        <TIcon v-if="$guard.can(['enforcer_view_deleted_uploaded_forms_list','enforcer_restore_deleted_form'])" name="folder_delete" size="md"
                            class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md hover:scale-110 hover:bg-green-700">
                        </TIcon>
                    </router-link>
                    
                </div>
            </div>

            <div class="flex flex-col md:flex-row  justify-between gap-2 px-8">
                <div class="flex flex-row w-full">
                    <input 
                    @input="debounceInput"
                    type="text" 
                    id="default-input"
                    class="font-sans p-2 rounded-md sm:w-1/2"
                    placeholder="Search Forms">
                </div>
                <div v-if="$guard.can('enforcer_view_uploaded_forms')" class="flex flex-col sm:flex-row ss:flex-row ss:gap-1 items-end justify-end 2xl:gap-5 px-2">
                    <router-link :to="{ name: 'EnforcerRoom' }">
                        <span class="w-[10rem] font-sans flex flex-col ss:flex-row  items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 2xl:text-base  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ss:text-xs ">Submitted Forms</span>
                    </router-link>
                    <router-link :to="{ name: 'EnforcerReminders' }">
                        <span class="font-sans flex flex-col ss:flex-row items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 2xl:text-base text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ss:text-xs ">Reminders</span>
                    </router-link>
                </div>

                

            </div>
        </div>
 

        <div class="flex flex-col px-1  md:px-5 lg:px-10">
             <!--Uploaded Forms List xs -->
             <div class="flex flex-col ">
                <div
                    class=" sm:hidden w-full bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 ">
                    <table class="w-full text-gray-500 dark:text-gray-400">
                        <thead class="text-lg font-semibold font-sans uppercase border-b border-slate-800 text-slate-800">
                            <th class="text-center pl-2 text-slate-800">Form Name</th>
                            <th class="text-center text-slate-800">Actions</th>
                        </thead>
                        <tbody>

                            <tr v-for="form in forms"
                            class="bg-white border-b last:border-b-0 cursor-pointer dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class=" py-4 font-medium font-sans text-gray-900 whitespace-nowrap dark:text-white">
                                {{ form.name }}
                            </th>

                                <td class="py-4 font-sans items-center justify-center text-center  ">

                                    <TIcon v-if="$guard.can(['enforcer_view_submitted_form_preview'])" @click="viewForm(form.id)" name="visibility" size="sm"
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600 px-1">
                                    </TIcon>

                                    <TIcon v-if="$guard.can(['enforcer_delete_submitted_form'])" @click="showModal('Delete', form.id)" name="delete" size="sm"
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600 px-1">
                                    </TIcon>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
             </div>
    
        </div>

        <div class="flex flex-col px-1  md:px-5 lg:px-10">
              <!--Submitted Forms List lg -->
           <div>
            <div
            class="hidden sm:flex lg:flex w-full bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 ">
            <table class="w-full text-gray-500 dark:text-gray-400">
                <thead class="text-lg font-semibold font-sans uppercase border-b border-slate-800 text-slate-800">
                    <th class="text-center pl-2 text-slate-800">Form Name</th>
                    <th class="text-center text-slate-800">File Size</th>
                    <th class="text-center text-slate-800">File Type</th>
                    <th class="text-center text-slate-800">Actions</th>
                </thead>
                <tbody>

                    <tr v-for="form in forms"
                    class="bg-white border-b last:border-b-0 cursor-pointer dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row"
                        class=" py-4 font-medium font-sans text-gray-900 whitespace-nowrap dark:text-white">
                        {{ form.name }}
                    </th>


                        <td class="py-4 font-sans items-center justify-center text-center ">
                            {{(form.size/(1024*1024)).toFixed(2)}} MB

                        </td>


                        <td class="py-4 font-sans items-center justify-center text-center">
                            {{ form.ext }}
                        </td>

                        <td class="py-4 font-sans items-center justify-center text-center  ">

                            <TIcon v-if="$guard.can(['enforcer_view_submitted_form_preview'])" @click="viewForm(form.id)" name="visibility" size="sm"
                                class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600 px-1">
                            </TIcon>

                            <TIcon v-if="$guard.can(['enforcer_delete_submitted_form'])" @click="showModal('Delete', form.id)" name="delete" size="sm"
                                class="pt-1 cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600 px-1">
                            </TIcon>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
         </div>
        </div>

         

        

       
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import UploadForm from './uploadForm.vue';
import DeleteForm from './deleteForm.vue';
import _ from 'lodash';


export default defineComponent({
    name: 'UploadedForms',
    components: {
        UploadForm,
        DeleteForm
    },
    setup() { },

    mounted() {
        this.getForms('')
    },
    data() {
        return {
            componentOptions: {
                showModal: false,
                mode: '',
                selectedForm:''
            },

            forms: [],
            searchOptions:{
                term: ''
            },
        }
    },
    methods: {
        showModal(mode,hash) {
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode

            if(mode=='Delete'){
                this.componentOptions.selectedForm=hash
            }
            
        },
        closeModal() {
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''

        },
        refreshTable() {
            this.getForms();
        },

        getForms(term='') {
            this.$api.get('/admin/enforcer-room/get-uploaded-forms',{
                params:{
                    term:term
                }
            })
                .then((response) => {
                   
                    this.forms = response.data.forms
                })
        },

        viewForm(hash){
            this.$router.push({ name: 'ViewUploadedForm', params: { hash:hash } })
        },

       
        debounceInput: _.debounce(function(e){
            this.searchOptions.offset = 0;
            this.searchOptions.term = e.target.value;
            this. getForms(this.searchOptions.term)
        },500)


    },

})
</script>
