<template>
    <Page>
        <div class="flex flex-col md:px-4">
            <div class="block items-center justify-center rounded-3xl px-1 py-1 md:px-5 xl:px-10 md:py-10">
                <!--Header-->
                <div class="flex flex-row md:py-7 lg:px-5 justify-between relative">
                    <!--Page Title-->
                    <div class="flex flex-row items-center">
                        <TIcon name="info" size="xl" class="text-slate-800 pl-2 md:px-2 "></TIcon>
                        <span class="text-xl md:text-3xl lg:text-5xl font-sans text-slate-800 font-semibold">About Page Management</span>
                    </div>
                    <!--Actions for md and above screen-->
                    <div v-if="$guard.can(['about_section_add','about_section_arrange'])" class="hidden md:flex flex-row items-end justify-center pr-4 gap-1">
                        <router-link to="/admin/about/create">
                            <span>
                                <TToolTip class="text-xs font-sans">Add New Section</TToolTip>
                                <TIcon name="add" size="md" 
                                class="cursor-pointer bg-green-600 text-white rounded-full  p-2 shadow-md hover:scale-110 hover:bg-green-700">
                            </TIcon>
                            </span>
                        </router-link>
                       
                        
                        <span>
                            <TToolTip class="text-xs font-sans">Rearrange Sections</TToolTip>
                            <TIcon @click="showModal(hash,'Arrange')" name="swap_vert" size="md"
                            class="cursor-pointer bg-green-600 text-white rounded-full  p-2 shadow-md hover:scale-110 hover:bg-green-700">
                        </TIcon>
                        </span>
                       

                    </div>
                    <!--Action for sm screen-->
                    <div v-if="$guard.can(['about_section_add','about_section_arrange'])" class="flex md:hidden flex-col items-end justify-center ">
                        <TIcon @click="showActions" name="more_vert" size="md" class="cursor-pointer"></TIcon>
                        <!--Actions-->
                        <div v-if="componentOptions.openOptions" class="absolute z-50 top-10 right-3 w-3/5 flex flex-col bg-white shadow-md">
                            <div class="flex flex-row cursor-pointer hover:bg-gray-400 w-full justify-end pr-1">
                                <router-link to="/admin/about/create">
                                    <span class="font-sans">Add Section</span>
                                </router-link>
                            </div>

                            <div class="flex flex-row cursor-pointer hover:bg-gray-400 justify-end w-full pr-1">
                                <span @click="showModal(hash,'Arrange')" class="font-sans">Arrange Section</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- search -->
                <div class="flex flex-row w-full md:w-[25rem] pb-2">
                    <input @input="debounceInput" type="text" class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Here">
                </div>
              

                <!-- Table -->
                <div class="flex flex-col bg-white p-5 rounded-lg">
                    <!-- md-lg -->
                    <div class="hidden md:flex flex-row w-full justify-between items-start hover:bg-emerald-300 duration-500 transition-all p-3 rounded-xl">
                        <span class="font-sans font-semibold text-lg w-1/4">Section</span>
                        <span class="font-sans font-semibold text-lg w-1/4">Content</span>
                        <span class="font-sans font-semibold text-lg w-1/4 text-end">Actions</span>
                    </div>

                    <!-- sm -->
                    <div class="flex flex-row md:hidden lg:hidden w-full justify-between items-start hover:bg-emerald-300 duration-500 transition-all p-3 rounded-xl">
                        <span class="font-sans font-semibold text-lg w-1/4">Section</span>
                        <span class="font-sans font-semibold text-lg w-1/4 text-end">Actions</span>
                    </div>

                    <!-- md-lg -->
                    <div v-for="section in sectionList" class="hidden md:flex flex-row w-full p-3 my-3 justify-between items-start border-t border-t-1 border-slate-900 hover:bg-slate-200 transition-all duration-500 rounded-xl">
                        <span class="font-sans font-light text-md w-1/4">{{ section.title }}</span>
                        <span class="font-sans font-light text-md w-1/4 line-clamp-1" v-html="section.content"></span>
                        <span class="font-sans font-light text-md w-1/4 text-end gap-1">
                            <span>
                                <TToolTip class="font-sans text-sm">{{section.show ? 'Section Visible, Click to Hide':'Section Hidden,Click to Show'}}</TToolTip>
                                <TIcon @click="showInPublic(section.hash)" v-if="!section.protected" :name="section.show ? 'visibility' : 'visibility_off'" size="md" :class="section.show ? 'm-1 text-amber-400 hover:scale-125 cursor-pointer' : 'm-1 text-slate-600 hover:scale-125 cursor-pointer'"></TIcon>
                            </span>
                            <span>
                                <TToolTip class="font-sans text-sm">Edit This Section Details</TToolTip>
                                <TIcon @click="goToEditPage(section.hash)" name="edit_note" size="md" class="m-1 text-blue-600 hover:scale-125 cursor-pointer"></TIcon>
                            </span>
                            <span>
                                <TToolTip class="font-sans text-sm">Delete This Section</TToolTip>
                                <TIcon @click="showModal(section.hash,'Delete')" v-if="!section.protected" name="delete" size="md" class="m-1 text-red-600 hover:scale-125 cursor-pointer"></TIcon>              
                            </span>
                           
                            
                        </span>
                    </div>

                    <!-- sm -->
                    <div v-for="section in sectionList" class="flex flex-row md:hidden lg:hidden w-full my-3 justify-between items-start border-t border-t-1 border-slate-900">
                        <span class="font-sans font-light text-md w-1/4">{{ section.title }}</span>
                        <span class="font-sans font-light text-md w-1/4 text-end gap-1">
                            <span>
                                <TToolTip class="font-sans text-sm">{{section.show ? 'Section Visible, Click to Hide':'Section Hidden,Click to Show'}}</TToolTip>
                                <TIcon @click="showInPublic(section.hash)" v-if="!section.protected" :name="section.show ? 'visibility' : 'visibility_off'" size="md" :class="section.show ? 'm-1 text-amber-400 hover:scale-125 cursor-pointer' : 'm-1 text-slate-400 hover:scale-125 cursor-pointer'"></TIcon>
                            </span>
                            <span>
                                <TToolTip class="font-sans text-sm">Edit This Section Details</TToolTip>
                                <TIcon @click="goToEditPage(section.hash)" name="edit_note" size="md" class="m-1 text-blue-600 hover:scale-125 cursor-pointer"></TIcon>
                            </span>
                            <span>
                                <TToolTip class="font-sans text-sm">Delete This Section</TToolTip>
                                <TIcon @click="showModal(section.hash,'Delete')" v-if="!section.protected" name="delete" size="md" class="m-1 text-red-600 hover:scale-125 cursor-pointer"></TIcon>              
                            </span>
                           
                            
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-center pt-3">
                    <Pagination
                        v-model="searchOptions.page"
                        v-model:limit="searchOptions.limit"
                        :total="searchOptions.total"
                        v-model:offset="searchOptions.offset"
                        @update:totalPage="(count) => {pages=count}"
                        @paginate="getSectionList(searchOptions.term)"
                        direction-nav
                        boundary-nav
                    />
                </div> 

            </div>
        </div>


        <Modal :show="componentOptions.deleteModal.show" @close="closeModal('Delete')">
            <div class="p-6 text-center">
                <div >
                    <TIcon name="warning" size="xl" class="text-red-600">
                        <span class="material-symbols-outlined ">
                            warning
                            </span>
                    </TIcon>
                </div>
                
                <h3 class="font-sans text-xl font-normal text-black dark:text-gray-400">Are you sure you want to delete this Section?</h3>
                <button  @click="deleteSection" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Yes, I'm sure
                </button>
                <button  @click="closeModal" data-modal-hide="popup-modal" type="button" class="font-sans text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
            </div>
        
        </Modal>

        <Modal :show="componentOptions.arrangeModal.show" @close="closeModal('Arrange')">
            
                <div class="flex flex-row justify-center items-center border-b-2 border-slate-800 mb-5">
                    <TIcon name="open_with" size="xl"></TIcon>
                    <span class="text-3xl font-sans">Drag Sections To Rearrange</span>
                </div>

                <draggable v-model="newArrangement" group="sections" @start="drag=true" @end="drag=true" item-key="hash">
                <template #item="{element}">
                    <div class="flex flex-col px-5 py-1">
                        <div class="flex flex-row  justify-center items-center gap-3 border border-slate-900">
                            <span class="w-full bg-green-400 font-sans p-5 rounded-lg text-center text-xl cursor-move">{{ element.title }}</span>
                        </div>
                    </div>
                </template>
                </draggable>
                    <div class="flex flex-col p-5 ">
                        <div class="flex  justify-end items-end gap-2">
                            <span @click="arrangeSections" class=" bg-slate-400 font-sans px-3 py-2 rounded-2xl text-center text-md hover:cursor-pointer">Save</span>
                            <span @click="closeModal('Arrange')" class=" bg-slate-400 font-sans px-3 py-2 rounded-2xl text-center text-md hover:cursor-pointer">Cancel</span>
                        </div>
                    </div>
        </Modal>
        <TInnerLoading :is-full-screen="true" :text="componentOptions.innerLoading.text"
            :active="componentOptions.innerLoading.show"></TInnerLoading>
    </Page>
</template>

<style>
.image {
    background-repeat: no-repat;
    background-blend-mode: multiply;
    border-top-left-radius: 0.375rem;
    /* 6px */
    border-top-right-radius: 0.375rem;

    /* 6px */
}
</style>

<script>
import { defineComponent } from 'vue';
import draggable from 'vuedraggable';
import {notify} from 'notiwind';
import _ from 'lodash';
import TInnerLoading from '@/components/TInnerLoading.vue';

export default defineComponent({
    name: 'AdminAbout',

    setup() { },

    components:{
        draggable,
        TInnerLoading
    },

    mounted(){
        this.getSectionList('')
    },

    data() {
        return {
            componentOptions: {
                'openOptions': false,

                deleteModal:{
                    show:false,
                    hash:'',
                },

                arrangeModal:{
                    show:false,
                    hash:'',
                },
                innerLoading: {
                    show: false,
                    text: ''
                }
  
            },

            searchOptions:{
                    term:'',
                    offset:0,
                    limit:5,
                    total:0,
                    page:1,
                    pages:1,
                },

            sectionList:null,
            newArrangement:null,
            
        }
    },
    methods:{
        showActions(){
            this.componentOptions.openOptions = !this.componentOptions.openOptions
        },
        //call this function to close the dropdown after clicking an option
        closeOptions(){
            this.componentOptions.openOptions = false
        },

        getSectionList(term=''){
            this.$api.get('/admin/about',{
                params:{
                    term:term,
                    limit:this.searchOptions.limit,
                    offset:this.searchOptions.offset
                }
            })
                .then((response)=>{
                    this.sectionList = response.data.data
                    this.searchOptions.total = response.data.total
                   
                
                    
                    
                })
                
        },
        goToEditPage(hash){
           
            this.$router.push({ name: 'UpdateSection', params: { hash: hash } })
                
        },

        showInPublic(hash){
            this.$api.post('/admin/about/section-show',{hash:hash})
                .then((response)=>{
                    this.getSectionList()  
                    notify({group:"main",title:response.data.message , type:"positive"},"3000")
                })
        },

        showModal(hash,mode){
            if(mode=='Delete'){
                this.componentOptions.deleteModal.show = true
                this.componentOptions.deleteModal.hash = hash
            }
            if(mode=='Arrange'){
                this.componentOptions.arrangeModal.show = true
                this.componentOptions.arrangeModal.hash = hash
                this.newArrangement=this.sectionList
            }
            
        },

        closeModal(mode){
            if(mode=='Delete'){
                this.componentOptions.deleteModal.show = false
            }
            if(mode=='Arrange'){
                this.componentOptions.arrangeModal.show = false
            }
        },

        deleteSection(){
            this.innerLoading('Deleting Section Please Wait...')
            this.$api.delete('/admin/about/delete-section', {
                params:{
                    hash:this.componentOptions.deleteModal.hash
                }
            })
            .then((response)=>{
                this.closeModal("Delete")
                this.getSectionList()
                this.closeInnerLoading()
                notify({group:"main",title:"Section Deleted",type:"positive"},"3000")
            })
        },

        arrangeSections(){
           
            this.$api.post('/admin/about/arrange-sections',
                {sections:this.newArrangement}
            )
            .then((response)=>{
               
                this.sectionList=this.newArrangement
                this.closeModal("Arrange")
                notify({group:"main",title:"Sections Rearranged Successfully",type:"positive"},"3000")
            })
        },

        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.getSectionList(this.searchOptions.term)
        },500),

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
