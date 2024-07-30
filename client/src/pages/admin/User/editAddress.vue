<template>
    <Page>
        <div class="flex flex-col p-5">
            <!-- header -->
            <div class="flex flex-row gap-3 p-1 m-1">
                <div class="flex flex-col">
                    <TIcon name="edit" size="xl"></TIcon>
                </div>
                <div class="flex flex-col">
                    <span class="text-5xl font-sans font-semibold">Edit Address</span>
                </div>
            </div>

            <!-- form -->
            <div class="flex flex-col p-2 gap-4 mb-6">   
                <div class="flex flex-col w-full ">                    
                    <label class="font-sans">City/Municipality</label>
                    <TSelect v-model="city" :options="cityList ?? [{label: 'Awaiting Data', value: 'hmmmmmm'}]" class="font-sans w-full text-sm"/>                    
                </div>                 
                <div class="flex flex-col w-full">
                    <div class="flex flex-col w-full">
                        <label class="font-sans">Barangay:</label>
                        <TSelect  v-model="barangay" :options="barangayList ?? [{label: 'Select a city first', value: 'hmmmmmm'}]" class="font-sans w-full text-sm"/>
                    </div>                    
                </div>
                <div class="flex flex-col w-full">
                    <label class="font-sans">House No. and Street</label>
                    <input type="text" id=""
                        class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="House No. and Street"
                        v-model="form.location"
                    >
                </div>
            </div>

            <!--submit button-->
            <div class="flex flex-row gap-2 items-center justify-center">
                <button @click="onsubmit" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-gray-500 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Submit
                </button>
                <button  @click="cancel" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-gray-500 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">Cancel</button>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'EditAddress',
    setup(){},
    props:{
        address:{
            type:Object,
            default:null
        }
    },
    emits: [
        'cancel',
        'done',
        'processing',
        'failed'
    ],
    mounted(){
        this.getCity()
        this.form.location = this.address.location
        this.city.value = this.address.city.code
        this.city.label = this.address.city.name
        this.barangay.value = this.address.barangay.code
        this.barangay.label = this.address.barangay.name
    },
    data(){
        return{
            componentOptions: {},
            form:{
                location: '',
                barangay_code: ''
            },
            city:{
                label: 'Select a City',
                value: '',
                disabled: true
            },
            barangay:{
                label: 'Select a Barangay',
                value: '',
                disabled: true
            },
            cityList: null,
            barangayList: null
        }
    },
    watch:{
        city(newValue){
            if(newValue.value != ''){
                this.getBarangay(newValue)
            }
        }
    },
    methods:{
        getCity(){
            this.$api.get(`/address/cities/${141100000}`)
            .then((response)=>{
                this.cityList = response.data
                this.cityList = this.cityList.map((item)=>{
                    let a = {}
                    a.label = item.name
                    a.value = item.code
                    return(a)
                })
            })
        },
        getBarangay(selected){
            this.$api.get(`/address/barangays/${selected.value}`)
            .then((response)=>{
                this.barangayList = response.data
                this.barangayList = this.barangayList.map((item)=>{
                    let a = {}
                    a.label = item.name
                    a.value = item.code
                    return(a)
                })
            })
        },
        onsubmit(){
            this.$emit('processing')
            this.form.barangay_code = this.barangay.value
            let changes = this.getChanges(this.form)
            this.$api.patch('/users/address-update',changes)
            .then((response)=>{
                this.$emit('done') 
            })
            .catch(error => {
                // Handle the error and perform a task
                this.$emit('failed')
            });
            
        },
        getChanges(form){
            let changes = {}
            changes['id'] = this.address.id
            if(form.location != this.address.location){
                changes['location'] = form.location
            }
            else{
                changes['location'] = form.location
            }
            if(form.barangay_code != this.address.barangay.code){
                changes['barangay_code'] = form.barangay_code
            }
            else{
                changes['barangay_code'] = form.barangay_code
            }
            return changes;
        },
        cancel(){
            this.$emit('cancel')
        }
    }
})
</script>
