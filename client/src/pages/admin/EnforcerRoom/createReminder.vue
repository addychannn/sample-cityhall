<template>
    <Page>
        <div class="p-2 md:px-8 md:py-16 ">
            <div class="flex flex-col px-2 ">
                <div class="block items-center justify-center rounded-3xl  ">
                    <div class="flex flex-row text-slate-800 ">
                        <div class="flex flex-col">
                            <TIcon name="notification_important" size="xl"></TIcon>

                        </div>

                        <div class="flex flex-col pb-2">
                            <span class="font-sans  text-5xl font-semibold flex flex-col items-center justify-center">Add New Reminder</span>

                        </div>

                    </div>

                    <!-- add article -->
                    <div class="flex flex-col gap-3 justify-start items-start">
                        <span class="sm:text-xl text-md font-sans mt-1">Title:</span>
                        <input v-model="title" class="rounded-md w-full h-10 font-sans p-2  "
                        type="text" name="title" placeholder="Input Title">
                    </div>

                    <!-- quill desc -->
                    <div class="flex flex-col  justify-start items-start ">
                        <span class="sm:text-xl text-md font-sans mt-1">Content:</span>
                        <div class="h-[20rem] w-full  overflow-hidden">
                            <QuillEditor v-model:content="content" content-type="html" theme="snow"
                                class="w-full h-full border text-lg bg-white" :toolbar="toolbarOptions"
                                placeholder="(required)" />
                        </div>
                    </div>

                 
                    <div class="flex flex-row my-2 items-center justify-center gap-2">
                        <span @click="addReminder" class="font-sans flex justify-center items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Add Reminder </span>

                        <span @click="cancel" class="font-sans flex justify-center items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
                    </div>
                </div>
            </div>
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
import { notify } from 'notiwind';

export default defineComponent({
    name: 'CreateReminder',
    setup(){},

    components:{
        QuillEditor,
        TInnerLoading
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
        addReminder(){
            this.innerLoading('Creating Reminder')

            this.$api.post('/admin/enforcer-room/add-new-reminder',
            {
                title:this.title,
                content:this.content
            })

            .then((response)=>{
                this.clearForm()
                this.closeInnerLoading('Reminder Stored')
                this.$router.push({ name: 'EnforcerReminders' })
                notify({ group: "main", title: "Created Reminder Successfuly", type: "positive" }, "3000")
            })

        },

        innerLoading(text) {
            this.componentOptions.innerLoading.show = true
            this.componentOptions.innerLoading.text = text
        },

        closeInnerLoading() {
            this.componentOptions.innerLoading.show = false
        },

        clearForm() {
            this.title = ''
            this.content = ''
        },

        cancel(){
            this.$router.push({name: 'EnforcerReminders'})
        },
    }
})
</script>
