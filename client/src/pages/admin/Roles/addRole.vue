<template>
    <Page>
        <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmAddRole v-if="componentOptions.showModal" @proceed="onSubmit" @cancel="closeModal"/>
        </Modal>
        <div class="p-2 md:px-8 md:py-16 ">
            <div class="flex flex-col px-2">
                <div class="block items-center justify-center rounded-3xl  ">
                    <!--Label-->
                    <div class="flex flex-row pb-5 items-center">
                        <TIcon name="add_moderator" size="xl"></TIcon>
                        <span class="font-sans font-bold text-2xl md:text-5xl text-slate-800">Add Role</span>
                    </div>

                   
                        <!--Role Name-->
                        <div class="flex flex-col justify-start items-start">
                            <span class="sm:text-xl text-md font-sans mt-1">Role</span>
                            <input v-model="form.name" class="rounded-md  w-full h-10 font-sans p-2 "
                                type="text" name="title" placeholder="Role name">
                        </div>

                        <!--Role Description-->
                        <div class="flex flex-col w-full pt-4">
                            <span class="sm:text-xl text-md font-sans mt-1">Description</span>
                            <textarea v-model="form.description" name="description" id="" rows="3" class="rounded-md  w-full font-sans p-2" placeholder="Short description of role" maxlength="255"></textarea>
                        </div>

                        <!--Assign Permissions-->
                        <div class="flex flex-col w-full pb-10 pt-4">
                            <span class="sm:text-xl text-md font-sans mt-1">Assign Permissions</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 md:grid-rows-10">
                                <div class="flex flex-row gap-2 font-sans px-2" v-for="permission in permissionList">
                                    <input
                                        type="checkbox"
                                        :value="permission.id"
                                        v-model="form.permissions"
                                        class="cursor-pointer"
                                    >{{ permission.name }}
                                    <TToolTip class="text-center">
                                        <span class="font-sans text-center">{{ permission.description }}</span>
                                    </TToolTip>
                                </div>
                            </div>
                        </div>


                        <!--Submit button-->
                        <div class="flex flex-row justify-center gap-2">
                            <div class="flex flex-col items-center justify-center pt-4">
                                <span @click="showModal"
                                    class="font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer px-2 py-1 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl">Submit</span>
                            </div>
                            <router-link :to="{ name: 'RolesManagement' }">
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
import { notify } from 'notiwind';
import ConfirmAddRole from './confirmAddRole.vue';

export default defineComponent({
    name: 'AddRole',
    setup(){},
    components:{
        ConfirmAddRole
    },
    mounted(){
        this.getPermissions()
    },
    data(){
        return{
            componentOptions: {
                showModal: false,
                activeStatus: false,
                alertMsg:''
            },
            form:{
                name: '',
                description: '',
                permissions: []
            },
            permissionList: null
        }
    },
    methods:{
        getPermissions(){
            this.$api.get('all-permissions')
            .then((response)=>{
                this.permissionList = response.data.data

                this.permissionList.forEach(element => {
                    let a = element.name.replace(/[_-]/g, ' ')
                    element.name = a
                });
            })
        },
        onSubmit(){
            this.closeModal()
            this.componentOptions.alertMsg = 'Creating Role'
            this.componentOptions.activeStatus = true
            this.$api.post('/roles',this.form)
            .then((response)=>{
                this.componentOptions.activeStatus = false
                notify({group:"main", title:response.data.message, type:"positive"},'3000')
                this.$router.push({name:'RolesManagement'})
            })
            .catch(error => {
                // Handle the error and perform a task
                this.componentOptions.activeStatus = false
            });
        },
        showModal(){
            this.componentOptions.showModal = true
        },
        closeModal(){
            this.componentOptions.showModal = false
        }
    }
})
</script>