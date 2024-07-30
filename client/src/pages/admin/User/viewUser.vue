<template>
    <Page>
        <div class="flex flex-col p-2 md:px-8 md:py-16 lg:px-10 lg:py-10">
            <div class="flex-col px-2  items-center justify-center gap-10 py-2 ">
                <!--content-->
                <div class="flex flex-col lg:flex-row  w-full">
                    <!--avatar-->
                    <div class="flex flex-col items-center p-2 md:p-8 lg:p-12 gap-5">
                        <div @click="viewImage(id)" v-if="mainAvatar.length != 0" class="flex flex-col h-40 w-40 md:w-80 md:h-80 cursor-pointer hover:brightness-50">
                            <img :src="mainAvatar" class="w-full h-full rounded-full" >
                        </div>
                        <div v-else class="flex flex-col h-40 w-40 md:w-80 md:h-80">
                            <img src="/src/assets/profile-user.png" class="w-full h-full rounded-full" >
                        </div>    
                    </div>
                    <!--user details-->
                    <div class="flex flex-col w-full pt-2 md:pt-8 lg:pt-12 gap-12">
                        <div class="flex flex-col text-center lg:text-start">
                            <span class="font-sans text-3xl " :class="item.name ? 'font-semibold uppercase' : 'font-semibold text-slate-600'">{{ item.name ? item.name : '(Name not provided)' }}</span>
                            <template v-for="role in roles">
                                <span class="font-sans text-md font-semibold  text-slate-600">{{ role.name }}</span>
                            </template>
                            
                            <span class="font-sans text-md font-semibold text-slate-600"> {{ item.email ? item.email : '(Email not provided)'}}</span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 w-full text-center lg:text-start gap-2">
                            <div class="flex flex-col w-full">
                                <span class="font-sans text-lg" :class="item.username ? 'font-semibold' : 'font-semibold text-slate-600 text-sm'">{{ item.username ? item.username : '(not provided)'}}</span>
                                <span class="font-sans text-md font-semibold text-slate-600">Username</span>
                            </div>
                            <div class="flex flex-col w-full">
                                <span class="font-sans text-lg font-semibold" :class="item.nickname ? 'font-semibold' : 'font-semibold text-slate-600 text-sm'"> {{ item.nickname ? item.nickname : '(not provided)' }}</span>
                                <span class="font-sans text-md font-semibold text-slate-600 text-sm">Nickname</span>
                            </div>
                            <div class="flex flex-col w-full">
                                <span class="font-sans text-lg font-semibold flex flex-col" :class="genders ? 'font-semibold' : 'font-semibold text-slate-600 text-sm'">{{ genders ? genders.name : '(not provided)' }}</span>
                                
                                <span class="font-sans text-md font-semibold text-slate-600">Gender</span>
                            </div>
                            <div class="flex flex-col w-full">
                                <span class="font-sans text-lg font-semibold" :class="item.birthdate ? 'font-semibold' : 'font-semibold text-slate-600 text-sm'">{{ item.birthdate ? item.birthdate : '(not provided)'}}</span>
                                <span class="font-sans text-md font-semibold text-slate-600">Birthdate</span>
                            </div>
                        </div>
                        <div class="flex flex-col w-full border-solid border-slate-600 border-2 rounded-md p-2" >
                            <label for="old_address"
                                class="block font-sans text-lg font-medium text-slate-600 dark:text-white">Addresses
                            </label>
                            <div v-if="address_length != 0" class="flex flex-row gap-2" v-for="address in addressList">                             
                                <span class="font-sans text-base flex-row pl-6">
                                    {{ address.full}}
                                    <span v-if="item.is_main && item.is_main.id == address.id" class="font-sans text-sm bg-green-300 rounded-lg text-center shadow-md w-10 p-0.5">main</span>
                                </span>                         
                            </div>
                            <div v-else class="flex flex-col">
                                <span class="font-sans text-base pl-6 text-center text-slate-600">(No added addresses)</span>                                
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

export default defineComponent({
    name: 'ViewUser',
    setup(){},
    mounted(){
        this.getUser()
    },
    data(){
        return{
            componentOptions: {},
            item:{
                name: '',
                email: '',
                username: '',
                nickname: '',
                birthdate: '',
                is_main: ''
            },
            userDetails: null,
            profileDetails: null,
            roles: [],
            genders: [],
            mainAvatar: [],
            addressList: null,
            address_length: 0,
            id: '',
        }
    },
    methods:{
        getUser(){
            this.$api.get(`/users/${this.$route.params.hash}`)
            .then((response)=>{
                this.userDetails = response.data.data
                this.id = this.userDetails.id
                this.roles = this.userDetails.roles
                this.item.email = this.userDetails.email
                this.item.username = this.userDetails.username

                if(this.userDetails.profile){
                    this.profileDetails = this.userDetails.profile
                    this.item.name = this.profileDetails.full_name
                    this.item.nickname = this.profileDetails.nickname
                    this.genders = this.profileDetails.gender
                    this.item.birthdate = this.profileDetails.birthdate
                    this.addressList = this.profileDetails.addresses
                    this.address_length = this.addressList.length
                    if(this.profileDetails.image){
                        this.mainAvatar = this.profileDetails.image.thumbnails.medium
                    }
                }
                
                if(response.data.main_address){
                    this.item.is_main = response.data.main_address[0]
                }
            })
        },
        viewImage(hash){
            this.$router.push({ name: 'view-images', params: { hash: hash } })
        }
    }
})
</script>
