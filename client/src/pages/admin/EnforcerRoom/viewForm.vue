<template>
    <Page class="p-5 m-5 border border-slate-700">

        <div class="flex flex-col w-full">
            <!-- header -->
            <div class="flex flex-row justify-center border-b border-slate-800 p-2 border-dashed">
                <TIcon name="assignment" size="lg"></TIcon>
                <span class="font-sans text-3xl uppercase">Form Information</span>
            </div>

                <!-- file info -->
                <div  class="flex flex-col p-2 m-3 w-full">
                    <div class="flex flex-row border-b border-slate-800 p-2 mx-5">
                        <span class="font-sans text-md"> Name:</span>
                        <span class="font-sans font-light text-md ml-4">{{form.name}}</span>
                    </div>
                    <div class="flex flex-row border-b border-slate-800 p-2 mx-5">
                        <span class="font-sans text-md"> Type:</span>
                        <span class="font-sans font-light text-md ml-4">{{form.mime}}</span>
                    </div>
                    <div class="flex flex-row border-b border-slate-800 p-2 mx-5">
                        <span class="font-sans text-md"> Size:</span>
                        <span class="font-sans font-light text-md ml-4">{{(form.size/convert).toFixed(2)}} MB</span>
                    </div>
                    
                </div>

            <div class="flex flex-col gap-3 w-full h-full">
            
                <div class="flex flex-row px-10">
                    <span class="font-sans text-sm font-light italic">#Note: PDF Files can Only be Previewed, In case of other document types, the file will automatically be downloaded upon view.</span>
                </div>

                <!-- file preview -->
                <div class="flex flex-col border w-full h-full rounded-xl shadow-md">
                    <span class="w-full text-center font-sans p-2 text-xl">Form Preview</span>
                    <div class="flex mx-10 border justify-center items-center h-full rounded-xl shadow-xl py-2 m-2" id="filePreview">
                        
                    </div>
                </div>

            </div>
        </div>
        

        
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import PDFObject from 'pdfobject';

export default defineComponent({
    name: 'componentName',
    setup(){},
    components:{
        PDFObject
    },
 
    props:{
        hash:{
            Type:String,
            default:''
        }
    },

    mounted(){
        this.getForm()
        //this.getFormPreview(this.hash)
        //this.preview = 'https://www.africau.edu/images/default/sample.pdf'
    },

    data(){
        return{
            componentOptions: {},

            form:[],
            preview:'',
            
            convert:1024*1024,

            options:{
                height: "600px",
                page: '2',
                pdfOpenParams: {
                    view: 'FitV',
                    pagemode: 'thumbs',
                    search: 'lorem ipsum'
                }
            }
        }
    },

    methods:{
        getForm(){
            this.$api.get('/admin/enforcer-room/get-uploaded-form',{
                params:{
                    id:this.hash
                }
            })
            .then((response)=>{
                
                this.form=response.data.form
                this.preview = response.data.form.path
               
                this.embedPDF(this.preview)
            })
        },

        embedPDF(preview){
            PDFObject.embed(preview, "#filePreview", this.options)
        }
    }
})
</script>
