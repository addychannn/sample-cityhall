<template>
    <Page>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmCreate v-if="showModal" @proceed="onsubmit" @cancel="closeModal"/>
        </Modal>
        <div class="flex flex-col p-5">
            <!-- header -->
            <div class="flex flex-row gap-3 p-1 m-1 items-center">
                <div class="flex flex-col">
                    <TIcon name="person_add" size="xl"></TIcon>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl md:text-5xl font-sans font-semibold">Add New User</span>
                </div>
            </div>

            <!-- form -->
            <div class="flex flex-col p-2 gap-2">                    
                <div class="flex flex-col w-full">
                    <span class="font-sans">Email</span>
                    <input type="email" class="font-sans border rounded pl-2" v-model="form.email">
                </div>
                <div class="flex flex-col w-full">
                    <span class="font-sans">Username</span>
                    <input type="text" class="font-sans border rounded pl-2" v-model="form.username">
                </div>
                <div class="flex flex-col w-full">
                    <span class="font-sans">Password</span>
                    <input type="password" class="font-sans border rounded pl-2" v-model="form.password">
                </div>
                <div class="flex flex-col w-full">
                    <span class="font-sans">Role/s</span>
                    <div class="flex flex-row gap-2"  v-for="role in roleList">
                        <TRadio :label="role.label" :value="role.value" v-model="form.role"></TRadio>
                    </div>
                </div>
            </div>

            <!--submit button-->
            <div class="flex flex-row gap-2 items-center justify-center">
                <button @click="showModal" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-gray-500 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Create
                </button>
                <button  @click="cancel" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-gray-500 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">Cancel</button>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import ConfirmCreate from './confirmAddUser.vue';

export default defineComponent({
    name: 'AddUser',
    setup() { },
    components:{
        ConfirmCreate
    },
    mounted(){
        this.getRoles()
    },
    emits: [
        'cancel',
        'done',
        'processing',
        'failed'
    ],
    data() {
        return {
            componentOptions: {
                alertMsg: '',
                activeStatus: false,
                showModal: false
            },
            form: {
                username: '',
                email: '',
                password: '',
                role: null
            },
            roleList: [],
        }
    },
    methods:{
        cancel(){
            this.$emit('cancel')
        },
        getRoles() {
            this.$api.get('/users/roles')
            .then((response)=>{
                this.roleList = response.data.data
                this.roleList = this.roleList.map((item)=>{
                    let a = {}
                    a.label = item.name
                    a.value = item.id
                    return a
                })
            })
        },
        onsubmit(){
            this.closeModal()
            this.$emit('processing')
            this.$api.post('/users/create',this.form)
            .then((response)=>{
                this.$emit('done')
            })
            .catch(error => {
                // Handle the error and perform a task
                this.$emit('failed')
            });
        },
        showModal() {
            this.componentOptions.showModal = true
        },
        closeModal() {
            this.componentOptions.showModal = false
        },
    }
})
</script>
