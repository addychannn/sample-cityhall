<template>
    <Page>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <DeleteMessageRecord :hash="hash" v-if="componentOptions.mode == 'delete'" @cancel="closeModal" @done="refreshTable"   />
        </Modal>

        <!-- large screen -->
        <div class="hidden lg:flex flex-col pl-4">
            <div class="p-10">
                
                <div class="p-9 border border-slate-700 rounded-2xl">
                    <div class="flex border-b-2 justify-center items-center border-slate-600 gap-4 m-3 p-3">
                        <TIcon name="account_circle" size="xl" ></TIcon>
                        <span class="text-5xl font-sans font-light uppercase">{{ message.lastname }},  {{ message.firstname }}</span>
                    </div>

                    <div class="flex  justify-between items-center gap-4  p-3">
                        <span v-if="message.email" class="text-xl font-sans font-light">Email: {{ message.email }}</span>
                        <span v-if="!message.email" class="text-xl font-sans font-light">Email: No Email Submitted</span>
                        <span class="text-xl font-sans font-light">Contact Number: {{ message.contact }}</span>
                    </div>

                    <div class="flex flex-col gap-4  p-3">
                        <span class="text-xl font-sans font-light">Message Content:</span>
                        <span class="text-xl font-sans font-light ml-5">{{ message.message }}</span>
                    </div>

                    <div class="flex flex-row justify-between items-center gap-4 p-3">
                        <span class="text-lg font-sans font-light">Sent: {{ message.created_at }}</span>
                        <span v-if="message.deleted_at" class="text-lg font-sans font-light">Deleted: {{ message.deleted_at }}</span>
                        <span v-if="!message.deleted_at" class="text-lg font-sans font-light">Deleted: Message Not Deleted</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- sm screen -->
        <div class="md:hidden flex flex-col">
            <div class="p-2">
                
                <div class=" border w-full border-slate-700 rounded-2xl">
                    <div class="p-2 flex flex-col border-b-2 justify-center items-center border-slate-600 gap-4">
                        <TIcon name="account_circle" size="xl" ></TIcon>
                        <span class="text-5xl font-sans font-light uppercase text-center">{{ message.lastname }},  {{ message.firstname }}</span>
                    </div>

                    <div class="flex flex-col items-start p-2 ">
                        <div class="flex flex-row">
                            <span v-if="message.email" class="text-xl flex flex-row font-sans font-semibold">Email: </span>
                            <span v-if="!message.email" class="text-xl font-sans font-light">No Email Submitted</span>
                            <span v-if="message.email" class="text-xl flex-row font-sans font-light">{{ message.email }}</span>
                        </div>
                       
                        <div class="flex flex-row">
                       
                        <span class="text-xl font-sans flex flex-row font-semibold">Contact Number: </span>
                        <span class="text-xl font-sans flex flex-row font-light">{{ message.contact }}</span>
                       
                       </div>

                      
                       
                    </div>

                    <div class="flex flex-col items-start p-2  ">
                        <span class="text-xl font-sans flex flex-row font-semibold">Message: </span>
                        <span class="text-xl font-sans flex flex-row font-light">{{ message.message}}</span>
                    </div>

                    <div class="flex flex-col items-start p-2  ">
                        <span class="text-xl font-sans flex flex-row font-semibold">Sent: </span>
                        <span class="text-xl font-sans flex flex-row font-light">{{ message.created_at}}</span>
                        <span class="text-xl font-sans flex flex-row font-semibold">Deleted: </span>
                        <span v-if="message.deleted_at" class="text-lg font-sans font-light">{{ message.deleted_at }}</span>
                        <span v-if="!message.deleted_at" class="text-lg font-sans font-light">Message Not Deleted</span>
                    </div>
                </div>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import DeleteMessageRecord from './deleteMessage.vue';

export default defineComponent({
    name: 'ViewMessages',
    setup() { },
    props:{
        hash:{
            type:String,
            default:null
        },
    },

    components: {
        DeleteMessageRecord
    },
    
     mounted() {

       this.getMessage()
       
    },
    data() {
        return {
            componentOptions: {
                showModal: false,
                mode: ''
            },

            message: [],


        }
    },
    methods: {
        getMessage() {
         
              this.$api.get('admin/message/get',{params:{
                hash:this.hash
              }})
              .then((response)=>{
                
                this.message=response.data.message[0]
                this.message.created_at=this.message.created_at
               
              })
        },

        showModal(mode, message=null) {
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            if(message != null){
                this.componentOptions.selectedItem = message
            }
           

        },
        closeModal() {
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''

        },
        refreshTable() {
            this.closeModal();
            //this.getMessage();
            this. $router.push({name:'Messages'})
        },


    }

})
</script>
