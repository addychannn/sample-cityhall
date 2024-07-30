<template>
    <Page class="p-3">
        <!--Modal-->
        <Modal :show="componentOptions.showModal" @close="hideModal">
            <CreateTestimony v-if="componentOptions.mode === 'create'"></CreateTestimony>
            <EditTestimony v-if="componentOptions.mode === 'edit'"></EditTestimony>
            <DeleteTestimony v-if="componentOptions.mode === 'delete'" @cancel="hideModal"></DeleteTestimony>
            <TestimonyContent v-if="componentOptions.mode === 'show'"></TestimonyContent>
            <ImageTestimony v-if="componentOptions.mode === 'showImage'"></ImageTestimony>
        </Modal>
        <!--Page Label and Add btn-->
        <div class="flex flex-row md:text-start justify-between">
            <div class="flex flex-col p-2">
                <span class="font-sans text-2xl md:text-3xl lg:text-4xl xl:text-5xl text-black font-semibold">Testimonies Management</span>
            </div>
            <div class="flex flex-col items-end justify-center lg:pr-4">
                <TIcon @click="showModal('create')" name="add" size="md" class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md hover:scale-110 hover:bg-green-700"></TIcon>
            </div>
        </div>
        <!--Search Bar-->
        <div class="flex flex-row pb-4">
            <div class="flex flex-col w-full md:w-[25rem] ">
                <input type="text" id="default-input"
                    class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search Testimony">
            </div> 
            <div class="flex flex-col w-full md:w-[25rem]">
                <input type="date" id="default-input"
                    class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search Testimony">
            </div> 
            
            
        </div>
       
        <!--Testimonies-->
        <div class="flex-col py-2 px-2 md:px-5 grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 w-full">
            <!-- sample1 -->
            <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <img @click="showModal('showImage')" src="../../assets/smokebaguio.png" class="object-cover">
                </a>
                <div class="p-5">
                    <span class=" font-sans mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">TITLE</span>
                    <div class="flex items-center">
                        <p class="mb-3 font-sans text-gray-700 dark:text-gray-400">Testimony overview</p>
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <TIcon @click="showModal('show')" name="visibility" size="md" class="cursor-pointer hover:scale-110 hover:text-green-700 dark:hover:bg-green-700 text-green-600"></TIcon>
                        <TIcon @click="showModal('edit')" name="edit" size="md" class="cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600"></TIcon>
                        <TIcon @click="showModal('delete')" name="delete" size="md" class="cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600"></TIcon>                        
                    </div>
                </div>
            </div> 

            <!-- sample2 -->
            <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <img src="../../assets/smokebaguio.png" class="object-cover">
                </a>
                <div class="p-5">
                    <span class=" font-sans mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white ">TITLE</span>
                    <div class="flex items-center">
                        <p class="mb-3 font-sans text-gray-700 dark:text-gray-400">Testimony overview</p>
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <TIcon name="visibility" size="md" class="cursor-pointer hover:scale-110 hover:text-green-700 dark:hover:bg-green-700 text-green-600"></TIcon>
                        <TIcon name="edit" size="md" class="cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600"></TIcon>
                        <TIcon name="delete" size="md" class="cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600"></TIcon>                        
                    </div>
                </div>
            </div> 

            <!-- sample3 -->
            <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <img src="../../assets/smokebaguio.png" class="object-cover">
                </a>
                <div class="p-5">
                    <span class=" font-sans mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white ">TITLE</span>
                    <div class="flex items-center">
                        <p class="mb-3 font-sans text-gray-700 dark:text-gray-400">Testimony overview</p>
                    </div>
                    <div class="flex flex-row items-center justify-center gap-2">
                        <TIcon name="visibility" size="md" class="cursor-pointer hover:scale-110 hover:text-green-700 dark:hover:bg-green-700 text-green-600"></TIcon>
                        <TIcon name="edit" size="md" class="cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600"></TIcon>
                        <TIcon name="delete" size="md" class="cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600"></TIcon>                        
                    </div>
                </div>
            </div> 
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import CreateTestimony from './testimonials/createTestimony.vue';
import EditTestimony from './testimonials/editTestimony.vue';
import DeleteTestimony from './DeleteTestimony.vue';
import TestimonyContent from '../public/testimonyContent.vue'
import ImageTestimony from './Testimonials/ImageTestimony.vue';

export default defineComponent({
    name: 'TestimonyManagement',
    setup(){},
    components:{
        CreateTestimony,
        EditTestimony,
        DeleteTestimony,
        TestimonyContent,
        ImageTestimony
    },
    data(){
        return{
            componentOptions: {
                showModal: false,   
                mode: ''
            }
        }
    },
    methods:{
        showModal(mode){
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
        },
        hideModal(){
            this.componentOptions.showModal = false
        },
    }
})
</script>
