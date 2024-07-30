
<template>
    <Page>
        <!-- content -->
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ViewMessages></ViewMessages>
        </Modal>

        <div class="flex flex-col md:px-4">
            <div class="block items-center justify-center rounded-3xl py-3">
                <!--Header-->
                <div class="flex flex-row items-center md:py-7 lg:px-5">
                    <TIcon name="import_contacts" size="xl" class="text-slate-800 pl-2 md:px-2"></TIcon>
                    <span class="text-3xl lg:text-5xl font-sans text-slate-800 font-semibold">Dashboard</span>
                </div>

                <!--Charts and Message-->
                <div class="flex flex-col py-2 md:px-1 lg:px-5 gap-y-7 ">

                    <!-- charts lg -->
                    <div class="flex flex-col">
                        <div class="flex flex-col md:flex-col lg:flex-row justify-center items-center gap-2" :class="$guard.can(['message_list']) ? 'border-b-2 border-slate-900' : ''">
                            <div class="chart-container w-full h-72" ref="quitterChart"></div>
                            <div class="chart-container w-full h-72" ref="reportChart"></div>
                        </div>
                    </div>
                  

                    <!--message-->
                    <div v-if="$guard.can(['message_list'])" class="flex flex-row items-start justify-start">
                        <TIcon name="message" size="xl" class="text-slate px-2"></TIcon>
                        <span class="text-3xl font-sans text-center text-slate">MESSAGES</span>
                    </div>

                    <div v-if="messagesCount != 0" class="flex flex-col w-full px-5 gap-3 justify-start items-center lg:flex lg:flex-row md:flex md:flex-col h-1/4 ">
                        <div v-for="message in messageList" @click="goToViewMessage(message.hash)" class="flex flex-col w-[18rem] h-36 border border-slate-900 mx-3 p-2 rounded-2xl shadow-lg hover:bg-green-300 hover:cursor-pointer transition-all duration-500">
                                    <span class="truncate font-sans text-center text-xl uppercase">{{ message.firstname }} {{ message.lastname }}</span>
                                    <span  class="truncate font-sans text-md font-light ml-3">{{ message.email }}</span>
                                    <span class="truncate font-sans text-md font-light ml-3">{{ message.contact }}</span>
                                    <span class="truncate font-sans text-md font-light ml-5">{{ message.message }}</span>
                        </div>

                    </div>
                    <div v-if="messagesCount == 0 && $guard.can(['message_list'])" class="flex flex-col w-full px-5 gap-3 justify-center items-center lg:flex lg:flex-row md:flex md:flex-col  ">
                        <span class="font-sans text-slate-600 text-xl">(No Received Messages)</span>
                    </div>
                </div>


                <!--Message button-->
                <div v-if="$guard.can(['message_list'])" class="flex flex-col  items-end justify-end ">
                    <div class="flex flex-col items-end justify-end p-2 w-[15rem]">
                        <router-link :to="{ name: 'Messages' }">
                            <div
                                class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                                <span class="font-sans">View more messages
                                </span>

                            </div>
                        </router-link>

                    </div>
                </div>

            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import _ from 'lodash';
import * as echarts from 'echarts';
import ApexCharts from 'apexcharts'
import ViewMessages from './messages/viewMessages.vue'

export default defineComponent({
    name: "AdminHome",

    components: {
        ViewMessages
    },
    setup() { },

    props: {
        value: Object,
    },
    mounted() {
        this.getMessages('');
        this.getReport();

    },
    data() {

        return {
            componentOptions: {
                showModal: false,
                mode: '',

                selectedItem: null,
            },
            searchOptions: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 3,
                offset: 0,
            },

            messageList: null,
            messagesCount: 0,

            quitterData: [],
            reportData: [],
            year: null,
            barQuitterGraphData: [],
            barReportGraphData: [],



        }



    },

    methods: {
        getMessages(term = '') {
            if(this.$guard.can('message_list')){
                this.$api.get('/admin/messages/list', {
                    params: {
                        term: term,
                        offset: this.searchOptions.offset,
                        limit: this.searchOptions.limit,
                    }

                })
                .then((response) => {
                    this.messageList = response.data.message
                    this.messagesCount = this.messageList.length
                    this.searchOptions.total = response.data.total
                })
            }
        },



        showModal(mode) {
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode

            

        },
        closeModal() {
            
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''

        },
        refreshTable() {
            this.closeModal();
            this.getMessages();
        },
        debounceInput: _.debounce(function (e) {
            this.searchOptions.term = e.target.value;
            this.getMessages(this.searchOptions.term)
        }, 500),

        renderQuitterChart(year) {
            const chart = echarts.init(this.$refs.quitterChart, null, {
                autoResize: true
            });
            chart.setOption({
                textStyle: {
                    fontFamily: 'Inter, "Helvetica Neue", Arial, sans-serif',
                },
                title: {
                    text: year + ' Quitter Graph',
                    left: 'center',
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: Object.keys(this.quitterData)
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b} : {c} ({d}%)',
                },
                series: [
                    {

                        name: 'Quitter',
                        type: 'pie',
                        radius: '50%',
                        data: Object.keys(this.quitterData).map(key => ({
                            value: this.quitterData[key],
                            name: key
                        })),
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)',
                            },
                        },
                    }
                ],
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)',
                    },
                },
            });
            window.addEventListener('resize', () => {
                chart.resize();
            });
        },

        renderReportChart(year) {
            const chart = echarts.init(this.$refs.reportChart, null, {
                autoResize: true
            });
            chart.setOption({
                textStyle: {
                    fontFamily: 'Inter, "Helvetica Neue", Arial, sans-serif',
                },
                title: {
                    text: year + ' Reports Graph',
                    left: 'center',
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: Object.keys(this.reportData)
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b} : {c} ({d}%)',
                },
                series: [
                    {

                        name: 'Violations',
                        type: 'pie',
                        radius: '50%',
                        data: Object.keys(this.reportData).map(key => ({
                            value: this.reportData[key],
                            name: key
                        })),
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)',
                            },
                        },
                    }
                ],
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)',
                    },
                },
            });
            window.addEventListener('resize', () => {
                chart.resize();
            });
        },

        getReport() {
            this.$api.get('/public/enforcement/get-quitter-report')
                .then((response) => {
                    this.quitterData = response.data.quitterData
                    this.reportData = response.data.reportData
                    this.year = response.data.year
                    this.renderQuitterChart(this.year);
                    this.renderReportChart(this.year);
                })
        },

        goToViewMessage(hash){
            this.$router.push({name:'ViewMessages',params:{hash:hash}})
        }


    },
})


</script>
