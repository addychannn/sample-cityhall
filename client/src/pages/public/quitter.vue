<template>
    <Page class="font-sans px-2 py-2 lg:px-12 relative flex justify-end items-end">        
        <!--Alert-->
        <transition leave-active-class="duration-200">
            <div v-show="componentOptions.hasHash" class="fixed inset-0 overflow-y-auto px-4 md:px-[20rem] py-6  z-50" scroll-region>
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div class="fixed inset-0 transform transition-all">
                        <div class="absolute inset-0 bg-gray-500 opacity-75" />
                    </div>
                </transition>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                <div class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto">
                    <div class="flex items-end justify-end px-2 py-2">
                        <TIcon @click="closeAlert" name="close" class="cursor-pointer"/>
                    </div>
                    <div class="flex flex-col p-5" ref="quitterDetails">
                        <div class="flex flex-col w-full font-bold font-sans text-center text-xl text-green-600 pb-4">
                            Appointment Request sent successfuly!
                        </div>
                        <div class="flex flex-col w-full text-center">
                            <span class="font-sans font-bold">
                                Here is your appointment id: 
                            </span>                 
                        </div>
                        <div class="flex flex-col w-full text-center pb-4">
                            <span class="font-black font-sans text-lg">
                                {{ quitterId}}
                            </span>                 
                        </div>
                        <div class="flex flex-col w-full text-center">
                            <span class="font-sans font-light text-sm">Use this id to follow up your appointment using the track feature or send message feature.</span>
                        </div>
                    </div>
                    <div class="flex flex-col px-10">
                        <div style="border-top: 1px solid gray;"></div>
                    </div>
                    
                    <div class="flex flex-col items-center pt-2 pb-5">
                        <div @click="downloadDetails" class="flex flex-row gap-1 rounded-full bg-slate-500 text-white p-2 hover:bg-green-600 hover:scale-110 cursor-pointer ">
                            <TIcon name="download" size="sm"></TIcon>
                            <span class="text-sm text-white font-sans">Download</span>
                        </div>
                    </div>
                </div>

                </transition>
            </div>
        </transition>
        <!--Confirmation Prompt-->
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmSubmission v-if="componentOptions.showModal"  @proceed="onSubmit" @cancel="closeModal"/>
        </Modal>>
        <div class="flex flex-col w-full lg:h-full xl:flex-row justify-center items-center gap-2">
            <!--logo and text-->
            <div class="flex flex-col items-center">
                <img src="../../assets/smokebaguio.png" class=" h-[12rem] w-[12rem] md:h-[20rem] md:w-[20rem] lg:h-[24rem] lg:w-[24rem]">
                <span class=" text-center text-green-600 font-bold text-xl font-sans">Are you a smoker who plans to quit smoking?</span>
                <span class="text-center text-sm font-sans px-5 text-gray-600">We’re here for you! Enroll in our Smoking Cessation Program. It’s FREE!</span>
                <span class="text-center text-xs font-sans px-5 pt-3 text-gray-600">We are available Monday-Friday, 9am to 4pm Visit our office at third floor, City Health Services Office, T. Alonzo, Baguio City</span>
                <span class="text-center text-sm font-sans px-5 font-semibold text-gray-600">Set an appointment with us by filling our form</span>
            </div>
            <!--form-->
            <div class="flex flex-col w-full h-full py-5 px-3 lg:px-0 xl:w-[60rem] ">
                <div class="bg-white h-full w-full flex flex-col justify-center  rounded-2xl shadow-md text-center  py-4 px-4">
                    <span class="font-sans font-bold text-2xl text-green-600   ">Appointment Form</span>
  
                    <!--Quitter name-->
                    <div class="flex flex-col md:flex-row text-left px-2 gap-2 w-full">
                        <!--fname-->
                        <div class="flex flex-col w-full">
                            <div class="text-left w-full">
                                <label for="fname" class="font-sans">First Name</label>
                                <span class="font-sans text-lg text-red-500">*</span>
                            </div>
                            <div class="text-left">
                                <input v-model="form.first_name" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full">
                            </div>
                        </div>

                        <!--mname-->
                        <div class="flex flex-col w-full">
                            <div class="text-left w-full">
                                <label for="mname" class="font-sans">Middle Name</label>
                            </div>
                            <div class="text-left">
                                <input v-model="form.middle_name" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full">
                            </div>
                        </div>

                        <!--lname-->
                        <div class="flex flex-col w-full">
                            <div class="text-left w-full">
                                <label for="lname" class="font-sans">Last Name</label>
                                <span class="font-sans text-lg text-red-500">*</span>
                            </div>
                            <div class="text-left">
                                <input v-model="form.last_name" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full">
                            </div>
                        </div>

                        <!--suffix-->
                        <div class="flex flex-col ">
                            <div class="text-left">
                                <label for="lname" class="font-sans">Suffix</label>
                            </div>
                            <div class="text-left">
                                <input v-model="form.suffix" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full">
                            </div>
                        </div>
                    </div>

                    <!--Quitter Contact Details-->
                    <div class="flex flex-col md:flex-row text-left px-2 gap-2 w-full">
                        <!--Contact Number-->
                        <div class="flex flex-col w-full">
                            <div class="text-left pt-1">
                                <label for="contactNumber" class="font-sans">Contact No.</label>
                                <span class="font-sans text-lg text-red-500">*</span>
                            </div>
                            <div class="text-left pb-1">
                                <input v-model="form.contact_number" type="text" @input="validateNumericInput" minlength="11" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full">
                            </div>
                        </div>
                        <!--Email-->
                        <div class="flex flex-col w-full">
                            <div class="text-left pt-1">
                                <label for="email" class="font-sans">Email Address</label>
                            </div>
                            <div class="text-left pb-4">
                                <input v-model="form.email" type="email" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full">
                            </div>                            
                        </div>
                    </div>

                    
                    <div style="border-top: 1px solid gray;"></div>
 
                    <!--Date of Appointment-->
                    <div class="text-left pt-2 px-2">
                        <label for="violationDate" class="font-sans">Appointment Date</label>
                        <span class="font-sans text-lg text-red-500">*</span>
                    </div>
                    <div class="text-left px-2">
                        <VueDatePicker
                            v-model="form.preferred_date"
                            placeholder="MM/DD/YYYY"
                            :minDate="new Date()"
                            :enableTimePicker="true"
                            :is24="false"
                            :clearable="true"
                            :disabledWeekDays="[6,0]"
                            :minutesIncrement="30"
                            :minutesGridIncrement="30"
                            :startTime="{hours:'08',minutes:'00',seconds:'00'}"
                        ></VueDatePicker>
                    </div>

                    <!--Address-->
                    <div class="text-left pt-1 px-2">
                        <label for="email" class="font-sans">Address</label>
                        <span class="font-sans text-lg text-red-500">*</span>
                    </div>
                    <div class="flex flex-col md:flex-row text-left px-2 gap-2 w-full">
                        <div class="flex flex-col w-full">
                            <input v-model="form.location" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full" placeholder="House No. and Street">
                        </div>

                        <div v-if="barangayList != []" class="flex flex-col w-full">
                            <TSelect  v-model="barangay" :options="barangayList ?? [{label: 'Awaiting data', value: 'hmmmmmm'}]" class="font-sans"/>
                        </div>
                    </div>

                    <!--Additional Notes-->
                    <div class="text-left pt-1 px-2">
                        <label for="landmark" class="font-sans">Reason</label>
                    </div>
                    <div class="text-left px-2">
                        <textarea v-model="form.additional_notes" name="note" id=""  rows="5" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full" maxlength="280" placeholder="(maximum of 280 characters)"></textarea>
                    </div>

                    <!--Submit Button-->
                    <div class="flex items-center justify-center pt-1">
                        <span @click="showModal" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Send</span>
                    </div>
                </div>
            </div>
        </div>       
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import ConfirmSubmission from './confirmSubmission.vue'
import html2canvas from 'html2canvas';
import { notify } from 'notiwind';

export default defineComponent({
    name: 'Quitter',
    setup(){},
    components:{
        ConfirmSubmission
    },
    async mounted(){
        await this.getBarangay('')
    },
    data(){
        return{
            componentOptions: {
                hasHash: false,
                showModal: false
            },
            form:{
                first_name: '',
                middle_name: '',
                last_name: '',
                suffix: '',
                contact_number: null,
                email: '',
                preferred_date: '',
                location: '',
                barangay_code: '',    
                additional_notes: ''
            },
            barangay: {label: 'Select a Barangay', value: 'hmmmmmm', disabled: true},
            barangayList: null,
            quitterId: ''
        }
    },
    methods:{
        async getBarangay(){
            await this.$api.get(`/address/get-barangays/${141102000}`)
            .then((response)=>{
                this.barangayList = response.data.barangay
                this.barangayList = this.barangayList.map((item) => {
                    let a = {}
                    a.label = item.name
                    a.value = item.code
                    return a
                })
            })
        },
        onSubmit(){
            this.closeModal()
            let selected_date = new Date(this.form.preferred_date)
            let new_date = new Date(selected_date.getTime() + 8 * 60 * 60 * 1000)
            this.form.preferred_date = new_date
            this.form.barangay_code = this.barangay.value
            this.$api.post('/quitter/create',this.form)
            .then((response)=>{
                this.quitterId = response.data.quitter.hash
                this.componentOptions.hasHash = true
                // setTimeout(async() =>{
                //     await this.downloadDetails()
                // }, 350)
            })
            
        },
        async downloadDetails() {
            // Download the quitter id as an image
            const el = this.$refs.quitterDetails;
            const option = {
                scale: 4,
            };
            await html2canvas(el,option)
            .then(canvas => {
                var myImage = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");

                var a = document.createElement("a");
                a.href = myImage;
                a.download = `Quitter-${this.quitterId}.png`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                notify({group:"main", title:"Appointment ID downloaded successfully!", type:"positive"},'3000')
            });
            
        },
        clearFields(){
            this.form.first_name = '',
            this.form.middle_name = '',
            this.form.last_name = '',
            this.form.suffix = '',
            this.form.contact_number = null,
            this.form.email = '',
            this.form.preferred_date = '',
            this.form.location = '',
            this.form.barangay_code = '',    
            this.form.additional_notes = '',
            this.barangay = {label: 'Select a Barangay', value: 'hmmmmmm', disabled: true}
            this.quitterId = ''
        },
        closeAlert(){
            this.componentOptions.hasHash= false
            this.clearFields()
        },
        validateNumericInput() {
            // Remove any non-numeric characters from the input value
            this.form.contact_number = this.form.contact_number.replace(/[^\d]/g, '');
        },
        showModal(){
            this.componentOptions.showModal = true
        },
        closeModal(){
            this.componentOptions.showModal = false
        }
    },
    computed:{
        barangayLabel(){
            return this.barangay.filter(x => x == this.form.code).name
        }
    }
})
</script>

<style>
  /* Remove the stepper arrows */
  input[type=number]::-webkit-outer-spin-button,
  input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
</style>
