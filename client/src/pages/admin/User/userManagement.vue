<template>
    <Page>
        <!--Loader-->
        <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
        <!--Modal-->
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <AddUser v-if="componentOptions.mode == 'add-user'" @processing="openLoader" @cancel="closeModal" @done="refreshPage" @failed="closeLoader"/>
            <ConfirmAlert v-if="componentOptions.mode == 'confirm'" @proceed="changeStatus(this.componentOptions.selectedItem)" @cancel="cancelChange"/>
        </Modal>
        
        <div class="flex flex-col p-2 md:p-4">
            <div class="flex-col px-2 md:px-10 items-center justify-center gap-10 py-2 md:py-10">
                <!--Header-->
                <div class="flex flex-row items-center justify-between">
                    <div class="flex flex-row text-slate-800 p-2 items-center">
                        <div class="flex flex-col">
                            <TIcon name="people" size="xl" ></TIcon>
                        </div>

                        <div class="flex flex-col">
                            <span
                                class="font-sans text-2xl md:text-5xl font-semibold flex flex-col items-center justify-center">
                                User Management
                            </span>
                        </div>

                    </div>

                    <div class="flex flex-col items-end">
                        <TIcon v-if="$guard.can(['users_add'])" @click="showModal('add-user')" name="add" size="md" class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md hover:scale-110 hover:bg-green-700"></TIcon> 
                    </div>
                </div>

                <!--Search bar-->
                <div class="flex flex-col py-2">
                    <div class="inline-flex gap-3">
                        <input type="text" id="default-input"
                            class="font-sans w-full md:w-[25rem] bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search User"
                            @input="debounceInput" >
                    </div>
                </div>

                <!-- table type for usermanagement -->
                <div
                    class="bg-white border border-gray-200 rounded-lg shadow p-2 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <ul role="list" class="divide-y w-full divide-gray-200 dark:divide-gray-700" v-for="user in userList">
                        
                            <li
                            class="sm:py-4 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 relative cursor-pointer">
                                <div class="flex items-center space-x-1 md:space-x-4">
                                    <div class="flex flex-col w-full">
                                        <router-link :to="{ name: 'view-user', params: {hash: user.id} }">
                                            <div class="flex flex-row items-center space-x-1 md:space-x-4 w-full">
                                                <div v-if="!user.profile" class="flex-shrink-0">
                                                    <img class="w-8 h-8 rounded-full" src="/src/assets/profile-user.png"
                                                        alt="Neil image">
                                                </div>
                                                <div v-if="user.profile" class="flex-shrink-0">
                                                    <img class="w-8 h-8 rounded-full" :src="user.profile.image ? user.profile.image.thumbnails.small : '/src/assets/profile-user.png'" alt="Avatar">                                                    
                                                </div>
                                                
                                                <div class="flex-col w-full ">
                                                    <p class="font-sans text-sm font-medium text-gray-900 truncate dark:text-white">
                                                        {{user.username}}
                                                    </p>
                                                    <div v-for="role in user.roles" >
                                                        <p class="font-sans text-sm text-gray-500 truncate dark:text-gray-400">
                                                            {{ role.name }}
                                                        </p>
                                                    </div>
                                                    <p class="font-sans text-sm text-gray-500 truncate dark:text-gray-400">
                                                        {{ user.email ?? '(no email)' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                    <!--actions md-up screen-->
                                    <div
                                        class="hidden sm:flex flex-row items-center justify-end text-base font-semibold text-gray-900 dark:text-white gap-2">
                                            <router-link :to="{ name: 'edit-credentials', params:{hash: user.id}}">
                                                <TIcon v-if="$guard.hasRole('Admin') || $guard.hasRole('Moderator') ||($guard.can(['users_edit-account']) && user.username == loggedUser.username)" name="badge" size="md" class="pt-1 hover:scale-110 hover:text-blue-600 dark:hover:bg-blue-800 text-gray-800"></TIcon>
                                            </router-link>
                                            
                                            <router-link :to="{ name: 'edit-profile', params:{hash: user.id} }">
                                                <TIcon v-if="$guard.can(['users_edit-profile']) && user.username == loggedUser.username" name="edit" size="md"
                                                    class="pt-1 hover:scale-110 hover:text-blue-600 dark:hover:bg-blue-800 text-gray-800">
                                                </TIcon>
                                            </router-link>

                                            <router-link :to="{ name: 'set-permission', params:{hash: user.id} }">
                                                <TIcon v-if="$guard.can(['users_edit-permission']) && user.username != loggedUser.username" name="admin_panel_settings" size="md" class="pt-1 hover:scale-110 hover:text-blue-600 dark:hover:bg-blue-800 text-gray-800"></TIcon>
                                            </router-link>

                                            <label class="relative inline-flex items-center cursor-pointer" v-if="$guard.can(['users_change-status']) && user.username != loggedUser.username">
                                                <input type="checkbox" value="" class="sr-only peer" v-model="user.active" @click="showModal('confirm',user.id)">
                                                <div class="w-10 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                            </label>
                                    </div>
                                    <!--actions sm-->
                                    <div class="flex sm:hidden flex-col items-end">
                                        <TIcon v-if="$guard.can(['users_edit-account','users_edit-permission','users_edit-profile','users_change-status'])" @click="openOptions(user.id)" name="more_vert" size="md" class=""></TIcon>
                                        <div v-if="componentOptions.openOptions && selectedUser == user.id" class="absolute z-50 top-10 right-2 w-3/5 flex flex-col bg-white shadow-md">
                                            <router-link :to="{ name: 'edit-credentials', params:{hash: user.id}}">
                                                <div v-if="$guard.hasRole('Admin') || $guard.hasRole('Moderator') ||($guard.can(['users_edit-account']) && user.username == loggedUser.username)" class="flex flex-row cursor-pointer hover:bg-gray-400 w-full justify-end pr-1">
                                                    <span class="font-sans">Edit Credentials</span>
                                                </div>
                                            </router-link>
                                            <router-link :to="{  name: 'edit-profile', params:{hash: user.id} }">
                                                <div v-if="$guard.can(['users_edit-profile']) && user.username != loggedUser.username" class="flex flex-row cursor-pointer hover:bg-gray-400 justify-end w-full pr-1">
                                                    <span class="font-sans">Edit Profile</span>
                                                </div>
                                            </router-link>
                                            <router-link :to="{  name: 'set-permission', params:{hash: user.id} }">
                                                <div v-if="$guard.can(['users_edit-permission']) && user.username != loggedUser.username" class="flex flex-row cursor-pointer hover:bg-gray-400 justify-end w-full pr-1">
                                                    <span class="font-sans">Set Permission</span>
                                                </div>
                                            </router-link>
                                            
                                            <div v-if="$guard.can(['users_change-status']) && user.username != loggedUser.username" @click="changeStatus(user.id)" class="flex flex-row cursor-pointer hover:bg-gray-400 justify-end w-full pr-1">
                                                <span class="font-sans">{{user.active ? 'Deactivate' : 'Activate'}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        
                    </ul>
                </div>
                <!--Pagination-->
                <div class="flex flex-col items-center pt-3">
                    <Pagination
                        v-model="data.page"
                        v-model:limit="data.limit"
                        :total="data.total"
                        v-model:offset="data.offset"
                        @update:totalPage="(count) => {pages=count}"
                        @paginate="getUsers(data.search)"
                        direction-nav
                        boundary-nav
                    />
                </div>  
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import AddUser from './AddUser.vue';
import { notify } from 'notiwind';
import _ from 'lodash'
import ConfirmAlert from './confirmationAlert.vue'

export default defineComponent({
    name: 'UserManagement',
    setup() { },
    components: {
        AddUser,
        ConfirmAlert
    },
    mounted(){
        this.getUsers('')
    },
    data() {
        return {
            componentOptions: {
                showModal: false,
                mode: null,
                selectedItem: null,
                openOptions: false,
                newUser: false,
                alertMsg: '',
                activeStatus: false
            },
            data:{
                page: 1,
                total: 0,
                search: null,
                offset: 0,
                limit_sm: 0,
                limit: 4,
                order: "asc",
                orderBy: "id",
                column: "name",
                role: null,
            },
            userList: null,
            selectedUser: '',
            loggedUser:null
        }
    },
    methods: {
        getUsers(search = ''){
            this.$api.get('/users',{
                params:{
                    search: search,
                    offset: this.data.offset,
                    limit: this.data.limit,
                    order: this.data.order,
                    orderBy: this.data.orderBy,
                    column: this.data.column,
                    role: this.data.role,
                }
            })
            .then((response)=>{
                this.userList = response.data.data
                this.loggedUser = response.data.logged_user
                this.data.total = response.data.count
                if(this.componentOptions.newUser){
                    this.componentOptions.activeStatus = false
                    notify({group:"main", title:'User Created Successfully', type:"positive"},'3000')
                    this.componentOptions.newUser = false
                }

            })
        },
        changeStatus(user){
            this.$api.patch(`/users/disable/${user}`,user)
            .then((response)=>{
                notify({group:"main", title:response.data.message, type:"positive"},'3000')
                this.closeModal()
            })
            this.componentOptions.openOptions = false
            this.getUsers('')
        },
        openLoader(){
            this.componentOptions.alertMsg = 'Creating New User'
            this.componentOptions.activeStatus = true
        },
        closeLoader(){
            this.componentOptions.activeStatus = false
        },
        showModal(mode,item=null) {
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            if(item != null){
                this.componentOptions.selectedItem = item
            }
        },
        closeModal() {
            this.componentOptions.showModal = false
        },
        cancelChange(){
            this.getUsers()
            this.closeModal()
        },
        openOptions(id){
            this.componentOptions.openOptions = !this.componentOptions.openOptions
            this.selectedUser = id
        },
        refreshPage(){
            this.componentOptions.showModal = false
            this.getUsers()
            this.componentOptions.newUser = true
            // notify({group:"main", title:'User Created Successfully', type:"positive"},'3000')
        },
        debounceInput: _.debounce(function(e){
            this.data.search = e.target.value;
            this.data.offset = 0
            this.getUsers(this.data.search)
        },500)
    }
})
</script>
