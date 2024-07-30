<template>
    <Page>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <ConfirmRestore v-if="componentOptions.showModal" @cancel="closeModal" @proceed="restoreMessage"/>
        </Modal>
        <div class="flex flex-col md:py-4">
            <div class="flex-col px-1 md:px-5 lg:px-10 items-center justify-center gap-10 py-1 md:py-10">
                <!--header-->
                <div class="flex pr-2 text-slate-800 p-2">
                    <TIcon name="layers_clear" size="xl" class="pt-1"></TIcon>
                    <span class="font-sans text-4xl md:text-5xl font-semibold flex flex-col items-center justify-center">
                        List of Deleted Messages
                    </span>
                </div>

                <!--search bar-->
                <div class="flex flex-row items-start justify-between ">
                    <div class="flex flex-col w-full md:w-[25rem] pb-3">
                        <input type="text" id="default-input"
                            class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 shadow-sm"
                            placeholder="Search Messages">

                    </div>

                </div>



                <!--message list-->
                <div
                    class="hidden lg:flex w-full bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 ">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody v-for="message in messageList">
                            <tr
                                class="bg-white border-b cursor-pointer dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium font-sans text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ message.firstname }} {{ message.lastname }}
                                </th>


                                <td class="px-6 py-4 font-sans line-clamp-1 h-4">
                                    {{ message.message }}
                                </td>


                                <td class="px-6 py-4 font-sans">
                                    {{ message.created_at }}
                                </td>

                                <td class="px-6 py-4">
                                    <TIcon @click="showMessage(message.hash)" name="visibility" size="sm"
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600 px-1">
                                    </TIcon>
                                    <TIcon @click="showModal(message.hash)" name="restore_from_trash" size="md"
                                        class="cursor-pointer text-green-500 hover:text-green-600 hover:scale-110"></TIcon>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- list sm -->
                <div
                    class="flex flex-col md:hidden w-full bg-white border border-gray-200 rounded-lg shadow p-2 dark:bg-gray-800 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700 gap-y-2">
                    <div class="flex flex-row" v-for="message in messageList">
                        <!--details-->

                        <div class="flex flex-row w-full">

                            <div class="flex flex-row cursor-pointer w-full">
                                <span class="font-sans font-bold text-sm">
                                    {{ message.firstname }} {{ message.lastname }}
                                </span>
                            </div>
                            <div class="flex flex-row w-full">

                                <div class="font-sans line-clamp-2" v-html="message.message">
                                </div>

                            </div>
                            <div>

                                <div class="flex flex-row justify-end">
                                    <TIcon @click="showMessage(message.hash)" name="visibility" size="sm"
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600 px-1">
                                    </TIcon>
                                    <TIcon @click="showModal(message.hash)" name="restore_from_trash" size="md"
                                        class="cursor-pointer text-green-500 hover:text-green-600 hover:scale-110"></TIcon>
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
import ConfirmRestore from './confirmRestore.vue';
import _ from 'lodash';

export default defineComponent({
    name: 'ListOfDeletedMessages',
    setup() { },
    components: {
        ConfirmRestore
    },
    mounted() {
        this.getDeletedMessages('')
    },
    data() {
        return {
            componentOptions: {
                selectedItem: null,
                showModal: false,
                alertMsg: '',
                activeStatus: false
            },
            messageList: null,

            searchOptions: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 5,
                offset: 0,
            },
            selected: null,
        }
    },

    methods: {
        getDeletedMessages(term = '') {
            this.$api.get('admin/message/get-deleted')
                .then((response) => {
                    this.messageList = response.data.data
                   
                })
        },

        showMessage(hash) {
           
            this.$router.push({ name: 'ViewMessages', params: { hash: hash } })
        },
        restoreMessage(){
            this.closeModal()
            this.componentOptions.alertMsg = 'Restoring Message'
            this.componentOptions.activeStatus = true
            this.$api.get(`message-restore/${this.componentOptions.selectedItem}`)
            .then((response)=>{
               
                this.getDeletedMessages('')
            })
        },
        showModal(message=null){
            this.componentOptions.showModal = true
            if(message != null){
                this.componentOptions.selectedItem = message
            }
        },
        closeModal(){
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''
        },
        debounceInput: _.debounce(function(e){
            this.searchOptions.term = e.target.value;
            this.getDeletedMessages(this.searchOptions.term)
        },500)
    }
})
</script>
