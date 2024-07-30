<template>
    <Page>

        <div class="flex flex-col ">

            <div class="flex-col lg:px-10 xs:p-2 items-center justify-center gap-10 lg:py-10 sm:px-8">
                <div class="flex flex-row items-start justify-between ">

                    <div class="flex flex-row  pr-2 text-slate-800 p-2">
                        <div class="flex flex-col">
                            <TIcon name="calendar_month" size="xl"></TIcon>

                        </div>

                        <div class="flex flex-col">
                            <span
                                class="font-sans text-5xl font-semibold flex flex-col items-center justify-center">Events</span>

                        </div>

                    </div>



                </div>

                <div class="flex flex-row p-2">
                    <div class="inline-flex gap-3">

                        <!--search bar-->
                        <div class="flex flex-row gap-2 w-full pb-3">
                            <input @input="debounceInput" type="text" id="default-input"
                                class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search Events">

                                <input 
                                @input="debounceInput"
                                type="date" 
                                id="default-input"
                                class="font-sans bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search Articles">
                        </div>

                       



                    </div>



                </div>


                <!-- event card -->
                <div
                    class="items-center justify-center flex-col p-2 grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div v-for="event in eventList" v-bind:key="event.hash">

                        <!-- sample2 -->
                        <div class="flex flex-col">
                            <div
                                class="2xl:h-[40rem] xl:h-[35rem] 2lg:h-[35rem] md:h-[25rem] xs:h-[35rem]  first-letter: max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 items-center justify-between">
                                <div class="flex flex-col ">


                                    <div class="object-cover max-h-full max-w-full">



                                        <div class="object-cover h-full w-full">
                                            <template v-if="event.image">
                                                <img :src="event.image.length != 0 ? event.image[0].thumbnails.medium : '/src/assets/smokebaguio.png'"
                                                    class="w-full h-full  md:h-[10rem] 2xl:h-[25rem] xs:h-[20rem] 2lg:h-[20rem] object-cover rounded-t-lg">
                                            </template>
                                            <template v-else>
                                                <img src="@/assets/smokebaguio.png"
                                                    class="w-full h-full  md:h-[10rem] 2xl:h-[25rem] 2lg:h-[20rem] object-cover">
                                            </template>

                                        </div>


                                    </div>



                                    <a href="#">
                                        <h5
                                            class=" font-sans text-center p-2 2xl:text-2xl xl:text-2xl lg:text-lg font-bold tracking-tight text-gray-900 dark:text-white w-full  md:line-clamp-1 ">
                                            {{ event.event_title }}</h5>
                                    </a>

                                    <div class="flex flex-col items-center justify-center">
                                        <div class=" p-2 w-full text-xs text-center  ">
                                            <span class="font-bold border-b border-white font-sans">
                                                {{ event.start_date }} - {{ event.end_date }}</span>

                                        </div>
                                    </div>


                                    <div class="p-2">

                                        <div class="flex items-center justify-center">
                                            <!-- <p class=" font-sans text-gray-700 dark:text-gray-400 line-clamp-3">{{ event.description }}</p> -->
                                            <p class="2xl:line-clamp-3 xl:line-clamp-3 lg:line-clamp-2 xs:line-clamp-3 ss:line-clamp-3 px-2"
                                                data-aos="fade-down-left" data-aos-duration="1500"
                                                v-html="event.description"></p>

                                        </div>

                                    </div>
                                </div>


                                <div class="flex flex-col justify-end items-end ">
                                    <div class="flex flex-row justify-end w-28 pr-2 gap-3 lg:p-2.5">
                                        <router-link :to="{ name: 'PublicViewEvent', params: { hash: event.hash } }">
                                            <div class="flex justify-end">
                                                <div
                                                    class="flex w-28 cursor-pointer pr-2 hover:scale-110 hover:text-green-800 dark:hover:bg-green-800 text-green-600">
                                                    <span class="font-sans text-md">Read more</span>
                                                    <TIcon name="arrow_forward" size="sm" class="pt-1"></TIcon>
                                                </div>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>





                    </div>
                </div>

               
                <div v-if="searchOptions.total == 0" class="p-8 text-center">
                    <span class="font-sans text-xl text-slate-700">({{ loadStatus }})</span>
                </div>
                 <!--Pagination-->
             <div class="flex flex-col items-center pt-3">
                <Pagination
                    v-model="searchOptions.page"
                    v-model:limit="searchOptions.limit"
                    :total="searchOptions.total"
                    v-model:offset="searchOptions.offset"
                    @update:totalPage="(count) => {pages=count}"
                    @paginate="getEvents(searchOptions.term)"
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
import PublicViewEvent from './publicViewEvent.vue';
import _ from 'lodash';

export default defineComponent({
    name: 'publicEvents',
    setup() { },
    props: {
        value: Object,
    },
    components: {
        PublicViewEvent
    },
    mounted() {
        this.getEvents('');
    },
    data() {
        return {
            componentOptions: {
                selectedItem: null,
            },
            searchOptions: {
                term: '',
                page: 1,
                total: 0,
                limit: 4,
                offset: 0,
            },
            eventList: null,
            loadStatus: ''
        }
    },
    methods: {
        getEvents(term = '') {
            this.loadStatus = 'Fetching Data'
          
            this.$api.get('/events/list', {
                params: {
                    term: term,
                    offset: this.searchOptions.offset,
                    limit: this.searchOptions.limit,
                }

            })
                .then((response) => {
                    this.eventList = response.data.event
                    this.searchOptions.total = response.data.total
                    this.loadStatus = 'No Events Found'
                })

        },
        debounceInput: _.debounce(function (e) {
            this.searchOptions.offset = 0;
            this.searchOptions.term = e.target.value;
            this.getEvents(this.searchOptions.term)
        }, 500)
    }
})
</script>
