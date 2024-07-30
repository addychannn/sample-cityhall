<template>
    <Page >
        <div class="hidden lg:flex md:hidden">
            <!-- for lg screen -->
            <div class="flex flex-col p-5 gap-3  w-full">
                <div class="flex flex-row items-start justify-between ">

                    <div class="flex flex-col  pr-2 text-slate-800 p-2 gap-5">
                        <div class="flex flex-row">
                            <TIcon name="developer_board" size="xl"></TIcon>
                            <span class="font-sans text-5xl">Logs</span>

                        </div>

                        <div class="flex flex-row w-full md:w-[25rem]">
                            <input @input="debounceInput" type="text"  class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search here">
                        </div>

                    </div>

                   


                </div>
                <div class="border-b-2 border-slate-800 grid grid-cols-6 items-center justify-center p-3 gap-5 ">
                    <span class="font-sans text-center font-semibold">Actor</span>
                    <span class="font-sans text-center font-semibold">Action</span>
                    <span class="font-sans text-center font-semibold">Module</span>
                    <span class="font-sans text-center font-semibold">Type</span>
                    <span class="font-sans text-center font-semibold">Date/Time</span>
                </div>

                <template v-if="searchOptions.total != 0">
                    <div v-for="item in logs">
                        <div class="grid grid-cols-6  justify-between border-t gap-5 p-3 hover:bg-slate-200 duration-700 rounded-xl">
                            <span class="font-sans text-center font-light uppercase">{{ item.actor }}</span>
                            <span class="font-sans text-center font-light">{{ item.action }}</span>
                            <span class="font-sans text-center font-light">{{ item.module }}</span>
                            <span class="font-sans text-center font-light">{{ item.type =="Unknown"? "Normal":item.type }}</span>
                            <span class="font-sans text-center font-light">{{ item.date }}</span>
                            <TIcon @click="showMore(item.id)" name="expand_more" size="lg" class="text-end cursor-pointer"></TIcon>
                        </div>
                        <transition>
                            <div v-if="componentOptions.showMore && item.id==componentOptions.logID" class="flex flex-col border-b-2 p-3 justify-center gap-3 bg-slate-300 rounded-2xl shadow-md w-full">
                                <div class="flex flex-row justify-center items-center gap-5">
                                    <span class="w-1/2 font-sans text-start">Level of Action:</span>
                                    <span class="w-1/2 font-sans text-start">{{ item.level }}</span>
                                </div>
                            </div>
                        </transition>
                    
                    </div>
                </template>
                
                <template v-else>
                    <div class="flex flex-col items-center">
                        <span class="font-sans text-lg text-slate-600">({{ loadMessage }})</span>
                    </div>
                </template>
            
            </div>
        </div>

        <div class="hidden md:flex lg:hidden">
             <!-- for md screen -->
            <div class="flex flex-col p-5  gap-3 w-full">
                <div class="flex flex-row items-center justify-between ">

                    <div class="flex flex-col  pr-2 text-slate-800 p-2 gap-5">
                        <div class="flex flex-row">
                            <TIcon name="developer_board" size="xl"></TIcon>
                            <span class="font-sans text-5xl">Logs</span>

                        </div>

                        <div class="flex flex-row">
                            <input type="text" class="px-3 py-3 text-lg font-sans rounded-lg" placeholder="Search Value Here">
                        </div>

                    </div>

                   


                </div>
               
                <div class="border-b-2 border-slate-800 grid grid-cols-4 justify-between p-3 gap-5">
                    <span class="font-sans text-center font-semibold">Actor</span>
                    <span class="font-sans text-center font-semibold">Action</span>
                    <span class="font-sans text-center font-semibold">Date/Time</span>
                </div>

                <template v-if="searchOptions.total != 0">
                    <div v-for="item in logs">
                        <div class="grid grid-cols-4  justify-between border-t gap-5 p-3 hover:bg-slate-200 duration-700 rounded-xl">
                            <span class="font-sans text-center font-light uppercase">{{ item.actor }}</span>
                            <span class="font-sans text-center font-light">{{ item.action }}</span>
                            <span class="font-sans text-center font-light">{{ item.created_at }}</span>
                            <TIcon @click="showMore(item.id)" name="expand_more" size="lg" class="text-end cursor-pointer"></TIcon>
                        </div>
                        <transition>
                            <div v-if="componentOptions.showMore && item.id==componentOptions.logID" class="flex flex-col border-b-2 p-3 justify-center gap-3 bg-slate-300 rounded-2xl shadow-md">
                                <div class="flex flex-row justify-center items-center gap-5">
                                    <span class="w-1/2 font-sans text-start">Level of Action:</span>
                                    <span class="w-full font-sans text-start">{{ item.level }}</span>
                                </div>
                    
                                <div class="flex flex-row justify-center items-center gap-5">
                                    <span class="w-1/2 font-sans text-start">Action Type:</span>
                                    <span class="w-full font-sans text-start">{{ item.type }}</span>
                                </div>
                                <div class="flex flex-row justify-center items-center gap-5">
                                    <span class="w-1/2 font-sans text-start">Action Module:</span>
                                    <span class="w-full font-sans text-start">{{ item.module }}</span>
                                </div>
                            </div>
                        </transition>
                    
                    </div>
                </template>
                <template v-else>
                    <div class="flex flex-col items-center">
                        <span class="font-sans text-lg text-slate-600">({{ loadMessage }})</span>
                    </div>
                </template>
                
            
            </div>
        </div>

        
        <div class="flex md:hidden lg:hidden">
            <!-- for sm screen -->
            <div class="flex flex-col gap-2 p-2 w-full">
                <div class="border-b-2 border-slate-800">
                    <span class="font-sans text-5xl font-bold ml-5">Logs</span>
                </div>
                <div class="flex flex-col">
                            <input type="text" class="px-3 py-3 text-lg font-sans rounded-lg" placeholder="Search Value Here">
                        </div>
                <div class="border-b-2 border-slate-800 grid grid-cols-2 justify-between p-1 ">
                    <span class="font-sans text-center font-semibold">Actor</span>
                    <span class="font-sans text-end font-semibold">Details</span>
                </div>

                <template v-if="searchOptions.total != 0">
                    <div v-for="item in logs">
                        <div class="grid grid-cols-2  justify-between border-t gap-5 p-3 hover:bg-slate-200 duration-700 rounded-xl">
                            <span class="font-sans text-center font-light uppercase">{{ item.actor }}</span>
                            <TIcon @click="showMore(item.id)" name="expand_more" size="lg" class="text-end"></TIcon>
                        </div>
                        <transition>
                            <div v-if="componentOptions.showMore && item.id==componentOptions.logID" class="flex flex-col justify-start p-2 bg-slate-300 rounded-2xl shadow-md gap-3">
                                <div class="flex flex-col justify-start items-start ">
                                    <span class="font-sans  text-start text-lg uppercase">Level of Action:</span>
                                    <span class="font-sans font-light text-start text-md uppercase">>>{{ item.level }}</span>
                                </div>
                                <div class="flex flex-col justify-start items-start ">
                                    <span class=" font-sans text-start text-lg uppercase">Actor:</span>
                                    <span class=" font-sans font-light text-start text-md">>> {{ item.actor }}</span>
                                </div>
                                <div class="flex flex-col justify-start items-start ">
                                    <span class=" font-sans text-start text-lg uppercase">Action Type:</span>
                                    <span class=" font-sans font-light text-start text-md">>> {{ item.type }}</span>
                                </div>
                                <div class="flex flex-col justify-start items-start ">
                                    <span class=" font-sans text-start text-lg uppercase">Action Module:</span>
                                    <span class="font-sans font-light text-start text-md">>> {{ item.module }}</span>
                                </div>
                                <div class="flex flex-col justify-start items-start ">
                                    <span class=" font-sans text-start text-lg uppercase">Date / Time:</span>
                                    <span class="font-sans font-light text-start text-md">>> {{ item.created_at }}</span>
                                </div>
                            </div>
                        </transition>
                    
                    </div>
                </template>
                <template v-else>
                    <div class="flex flex-col items-center">
                        <span class="font-sans text-lg text-slate-600">({{ loadMessage }})</span>
                    </div>
                </template>
                
            
            </div>
        </div>

        <div class="flex flex-col items-center pt-3">
            <Pagination v-model="searchOptions.page" v-model:limit="searchOptions.limit"
                :total="searchOptions.total" v-model:offset="searchOptions.offset"
                @update:totalPage="(count) => { pages = count }" @paginate="getLogs(searchOptions.term)"
                direction-nav boundary-nav />
        </div>

       



    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import _ from 'lodash';


export default defineComponent({
    name: 'Logs',
    setup(){},
    mounted(){
        this.getLogs('')
    },
    data(){
        return{
            componentOptions: {
                showMore: false,
                logID:null,
            },

            logs:null,

            searchOptions: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 5,
                offset: 0,
            },
            loadMessage: ''
        }
    },
    methods: {
        showMore(id){
            this.componentOptions.showMore = !this.componentOptions.showMore
            this.componentOptions.logID=id

        },
        // closeModal(){
        //     this.componentOptions.showModal = false
        // },
        getLogs(term=''){
            this.loadMessage = 'Fetching Data'
            let year=new Date().getFullYear()
            let month=new Date().getMonth()+1
            let date=new Date().getDate()
            this.$api.get(`/logsy/${year}/${month}/${date}`,{
                params:{
                    term:term,
                    limit:this.searchOptions.limit,
                    offset:this.searchOptions.offset,
                }
            })
            .then((response)=>{
                this.logs=response.data.data
                this.searchOptions.total=response.data.count
                this.loadMessage = 'No Data'
            })
        },

        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.searchOptions.offset = 0
            this.getLogs(this.searchOptions.term)
        },500)
    }
})
</script>

<style>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.7s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
