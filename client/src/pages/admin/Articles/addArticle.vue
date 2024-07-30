<template>
    <Page>
        <div class="p-2 md:px-8 md:py-16 ">
            <div class="flex flex-col px-2 ">
                <div class="block items-center justify-center rounded-3xl  ">
                    <div class="flex flex-row text-slate-800 ">
                        <div class="flex flex-col">
                            <TIcon name="newspaper" size="xl"></TIcon>

                        </div>

                        <div class="flex flex-col pb-2">
                            <span class="font-sans  text-5xl font-semibold flex flex-col items-center justify-center">Add
                                Article</span>

                        </div>

                    </div>

                    <!-- add article -->
                    <div class="flex flex-col gap-3 justify-start items-start">
                        <span class="sm:text-xl text-md font-sans mt-1">Title:</span>
                        <input v-model="form.articleTitle" class="rounded-md  w-full h-10 font-sans p-2 "
                            type="text" name="title" placeholder="Article Title">
                    </div>

                    <!-- quill desc -->
                    <div class="flex flex-col  justify-start items-start pt-4 ">
                        <span class="sm:text-xl text-md font-sans mt-1">Content:</span>
                        <div class="h-[20rem] w-full  overflow-hidden">
                            <QuillEditor v-model:content="form.description" content-type="html" theme="snow"
                                class="w-full h-full border text-lg bg-white" :toolbar="toolbarOptions"
                                placeholder="(required)" />
                        </div>
                    </div>

                    <!-- add event image -->
                    <div class=" flex flex-col w-full p-2">
                        <span class="sm:text-xl text-md font-sans mt-1">Upload Image/s:</span>
                        <ArticleFileDrop v-model="files" multiple accept=".jpeg,.png,.jpg,.gif" class="w-full  items-center justify-center ">
                        </ArticleFileDrop>
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
import TFileDrop from '@/components/Forms/TFileDrop.vue';
import ArticleFileDrop from '@/components/Forms/ArticleFileDrop.vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { notify } from 'notiwind';
import TInnerLoading from '@/components/TInnerLoading.vue';

export default defineComponent({
    name: 'AddArticle',
    setup() { },
    components: {
        TFileDrop,
        QuillEditor,
        ArticleFileDrop,
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
                ['clean']
            ],
            form: {
                articleTitle: '',
                description: '',

            },
            files: []



        }
    },
    methods: {
        async submit() {
            this.innerLoading('Adding Article')
            let token = null
            await this.$api.post('admin/articles/AddArticle/create', this.form)
            .then(async(response) => {
                token = response.data.article.hash
                await this.uploadImage(token, this.files)
                this.clearForm()
                this.closeInnerLoading('Adding Articles')
                this.$router.push({ name: 'Articles' })
                notify({ group: "main", title: "Article Added", type: "positive" }, "3000")
            })
            .catch(error => {
               
                this.closeInnerLoading()
            });
            

        },
        async uploadImage(hash, files) {
          for (let i = 0; i < files.length; i++) {

                let formData = await this.compressAndPrepImage(hash, files[i])
                await this.$api.post('/articles/AddArticle/create/photo', formData)

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
            this.$router.push({name: 'Articles'})
        },

       
        clearForm() {
            this.form.articleTitle = ''
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
