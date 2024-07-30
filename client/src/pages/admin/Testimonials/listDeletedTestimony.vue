<template>
    <Page>     
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmRestore v-if="componentOptions.showModal" @cancel="closeModal" @proceed="restoreTestimony"/>
        </Modal>
        <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
        <div class="flex flex-col md:py-4">
            <div class="flex-col px-2 md:px-5 lg:px-10 items-center justify-center gap-10 py-1 md:py-6">
                <!--header-->
                <div class="flex flex-row md:py-5 justify-between">
                    <!--Label-->
                    <div class="flex flex-row text-slate-800 pt-2 items-center">
                        <TIcon name="text_snippet" size="xl" class="text-slate-800 md:px-2"></TIcon>
                        <span
                            class="text-xl md:text-3xl lg:text-5xl font-sans text-slate-800 font-semibold">Deleted Testimonials
                        </span>
                    </div>
                </div>

                <!--search-->
                <div class="flex flex-col w-full md:w-[25rem] py-3">
                    <input 
                        @input="debounceInput" 
                        type="text" 
                        id="default-input"
                        class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 shadow-sm"
                        placeholder="Search Testimony">
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
                                        class="bg-white border-b last:border-b-0 cursor-pointer dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                        v-for="item in deletedList"
                                       >
                                        <th scope="row"
                                            class="px-6 py-4 font-medium font-sans text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ item.title }} 
                                        </th>

                                        
                                            <td class="px-6 py-5 font-sans">
                                                <router-link :to="{ name: 'ViewDeletedTestimony', params:{hash: item.hash} }">
                                                    <div 
                                                        class="font-sans line-clamp-2" 
                                                        v-html="item.body"
                                                    >  
                                                    </div>
                                                </router-link>
                                                
                                            </td>
                                            <td v-if="$guard.can(['testimony_restore'])">
                                                <TIcon @click="showModal(item.hash)" name="restore_from_trash" size="md" class="cursor-pointer text-green-500 hover:text-green-600 hover:scale-110"></TIcon>
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
                                v-for="item in deletedList"
                            >
                                <!--details-->
                                
                                <div class="flex flex-col w-full">
                                    <router-link :to="{ name: 'ViewTestimony', params:{hash: item.hash} }">
                                    <div class="flex flex-row cursor-pointer w-full">
                                        <span class="font-sans font-bold text-sm">
                                            {{ item.title }}
                                        </span>
                                    </div>
                                    <div class="flex flex-row w-full">
                                        <span 
                                            class="line-clamp-2 font-sans text-xs text-gray-500"
                                            v-html="item.body"
                                        >
                                        </span>
                                    </div>
                                     </router-link>
                                </div>
                                <div v-if="$guard.can(['testimony_restore'])" class="flex flex-col justify-center">
                                    <TIcon name="restore_from_trash" size="md" class="cursor-pointer text-green-500 hover:text-green-600 hover:scale-110"></TIcon>
                                </div>
                            </div>
                        
                        

                    </div>
                </div>

                <div v-if="searchOptions.total == 0" class="bg-white relative overflow-x-auto p-8 rounded-lg border border-gray-200 text-center">
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
                        @paginate="getDeletedTestimonies(searchOptions.term)"
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
import _ from 'lodash'
import  ConfirmRestore from './ConfirmRestore.vue';
import { notify } from 'notiwind';

export default defineComponent({
    name: 'ListDeletedTestimony',
    setup(){},
    components:{
        ConfirmRestore
    },
    mounted(){
        this.getDeletedTestimonies('')
    },
    data(){
        return{
            componentOptions: {
                showModal: false,
                selectedItem: null,
                alertMsg: '',
                activeStatus: false
            },
            searchOptions:{
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 5,
                offset: 0,
            },
            deletedList: null,
            loadStatus:''
        }
    },
    methods:{
        getDeletedTestimonies(term = ''){
            this.loadStatus = 'Fetching Data'
            this.$api.get('testimony/deleted-list',{
                params:{
                    term:term,
                    offset:this.searchOptions.offset,
                    limit:this.searchOptions.limit
                }
            })
            .then((response)=>{
                this.deletedList = response.data.testimonies
                this.searchOptions.total = response.data.total
                this.loadStatus  = 'No Deleted Testimony'
                if(this.componentOptions.activeStatus){
                    this.componentOptions.activeStatus = false
                    notify({group:"main", title:"Restored Testimony Successfully!", type:"positive"},'3000')
                }
            })
        },
        restoreTestimony(){
            this.closeModal()
            this.componentOptions.alertMsg = 'Restoring testimony'
            this.componentOptions.activeStatus = true
            this.$api.get(`testimony-restore/${this.componentOptions.selectedItem}`)
            .then((response)=>{
                this.getDeletedTestimonies('')
            })
        },
        showModal(item=null){
            this.componentOptions.showModal = true
            if(item != null){
                this.componentOptions.selectedItem = item
            }
        },
        closeModal(){
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''
        },
        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.getDeletedTestimonies(this.searchOptions.term)
        },500)
    }
})
</script>
