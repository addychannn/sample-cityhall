<template>
    <Page>
        <Modal :show="componentOptions.showModal" @close="closeModal">
            <DeleteMessage :hash="componentOptions.hash" v-if="componentOptions.mode == 'delete'" @cancel="closeModal"
                @done="refreshTable" />
        </Modal>

        <div class="flex flex-col md:py-4">
            <div class="flex-col px-1 md:px-5 lg:px-10 items-center justify-center gap-10 py-1 md:py-10">
                <!--header-->
                <div class="flex flex-row justify-between text-slate-800 p-2">
                    <div class="flex flex-row">
                        <TIcon name="email" size="xl" class="pt-1"></TIcon>
                        <span class="font-sans text-4xl md:text-5xl font-semibold flex flex-col items-center justify-center">
                            Messages
                        </span>
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="flex flex-col items-end justify-end " v-if="$guard.can(['message_list-deleted'])">
                            <router-link :to="{ name: 'ListOfDeletedMessages' }">
                                <TIcon name="folder_delete" size="md"
                                    class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 md:p-2 shadow-md hover:scale-110 hover:bg-green-700">
                                </TIcon>
                                <TToolTip  class="text-center">
                                    <span class="font-sans text-center">Deleted Messages</span>
                                </TToolTip>
                            </router-link>
                        </div>
                    </div>
                </div>

                <!--search bar-->
                <div class="flex flex-row items-start justify-between">
                    <div class="flex flex-col w-full md:w-[25rem] pb-3">
                        <input @input="debounceInput" type="text" id="default-input"
                            class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 shadow-sm"
                            placeholder="Search Messages">

                    </div>
                </div>



                <!--message list-->
                <div
                    class="hidden lg:flex lg:flex-col w-full bg-white border  border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700 ">

                    <div v-if="componentOptions.fetch.show" class="w-full flex justify-center items-center">
                        <span class="w-full text-center text-xl font-sans" v-text="componentOptions.fetch.text"></span>
                    </div>


                    <div v-if="messageList && !componentOptions.fetch.show" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
                        <div v-for="message in messageList" class=" p-2 border-b last:border-b-0  ">
                            
                                <div class="flex flex-col md:flex-row w-full h-full   ">
                                    <!--name-->
                                    <div class="flex flex-col w-full">
                                        <!--name-->
                                        <div class="flex flex-row">
                                            <div class="flex flex-col">
                                                <span>
                                                    <pre class="font-sans font-bold text-base">Name: </pre>
                                                </span>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <span class="font-sans font-semibold text-base">{{ message.lastname }},
                                                    {{ message.firstname }}</span>
                                            </div>
                                        </div>
                                        <!--contact-->
                                        <div class="flex flex-row w-full gap-1">
                                            <span class="font-sans text-sm font-semibold">
                                                Contact No:
                                            </span>
                                            <span class="font-sans text-sm">
                                                {{ message.contact }}
                                            </span>
                                        </div>
                                        <!--email-->
                                        <div class="flex flex-row gap-1">
                                            <span class="font-sans text-sm font-semibold">Email:</span>
                                            <span class="font-sans text-sm">{{ message.email }}</span>
                                        </div>


                                    </div>

                                    <div class="flex flex-col w-full">
                                        <!-- message -->
                                        <div class="flex flex-row">
                                            <div class="flex flex-col">
                                                <span>
                                                    <pre class="font-sans font-bold text-base">Message: </pre>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <span class="font-sans text-base line-clamp-3">{{ message.message }},
                                            </span>
                                        </div>



                                    </div>

                                    <div class="flex flex-col w-full pl-8 items-center justify-center">
                                        <!-- date -->
                                        <!-- <div class="flex flex-row">
                                             <div class="flex flex-col">
                                                 <span>
                                                     <pre class="font-sans font-bold text-base text-left">Date: </pre>
                                                 </span>
                                             </div>
                                             
                                         </div> -->
                                        <div class="flex flex-col justify-center">
                                            <span class="font-sans  text-base">{{ message.created_at }},
                                            </span>
                                        </div>



                                    </div>

                                    <div class="flex flex-col w-full">
                                        <!-- message -->
                                        <div class="flex flex-row items-center justify-center pt-8">
                                            <div class="flex flex-col items-center justify-center">
                                                <TIcon @click="viewMessage(message.hash)" name="visibility" size="sm"
                                                    class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600 px-1">
                                                </TIcon>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <TIcon v-if="$guard.can(['message_delete'])"
                                                    @click="showModal('delete', message.hash)" name="delete" size="sm"
                                                    class="pt-1 cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600 px-1">
                                                </TIcon>
                                            </div>
                                        </div>



                                    </div>


                                </div>
                                
                           

                        </div>
                    </div>
                   

                </div>

                <!--message list for sm and md screen-->
                <div
                    class="flex flex-col lg:hidden w-full bg-white border border-gray-200 rounded-lg shadow p-3 md:p-8 dark:bg-gray-800 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700 gap-y-2">
                    <div v-for="message in messageList" v-bind:key="message.hash">

                        <div class="flex flex-col hover:bg-gray-50 dark:hover:bg-gray-600">
                            <div class="flex flex-row justify-between items-center">
                                <div
                                    class="flex flex-col font-medium font-sans text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ message.firstname }} {{ message.lastname }}
                                </div>
                                <div class="flex flex-col font-sans text-xs">
                                    {{ message.created_at }}
                                </div>
                            </div>

                            <div class="flex flex-row justify-between items-center">
                                <div class="flex flex-col font-sans line-clamp-2 text-sm">
                                    {{ message.message }}
                                </div>

                                <div class="flex flex-row" v-if="$guard.can(['message_delete'])">
                                    <TIcon @click="viewMessage(message.hash)" name="visibility" size="sm"
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-blue-700 dark:hover:bg-blue-700 text-blue-600 px-1">
                                    </TIcon>
                                    <TIcon @click="showModal('delete', message.hash)" name="delete" size="sm"
                                        class="pt-1 cursor-pointer hover:scale-110 hover:text-red-700 dark:hover:bg-red-700 text-red-600 px-1">
                                    </TIcon>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                  <!--Pagination-->
                  <div class="flex flex-col items-center pt-3">
                    <Pagination
                        v-model="searchOptions.page"
                        v-model:limit="searchOptions.limit"
                        :total="searchOptions.total"
                        v-model:offset="searchOptions.offset"
                        @update:totalPage="(count) => {pages=count}"
                        @paginate="getMessages(searchOptions.term)"
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
import ViewMessages from './viewMessages.vue';
import DeleteMessage from './deleteMessage.vue';
import ListOfDeletedMessages from './listOfDeletedMessages.vue';
import _ from 'lodash';

export default defineComponent({
    name: 'Messages',
    setup() { },
    props: {
        value: Object,
    },
    components: {
        ViewMessages,
        DeleteMessage,
        ListOfDeletedMessages

    },
    mounted() {
        this.getMessages('');
    },

    data() {
        return {
            componentOptions: {
                showModal: false,
                mode: '',

                hash: null,

                fetch: {
                    text: '',
                    show: false
                }
            },
            searchOptions: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 5,
                offset: 0,
            },
            messageList: null,
        }
    },
    methods: {
        getMessages(term = '') {
            this.componentOptions.fetch.text = '( Fetching Data )'
            this.componentOptions.fetch.show = true

            this.$api.get('/admin/messages/list', {
                params: {
                    term: term,
                    offset: this.searchOptions.offset,
                    limit: this.searchOptions.limit,
                }

            })
                .then((response) => {
                    this.messageList = response.data.message

                    if (this.messageList.length != 0) {
                        this.componentOptions.fetch.show = false,
                            this.componentOptions.fetch.text = ''

                    }
                    else {
                        this.componentOptions.fetch.show = true,
                            this.componentOptions.fetch.text = '( No Data Fetched )'

                    }

                    this.searchOptions.total = response.data.total
                    this.messageList.created_at = response.data.date
                })

        },
        showModal(mode, hash) {
            this.componentOptions.showModal = true
            this.componentOptions.mode = mode
            this.componentOptions.hash = hash

        },
        closeModal() {
            this.componentOptions.showModal = false
            this.componentOptions.mode = ''


        },
        refreshTable() {
            this.closeModal();
            this.getMessages();
        },
        debounceInput: _.debounce(function (e) {
            this.searchOptions.offset = 0;
            this.searchOptions.term = e.target.value;
            this.getMessages(this.searchOptions.term)
        }, 500),

        viewMessage(hash) {

            this.$router.push({
                name: 'ViewMessages',
                params: {
                    hash: hash,
                },
            })

        }

    }
})
</script>
