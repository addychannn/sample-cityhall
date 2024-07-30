<template>
    <Page class="p-2 md:px-8 md:py-16 lg:p-16">
        <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmPermissionUpdate @proceed="setPermissions" @cancel="cancelUpdate"/>
        </Modal>
        <!--Label-->
        <div class="flex flex-row items-center pb-10">
            <TIcon name="border_color" size="xl"></TIcon>
            <span class="font-sans font-bold text-2xl md:text-5xl text-slate-800">Set Permissions</span>
        </div>
        <!--user-->
        <div class="flex flex-col pb-5">
            <div class="flex flex-row">
                <span class="font-sans font-bold text-xl">
                    {{ username }}
                </span>
            </div>
            <div class="flex flex-row">
                <span class="font-sans" v-for="item in user_role">
                    {{ item.name }}
                </span>
            </div>
        </div>
        <!--permissions-->
        <div class="flex flex-col pb-5">
            <div class="flex flex-row text-center">
                <span class="font-sans font-bold text-2xl">Permissions</span>
            </div>
            <div class="flex flex-row">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 md:grid-rows-10">   
                    <div class="flex flex-row gap-2 font-sans px-2" v-for="permission in all_permissions">
                        <input
                            type="checkbox"
                            :value="permission.id"
                            v-model="change.selected_permission"
                            class="cursor-pointer"
                            v-bind:disabled="isInList(permission.id)"
                        >{{ permission.name }}
                        <TToolTip class="text-center">
                            <span class="font-sans text-center">{{ permission.description }}</span>
                        </TToolTip>
                    </div>                    
                </div>
            </div>
        </div>
        <!--button-->
        <div class="flex flex-row w-full gap-2">
            <div
                @click="showModal"
                class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl"
            >
                <span class="font-sans">Update</span>
            </div>
            <div
                @click="this.$router.push({ name: 'UserManagement'})"
                class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-red-500 hover:to-red-600 shadow-xl"
            >
                <span class="font-sans">Cancel</span>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { notify } from 'notiwind';
import ConfirmPermissionUpdate from './ConfirmPermissionUpdate.vue'

export default defineComponent({
    name: "SetUserPermissions",
    setup() { },
    components:{
        ConfirmPermissionUpdate
    },
    mounted() {
        this.getUserRole();
    },
    data() {
        return {
            componentOptions: {
                showModal: false,
                alertMsg: '',
                activeStatus:false    
            },
            user_role: [],
            username: null,
            user_id: null,
            all_permissions: null,
            admin_permissions: [],
            change:{
                role_id: [],
                selected_permission: [],
            },
            indirectPermissions: null
        };
    },
    methods: {
        getUserRole() {
            this.$api.get(`/users/${this.$route.params.hash}`)
            .then((response) => {
                this.username = response.data.data.username;
                this.user_role = response.data.data.roles;
                this.user_id = response.data.data.id

                this.user_role.forEach(element => {
                    this.change.role_id.push(element.id)
                });

                this.getPermissions(this.user_id)
            });
        },
        getPermissions(user_id){
            //get all permissions
            this.$api.get('/users/permissions')
            .then((response)=>{
                this.all_permissions = response.data.data

                this.all_permissions.forEach(element => {
                    let a = element.name.replace(/[_-]/g, ' ')
                    element.name = a
                });
            })
            //get permissions of user
            this.$api.get(`/get-permissions/${user_id}`)
            .then((response)=>{
                this.indirectPermissions = response.data.indirect_permission
                if(this.user_role[0].name == 'Admin' && response.data.permissions.length == 0){
                    this.all_permissions.forEach(element => {
                        this.admin_permissions.push(element.id)
                    });
                    this.indirectPermissions = this.admin_permissions
                    this.change.selected_permission = this.admin_permissions
                }
                else{
                    this.change.selected_permission = response.data.permissions
                } 
            })
        },
        isInList(id) {
            if(id && this.indirectPermissions && this.all_permissions){
                return this.indirectPermissions.indexOf(id) !== -1; // Check if the permission is in the indirectPermission array
            }
        },
        setPermissions(){
            this.closeModal()

            this.componentOptions.alertMsg = 'Updating User Permissions'
            this.componentOptions.activeStatus = true
            this.$api.patch(`/users/permissions/${this.user_id}`,{
                params:{
                    roles: this.change.role_id,
                    permissions: this.change.selected_permission
                }
            })
            .then((response)=>{
                this.componentOptions.activeStatus = false
                notify({group:"main", title:response.data.message, type:"positive"},'3000')
            })
        },
        cancelUpdate(){
            this.getUserRole()
            this.closeModal()
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
