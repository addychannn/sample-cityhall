<template>
    <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus">
    </TInnerLoading>
    <Page>
        <div class="p-2 md:px-8 md:py-16 ">
            <div class="flex flex-col px-2">
                <div class="block items-center justify-center rounded-3xl  ">
                    <!--Label-->
                    <div class="flex flex-row pb-5 items-center">
                        <TIcon name="border_color" size="xl"></TIcon>
                        <span class="font-sans font-bold text-2xl md:text-5xl text-slate-800">Create Testimony</span>
                    </div>

                   
                        <!--Testimony Title-->
                        <div class="flex flex-col gap-3 justify-start items-start">
                            <span class="sm:text-xl text-md font-sans mt-1">Title:</span>
                            <input v-model="form.title" class="rounded-md  w-full h-10 font-sans p-2 "
                                type="text" name="title" placeholder="Testimony Title">
                        </div>

                        <!--Testimony-->
                        <div class="flex flex-col  justify-start items-start pt-4 ">
                            <span class="sm:text-xl text-md font-sans mt-1">Content:</span>
                            <div class="h-[20rem] w-full  overflow-hidden">
                                <QuillEditor v-model:content="form.body" content-type="html" theme="snow"
                                    class="w-full h-full border text-lg bg-white" :toolbar="toolbarOptions"
                                    placeholder="(required)" required/>
                            </div>
                        </div>

                        <!--Image Upload-->
                        <div class="flex flex-col w-full  pt-4 ">
                            <label for="TestimonyImage" class="font-sans text-start sm:text-xl text-md mt-1">Upload Image/s</label>
                            <TFileDrop v-model="files" multiple accept=".jpeg,.png,.jpg,.gif"
                                class="p-2 text-xs md:p-10 md:text-sm"></TFileDrop>
                        </div>

                        <!--Submit button-->
                        <div class="flex flex-row justify-center gap-2">
                            <div class="flex flex-col items-center justify-center pt-4">
                                <span @click="onSubmit"
                                    class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
                            </div>
                            <router-link :to="{ name: 'AdminTestimonials' }">
                                <div class="flex flex-col items-center justify-center pt-4">
                                    <span
                                        class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
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
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import imageCompression from 'browser-image-compression';
import { notify } from 'notiwind';
import TInnerLoading from '@/components/TInnerLoading.vue';

export default defineComponent({
    name: 'CreateTestimony',
    setup() { },
    components: {
        QuillEditor,
        TInnerLoading
    },
    data() {
        return {
            componentOptions: {
                activeStatus: false,
                alertMsg: ''
            },
            form: {
                title: '',
                body: ''
            },
            files: [],
            toolbarOptions: [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
                // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                [{ 'color': [] }],          // dropdown with defaults from theme
                [{ 'align': [] }],
                ['clean']                                         // remove formatting button
            ]
        }
    },
    methods: {
        async onSubmit() {
            let token = null
            await this.$api.post('/testimony/create', this.form)
                .then((response) => {
                    token = response.data.testimony.hash
                    // notify({group:"main", title:"Creating testimony", type:"positive"},'3000')
                    this.componentOptions.alertMsg = "Creating Testimony"
                    this.componentOptions.activeStatus = true
                })
            await this.uploadImage(token, this.files)
            this.componentOptions.activeStatus = false
            notify({ group: "main", title: "Testimony created successfully", type: "positive" }, '3000')
            this.$router.push({ name: 'AdminTestimonials' })
            this.clearFields()
        },
        async uploadImage(hash, files) {
            for (let i = 0; i < files.length; i++) {
                let formData = await this.compressAndPrepImage(hash, files[i])
                await this.$api.post('/testimony/create-photo', formData)
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
        clearFields() {
            this.form.title = '',
                this.form.body = '',
                this.files = ''
        }
    }
})
</script>
