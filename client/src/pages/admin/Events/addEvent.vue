<template>
    <Page>
        <div class="p-2 md:px-8 md:py-16 ">
            <div class="flex flex-col px-2 ">
                <div class="block items-center justify-center rounded-3xl  ">
                    <div class="flex flex-row text-slate-800 ">
                        <div class="flex flex-col">
                            <TIcon name="library_add" size="xl"></TIcon>

                        </div>

                        <div class="flex flex-col pb-2">
                            <span class="font-sans  text-5xl font-semibold flex flex-col items-center justify-center">Add
                                Event</span>

                        </div>

                    </div>



                    <!-- add event -->
                    <div class="flex flex-col gap-3 justify-start items-start">
                        <span class="sm:text-xl text-md font-sans mt-1">Title:</span>
                        <input v-model="form.event_title" class="rounded-md  w-full h-10 font-sans p-2  "
                            type="text" name="title" placeholder="Event Title">
                    </div>
                    <!-- date -->
                    <div class="flex flex-col items-center justify-center w-full  pt-4 ">
                        <div class="grid grid-cols-2 md:grid-cols-2 w-full ">
                            <div class="flex flex-col w-full  ">
                                <label class="font-sans flex flex-col items-center justify-center">
                                   Start Date</label>

                                    <VueDatePicker
                                    v-model="form.start_date"
                                    class="pr-4"
                                    placeholder="MM/DD/YYYY"
                                    :minDate="new Date()"
                                    :enableTimePicker="true"
                                    :is24="false"
                                    :clearable="true"
                                    :startTime="{hours:'08',minutes:'00',seconds:'00'}"
                                    required
                                ></VueDatePicker>
                            </div>

                            <div class="flex flex-col w-full">
                                <label class="font-sans flex flex-col items-center justify-center">End
                                    Date</label>

                                    <VueDatePicker
                                    v-model="form.end_date"
                                    class="pl-4"
                                    placeholder="MM/DD/YYYY"
                                    :minDate="form.start_date"
                                    :enableTimePicker="true"
                                    :is24="false"
                                    :clearable="true"
                                    :endTime="{hours:'08',minutes:'00',seconds:'00'}"
                                    required
                                ></VueDatePicker>
                            </div>



                        </div>


                    </div>
                    <!-- quill desc -->
                    <div class="flex flex-col  justify-start items-start  pt-4 ">
                        <span class="sm:text-xl text-md font-sans mt-1">Content:</span>
                        <div class="h-[20rem] w-full  overflow-hidden">
                            <QuillEditor v-model:content="form.description" content-type="html" theme="snow"
                                class="w-full h-full border text-lg bg-white" :toolbar="toolbarOptions"
                                placeholder="(required)" />
                        </div>
                    </div>

                    <!-- add event image -->
                    <div class=" flex flex-col  w-full p-2">
                        <span class="sm:text-xl text-md font-sans mt-1 pt-4">Upload Image/s:</span>
                        <EventFileDrop v-model="files" multiple accept=".jpeg,.png,.jpg,.gif" class="w-full items-center justify-center ">
                        </EventFileDrop>
                    </div>

                    <div class="flex flex-row justify-center gap-2">
                        <div class="flex flex-col items-center justify-center pt-4">
                            <span @click="submit" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
                        </div>
                       
                            <div class="flex flex-col items-center justify-center pt-4">
                                <span @click="cancel" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
                            </div>
                        
        
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
import imageCompression from 'browser-image-compression';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import TFileDrop from '@/components/Forms/TFileDrop.vue';
import EventFileDrop from '@/components/Forms/EventFileDrop.vue';
import TInnerLoading from '@/components/TInnerLoading.vue';
import { notify } from 'notiwind';

export default defineComponent({
    name: 'AddEvent',
    setup() { },
    components: {
        TFileDrop,
        EventFileDrop,
        QuillEditor,
        TInnerLoading
    },
    data() {
        return {
            componentOptions: {
                innerLoading: {
                    show: false,
                    text: ''
                }
            },

            toolbarOptions: [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
                // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                [{ 'color': [] }],          // dropdown with defaults from theme
                [{ 'align': [] }],
                ['clean']                                         // remove formatting button
            ],
            form: {
                event_title: '',
                description: '',
                start_date: '',
                end_date: '',

            },
            files: [],

            date: null,



        }
    },
    methods: {
        async submit() {
            let selected_start_date = new Date(this.form.start_date)
            let new_start_date = new Date(selected_start_date.getTime() + 8 * 60 * 60 * 1000)
            this.form.start_date = new_start_date
            let selected_end_date = new Date(this.form.end_date)
            let new_end_date = new Date(selected_end_date.getTime() + 8 * 60 * 60 * 1000)
            this.form.end_date = new_end_date

            this.innerLoading('Adding Event')
            let token = null
            await this.$api.post('/events/create', this.form)
                .then(async(response) => {
                    token = response.data.event.hash
                    await this.uploadImage(token, this.files)
                    this.clearForm()
                    this.closeInnerLoading('Adding Event')
                    this.$router.push({ name: 'Events' })
                    notify({ group: "main", title: "Event Added", type: "positive" }, "3000")
                })
                .catch(error => {
                    // Handle the error and perform a task
                    this.closeInnerLoading()
                });
        },
        async uploadImage(hash, files) {
            for (let i = 0; i < files.length; i++) {
                let formData = await this.compressAndPrepImage(hash, files[i])
                await this.$api.post('/events/create/photo', formData)
            }


        },

        async compressAndPrepImage(hash, file) {
            // Create a form data
            let form = new FormData()
            // Compress Each file in the files variable then append
            // form details.
            let image = await imageCompression(file.file, {
                maxSizeMB: 2,
            })
            form.append('file', image)
            // for identification
            form.append('uuid', file.id)
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

        cancel(){
            this.$router.push({name: 'Events'})
        },

        // refreshTable() {
        //     this.getEvents();
        // },

        clearForm() {
            this.form.event_title = ''
            this.form.description = ''
            this.images = ''
        },

        innerLoading(text) {
            this.componentOptions.innerLoading.show = true
            this.componentOptions.innerLoading.text = text
        },

        closeInnerLoading() {
            this.componentOptions.innerLoading.show = false
        },


    }
})
</script>
