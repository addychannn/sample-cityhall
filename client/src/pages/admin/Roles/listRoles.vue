<template>
    <Page>
        <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
        <!--Modal-->
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmDeleteRole v-if="componentOptions.showModal" @proceed="deleteRole" @cancel="closeModal"/>
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
                                Roles Management
                            </span>
                        </div>

                    </div>

                    <div class="flex flex-col items-end">
                        <router-link :to="{ name: 'AddRole'}">
                            <TIcon v-if="$guard.can(['roles_add'])" name="add" size="md" class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md hover:scale-110 hover:bg-green-700"></TIcon> 
                        </router-link>

                    </div>
                </div>

                <!--Search bar-->
                <div class="flex flex-col py-2">
                    <div class="inline-flex gap-3">
                        <input type="text" id="default-input"
                            class="font-sans w-full md:w-[25rem] bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Role"
                            @input="debounceInput" >
                    </div>
                </div>

                <!--list-->
                <div
                    class="bg-white border border-gray-200 rounded-lg shadow p-2 md:p-8 dark:bg-gray-800 dark:border-gray-700 w-full">
                    <ul role="list" class="divide-y w-full divide-gray-200 dark:divide-gray-700" v-for="role in roleList">
                        <li
                            class="sm:py-4 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 relative cursor-pointer">
                            <div class="flex items-center space-x-1 md:space-x-4">
                                    <div class="flex flex-col w-full">
                                        <p class="font-sans text-sm font-medium text-gray-900 dark:text-white">
                                            {{role.name}}
                                        </p>
                                        <p class="font-sans text-sm text-gray-500 dark:text-gray-400">
                                            {{role.description}}
                                        </p>
                                    </div>
                                    <!--actions md-up screen-->
                                    <div
                                        class="hidden sm:flex flex-row items-center justify-end text-base font-semibold text-gray-900 dark:text-white gap-2">
                                            <router-link :to="{ name: 'EditRole', params:{hash: role.id}}">
                                                <TIcon v-if="$guard.can(['roles_edit']) && role.name != 'Admin'" name="edit" size="md" class="pt-1 hover:scale-110 hover:text-blue-600 dark:hover:bg-blue-800 text-gray-800"></TIcon>
                                            </router-link>
                                            
                                            <TIcon v-if="$guard.can(['roles_delete']) && !role.protected" @click="showModal(role.id)" name="delete" size="md"
                                                class="pt-1 hover:scale-110 hover:text-red-600 dark:hover:bg-red-800 text-gray-800">
                                            </TIcon>
                                    </div>
                                    <!--actions sm-->
                                    <div class="flex sm:hidden flex-col items-end">
                                        <TIcon v-if="$guard.can(['roles_edit','roles_delete']) && role.name != 'Admin'" @click="openOptions(role.id)" name="more_vert" size="md" class=""></TIcon>
                                        <div v-if="componentOptions.openOptions && componentOptions.selected_role == role.id" class="absolute z-50 top-10 right-2 w-3/5 flex flex-col bg-white shadow-md">
                                            <router-link :to="{name: 'EditRole', params:{hash: role.id}}">
                                                <div v-if="$guard.can(['roles_edit']) && role.name != 'Admin'" class="flex flex-row cursor-pointer hover:bg-gray-400 w-full justify-end pr-1">
                                                    <span class="font-sans">Edit Role</span>
                                                </div>
                                            </router-link>
                                            <div v-if="$guard.can(['roles_delete']) && !role.protected" @click="showModal(role.id)" class="flex flex-row cursor-pointer hover:bg-gray-400 justify-end w-full pr-1">
                                                <span class="font-sans">Delete Role</span>
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
                        v-model="searchOptions.page"
                        v-model:limit="searchOptions.limit"
                        :total="searchOptions.total"
                        v-model:offset="searchOptions.offset"
                        @update:totalPage="(count) => {pages=count}"
                        @paginate="getRoles(searchOptions.term)"
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
import _ from 'lodash'
import { notify } from 'notiwind';
import ConfirmDeleteRole from './confirmDeleteRole.vue';

export default defineComponent({
    name: 'ListRoles',
    setup(){},
    components:{
        ConfirmDeleteRole
    },
    mounted(){
        this.getRoles('')
    },
    data(){
        return{
            componentOptions: {
                showModal: false,
                selected_role: null,
                alertMsg: '',
                activeStatus: false,
                openOptions:false
            },
            searchOptions:{
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 5,
                offset: 0,
            },
            roleList:null
        }
    },
    methods:{
        getRoles(search = ''){
            this.$api.get('/roles',{
                params:{
                    search: search,
                    limit: this.searchOptions.limit,
                    offset: this.searchOptions.offset,
                    orderBy: 'id'
                }
            })
            .then((response)=>{
                this.roleList = response.data.data
                this.searchOptions.total = response.data.count
                if(this.componentOptions.activeStatus == true){
                    this.componentOptions.activeStatus = false
                    notify({group:"main", title:'Role Deleted Successfully!', type:"positive"},'3000')
                }
            })
        },
        deleteRole(){
            this.closeModal()
            this.componentOptions.alertMsg = 'Deleting Role'
            this.componentOptions.activeStatus = true
            this.$api.delete(`/roles/${this.componentOptions.selected_role}`)
            .then((response)=>{
                this.getRoles('')
            })
        },
        showModal(item=null){
            this.componentOptions.showModal = true
            this.componentOptions.selected_role = item
        },
        closeModal(){
            this.componentOptions.showModal = false
        },
        openOptions(role){
            this.componentOptions.selected_role = role
            this.componentOptions.openOptions = !this.componentOptions.openOptions
        },
        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.searchOptions.offset = 0
            this.getRoles(this.searchOptions.term)
        },500)
    }
})
</script>