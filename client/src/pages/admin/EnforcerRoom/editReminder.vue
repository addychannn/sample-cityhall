<template>
    <Page class="p-5 gap-5">
        <div class="flex flex-row  border-b border-slate-900 gap-4 my-2">
            <TIcon name="notification_important" size="xl"></TIcon>
            <span class="font-sans font-bold text-5xl">Update Reminder Details</span>
        </div>

        <div class="flex flex-col gap-5 my-2">
            <div class="flex flex-col gap-3">
                <span class="font-sans font-semibold text-3xl ">Title</span>
                <input v-model="title" type="text" placeholder="Input Title" class="bg-slate-300 p-2 rounded-lg text-lg font-sans">
            </div>

            <div class="flex flex-col my-2">
                <span class="font-sans font-semibold text-3xl">Content</span>
                <QuillEditor v-model:content="content" content-type="html" theme="snow"
                                class="w-full h-[24rem]  bg-slate-300"
                               />
            </div>
        </div>

        <div class="flex flex-row my-2">
            <span @click="saveChanges" class="font-sans font-semi-bold text-xl bg-slate-400 shadow-xl py-2 px-3 rounded-full hover:bg-green-500 cursor-pointer">Save Changes</span>
        </div>

        <TInnerLoading :is-full-screen="true" :text="componentOptions.innerLoading.text"
            :active="componentOptions.innerLoading.show"></TInnerLoading>

    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import TInnerLoading from '@/components/TInnerLoading.vue';
import { notify } from 'notiwind'

export default defineComponent({
    name: 'EditReminder',

    props:{
        id:{
            type:String,
            default:null
        }
    },

    components:{
        QuillEditor,
        TInnerLoading
    },

    setup(){},

    mounted(){
        this.getSpecificReminder(this.id)
    },

    data(){
        return{
            componentOptions: {
                innerLoading: {
                    show: false,
                    text: ''
                }
            },

            content:"",
            title:""
        }
    },

    methods:{
        innerLoading(text) {
            this.componentOptions.innerLoading.show = true
            this.componentOptions.innerLoading.text = text
        },

        closeInnerLoading() {
            this.componentOptions.innerLoading.show = false
        },

        getSpecificReminder(id){
            this.$api.get('/admin/enforcer-room/get-specific-reminder',{
                params:{
                    id:id
                }
            })

            .then((response)=>{
                this.title=response.data.reminder.title
                this.content=response.data.reminder.content
            })
        },

        saveChanges(){
            this.innerLoading('Updating Reminder')

            this.$api.patch('/admin/enforcer-room/edit-specific-reminder',
            {
                id:this.id,
                title:this.title,
                content:this.content
            })

            .then((response)=>{
                this.closeInnerLoading('Reminder Updated')
                this.$router.push({ name: 'EnforcerReminders' })
                notify({ group: "main", title: "Updated Reminder Successfuly", type: "positive" }, "3000")
            })
        }
    }
})
</script>
