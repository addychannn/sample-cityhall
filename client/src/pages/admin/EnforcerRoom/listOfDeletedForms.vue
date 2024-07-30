<template>
    <Page>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmRestore v-if="componentOptions.showModal" @cancel="closeModal" @proceed="restoreForm"/>
        </Modal>
        <div class="hidden">
            <div class="flex-col px-1 md:px-5 lg:px-10 items-center justify-center gap-10 py-1 md:py-10">
                <div class="flex flex-row items-start justify-between ">

                    <div class="flex flex-row  pr-2 text-slate-800 p-2">
                        <div class="flex flex-col">
                            <TIcon name="newspaper" size="xl"></TIcon>

                        </div>

                        <div class="flex flex-col">
                            <span class="font-sans text-5xl font-semibold flex flex-col items-center justify-center">Deleted Smoke
                                Free Forms</span>

                        </div>

                    </div>

                    <div class="flex flex-row items-end justify-center lg:pr-4">

                        <div class="flex flex-col items-end justify-center lg:pr-4">



                        </div>

                    </div>

                </div>
                <div class="flex flex-col w-[25rem] p-2">

                    <!--search bar-->
                    <div class="flex flex-col w-full md:w-[25rem] pb-3">
                        <input @input="debounceInput" type="text" id="default-input"
                            class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Form">
                    </div>
                </div>


                <!--Forms list-->
                <div
                    class="hidden lg:flex w-full bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 ">
                    <table class="w-full">
                        <thead class="text-lg font-sans uppercase border-b border-slate-800 h-20">
                            <th class="text-start">Form Name</th>
                            <th class="text-center">File Type</th>
                            <th class="text-center">File Size</th>
                            <th v-if="$guard.can('enforcer_restore_deleted_form')" class="text-center">Actions</th>
                        </thead>
                        <tbody>
                            <tr v-for="form in forms" class="font-sans border-b border-slate-200 h-14 ">
                                <td class="text-start">
                                   {{ form.name }}
                                </td>


                                <td class="text-center">
                                    {{ form.ext }}

                                </td>


                                <td class="text-center">
                                    {{form.size}} KB
                                </td>

                                <td class="text-center">
                                    <div class="flex flex-col justify-center">
                                        <TIcon v-if="$guard.can('enforcer_restore_deleted_form')" @click="showModal(form.id)" name="restore_from_trash" size="md" class="cursor-pointer text-green-500 hover:text-green-600 hover:scale-110"></TIcon>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>



            </div>
        </div>

        <div class="flex flex-row p-5 justify-between items-start">
            <!-- Header -->
            <div class="flex flex-row">
                <TIcon name="newspaper" size="xl"></TIcon>
                <span class="font-sans text-xl sm:text-5xl font-semibold flex flex-col items-center justify-center">Deleted Smoke-Free Forms</span>
            </div>
        </div>

        <div class="flex flex-row mx-3 p-2">
            <!-- search -->
            <input @input="debounceInput" type="text" id="default-input"
                            class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Forms">
        </div>

        <div class="flex flex-col px-5">
             <!--Uploaded Forms List xs -->
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

                                    <div class="flex flex-col justify-center">
                                        <TIcon v-if="$guard.can('enforcer_restore_deleted_form')" @click="showModal(form.id)" name="restore_from_trash" size="md" class="cursor-pointer text-green-500 hover:text-green-600 hover:scale-110"></TIcon>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                 <!--Submitted Forms List lg -->
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

                                    <div class="flex flex-col justify-center">
                                        <TIcon v-if="$guard.can('enforcer_restore_deleted_form')" @click="showModal(form.id)" name="restore_from_trash" size="md" class="cursor-pointer text-green-500 hover:text-green-600 hover:scale-110"></TIcon>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>


    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import ConfirmRestore from './confirmRestore.vue';
import { notify } from 'notiwind';


export default defineComponent({
    name: 'ListOfDeletedForms',


    setup(){},
    components: {
        ConfirmRestore
    },
    
    mounted(){
        this.getDeletedForms()
    },

    data(){
        return{
            componentOptions: {
                selectedItem: null,
                showModal: false,
                alertMsg: '',
                activeStatus: false
            },

            forms:[],
            selected: null,
        }
    },

    methods:{
        getDeletedForms(){
            this.$api.get('/public/enforcement/get-deleted-forms')
            .then((response)=>{
                this.forms = response.data.forms
               
            })
        },
        restoreForm(){
            this.closeModal()
            this.componentOptions.alertMsg = 'Restoring Form'
            this.componentOptions.activeStatus = true
            this.$api.get(`form-restore/${this.componentOptions.selectedItem}`)
            .then((response)=>{
               
                this.getDeletedForms('')
                notify({group:"main", title:"File Restored", type:"positive"},'3000')
            })
        },
        showModal(form=null){
            this.componentOptions.showModal = true
            if(form != null){
                this.componentOptions.selectedItem = form
            }
        },
        closeModal(){
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''
        },
    }
})
</script>
