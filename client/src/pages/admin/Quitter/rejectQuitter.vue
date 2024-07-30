<template>
    <Modal :show="componentOptions.showModal" @close="closeModal">
        <ConfirmReschedule v-if="componentOptions.showModal" @proceed="rescheduleQuitter" @cancel="closeModal"/>
    </Modal>
    <div class="flex flex-col p-6 rounded-lg text-center">
        <!--actions-->
        <div v-if="quitter.status != 3" class="flex flex-row text-center justify-center">
            <span class="text-xl md:text-3xl font-sans font-semibold text-center">Choose an Action</span>
        </div>
        <div v-if="quitter.status != 3" class="flex flex-row gap-1">
            <div @click="openAction('reject')" class="flex flex-col w-full bg-slate-500 text-center cursor-pointer hover:bg-slate-600 text-white" v-if="$guard.can(['quitter_reject'])">
                <span class="font-sans p-2 text-center">Reject</span>
            </div>
            <div @click="openAction('reschedule')" class="flex flex-col w-full bg-slate-500 text-center cursor-pointer hover:bg-slate-600 text-white" v-if="$guard.can(['quitter_reschedule'])">
                <span class="font-sans p-2 textcenter">Reschedule</span>
            </div>
        </div>
        <!--reschedule-->
        <div v-if="componentOptions.openReschedule || quitter.status == 3" class="flex flex-col py-4 gap-4" :class="componentOptions.openReschedule || quitter.status == 3 ? 'h-[28rem]' : 'h-fit'">
            <div class="flex flex-col">
                <label class="font-sans text-start">New Appointment Date and Time:</label>
                <VueDatePicker
                    v-model="forms.date"
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
            <!--Admin remarks-->
            <div class="flex flex-col py-2">
                <div class="flex flex-row">
                    <label class="font-sans">Remarks</label>
                </div>
                <textarea v-model="forms.admin_remarks" rows="3" class="w-full border text-sm p-1 font-sans" placeholder="Reason of Reschedule (required)"></textarea>
                <!-- <QuillEditor v-model:content="forms.admin_remarks" content-type="text" theme="snow" class="w-full border bg-slate-300 text-lg" :toolbar="toolbarOptions" placeholder="Reason of Reschedule(required)"/> -->
            </div>
            <div class="flex flex-row justify-center">
                <button @click="showModal" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Submit
                </button>
                <button  @click="cancel" data-modal-hide="popup-modal" type="button" class="font-sans text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
            </div>
        </div>
        <!--reject-->
        <div v-if="componentOptions.openReject && quitter.status != 3" class="flex flex-col py-4">
            <div>
                <TIcon name="warning" size="xl" class="text-red-600"></TIcon>
            </div>
            <div class="font-sans text-xl font-normal text-black dark:text-gray-400">Are you sure you want to <span class="font-bold font-sans">reject</span> appointment {{this.quitter.hash}}?</div>
            <!--Admin remarks-->
            <div class="flex flex-col py-2">
                <div class="flex flex-row">
                    <label class="font-sans">Remarks</label>
                </div>
                <textarea v-model="forms.admin_remarks" rows="3" class="w-full border text-sm font-sans p-1" placeholder="Reason of Rejection (required)"></textarea>
                <!-- <QuillEditor v-model:content="forms.admin_remarks" content-type="text" theme="snow" class="w-full border bg-slate-300 text-lg" :toolbar="toolbarOptions" placeholder="Reason of Rejection(required)"/> -->
            </div>
            <!--Actions-->
            <div class="flex items-center justify-center gap-2">
                <button @click="rejectQuitter" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Yes, I'm sure
                </button>
                <button  @click="cancel" data-modal-hide="popup-modal" type="button" class="font-sans text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
            </div>
        </div>

    </div>
</template>

<script>
import { defineComponent } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { notify } from 'notiwind';
import ConfirmReschedule from './confirmReschedule.vue'

export default defineComponent({
    name: 'RejectQuitter',
    setup(){},
    components:{
        QuillEditor,
        ConfirmReschedule
    },
    emit:['cancel','done'],
    props:{
        quitter:{
            type:Object,
            default:null,
        }
    },
    data(){
        return{
            componentOptions: {
                mode: '',
                openReject: false,
                openReschedule:false,
                showModal:false
            },
            forms:{
                quitter_id: null,
                admin_remarks: '',
                status: null,
                date: null
            },
            toolbarOptions: [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                [{ 'color': [] }],          // dropdown with defaults from theme
                [{ 'align': [] }],
                ['clean']                                         // remove formatting button
            ]
        }
    },
    mounted(){
        this.forms.date = this.quitter.preferred_date
        this.forms.quitter_id = this.quitter.hash
    },
    methods:{
        cancel(){
            this.$emit('cancel')
        },
        openAction(mode){
            if(mode == 'reject'){
                this.componentOptions.openReject = !this.componentOptions.openReject
                this.componentOptions.openReschedule = false
            }
            if(mode == 'reschedule'){
                this.componentOptions.openReject = false
                this.componentOptions.openReschedule = !this.componentOptions.openReschedule
            }
        },
        rejectQuitter(){
            this.forms.status = 2
            let changes = this.getChanges(this.forms)
            this.$api.patch('/quitter/reject', changes)
            .then((response)=>{
                this.$emit('done')
                notify({group:"main", title:"Quitter Appointment Rejected", type:"positive"},'3000')
                this.deleteQuitter()
            })
        },
        getChanges(form){
            let changes = {}
            changes['id'] = this.quitter.hash
            if(form != this.quitter.admin_remarks){
                changes['admin_remarks'] = this.forms.admin_remarks
            }
            if(form != this.quitter.status){
                changes['status'] = this.forms.status
            }
            return changes;
        },
        deleteQuitter(){
            this.$api.delete('/quitter/delete',{
                params:{
                    id: this.quitter.hash
                }
            })
        },
        rescheduleQuitter(){
            this.closeModal()
            let selectedDate = new Date(this.forms.date)
            // Add 8 hours to the selected date and time
            let newDate = new Date(selectedDate.getTime() + 8 * 60 * 60 * 1000)
            //store new date to data date for submission
            this.forms.date = newDate
            this.$api.patch('/quitter/reshedule',this.forms)
            .then((response)=>{
                this.$emit('done')
                notify({group:"main", title:"Quitter Appointment Rescheduled", type:"positive"},'3000')
            })
            
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
