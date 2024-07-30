<template>
    <Page>
        <Modal :show="componentOptions.showModal" @close="hideModal">
            <RejectReport :report="componentOptions.selectedItem" v-if="componentOptions.mode == 'reject'" @cancel="hideModal" @done="refreshPage"></RejectReport>
            <ResolveReport :report="componentOptions.selectedItem" v-if="componentOptions.mode == 'resolve'" @cancel="hideModal" @done="refreshPage"></ResolveReport>
            <ViewReportImage :report="componentOptions.selectedItem" v-if="componentOptions.mode == 'show_image'"></ViewReportImage>
            <ViewReportDetails :report="componentOptions.selectedItem" v-if="componentOptions.mode  == 'show-details'"></ViewReportDetails>
        </Modal>
        <div class="flex flex-col md:py-4">
            <div class="flex-col px-1 md:px-5 lg:px-10 items-center justify-center gap-10 py-1 md:py-10">
                <!--Page Label-->
                <div class="flex flex-row items-center pr-2 text-slate-800 p-2 md:text-start  justify-between relative">
                    <div class="flex flex-row items-center">
                        <TIcon name="report" size="xl" class="pt-1"></TIcon>
                        <span class="font-sans text-2xl md:text-3xl lg:text-4xl xl:text-5xl text-slate-800 font-semibold">Reports Management</span>
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
                            <span class="font-sans text-xs xl:text-base" >Resolved</span>
                        </div>
                        <div @click="filterData('Rejected')" class="flex flex-col border items-center p-1 cursor-pointer shadow-md hover:bg-gray-200" :class="selected_type == 2 ? 'bg-gray-200' : 'bg-white'">
                            <span class="font-sans text-xs xl:text-base" >Rejected</span>
                        </div>
                    </div>
                </div>
                
                <!--Reports Table-->
                <div v-if="searchOptions.total != 0" class="hidden lg:flex  relative overflow-x-auto p-8 rounded-lg bg-white border border-gray-200">
                    <div class="flex flex-col w-full ">
                        <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                            <tbody>
                                <!-- sample 1 -->
                                <tr
                                    class="bg-white border-b last:border-b-0 dark:bg-gray-800 dark:border-gray-700"
                                    v-for="item in reportList" v-bind:key="item.id"
                                >
                                    <!--Report deatils-->
                                    <td class=" font-sans w-[10rem]">
                                        {{ item.status == 0 ? 'Pending' : item.status == 1 ? 'Resolved' : 'Rejected' }}
                                    </td>
                                    <td scope="row"
                                        class="py-4 font-sans text-gray-900 whitespace-nowrap dark:text-white"                                       
                                    >
                                        <tr class="font-bold">Ticket ID: {{item.id}} </tr> 
                                        <tr class="font-normal">{{item.created_at}}</tr>
                                    </td>

                                    <!--complainant-->
                                    <td class="flex flex-col items-start justify-start py-4 font-sans">
                                        <tr class="font-bold">Complainant Details</tr>
                                        <tr>
                                            {{item.first_name}}
                                            {{ item.middle_name }}
                                            {{ item.last_name }}
                                            {{ item.suffix }}
                                        </tr>
                                        <tr>{{item.contact_number}}</tr>
                                        <tr>{{item.email}}</tr>
                                    </td>

                                    <!--violator-->
                                    <td class=" py-4 font-sans w-[10rem]">
                                        <tr class="font-bold">Violator Details</tr>
                                        <tr>{{ item.violator_name }}</tr>
                                        <tr>
                                            {{item.violator_location}}
                                            {{ item.barangay}}         
                                        </tr>
                                        <tr class="line-clamp-1 ">{{item.landmark}}</tr>
                                    </td>

                                    <!--violation-->
                                    <td class=" py-4 px-12 w-[25rem] font-sans">
                                        <tr class="font-bold">Violation:</tr>
                                        <tr class="line-clamp-2 ">{{item.violation_description}}</tr>
                                        <tr>{{ item.violation_date }}</tr>
                                    </td>

                                    <!--actions-->     
                                    <td class="py-2 text-right">
                                        <TIcon name="visibility" size="sm"
                                        @click="showModal('show-details',item)"
                                            class="pt-1 cursor-pointer hover:scale-110 hover:text-green-700 dark:hover:bg-green-700 text-green-600">
                                        </TIcon>
                                    </td>
                                    <td v-if="$guard.can(['report_resolve']) && item.status == 0" class="py-2 text-right">    
                                        <TIcon name="done" size="sm"
                                            @click="showModal('resolve',item)"
                                            class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600">
                                        </TIcon>
                                    </td>                 
                                    <td v-if="$guard.can(['report_reject']) && item.status == 0" class="py-2 text-right">
                                        <TIcon name="close" size="sm"
                                            @click="showModal('reject',item)" 
                                            class="pt-1 cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600">
                                        </TIcon>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!--Report list sm-md-->
                <div v-if="searchOptions.total != 0" class="flex flex-col lg:hidden p-2">

                    <div v-for="item in reportList" v-bind:key="item.id">
                        <!--header-->
                        <div @click="openDetails(item.id)" class="flex flex-col w-full border  p-2 cursor-pointer bg-white hover:bg-gray-50 dark:hover:bg-gray-600">
                            <div class="flex flex-row">
                                <!--Details-->
                                <div class="flex flex-col md:flex-row w-full h-full ">
                                    <!--ticket id and date-->
                                    <div class="flex flex-col w-full">
                                        <!--Ticket ID-->
                                        <div class="flex flex-row">
                                            <div class="flex flex-col">
                                                <span><pre class="font-sans font-bold text-base">Ticket ID: </pre></span>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <span class="font-sans font-semibold text-base">{{item.id}}</span>
                                            </div>
                                        </div>
                                        <!--Violator-->
                                        <div class="flex flex-row w-full gap-1">
                                            <span class="font-sans text-sm font-semibold">
                                                Violator:
                                            </span>
                                            <span class="font-sans text-sm">
                                                {{ item.violator_name }}
                                            </span>
                                        </div>
                                        <!--Date Reported-->
                                        <div class="flex flex-row gap-1">
                                            <span class="font-sans text-sm font-semibold">Date Reported:</span>
                                            <span class="font-sans text-sm">{{item.created_at}}</span>
                                        </div>
                                        <!--Status-->
                                        <div class="flex flex-row gap-1">
                                            <span class="font-sans text-sm font-semibold">Status:</span>
                                            <span class="font-sans text-sm">{{ item.status == 0 ? 'Pending' : item.status == 1 ? 'Resolved' : 'Rejected' }}</span>
                                        </div>
                                       
                                    </div>
                                </div>
                                
                              

                                <!--Icon-->
                                <div class="flex flex-row items-center justify-center px-2 gap-2">
                                    <TIcon v-if="!componentOptions.targetOpen || selected!=item.id" name="expand_more" size="md"></TIcon>
                                    <TIcon v-if="componentOptions.targetOpen && selected==item.id" name="expand_less" size="md"></TIcon>
                                </div>
                            </div>

                            <!--Show Icon when screen sm"-->
                            <div class="flex flex-row md:hidden items-center justify-center px-2 gap-2">
                                <TIcon @click="showModal('show_image',item)" v-if="this.componentOptions.targetOpen && selected==item.id" name="image" size="sm" class="pt-1 cursor-pointer hover:scale-110 hover:text-green-700 dark:hover:bg-green-700 text-green-600"></TIcon>
                                <TIcon  @click="showModal('resolve',item)" v-if="this.componentOptions.targetOpen && selected==item.id && $guard.can(['report_resolve']) && item.status == 0" name="done" size="sm" class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600"></TIcon>
                                <TIcon @click="showModal('reject',item)"  v-if="this.componentOptions.targetOpen && selected==item.id && $guard.can(['report_reject']) && item.status == 0" name="close" size="sm" class="pt-1 cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600"></TIcon>
                            </div>
                        </div>
                        <!--content-->
                        <div v-if="componentOptions.targetOpen && selected==item.id" class="flex flex-col bg-gray-200 w-full h-full border border-slate-400 p-2">
                          
                            <!--Complainant Details-->
                            <div class="flex flex-col px-2 py-1">
                                <div class="flex flex-row font-sans font-bold text-md ">
                                    <label>Complainant Details</label>
                                </div>

                                <div class="flex flex-row font-sans md:pl-2">
                                    <div class="flex flex-col">
                                        <label><pre class="font-sans font-semibold text-sm">Full Name: </pre></label>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <span class="font-sans text-xs md:text-sm">
                                            {{ item.first_name }}
                                            {{ item.middle_name }}
                                            {{ item.last_name }}
                                            {{ item.suffix }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-row font-sans md:pl-2">
                                    <div class="flex flex-col">
                                        <label><pre class="font-sans font-semibold text-sm">Contact Number: </pre></label>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <span class="font-sans text-xs md:text-sm">{{item.contact_number}}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row font-sans md:pl-2">
                                    <div class="flex flex-col">
                                        <label><pre class="font-sans font-semibold text-sm ">Email Address: </pre></label>
                                    </div>
                                    <div class="flex flex-col justify-center pl-2 md:pl-0">
                                        <span class="font-sans text-xs md:text-sm">{{item.email}}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!--Violator Details-->
                            <div class="flex flex-col px-2 py-1">
                                <div class="flex flex-row font-sans font-bold text-md ">
                                    <label>Violator Details</label>
                                </div>
                                <div class="flex flex-row font-sans md:pl-2">
                                    <div class="flex flex-col">
                                        <label><pre class="font-sans font-semibold text-sm ">Full Name: </pre></label>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <span class="font-sans text-xs md:text-sm">{{item.violator_name ?? '(Not Provided)'}}</span>
                                    </div>
                                </div>
                                <div class="flex flex-row font-sans md:pl-2">
                                    <div class="flex flex-col">
                                        <label><pre class="font-sans font-semibold text-sm ">Address: </pre></label>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <span class="font-sans text-xs md:text-sm">{{item.violator_location}} {{ item.barangay }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-row font-sans md:pl-2">
                                    <div class="flex flex-col">
                                        <label><pre class="font-sans font-semibold text-sm ">Landmark: </pre></label>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <span class="font-sans text-xs md:text-sm">{{item.landmark}}</span>
                                    </div>
                                </div>
                            </div>
                              <!--Date of Violation-->
                              <div class="flex flex-col md:flex-row px-2 py-2">
                                <div class="flex flex-col">
                                    <label><pre class="font-sans font-bold text-md">Date of Violation: </pre></label>
                                </div>
                                <div class="flex flex-col justify-center pl-2 md:pl-0">
                                    <span class="font-sans text-xs md:text-sm">{{item.violation_date}}</span>
                                </div>
                            </div>

                            <!--Violation-->
                            <div class="flex flex-col px-2 ">
                                <div class="flex flex-col">
                                    <label><pre class="font-sans font-bold text-md">Violation Description: </pre></label>
                                </div>
                                <div class="flex flex-col justify-center pl-2">
                                    <span class="font-sans text-xs md:text-sm">{{item.violation_description}}</span>
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
                        @paginate="getReports(searchOptions.term)"
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
import RejectReport from './rejectReport.vue';
import ResolveReport from './resolvedReport.vue';
import ViewReportImage from './viewReportImage.vue';
import ViewReportDetails from './viewReportDetails.vue';
import _ from 'lodash'

export default defineComponent({
    name: 'ReportManagement',
    setup(){},
    components:{
        RejectReport,
        ResolveReport,
        ViewReportImage,
        ViewReportDetails
    },
    mounted(){
        this.getReports('');
    },
    data(){
        return{
            componentOptions: {
                targetOpen: false,
                showModal: false,
                selectedItem: null,
                mode:'',
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
            reportList: null,
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
        getReports(term = ''){
            this.loadStatus = 'Fetching Data'

            this.$api.get('/report/list',{
                params:{
                    term: term,
                    offset: this.searchOptions.offset,
                    limit: this.searchOptions.limit
                }
            })
            .then((response)=>{
                this.reportList = response.data.report
                this.searchOptions.total = response.data.total
                this.loadStatus = 'No Report'

               
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
            this.hideModal();
            this.getReports('');
        },
        showActions(){
            this.componentOptions.openOptions = !this.componentOptions.openOptions
        },
        filterData(mode,term = ''){
            this.loadStatus = 'Fetching Data'
            this.searchOptions.offset = 0
            if(mode == 'All'){
                this.selected_type = null
                this.getReports('')
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
            if(mode != 'All'){
                this.$api.get('/report/filter',{
                    params:{
                        term: term,
                        offset: this.searchOptions.offset,
                        limit: this.searchOptions.limit,
                        status: this.selected_type
                    }
                })
                .then((response)=>{
                    this.reportList = response.data.data
                    this.searchOptions.total = response.data.total
                    this.loadStatus = response.data.message
                })
            }
            
        },
        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.searchOptions.offset = 0
            this.getReports(this.searchOptions.term)
        },500)
    }
})
</script>
