<template>
    <Page class="font-sans px-2 py-2 lg:px-12 relative flex justify-end items-end">  
        <div class="flex flex-col w-full lg:h-full xl:flex-row justify-center items-center gap-2">
            <!--logo and text-->
            <div class="flex flex-col items-center">
                <img src="../../assets/smokebaguio.png" class=" h-[12rem] w-[12rem] md:h-[20rem] md:w-[20rem] lg:h-[24rem] lg:w-[24rem]">
                <span class=" text-center text-green-600 font-bold text-xl font-sans">Track your transaction now!</span>
                <span class="text-center text-sm font-sans px-5 text-gray-600">Track your submitted report or quitter appointment using the provided id after submission.</span>
            </div>
            <!--form-->
            <div class="flex flex-col w-full h-full py-5 px-3 lg:px-0 xl:w-[60rem] lg:h-[36rem]">
                <div class="bg-white h-full w-full  rounded-2xl shadow-md text-center p-20">  
                    <!--Transaction Type-->
                    <div class="flex flex-col md:flex-row text-left px-2 gap-2 w-full pb-2">
                        <div class="text-left w-full">
                            <label for="transactionType" class="font-sans">Type</label>
                            <span class="font-sans text-xs text-red-500">*</span>
                        </div>
                        <div class="text-left w-full">
                            <TSelect v-model="selected_type" :options="typeList ?? [{label:'Awaiting data', value:'hmmmm'}]"></TSelect>
                        </div>

                    </div>

                    <!--ID-->
                    <div class="flex flex-col md:flex-row text-left px-2 gap-2 w-full pb-4">
                        <div class="text-left w-full">
                            <label for="transactionId" class="font-sans">Report/Appointment ID</label>
                            <span class="font-sans text-xs text-red-500">*</span>
                        </div>
                        <div class="text-left w-full">
                            <input v-model="form.id" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full">
                        </div>
                    </div>

                    <!--Submit Button-->
                    <div class="flex items-center justify-center pb-4">
                        <span @click="onSubmit" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Search</span>
                    </div>

                    <div style="border-top: 1px solid gray;"></div>
 
                    <!--Details-->
                    <div class="flex flex-col pt-4">
                        <div class="flex flex-row text-center justify-center">
                                <span class="font-sans font-bold text-xl text-green-600">Details</span>
                            </div>
                        <div v-if="data_length == 0" class="flex flex-col">
                            <span class="font-sans text-slate-600 text-xl text-center">(No Record)</span>
                        </div>
                        <div v-for="item in data" v-else class="flex flex-col border rounded-2xl p-4 shadow-md">
                            <div class="flex flex-row justify-between">
                                <span class="font-sans font-bold">Type:</span>
                                <span class="font-sans text-slate-600 capitalize"> {{ submitted_type }}</span>
                            </div>
                            <div class="flex flex-row justify-between">
                                <span class="font-sans font-bold">ID:</span>
                                <span class="font-sans text-slate-600"> {{ item.hash || item.id }}</span>
                            </div>
                            <div class="flex flex-row justify-between">
                                <span class="font-sans font-bold">Status:</span>
                                <span v-if="form.type == 'report'" class="font-sans" :class="item.status  == 0 ? 'text-amber-600' : item.status == 1 ? 'text-green-600' : 'text-red-600'">
                                    {{ item.status == 0 ? 'Pending' :item.status == 1 ? 'Resolved' : 'Rejected'}}
                                </span>
                                <span v-else class="font-sans" :class="item.status == 0 ? 'text-amber-600': item.status == 1 ? 'text-green-600': item.status == 2 ? 'text-red-600' : 'text-blue-600'">
                                    {{item.status == 0 ? 'Pending' : item.status == 1 ? 'Approved':item.status == 2 ? 'Rejected' : 'Rescheduled'}}
                                    <span v-if="item.status == 3" class="font-sans text-blue-600"> to {{ item.preferred_date }}</span>
                                </span>
                            </div>
                            <div class="flex flex-row justify-between">
                                <span class="font-sans font-bold">Remarks:</span>
                                <span class="font-sans text-slate-600 lowercase"> {{ item.admin_remarks ? item.admin_remarks : '(No Remarks)' }}</span>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>  
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { notify } from 'notiwind';

export default defineComponent({
    name: 'Track',
    setup(){},
    data(){
        return{
            componentOptions: {},
            form:{
                type: null,
                id: null
            },
            typeList:[
                {label:'Report', value:'report'},
                {label:'Quitter Appointment', value:'quitter appointment'}
            ],
            selected_type:{label:'Select Type', value:'hmmmm', disabled:true},
            data: null,
            submitted_type: null,
            data_length: 0,
        }
    },
    methods:{
        onSubmit(){
            this.form.type = this.selected_type.value
            this.$api.get('/track',{
                params:{
                    type: this.form.type,
                    id:this.form.id
                }
            })
            .then((response)=>{
                this.data = response.data.result
                this.submitted_type = this.form.type
                this.data_length = response.data.result.length
                if(this.data.length == 0){
                    notify({group:"main", title:'Record not found', type:"negative"},'3000')
                }
                
            })
        }
    }
})
</script>