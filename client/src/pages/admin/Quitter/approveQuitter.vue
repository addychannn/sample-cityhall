<template>
    <div class="flex flex-col p-6 rounded-lg text-center">
        <div >
            <TIcon name="check_circle" size="xl" class="text-green-600"></TIcon>
        </div>
        <div class="font-sans text-xl font-normal text-black dark:text-gray-400">Are you sure you want to <span class="font-bold font-sans">approve</span> appointment {{this.quitter.hash}}?</div>
        <!--Admin remarks-->
        <div class="flex flex-col py-2">
            <div class="flex flex-row">
                <label class="font-sans">Remarks</label>
            </div>
            <textarea v-model="forms.admin_remarks" rows="3" class="w-full border text-sm font-sans p-1"></textarea>
            <!-- <QuillEditor v-model:content="forms.admin_remarks" content-type="text" theme="snow" class="w-full border bg-slate-300 text-lg" :toolbar="toolbarOptions"/> -->
        </div>
        <!--Actions-->
        <div class="flex items-center justify-center gap-2">
            <button @click="confirmQuitter" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                Yes, I'm sure
            </button>
            <button  @click="cancel" data-modal-hide="popup-modal" type="button" class="font-sans text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { notify } from 'notiwind';

export default defineComponent({
    name: 'AcceptQuitter',
    setup(){},
    emit:['cancel','done'],
    props:{
        quitter:{
            type:Object,
            default:null,
        }
    },
    components:{
        QuillEditor
    },
    data(){
        return{
            componentOptions: {},
            forms:{
                admin_remarks:'',
                status: null
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
    methods:{
        cancel(){
            this.$emit('cancel')
        },
        confirmQuitter(){
            this.forms.status = 1
            let changes = this.getChanges(this.forms)
            this.$api.patch('/quitter/update', changes)
            .then((response)=>{
                this.$emit('done')
            })
            notify({group:"main", title:"Quitter Appointment Approved!", type:"positive"},'3000')
        },
        getChanges(form){
            let changes = {}
            changes['id'] = this.quitter.hash
            if(form.admin_remarks != this.quitter.admin_remarks){
                changes['admin_remarks'] = form.admin_remarks
            }
            if(form.status != this.quitter.status){
                changes['status'] = form.status
            }
            return changes;
        }
    }
})
</script>
