<template>
    <Page class="p-2 md:px-8 md:py-16 lg:p-16">
        <!--Modal-->
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmAlert v-if="componentOptions.showModal" @proceed="onSubmit" @cancel="closeModal"/>
        </Modal>
        <!--Label-->
        <div class="flex flex-row pb-5 items-center">
            <TIcon name="border_color" size="xl"></TIcon>
            <span class="font-sans font-bold text-2xl md:text-5xl text-slate-800">Edit Credentials</span>
        </div>

        <div class="flex flex-col px-2 lg:px-5 w-full justify-center items-center">
            <!--Username-->
            <div class="flex flex-col pb-2 w-full lg:w-[50rem]">
                <label for="TestimonyTitle" class="font-sans text-center">Username</label>
                <input type="text" class="font-sans pl-1 md:pl-2 w-full border" placeholder="(required)" v-model="form.username">
            </div>

            <!--Email-->
            <div class="flex flex-col pb-2 w-full lg:w-[50rem]">
                <label for="TestimonyContent" class="font-sans text-center">Email</label>
                <input type="email" class="font-sans pl-1 md:pl-2 w-full border" placeholder="(optional)" v-model="form.email">
            </div>
            
            <!--Password-->
            <div class="flex flex-col pb-2 w-full lg:w-[50rem]">
                <label for="TestimonyContent" class="font-sans text-center">Password</label>
                <input type="password" class="font-sans pl-1 md:pl-2 w-full border" placeholder="(input new password)" v-model="form.password">
            </div>

            <!--Submit button-->
            <div class="flex flex-row justify-center gap-2">
                <div class="flex flex-col items-center justify-center pt-4">
                    <span @click="showModal" class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
                </div>
                <div @click="cancel" class="flex flex-col items-center justify-center pt-4">
                    <span class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Cancel</span>
                </div>

            </div>

        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { notify } from 'notiwind';
import ConfirmAlert from './confirmationAlert.vue'

export default defineComponent({
    name: 'EditUserCredentials',
    setup(){},
    components:{
        ConfirmAlert
    },
    mounted(){
        this.getCredentials()
    },
    data(){
        return{
            componentOptions: {
                showModal: false
            },
            form:{
                username:'',
                email: '',
                password: ''
            },
            userDetails: null
        }
    },
    methods:{
        getCredentials(){
            this.$api.get(`/users/${this.$route.params.hash}`)
            .then((response)=>{
                this.userDetails = response.data.data
                this.form.username = this.userDetails.username
                this.form.email = this.userDetails.email
            })
        },
        onSubmit(){
            let changes = this.getChanges(this.form)
            this.closeModal()
            this.$api.patch(`/users/account/${this.userDetails.id}`,changes)

            let new_passsword = this.getNewPassword(this.form)
            if(this.form.password != ''){
                this.$api.patch(`/users/password/${this.userDetails.id}`,new_passsword)
                .then((response)=>{
                    notify({group:"main", title:"Credentials Updated", type:"positive"},'3000')
                    this.$router.push({ name: 'UserManagement'})
                })
            }
            

        },
        getChanges(form){
            let changes = {}
            changes['id'] = this.userDetails.id
            if(form.username != this.userDetails.username){
                changes['username'] = form.username
            }
            else{
                changes['username'] = form.username
            }
            if(form.email != this.userDetails.email){
                changes['email'] = form.email
            }
            else{
                changes['email'] = form.email
            }
            return changes
        },
        getNewPassword(form){
            let changes = {}
            changes['id'] = this.userDetails.id
            changes['password'] = form.password
            changes['notify'] = true
            return changes
        },
        cancel(){
            this.$router.push({ name: 'UserManagement'})
        },
        showModal(){
            this.componentOptions.showModal = true
        },
        closeModal(){
            this.componentOptions.showModal = false
        },
    }
})
</script>
