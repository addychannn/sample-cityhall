<template> 
    <Page class="font-sans px-2 py-2 lg:px-12 flex justify-center items-center">
        <!--Confirmation Prompt-->
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmSubmission v-if="componentOptions.showModal"  @proceed="onSubmit" @cancel="closeModal"/>
        </Modal>
        <!--Loader-->
        <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
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
                    <div class="flex flex-col p-5" ref="reportDetails">
                        <div class="flex flex-col w-full font-bold font-sans text-center text-xl text-green-600 pb-4">
                            Report sent successfuly!
                        </div>
                        <div class="flex flex-col w-full text-center">
                            <span class="font-sans font-bold">
                                Here is your report id: 
                            </span>
                        </div>
                        <div class="flex flex-col w-full text-center  pb-4">
                            <span class="font-black font-sans text-lg">
                                {{ reportId }}   
                            </span>
                        </div>
                        <div class="flex flex-col w-full text-center">
                            <span class="font-sans font-light text-sm">Use this id to follow up your report using the track feature or send message feature.</span>
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
        
        <div class="flex flex-col w-full lg:h-full xl:flex-row justify-center items-center gap-2">
            <!--logo and text-->
            <div class="flex flex-col items-center">
                <img src="../../assets/smokebaguio.png" class=" h-[12rem] w-[12rem] md:h-[20rem] md:w-[20rem] lg:h-[24rem] lg:w-[24rem] xl:w-[28rem]">
                <span class=" text-center text-green-600 font-bold text-xl font-sans">Saw an anti-smoking violation in your area?</span>
                <span class="text-center text-xs font-sans px-5 pt-3 text-gray-600">Report them by filling out information here. Don’t worry, your identity and personal information will be kept confidential.</span>
            </div>
            <!--form-->
            <div class="flex flex-col w-full h-full py-5 px-3 lg:px-0   xl:w-[60rem] xl:h-full">
                <div class="bg-white h-full w-full rounded-2xl shadow-md text-center py-2 px-2 md:px-4">
                    <span class="font-sans font-bold text-2xl text-green-600 ">Report Violation</span>
                    <!--complainant details-->
                    <div>
                        <label class="font-sans text-sm">Complainant Details</label>
                    </div>
                    <!--complainant name-->
                    <div class="flex flex-col md:flex-row text-left px-2 gap-2 w-full">
                        <!--fname-->
                        <div class="flex flex-col w-full">
                            <div class="text-left w-full">
                                <label for="fname" class="font-sans">First Name</label>
                            </div>
                            <div class="text-left">
                                <input v-model="form.first_name" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 font-sans w-full">
                            </div>
                        </div>

                        <!--mname-->
                        <div class="flex flex-col w-full">
                            <div class="text-left w-full">
                                <label for="mname" class="font-sans">Middle Name</label>
                            </div>
                            <div class="text-left">
                                <input v-model="form.middle_name" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 font-sans w-full">
                            </div>
                        </div>

                        <!--lname-->
                        <div class="flex flex-col w-full">
                            <div class="text-left w-full">
                                <label for="lname" class="font-sans">Last Name</label>
                            </div>
                            <div class="text-left">
                                <input v-model="form.last_name" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 font-sans w-full">
                            </div>
                        </div>

                        <!--suffix-->
                        <div class="flex flex-col ">
                            <div class="text-left">
                                <label for="lname" class="font-sans">Suffix</label>
                            </div>
                            <div class="text-left">
                                <input v-model="form.suffix" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 font-sans w-full">
                            </div>
                        </div>
                    </div>
                    
                    <!--Complainant contact details-->
                    <div class="flex flex-col md:flex-row text-left px-2 gap-2 w-full pb-2">
                        <!--Contact Number-->
                        <div class="flex flex-col w-full">
                            <div class="text-left">
                                <label for="contactNumber" class="font-sans">Contact No.</label>
                                <span class="font-sans text-lg text-red-500">*</span>
                            </div>
                            <div class="text-left">
                                <input v-model="form.contact_number" type="text" @input="validateNumericInput" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 font-sans w-full">
                            </div>
                        </div>
                        <!--Email-->
                        <div class="flex flex-col w-full">
                            <div class="text-left">
                                <label for="email" class="font-sans">Email Address</label>
                            </div>
                            <div class="text-left">
                                <input v-model="form.email" type="email" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full">
                            </div>                            
                        </div>
                    </div>

                    
                    <div style="border-top: 1px solid gray;"></div>

                    <div>
                        <label for="fname" class="font-sans text-sm">Violator Details</label>
                    </div>  

                    <!--Name of violator (person/establishment) and date-->
                    <div class="flex flex-col md:flex-row px-2 w-full gap-2">
                        <div class="flex flex-col w-full">
                            <div class="text-left">
                                <label for="violatorName" class="font-sans">Violator</label>
                            </div>
                            <div class="text-left">
                                <input v-model="form.violator_name" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full" placeholder="Violator/establishment name">
                            </div>
                        </div>
                        <!--Date of Violation-->
                        <div class="flex flex-col">
                            <div class="flex flex-col">
                                <div class="text-left">
                                    <label for="violationDate" class="font-sans">Date of Violation</label>
                                    <span class="font-sans text-lg text-red-500">*</span>
                                </div>
                                <VueDatePicker
                                    class="absolute"
                                    v-model="form.violation_date"
                                    placeholder="MM/DD/YYYY"
                                    :maxDate="new Date()"
                                    :enableTimePicker="false"
                                ></VueDatePicker>
                            </div>
                        </div>
                            
                    </div>

                    <!--Location (house no, street, barangay)-->
                    <div class="flex flex-col w-full px-2">
                            <div class="text-left">
                                <label for="address" class="font-sans">Location</label>
                                <span class="font-sans text-lg text-red-500">*</span>
                            </div>
                            <div class="flex flex-col md:flex-row text-left gap-2 w-full">
                                <div class="text-left w-full">
                                    <input v-model="form.violator_location" type="text" class="rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark font-sans w-full" placeholder="Bldg No. and Street">
                                </div>
                                <div v-if="barangayList != []" class="flex flex-col w-full">
                                    <TSelect  v-model="barangay" :options="barangayList ?? [{label: 'Awaiting data', value: 'hmmmmmm'}]" class="font-sans"/>
                                </div>
                            </div>
                    </div>

                    <!--Violation Details-->
                    <div class="flex flex-col md:flex-row text-left px-2 gap-2 w-full">
                        <div class="flex flex-col w-full">
                            <div class="flex flex-col w-full">
                                <!--Description of landmark-->
                                <div class="text-left">
                                    <label for="landmark" class="font-sans">Landmark</label>
                                    <span class="font-sans text-lg text-red-500">*</span>
                                </div>
                                <div class="text-left w-full">
                                    
                                    <textarea v-model="form.landmark" name="landmark" id=""  rows="3" class="border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark rounded-md bg-white pl-1 font-sans flex w-full" placeholder="Description of the violator's landmark" maxlength="280"></textarea>
                                </div>
                            </div>
                            <div class="flex flex-col w-full">
                                <!--Short description of the violation-->
                                <div class="text-left">
                                    <label for="violation" class="font-sans">Violation</label>
                                    <span class="font-sans text-lg text-red-500">*</span>
                                </div>
                                <div class="text-left w-full">
                                    <textarea v-model="form.violation_description" name="violation" id="" rows="3" class="border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left leading-5 text-dark rounded-md bg-white pl-1 font-sans flex w-full" placeholder="Short description of the violation" maxlength="280"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Upload Image-->
                        <div class="flex flex-col w-full">
                            <div class="flex flex-col text-left  w-full">
                                <div class="flex-col text-left px-2">
                                    <label for="violation" class="font-sans">Upload Image/s</label>
                                </div>
                                <div class="flex-col text-left w-full px-2 md:h-[11rem] overflow-auto pt-1">
                                    <ReportFileDrop v-model="files" multiple accept=".jpeg,.png,.jpg,.gif"></ReportFileDrop>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Submit Button-->
                    <div class="flex flex-col items-center justify-center pt-2">
                        <span @click="showModal" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
                    </div>
                </div>
            </div>
        </div>       
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import imageCompression from 'browser-image-compression';
import { notify } from 'notiwind';
import ConfirmSubmission from './confirmSubmission.vue'
import html2canvas from 'html2canvas';

export default defineComponent({
    name: 'ReportViolation',
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
                alertMsg: '',
                activeStatus: false,
                showModal: false
            },
            form:{
                first_name: '',
                middle_name: '',
                last_name: '',
                suffix: '',
                contact_number: null,
                email: '',
                violator_name: '',
                violator_location: '',
                barangay_code: '',
                violation_date: '',
                landmark: '',
                violation_description: ''
            },
            files: [],
            barangay: {label: 'Select a Barangay', value: 'hmmmmmm', disabled: true},
            barangayList: null,
            reportId: '' ,
            today: ''
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
        async onSubmit(){
            this.closeModal()
            let token = null
            this.form.barangay_code = this.barangay.value
            await this.$api.post('/report/create',this.form)
            .then((response)=>{
                token = response.data.report.hash
                this.componentOptions.alertMsg = response.data.message
                this.componentOptions.activeStatus =true
                this.reportId = response.data.report.hash
            })
            await this.uploadImage(token, this.files)
            await this.provideId()
            this.clearFields()
        },
        async provideId(){
            this.componentOptions.activeStatus = false
            this.componentOptions.hasHash = true
            // setTimeout(async() =>{
            //     await this.downloadDetails()
            // }, 350)
        },
        async downloadDetails() {
            this.componentOptions.alertMsg = "Downloading report ID"
            this.componentOptions.activeStatus =true
            // Download the report id as an image
            const el = this.$refs.reportDetails;
            const option = {
                scale: 4,
            };
            await html2canvas(el,option)
            .then(canvas => {
                var myImage = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");

                var a = document.createElement("a");
                a.href = myImage;
                a.download = `Report-${this.reportId}.png`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                this.componentOptions.activeStatus = false
                notify({group:"main", title:"Report ID downloaded successfully!", type:"positive"},'3000')
            });
        },
        async uploadImage(hash,files){
            for(let i=0; i<files.length; i++){
                let formData = await this.compressAndPrepImage(hash,files[i])
                await this.$api.post('/report/create-photo',formData)
            }
        },
        async compressAndPrepImage(hash, file){
            // Create a form data
            let form = new FormData()
            // Compress Each file in the files variable then append
            // form details.
            let image = await imageCompression(file.file, {
                maxSizeMB: 2,
            })
            form.append('file', image)
            // for identification
            form.append('uuid',file.id)
            form.append('timestamp', null)
            // Chunking
            form.append('chunk', 0)
            form.append('name', file.file.name)
            form.append('ext', file.file.name?.split(".").pop())
            form.append('is_last', 1)
            form.append('hash', hash)
            form.append('is_main', false)
            return form
        },
        clearFields(){
            this.form.first_name = '',
            this.form.middle_name = '',
            this.form.last_name = '',
            this.form.suffix = '',
            this.form.contact_number = null,
            this.form.email = '',
            this.form.violator_name = '',
            this.form.violator_location = '',
            this.form.barangay_code = '',
            this.form.violation_date = '',
            this.form.landmark = '',
            this.form.violation_description = '',
            this.files = [], 
            this.barangay = {label: 'Select a Barangay', value: 'hmmmmmm', disabled: true}
        },
        closeAlert(){
            this.componentOptions.hasHash = false
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
    } 
})
</script>
