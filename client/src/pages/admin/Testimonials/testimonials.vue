<template>
    <Page>     
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <DeleteTestimony :testimony="componentOptions.selectedItem" @cancel="closeModal" @done="refreshTable" v-if="componentOptions.mode == 'delete'"/>
        </Modal>

        <div class="flex flex-col md:py-4">
            <div class="flex-col px-2 md:px-5 lg:px-10 items-center justify-center gap-10 py-1 md:py-6">
                <!--header-->
                <div class="flex flex-row md:py-5 justify-between">
                    <!--Label-->
                    <div class="flex flex-row text-slate-800 pt-2 items-center">
                        <TIcon name="text_snippet" size="xl" class="text-slate-800 md:px-2"></TIcon>
                        <span
                            class="text-xl md:text-3xl lg:text-5xl font-sans text-slate-800 font-semibold">Testimonials
                        </span>
                    </div>
                    <!--Create-->
                    <div class="flex flex-row items-center lg:pr-4 gap-2">
                        <router-link :to="{ name: 'CreateTestimony' }">
                            <TIcon v-if="$guard.can(['testimony_add'])" name="add" size="md" class="cursor-pointer bg-green-600 text-white rounded-full p-1 md:p-2 shadow-md hover:scale-110 hover:bg-green-700"></TIcon>
                        </router-link>
                        <router-link :to="{ name: 'ListDeletedTestimony' }">
                            <TIcon v-if="$guard.can(['testimony_list_deleted'])" name="folder_delete" size="md" class="cursor-pointer bg-green-600 text-white rounded-full p-1 md:p-2 shadow-md hover:scale-110 hover:bg-green-700"></TIcon>
                            <TToolTip  class="text-center">
                                <span class="font-sans text-center">Deleted Testimony</span>
                            </TToolTip>
                        </router-link>
                        
                        
                    </div>
                </div>

                <!--search-->
                <div class="flex flex-row gap-2 pb-2">
                    <div class="flex flex-row w-full md:w-[25rem] gap-2">
                        <input 
                            @input="debounceInput" 
                            type="text" 
                            id="default-input"
                            class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Testimony">

                            <input 
                                @input="debounceInput"
                                type="date" 
                                id="default-input"
                                class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search Testimony">
                    </div>
                  
                </div>
               
                

                
                <!--testimonies-->
                <div
                    v-if="searchOptions.total != 0"
                    class="bg-white border border-gray-200 rounded-lg shadow md:p-8 dark:bg-gray-800 dark:border-gray-700">
                
                    <!--list on md-above-->
                    <div class="hidden md:flex">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <tbody>
                                    <tr
                                        class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-b last:border-b-0"
                                        v-for="item in testimonyList"
                                       >
                                        <th scope="row"
                                            class="px-6 py-4 font-medium font-sans text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ item.title }} 
                                        </th>

                                        
                                            <td class="px-6 py-5 font-sans">
                                                <!-- <router-link :to="{ name: 'ViewTestimony', params:{hash: item.hash} }"> -->
                                                    <div 
                                                        class="line-clamp-2 " 
                                                        v-html="item.body">
                                                    </div>

                                                <!-- </router-link> -->
                                                
                                            </td>

                                            <td class="px-8  font-sans">
                                               {{ item.date }} 
                                            </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-row gap-1 justify-end">
                                                <div class="flex flex-col">
                                                    <router-link :to="{ name: 'ViewTestimony', params:{hash: item.hash} }">
                                                        <TIcon name="visibility" size="sm"
                                                            class="pt-1 cursor-pointer hover:scale-110 hover:text-gray-900 dark:hover:bg-gray-900 text-black">
                                                        </TIcon>
                                                    </router-link>
                                                </div>
                                                <div v-if="$guard.can(['testimony_edit'])" class="flex flex-col">
                                                    <router-link :to="{ name: 'testimony-edit', params:{hash: item.hash}}">
                                                        <TIcon name="edit" size="sm"
                                                            class="pt-1 cursor-pointer hover:scale-110 hover:text-green-700 dark:hover:bg-green-700 text-green-600">
                                                        </TIcon>
                                                    </router-link>
                                                </div>
                                                <div v-if="$guard.can(['testimony_delete'])" class="flex flex-col">
                                                    <TIcon @click="showModal('delete',item)" name="delete" size="sm"
                                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600">
                                                    </TIcon>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>

                    <!--list sm-->
                    <div  class="flex flex-col md:hidden w-full bg-white border border-gray-200 rounded-lg shadow p-2 dark:bg-gray-800 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700 gap-y-2">
                        <!--sample 1-->
                            <div 
                                class="flex flex-row"
                                v-for="item in testimonyList">
                                <!--details-->
                                
                                <div class="flex flex-col w-full">
                                    <router-link :to="{ name: 'ViewTestimony', params:{hash: item.hash} }">
                                    <div class="flex flex-row cursor-pointer w-full">
                                        <span class="font-sans font-bold text-xs">{{ item.title }}</span>
                                    </div>
                                    <div class="flex flex-row w-full">
                                        <span 
                                            class="line-clamp-2 text-gray-500 font-sans text-xs"
                                            v-html="item.body"
                                        >
                                        </span>
                                    </div>
                                    </router-link>
                                </div>
                                
                                <!--buttons-->
                                <div class="flex flex-row items-center">
                                    <div class="flex flex-col justify-center">
                                        <router-link :to="{ name: 'ViewTestimony', params:{hash: item.hash} }">
                                            <div class="flex flex-col justify-center">
                                                <TIcon name="visibility" size="sm"
                                                    class="cursor-pointer hover:scale-110 hover:text-gray-900 dark:hover:bg-gray-900 text-black">
                                                </TIcon>
                                            </div>
                                            
                                        </router-link>
                                    </div>
                                    <div v-if="$guard.can(['testimony_edit'])" class="flex flex-col justify-center">
                                        <router-link :to="{ name: 'testimony-edit', params:{hash: item.hash}}">
                                            <div class="flex flex-col justify-center items-center">
                                                <TIcon name="edit" size="sm"
                                                    class="cursor-pointer hover:scale-110 hover:text-green-700 dark:hover:bg-green-700 text-green-600">
                                                </TIcon>
                                            </div>
                                            
                                        </router-link>
                                    </div>
                                    <div v-if="$guard.can(['testimony_delete'])" class="flex flex-col">
                                        <TIcon @click="showModal('delete',item)" name="delete" size="sm"
                                            class="cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600">
                                        </TIcon>
                                    </div>

                                </div>
                            </div>
                        
                        

                    </div>
                </div>

                <div v-if="searchOptions.total == 0" class="bg-white relative overflow-x-auto p-8 rounded-lg bg-white border border-gray-200 text-center">
                    <span class="font-sans text-xl text-slate-700">({{ loadStatus }})</span>
                </div>
                <!--Pagination-->
                <div class="flex flex-col items-center pt-3">
                    <Pagination
                        v-model="searchOptions.page"
                        v-model:limit="searchOptions.limit"
                        :total="searchOptions.total"
                        v-model:offset="searchOptions.offset"
                        @update:totalPage="(count) => {pages=count}"
                        @paginate="getTestimonies(searchOptions.term)"
                        direction-nav
                        boundary-nav
                    />
                </div>  
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import ViewTestimony from './ViewTestimony.vue';
import DeleteTestimony from './DeleteTestimony.vue';
import _ from 'lodash'

export default defineComponent({
    name: 'AdminTestimonials',
    setup(){},
    components: {
        ViewTestimony,
        DeleteTestimony
    },
    mounted(){
        this.getTestimonies('')
    },
    data(){
        return{
            componentOptions: {
                showModal: false,
                selectedItem: null,
                mode:''
            },
            searchOptions:{
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 5,
                offset: 0,
            },
            testimonyList: null,
            loadStatus:''
        }
    },
    methods: {
        getTestimonies(term=''){
            this.loadStatus = 'Fetching Data'
            this.$api.get('/testimony/list',{
                params:{
                    term: term,
                    offset: this.searchOptions.offset,
                    limit: this.searchOptions.limit
                }
            })
            .then((response)=>{
                this.testimonyList = response.data.testimonies
                this.testimonyList.date = response.data.date
                this.searchOptions.total = response.data.total
                this.loadStatus = 'No Testimony'
            })
        },
        showModal(mode, item=null){
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            if(item != null){
                this.componentOptions.selectedItem = item
            }
        },
        closeModal(){
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''
        },
        refreshTable(){
            this.closeModal()
            this.getTestimonies('')
        },
        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.searchOptions.offset = 0
            this.getTestimonies(this.searchOptions.term)
        },500)
    }
})
</script>
