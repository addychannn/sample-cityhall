<template>
    <Page class="p-3 w-full">
        <!--Modal-->
        <Modal :show="componentOptions.showModal" @close="hideModal">
            <RejectQuitter :quitter="componentOptions.selectedItem" v-if="componentOptions.mode == 'reject'" @cancel="hideModal" @done="refreshPage"></RejectQuitter>
            <ApproveQuitter :quitter="componentOptions.selectedItem" v-if="componentOptions.mode == 'approve'" @cancel="hideModal" @done="refreshPage"></ApproveQuitter>
            <ViewQuitterDetails :quitter="componentOptions.selectedItem"  v-if="componentOptions.mode == 'show-details'"></ViewQuitterDetails>
        </Modal>
        <div class="flex flex-col md:py-4">
            <div class="flex-col px-1 md:px-5 lg:px-10 items-center justify-center gap-10 py-1 md:py-10">
                <!--Page Label-->
                <div class="flex flex-row items-center pr-2 text-slate-800 p-2 md:text-start justify-between relative">
                    <div class="flex flex-row">
                        <TIcon name="smoke_free" size="xl" class="pt-1"></TIcon>
                        <span class="font-sans text-2xl md:text-3xl lg:text-4xl xl:text-5xl text-slate-800 font-semibold">Quitters Management</span>
                    </div>
                </div>
                <!--Search and Filter-->
                <div class="flex flex-col xl:flex-row xl:gap-2 pb-3 px-2">
                    <!--Search-->
                    <div class="flex flex-col justify-center w-full md:w-[25rem]">
                        <input 
                            @input="debounceInput"
                            type="text" 
                            id="default-input"
                            class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Ticket ID and Violator name here">
                    </div>                   
                    <!--Filter-->
                    <div class="flex flex-row items-center gap-1">
                        <span class="font-sans text-slate-600 text-xs xl:text-base">Sort By:</span>
                        <div @click="filterData('All')" class="flex flex-col border items-center p-1 cursor-pointer shadow-md hover:bg-gray-200" :class="selected_type == null ? 'bg-gray-200' : 'bg-white'">
                            <span class="font-sans text-xs xl:text-base">All</span>
                        </div>
                        <div @click="filterData('Pending')" class="flex flex-col border items-center p-1 cursor-pointer shadow-md hover:bg-gray-200" :class="selected_type == 0 ? 'bg-gray-200' : 'bg-white'">
                            <span class="font-sans text-xs xl:text-base" >Pending</span>
                        </div>
                        <div @click="filterData('Resolved')" class="flex flex-col border items-center p-1 cursor-pointer shadow-md hover:bg-gray-200" :class="selected_type == 1 ? 'bg-gray-200' : 'bg-white'">
                            <span class="font-sans text-xs xl:text-base" >Approved</span>
                        </div>
                        <div @click="filterData('Rejected')" class="flex flex-col border items-center p-1 cursor-pointer shadow-md hover:bg-gray-200" :class="selected_type == 2 ? 'bg-gray-200' : 'bg-white'">
                            <span class="font-sans text-xs xl:text-base" >Rejected</span>
                        </div>
                        <div @click="filterData('Rescheduled')" class="flex flex-col border items-center p-1 cursor-pointer shadow-md hover:bg-gray-200" :class="selected_type == 3 ? 'bg-gray-200' : 'bg-white'">
                            <span class="font-sans text-xs xl:text-base" >Rescheduled</span>
                        </div>
                    </div>
                </div>
                
                <!--Quitters Table-->
                <div v-if="searchOptions.total != 0" class="hidden lg:flex relative overflow-x-auto shadow-md rounded-lg p-8 bg-white flex-col bg-white border border-gray-200">
                    <div class="flex flex-row w-full px-8">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr
                                class="bg-white border-b last:border-b-0 dark:bg-gray-800 dark:border-gray-700"
                                v-for="item in quitterList" v-bind:key="item.hash">
                                <!--Quitter deatils-->
                                <td class="px-6 py-4 font-sans">
                                    {{ item.status == 0 ? 'Pending' : item.status == 1 ? 'Approved' : item.status == 2 ? 'Rejected' : 'Rescheduled' }}
                                </td>
                                <td scope="row"
                                    class="px-6 py-4 font-sans text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <tr class="font-bold">Appointment ID: {{item.hash}} </tr>
                                    <tr class="font-light">
                                        {{item.first_name}} 
                                        {{item.middle_name}} 
                                        {{ item.last_name }} 
                                        {{ item.suffix }}
                                    </tr>
                                    <tr class="font-light">{{item.contact_number}}</tr>
                                    <tr class="font-light">{{item.email}}</tr>
                                </td>

                                <!--reason-->
                                <td class="px-6 py-4 font-sans">
                                    <tr class="font-bold">
                                        Reason:
                                    </tr>
                                    
                                    <tr class="font-light line-clamp-1">
                                        {{item.additional_notes ?? '(Reason not provided)'}}
                                    </tr>
 
                                </td>

                                <!--barangay-->
                                <td class="px-6 py-4 font-sans">
                                    {{ item.location }}
                                    {{item.barangay}}
                                </td>

                                <!--date-->
                                <td class="px-6 py-4 font-sans">
                                    {{ item.preferred_date }}
                                </td>
                                <!--actions-->                      
                                <td>
                                    <TIcon name="visibility" size="sm"
                                        @click="showModal('show-details',item)"
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-green-700 dark:hover:bg-green-700 text-green-600"
                                    >
                                    </TIcon>
                                </td>
                                <td v-if="$guard.can(['quitter_accept']) && item.status == 0" class="text-center">
                                    <TIcon name="done" size="sm"
                                        @click="showModal('approve',item)"
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600">
                                    </TIcon>
                                </td>
                                <td v-if="($guard.can(['quitter_reject']) || $guard.can(['quitter_reschedule'])) && item.status == 0" class="text-center">
                                    <TIcon name="close" size="sm"
                                        @click="showModal('reject',item)" 
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600">
                                    </TIcon>
                                </td>
                                <td v-if="$guard.can(['quitter_reschedule']) && item.status == 3 && item.reschedule_count < 5">
                                    <TIcon name="edit_calendar" size="sm"
                                        @click="showModal('reject',item)" 
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600">
                                    </TIcon>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                </div>

                <!--Quitter List: sm-md-->
                <div v-if="searchOptions.total != 0" class="flex flex-col lg:hidden p-2 ">
                    <div v-for="item in quitterList" v-bind:key="item.hash">
                        <!--header-->
                        <div 
                            @click="openDetails(item.hash)" 
                            class="flex flex-row w-full border shadow-lg p-2 cursor-pointer bg-white hover:bg-gray-50 dark:hover:bg-gray-600"
                            
                        >
                            <!--Details-->
                            <div class="flex flex-col md:flex-row w-full h-full">
                                <!--ticket id and date-->
                                <div class="flex flex-col w-full">
                                    <!--Ticket ID-->
                                    <div class="flex flex-row">
                                        <div class="flex flex-col">
                                            <span><pre class="font-sans font-bold text-sm md:text-base">Appointment ID: </pre></span>
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <span class="font-sans font-semibold text-sm md:text-base">{{item.hash}}</span>
                                        </div>
                                    </div>
                                     <!--Quitter Name-->
                                     <div class="flex flex-row w-full gap-1">
                                        <span class="font-sans text-xs md:text-sm font-semibold ">
                                            Name:
                                        </span>
                                        <span class="font-sans text-xs md:text-sm">
                                           
                                            {{item.first_name}}
                                            {{item.middle_name}}
                                            {{ item.last_name }}
                                            {{ item.suffix }}
                                        </span>
                                    </div>
                                    <!--Appointment Date-->
                                    <div class="flex flex-row gap-1">
                                        <span class="font-sans text-xs md:text-sm font-semibold ">
                                           Date:
                                        </span>
                                        <span class="font-sans text-xs md:text-sm">{{item.preferred_date}}</span>
                                    </div>

                                    <!--Appointment Date-->
                                    <div class="flex flex-row gap-1">
                                        <span class="font-sans text-xs md:text-sm font-semibold ">
                                           Status:
                                        </span>
                                        <span class="font-sans text-xs md:text-sm">{{ item.status == 0 ? 'Pending' : item.status == 1 ? 'Approved' : item.status == 2 ? 'Rejected' : 'Rescheduled' }}</span>
                                    </div>
                                   
                                </div>
                            </div>

                            <!--Icon-->
                            <div class="flex flex-row items-center justify-center px-2 gap-1">
                                <TIcon @click="showModal('approve',item)" v-if="this.componentOptions.targetOpen && selected==item.hash && $guard.can(['quitter_accept']) && item.status == 0" name="done" size="sm" class="pl-2 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600"></TIcon>
                                <TIcon @click="showModal('reject',item)" v-if="this.componentOptions.targetOpen && selected==item.hash && ($guard.can(['quitter_reject']) || $guard.can(['quitter_reschedule'])) && item.status == 0" name="close" size="sm" class="cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600"></TIcon>
                                <TIcon @click="showModal('reject',item)" v-if="this.componentOptions.targetOpen && selected==item.hash && $guard.can(['quitter_reschedule']) && item.status == 3 && item.reschedule_count < 5" name="edit_calendar" size="sm" class="cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600"></TIcon>
                                <TIcon v-if="!this.componentOptions.targetOpen || selected!=item.hash" name="expand_more" size="md"></TIcon>
                                <TIcon v-if="this.componentOptions.targetOpen && selected==item.hash" name="expand_less" size="md"></TIcon>
                            </div>
                        </div>
                       
                        <!--content-->
                        <div v-if="componentOptions.targetOpen && selected==item.hash" class="flex flex-col bg-gray-200 w-full h-full border border-slate-400 p-2 gap-1">
                            <span class="font-sans text-sm font-semibold ">
                                Other Details:
                            </span>
                            <!--Contact Details-->
                            <div class="flex flex-col justify-center gap-1">
                                <div class="flex flex-row gap-1">
                                    <span class="font-sans text-xs md:text-sm font-semibold ">
                                       Contact Number:
                                    </span>
                                    <span class="font-sans text-xs md:text-sm">
                                  {{item.contact_number}}
                                </span>
                                </div>
                                <div class="flex flex-row gap-1">
                                    <span class="font-sans text-xs md:text-sm font-semibold ">
                                        Email:
                                    </span>
                                    <span class="font-sans text-xs md:text-sm">
                                    {{ item.email ?? '(Not Provided)'}}
                                </span>
                                </div>
                            </div>

                            <!--Barangay-->
                            <div class="flex flex-row md:flex-row gap-1 ">
                                <!-- <div class="flex flex-col">
                                    <label><pre class="font-sans font-bold text-sm md:text-lg">Barangay: </pre></label>
                                </div> -->
                                <span class="font-sans text-xs md:text-sm font-semibold ">
                                    Barangay:
                                </span>
                                <div class="flex flex-col justify-center pl-2 md:pl-0">
                                    <span class="font-sans text-xs md:text-sm">{{item.barangay}}</span>
                                </div>
                            </div>

                            <!--Reason-->
                            <div class="flex flex-row gap-1">
                                <!-- <div class="flex flex-col">
                                    <label><pre class="font-sans font-bold text-sm md:text-lg">Additional Notes: </pre></label>
                                </div> -->
                                <span class="font-sans text-xs md:text-sm font-semibold ">
                                   Reason:
                                </span>
                                <div class="flex flex-col justify-center">
                                    <span class="font-sans text-xs md:text-sm">{{ item.additional_notes ?? '(Reason not provided)'}}</span>
                                </div>
                            </div>
                            
                            <!--Remarks-->
                            <div class="flex flex-row gap-1">
                                <!-- <div class="flex flex-col">
                                    <label><pre class="font-sans font-bold text-sm md:text-lg">Additional Notes: </pre></label>
                                </div> -->
                                <span class="font-sans text-xs md:text-sm font-semibold ">
                                   Remarks:
                                </span>
                                <div class="flex flex-col justify-center">
                                    <span class="font-sans text-xs md:text-sm">{{ item.admin_remarks ?? '(No Remarks)'}}</span>
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
                        @paginate="getQuitters(searchOptions.term)"
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
import RejectQuitter from './RejectQuitter.vue';
import ApproveQuitter from './ApproveQuitter.vue';
import ViewQuitterDetails from './ViewQuitterDetails.vue';

export default defineComponent({
    name: 'QuitterManagement',
    setup(){},
    components:{
        RejectQuitter,
        ApproveQuitter,
        ViewQuitterDetails
    },
    mounted(){
        this.getQuitters('');
    },
    data(){
        return{
            componentOptions: {
                targetOpen: false,
                showModal: false,
                selectedItem: null,
                mode: '',
                openOptions: false
            },
            searchOptions:{
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 5,
                offset: 0,
            },
            quitterList: null,
            selected: null,
            loadStatus: '',
            selected_type: null
        }
    },
    methods:{
        openDetails(item){
            this.selected = item
            this.componentOptions.targetOpen = !this.componentOptions.targetOpen
        },
        getQuitters(term = ''){
            this.loadStatus = 'Fetching Data'
            this.$api.get('/quitter/list',{
                params:{
                    term: term,
                    offset: this.searchOptions.offset,
                    limit: this.searchOptions.limit,
                }
            })
            .then((response)=>{
                this.loadStatus = 'No Quitter Appointment'
                this.quitterList = response.data.quitter
                this.searchOptions.total = response.data.total
            })
        },
        showModal(mode, item=null){
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            if(item !=null){
                this.componentOptions.selectedItem = item
            }
        },
        hideModal(){
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''
        },
        refreshPage(){
            this.getQuitters('');
            this.hideModal();
        },
        showActions(){
            this.componentOptions.openOptions = !this.componentOptions.openOptions
        },
        filterData(mode,term = ''){
            this.loadStatus = 'Fetching Data'
            this.searchOptions.offset = 0
            if(mode == 'All'){
                this.selected_type = null
                this.getQuitters('')
            }
            if(mode == 'Pending'){
                this.selected_type = 0
            }
            if(mode == 'Resolved'){
                this.selected_type = 1
            }
            if(mode == 'Rejected'){
                this.selected_type = 2
            }
            if(mode == 'Rescheduled'){
                this.selected_type = 3
            }
            if(mode != 'All'){
                this.$api.get('/quitter/filter',{
                    params:{
                        term: term,
                        offset: this.searchOptions.offset,
                        limit: this.searchOptions.limit,
                        status: this.selected_type
                    }
                })
                .then((response)=>{
                    this.quitterList = response.data.quitter
                    this.searchOptions.total = response.data.total
                    this.loadStatus = response.data.message
                })
            }
            
        },
        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.searchOptions.offset = 0
            this.getQuitters(this.searchOptions.term)
        },500),

    }
})
</script>
