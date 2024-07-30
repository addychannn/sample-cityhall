<template>
    <Page class="p-5">
        <div class="flex flex-row pt-2">
            <TIcon name="notification_important" size="xl"></TIcon>
            <span class="font-sans font-bold text-5xl">Reminders To Enforcers</span>
        </div>

        <div class="flex flex-row justify-between items-center  w-full">
            <div class="flex flex-col w-[30rem] pt-4">
                <input @input="debounceInput" type="text" id="default-input"
                class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Search Forms">
            </div>
           
           <div>
            <TIcon @click="goToCreatePage" name="add" size="md"
            class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md hover:scale-110 hover:bg-green-700">
        </TIcon>
           </div>
           
        </div>


        <div class="flex flex-col p-4">
            <div class="flex flex-row border-b border-slate-900 justify-start items-start gap-5 p-2">
                <span class="font-sans text-xl font-semibold w-1/4">Title</span>
                <span class="font-sans text-xl font-semibold w-2/4">Content</span>
                <span class="font-sans text-xl text-end font-semibold w-1/4">Action</span>
            </div>
            <div v-for="item in reminders" class="flex flex-row border-t border-slate-900 justify-start items-start gap-5 rounded-xl hover:bg-slate-300 py-2">
                <span class="font-sans text-lg w-1/4 p-2 line-clamp-1 font-semibold">{{item.title}}</span>
                <span class="font-sans text-lg font-light w-2/4 p-2 h-[2rem] line-clamp-1" v-html="item.content"></span>
                <span class="font-sans text-lg font-light w-1/4 p-2 line-clamp-1 text-end">
                    <TIcon @click="goToEditPage(item.id)" name="edit_note" size="md" class="text-blue-600 cursor-pointer hover:scale-125"></TIcon>
                    <TIcon @click="showModal(item.id)" name="delete" size="md" class="text-red-600 cursor-pointer hover:scale-125"></TIcon>
                </span>
            </div>
            <div class="flex flex-col items-center pt-3">
                    <Pagination
                        v-model="searchOptions.page"
                        v-model:limit="searchOptions.limit"
                        :total="searchOptions.total"
                        v-model:offset="searchOptions.offset"
                        @update:totalPage="(count) => {pages=count}"
                        @paginate="getReminders(searchOptions.term)"
                        direction-nav
                        boundary-nav
                    />
                </div> 
        </div>


        <Modal :show="componentOptions.modal.show" @close="closeModal()">
           

            <div class="p-6 text-center">
                <div >
                    <TIcon name="warning" size="xl" class="text-red-600"></TIcon>
                </div>
                
                <h3 class="font-sans text-xl font-normal text-black dark:text-gray-400">Are you sure you want to delete this Reminder?</h3>
                <button @click="deleteReminder()" data-modal-hide="popup-modal" type="button" class="font-sans text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Yes, I'm sure
                </button>
                <button  @click="closeModal()" data-modal-hide="popup-modal" type="button" class="font-sans text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
            </div>
        </Modal>
    
        <TInnerLoading :is-full-screen="true" :text="componentOptions.innerLoading.text"
            :active="componentOptions.innerLoading.show"></TInnerLoading>
     
    </Page>
</template>

<script>
import { defineComponent } from 'vue';
import _ from 'lodash';
import TInnerLoading from '@/components/TInnerLoading.vue';
import { notify } from 'notiwind';

export default defineComponent({
    name: 'EnforcerReminders',
    setup(){},
    
    mounted(){
        this.getReminders('')
    },

    components:{
        TInnerLoading
    },

    data(){
        return{
            componentOptions: {
                modal:{
                    show:false,
                    id:null
                },
                innerLoading: {
                    show: false,
                    text: ''
                }
            },

            reminders:[],

            searchOptions:{
                    term:'',
                    offset:0,
                    limit:5,
                    total:0,
                    page:1,
                    pages:1,
                },
        }

    
    },

    methods:{
        getReminders(term){
            this.$api.get('/public/enforcement/get-reminders',{
                params:{
                    term:term,
                    limit:this.searchOptions.limit,
                    offset:this.searchOptions.offset
                }
            })

            .then((response)=>{
                this.reminders=response.data.data
                this.searchOptions.total = response.data.total
            })
        },

        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.getReminders(this.searchOptions.term)
        },500),

        goToCreatePage(){
            this.$router.push({name:'CreateReminder'})
        },

        goToEditPage(id){
            this.$router.push({name:'EditReminder', params:{id:id}})
        },

        closeModal(){
            this.componentOptions.modal.show = false
        },

        showModal(id){
            this.componentOptions.modal.show=true
            this.componentOptions.modal.id=id
        },

        deleteReminder(){
            this.innerLoading('Deleting Reminder')

            this.$api.delete('/admin/enforcer-room/delete-reminder',
            {
                params:{
                    id:this.componentOptions.modal.id
                }
            })

            .then((response)=>{
                this.closeInnerLoading('Reminder Deleted')
                this.$router.push({ name: 'EnforcerReminders' })
                this.closeModal()
                notify({ group: "main", title: "Deleted Reminder Successfuly", type: "positive" }, "3000")
            })
        },

        innerLoading(text) {
            this.componentOptions.innerLoading.show = true
            this.componentOptions.innerLoading.text = text
        },

        closeInnerLoading() {
            this.componentOptions.innerLoading.show = false
        },
    }
})
</script>
