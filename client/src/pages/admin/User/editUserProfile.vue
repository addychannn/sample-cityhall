<template>
    <Page>
        <TInnerLoading :text="componentOptions.alertMsg" :isFullScreen="true" :active="componentOptions.activeStatus"></TInnerLoading>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <EditAddress v-if="componentOptions.mode == 'edit'" :address="componentOptions.selectedItem" @cancel="closeModal" @done="editDone" @processing="showLoader('Updating Address')" @failed="failedEdit"/>
            <DeleteAddress v-if="componentOptions.mode == 'delete'" :address="componentOptions.selectedItem" @cancel="closeModal" @done="deleteDone" @processing="showLoader('Deleting Address')"/>
            <UploadAvatar v-if="componentOptions.mode == 'upload-avatar'" :user="componentOptions.selectedItem" @cancel="closeModal" @done="uploadPhotoDone" @processing="showLoader('Uploading photo')"/>
            <ConfirmAlert v-if="componentOptions.mode == 'confirm'" @proceed="onSubmit" @cancel="closeModal"/>
        </Modal> 
        <div class="flex flex-col p-2 md:px-8 md:py-16 lg:px-10 lg:py-10">
            <div class="flex-col px-2 items-center justify-center gap-10 py-2">
                <!--label-->
                <div class="flex flex-row items-center text-slate-800 p-2 w-full">
                    <div class="flex flex-col">
                        <TIcon name="people" size="xl"></TIcon>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="font-sans text-3xl md:text-5xl font-semibold flex flex-col items-center justify-center">Edit
                            Profile</span>
                    </div>
                </div>

                <!--form-->
                <div class="flex flex-col lg:flex-row w-full">
                    <!--avatar-->
                    <div class="flex flex-col items-center p-2 md:p-8 lg:p-12 gap-5">
                        <div v-if="mainAvatar" class="flex flex-col items-center justify-center h-40 w-40 md:w-80 md:h-80 relative">
                            <img :src="mainAvatar.thumbnails.medium" class="w-full h-full rounded-full object-cover cursor-pointer hover:brightness-50" @click="showImages"/>
                            <div @click="showModal('upload-avatar',form.id)" class="absolute z-10 bottom-2 right-2 md:bottom-6 md:right-6 flex flex-col justify-center bg-slate-400 rounded-full p-2 cursor-pointer hover:scale-110 hover:bg-slate-500">
                                <TIcon name="photo_camera" size="md"></TIcon>
                            </div>
                        </div>
                        
                        <div v-else class="flex flex-col items-center justify-center h-40 w-40 md:w-80 md:h-80 relative">
                            <img :src="'/src/assets/profile-user.png'" class=" w-full h-full rounded-full object-cover"/>
                            <div @click="showModal('upload-avatar',form.id)" class="absolute z-10 bottom-2 right-2 md:bottom-6 md:right-6 flex flex-col justify-center bg-slate-400 rounded-full p-2 cursor-pointer hover:scale-110 hover:bg-slate-500">
                                <TIcon name="photo_camera" size="md"></TIcon>
                            </div>
                        </div>

                        <!--user-->
                        <div class="flex flex-col py-2 items-center">
                            <span class="font-sans font-bold text-xl">{{ username }}</span>
                            <template v-for="role in roles">
                                <span class="font-sans">{{ role.name }}</span>
                            </template>
                            
                        </div>
                    </div>

                    <!--form-->
                    <div class="flex flex-col justify-center p-2 md:p-8 lg:p-0 xl:p-12">
                        <!--name-->
                        <div class="flex flex-col md:flex-row gap-2">

                            <div class="flex flex-col w-full mb-6">
                                <label for="firstname"
                                    class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                    name</label>
                                <input type="text" id=""
                                    class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 rounded-md"
                                    placeholder="(required)"
                                    v-model="form.first_name" 
                                    required>
                            </div>

                            <div class="flex flex-col w-full mb-6">
                                <label for="middlename"
                                    class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">Middle
                                    name</label>
                                <input type="text" id=""
                                    class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 rounded-md"
                                    v-model="form.middle_name"
                                    >
                            </div>

                            <div class="flex flex-col w-full mb-6">
                                <label for="lastname"
                                    class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                    name</label>
                                <input type="text" id=""
                                    class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 rounded-md"
                                    placeholder="(required)" 
                                    required
                                    v-model="form.last_name"
                                    >
                            </div>

                            <div class="mb-6">
                                <label for="suffix"
                                    class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">Suffix</label>
                                <input type="text" id=""
                                    class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 rounded-md"
                                    v-model="form.suffix"
                                    >
                            </div>
                        </div>

                        <!--nickname, gender, birthdate-->
                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="flex flex-col w-full mb-6 ">
                                <label for="nickname"
                                    class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">Nickname</label>
                                <input type="text" id=""
                                    class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 rounded-md"
                                    v-model="form.nickname"
                                >
                            </div>
                            <div class="flex flex-col w-full mb-6 ">
                                <label for="gender"
                                    class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender
                                </label>
                                <TSelect  v-model="selected_gender" :options="genderList ?? [{label: 'Awaiting data', value: 'hmmmmmm'}]" class="font-sans text-sm"/>
                            </div>
                            <div class="flex flex-col w-full mb-6 ">
                                <label for="birthday"
                                    class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">BirthDate
                                </label>
                                <VueDatePicker
                                    class="absolute"
                                    v-model="form.birthdate"
                                    placeholder="YYYY/MM/DD"
                                    :maxDate="new Date()"
                                    :enableTimePicker="false"
                                    format="yyyy-MM-dd"
                                ></VueDatePicker>
                            </div>
                        </div>

                        <!--Address-->
                        <div class="flex flex-col border-solid-black border-2 p-2 rounded-md">
                            <!--Addresses List -->
                            <div class="flex flex-col mb-6">
                                <label for="old_address"
                                        class="block font-sans text-sm font-medium text-gray-900 dark:text-white">Addresses:
                                </label>
                                <div v-if="addresses.length != 0" v-for="address in addresses" class="flex flex-row items-center w-full h-full">
                                    <div class="flex flex-row items-center w-full">
                                        <div class="flex flex-col">
                                            <TRadio v-model="form.isMain" :value="address.id" @click="setAsMain"/>
                                            <TToolTip  class="text-center">
                                                <span class="font-sans text-center">Set as Main Residence</span>
                                            </TToolTip>
                                        </div>
                                        
                                        <span class="font-sans text-sm font-light pl-2">{{ address.full }} </span>
                                    </div>
                                    <div class="flex flex-row justify-end">
                                        <TIcon @click="showModal('edit',address)" name="edit" size="sm" class="text-blue-500 cursor-pointer hover:text-blue-600 hover:scale-110"></TIcon>
                                        <TIcon @click="showModal('delete',address)" name="delete" size="sm" class="text-red-500 cursor-pointer hover:text-red-600 hover:scale-110"></TIcon>
                                    </div>
                                </div>
                                <div v-else class="flex flex-col w-full h-full text-center">
                                    <span class="font-sans text-sm text-slate-600">(No Added Address)</span>
                                </div>
                                
                            </div>
                            <!--Add New Address-->
                            <div class="flex flex-col gap-2">
                                <label for="new_address"
                                        class="block font-sans text-sm font-medium text-gray-900 dark:text-white">Add New Address:
                                </label>
                                <div class="flex flex-col md:flex-row w-full gap-2">
                                    <TSelect v-model="city" :options="cityList ?? [{label: 'Awaiting data', value: 'hmmmmmm'}]" class="font-sans w-full text-sm"/>
                                    <TSelect  v-model="barangay" :options="barangayList ?? [{label: 'Select a city first', value: 'hmmmmmm'}]" class="font-sans w-full text-sm"/>
                                    <input type="text" id=""
                                        class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="House No. and Street"
                                        v-model="form.location"
                                    >
                                </div>
                            </div>
                        </div>

                        <!--Actions-->
                        <div class="flex flex-col items-end justify-end">
                            <div class="flex flex-col items-end justify-end p-2 w-[15rem]">
                                    <div
                                        class="font-sans flex flex-col items-center justify-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-md  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl"
                                        @click="showModal('confirm')">
    
                                        <span class="font-sans">Save Changes
                                        </span>
                                    </div>
                            </div>
                        </div>                   
                    </div>
                </div>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import { notify } from 'notiwind';
import EditAddress from './EditAddress.vue';
import DeleteAddress from './DeleteAddress.vue';
import UploadAvatar from './UploadAvatar.vue';
import ConfirmAlert from './confirmationAlert.vue';

export default defineComponent({
    name: 'EditUserProfile',
    setup() { },
    components:{
        EditAddress,
        DeleteAddress,
        UploadAvatar,
        ConfirmAlert
    },
    mounted(){
        this.getUser()
        this.getGender()
        this.getCity()
    },
    data() {
        return {
            componentOptions: {
                showModal: false,
                selectedItem: null,
                mode:'',
                alertMsg: '',
                activeStatus: false,
                changedImage: false
            },
            form:{
                id: '',
                first_name: '',
                middle_name: '',
                last_name: '',
                suffix: '',
                nickname: '',
                gender: '', //gender id
                birthdate: '',
                location: '',
                barangay_code: '',
                isMain: null
            },
            addresses: [], //all address of user
            selected_gender:{
                label: 'Select Gender (required)', 
                value: '', 
                disabled: true
            },
            city:{
                label: 'Select a City',
                value: '',
                disabled: true
            },
            barangay:{
                label: 'Select a Barangay',
                value: '',
                disabled: true
            },
            userDetails: null,
            username:null,
            roles:null,
            genderList: null,
            regionList: null,
            provinceList: null,
            cityList: null,
            barangayList: null,
            alertMessage: '',
            mainAvatar: null,
            avatarList: null,
        }
    },
    watch:{
        city(newValue){
            if(newValue.value != ''){
                this.getBarangay(newValue)
            }
        }
    },
    methods:{
        async getUser(){
            this.form.id = this.$route.params.hash
            this.$api.get(`/users/${this.$route.params.hash}`)
            .then((response)=>{
                this.userDetails = response.data.data
                if(this.userDetails.profile){
                    this.form.first_name = this.userDetails.profile.first_name
                    this.form.middle_name = this.userDetails.profile.middle_name
                    this.form.last_name = this.userDetails.profile.last_name
                    this.form.suffix = this.userDetails.profile.suffix
                    this.form.nickname = this.userDetails.profile.nickname
                    this.username = this.userDetails.username
                    this.roles = this.userDetails.roles
                    if(this.userDetails.profile.gender){
                        this.selected_gender.value = this.userDetails.profile.gender.id
                        this.selected_gender.label = this.userDetails.profile.gender.name
                    }
                    
                    this.form.birthdate = this.userDetails.profile.birthdate
                    this.addresses = this.userDetails.profile.addresses
                    this.mainAvatar = this.userDetails.profile.image
                }

                
                if(response.data.main_address.length != 0){
                    this.form.isMain = response.data.main_address[0].id
                }
                if(this.alertMessage){
                    this.componentOptions.activeStatus = false
                    notify({group:"main", title:this.alertMessage, type:"positive"},'3000')
                }
                this.alertMessage = ''
            })
        },
        getGender(){
            this.$api.get('/users/gender')
            .then((response)=>{                
                this.genderList = response.data.data
                this.genderList = this.genderList.map((item)=>{
                    let a = {}
                    a.label = item.name
                    a.value = item.id
                    return a
                })
            })
        },
        getCity(){
            this.$api.get(`/address/cities/${141100000}`)
            .then((response)=>{
                this.cityList = response.data
                this.cityList = this.cityList.map((item)=>{
                    let a = {}
                    a.label = item.name
                    a.value = item.code
                    return(a)
                })
            })
        },
        getBarangay(selected){
            this.$api.get(`/address/barangays/${selected.value}`)
            .then((response)=>{
                this.barangayList = response.data
                this.barangayList = this.barangayList.map((item)=>{
                    let a = {}
                    a.label = item.name
                    a.value = item.code
                    return(a)
                })
            })
        },
        onSubmit(){
            this.componentOptions.alertMsg = 'Updating Credentials'
            this.componentOptions.activeStatus = true
            this.form.gender = this.selected_gender.value
            this.form.barangay_code = this.barangay.value
            this.$api.post(`/users/edit-profile/${this.userDetails.id}`,this.form)
            .then((response)=>{
                this.componentOptions.activeStatus = false
                notify({group:"main", title:"Profile Updated Successfully", type:"positive"},'3000')
                this.refreshPage()
            })
            .catch(error => {
                // Handle the error and perform a task
                this.closeModal()
                this.componentOptions.activeStatus = false
            });
        },
        setAsMain(){
            this.componentOptions.alertMsg = 'Setting to main address'
            this.componentOptions.activeStatus = true
            this.$api.patch(`/users/main-address/${this.userDetails.id}`,this.form)
            .then((response)=>{
                this.componentOptions.activeStatus = false
                notify({group:"main", title:response.data.message, type:"positive"},'5000')
            })
            this.refreshPage()
        },
        showImages(){
            this.$router.push({name: 'manage-images', params:{hash: this.form.id}})
        },
        showModal(mode, item=null){
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            if(item!=null){
                this.componentOptions.selectedItem = item
            }
        },
        async closeModal(){
            this.componentOptions.showModal = false
        },
        refreshPage(){
            this.closeModal()
            this.getUser()
            this.getGender()
            this.getCity()
            this.form.location = ''
            this.barangay = {
                label: 'Select a Barangay',
                value: '',
                disabled: true
            }
            this.city = {
                label: 'Select a City',
                value: '',
                disabled: true
            }
        },
        async showLoader(message){
            // await this.closeModal()
            this.componentOptions.alertMsg = message
            this.componentOptions.activeStatus = true
        },
        failedEdit(){
            this.componentOptions.activeStatus = false
        },
        editDone(){
            this.refreshPage()
            this.alertMessage = 'Updated Address Successfully'
        },
        deleteDone(){
            this.refreshPage()
            this.alertMessage = 'Deleted Address Successfully'
        },
        async uploadPhotoDone(){
            await this.closeModal()
            await this.getUser()
            this.alertMessage = 'Avatar Updated Successfully'
        }
    }
})
</script>
