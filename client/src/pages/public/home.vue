<template>
    <Page class="flex flex-col font-semibold lg:px-20 xl:px-15">
        <!-- large screen -->
        <div class="hidden lg:flex flex-col w-full ">
            <!-- WELCOME  -->
            <div class="flex flex-col items-center justify-center pb-10 h-screen w-full motion-safe:animate-fadeIn">
                <!--We are smoke free baguio-->
                <div class="font-sans w-full h-full items-center font-semibold py-4 lg:py-10">
                    <!-- main background image -->
                    <div class="shadow-lg bg-gray-600 flex flex-row antialiased relative w-full h-full"
                        style="
                    background-image: url('/src/assets/smoke.jpg');background-repeat: no-repeat; background-size: cover; background-blend-mode: multiply; border-radius:2rem;">

                        <div class="flex flex-col items-center justify-center text-center h-full w-full ">
                            <span class="font-sans text-white font-semibold text-xs md:text-lg lg:text-7xl">WE ARE</span>
                            <span class="font-sans text-white font-semibold text-md md:text-3xl lg:text-8xl"> SMOKE-FREE
                                BAGUIO</span>

                        </div>
                    </div>
                </div>

                <!-- buttons -->
                <div class="flex flex-col md:flex-row items-center justify-center gap-3 lg:gap-10">
                    <router-link :to="{ name: 'Quitter' }">
                        <div
                            class="font-sans flex font-bold  bg-gray-800  rounded-full cursor-pointer p-4 text-sm md:text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                            <span class="font-sans text-center">I WANT TO QUIT
                            </span>
                        </div>

                    </router-link>
                    <router-link :to="{ name: 'ReportViolation' }">
                        <div
                            class="font-sans flex font-bold bg-gray-800  rounded-full cursor-pointer p-4 text-sm md:text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                            <span class="font-sans text-center">REPORT VIOLATIONS
                            </span>
                        </div>
                    </router-link>
                    <router-link :to="{ name: 'EnforcersRoom' }">
                        <div
                            class="font-sans flex font-bold  bg-gray-800  rounded-full cursor-pointer p-4 text-sm md:text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                            <span class="font-sans text-center">ENFORCER'S ROOM
                            </span>
                        </div>
                    </router-link>

                </div>
            </div>

            <div class="flex flex-row items-center justify-center">
                <hr class=" border border-black w-[50rem] sm:w-[40rem]">
            </div>

            <!-- ARTICLES -->
            <div class="w-full h-screen lg:h-screen xl:h-screen 2xl:h-screen">
                <div class="w-full h-full flex-col grid grid-cols-2 p-4">
                    <!--Articles-->
                    <div class="flex flex-col h-full w-full justify-center p-2">
                        <span
                            class="font-sans text-gray-700 dark:text-gray-400 font-semibold text-center items-center md:text-center lg:text-start xl:text-6xl md:text-5xl lg:text-3xl">WHAT'S
                            HAPPENING</span>
                    </div>
                    <!--Article Card-->
                    <div class="justify-center lg:h-screen">
                        <div v-for="article in articleList">
                            <div class="flex flex-col rounded-2xl lg:rounded-2xl  shadow-lg">
                                <div class=" flex flex-col justify-center">


                                    <!--image-->
                                    <div class="w-full 2xl:h-[25rem] lg:h-[10rem] xl:h-[30rem]">
                                        <template v-if="article.image">
                                            <img :src="article.image ? article.image[0].thumbnails.medium : '@/assets/smokebaguio.png'"
                                                class="object-cover w-full h-full rounded-lg">
                                        </template>
                                        <template v-else>
                                            <img src="@/assets/smokebaguio.png" class="w-full h-full">
                                        </template>

                                    </div>

                                    <!--Date, Title, Image-->
                                    <div class="flex flex-col p-4 h-full justify-between">
                                        <div>
                                            <!-- <div
                                                class=" p-2 w-[8rem] text-center bg-green-600 rounded-2xl text-white float-right items-end justify-end fd-cl ">
                                                <span class="text-base  font-bold border-b border-white font-sans">
                                                    {{ article.created_at }}</span>

                                            </div> -->
                                            <div class="flex flex-col text-center ">
                                                <div class="text-green-600  text-3xl font-sans font-extrabold ">{{
                                                    article.articleTitle
                                                }}
                                                </div>
                                                <div class="text-gray-800  text-lg font-sans font-extrabold ">{{
                                                    article.created_at
                                                }}
                                                </div>
                                                <p class="2xl:line-clamp-5 lg:line-clamp-3 xl:line-clamp-3 " data-aos="fade-down-left" data-aos-duration="1500"
                                                    v-html="article.description"></p>
                                            </div>
                                        </div>
                                        <!-- button -->
                                        <div class="flex flex-row justify-end pr-1 gap-2">
                                            <router-link
                                                :to="{ name: 'PublicViewArticle', params: { hash: article.hash } }">
                                                <div
                                                    class="font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                    <!-- <span class="font-sans">View Article</span> -->
                                                    <TIcon name="visibility" class="flex cursor-pointer"></TIcon>
                                                </div>
                                            </router-link>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="flex flex-col">
                            <!-- 3 recert articles  -->
                            <div class="pt-4 grid flex-col grid-cols-3 items-center justify-center gap-1">
                                <div v-for="article in articleListForBelow" class="">
                                    <router-link :to="{ name: 'PublicViewArticle', params: { hash: article.hash } }">
                                        <div
                                            class=" lg:w-full xl:max-w-sm xl:h-full lg:h-[8rem] p-6 cursor-pointer border border-gray-200 rounded-lg shadow  hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 hover:scale-110">
                                            <a href="#">
                                                <h5
                                                    class="mb-2 text-2xl xl:line-clamp-2 lg:line-clamp-1 font-bold font-sans tracking-tight text-gray-900 dark:text-white xl:text-lg">
                                                    {{ article.articleTitle }}</h5>
                                            </a>
                                            <p class="xl:line-clamp-2 lg:line-clamp-1 " data-aos="fade-down-left" data-aos-duration="1500"
                                                v-html="article.description"></p>
                                            <!-- button -->
                                        </div>
                                    </router-link>




                                </div>

                            </div>
                            <div v-if="ArticleSearchOptionsForBelow.total >= 1" >
                                <div class="flex flex-row justify-end items-center lg:p-2 ">
                                    <div class=" items-end justify-end">
                                        <router-link :to="{ name: 'publicArticles' }">
                                            <div
                                                class="w-[8rem] font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                <span class="font-sans">View More</span>
                                                <TIcon name="arrow_forward" class="flex cursor-pointer"></TIcon>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>




                        </div>

                        <div class="pb-8 items-center justify-center flex h-full w-full">
                            <div v-if="ArticleSearchOptions.total == 0"
                                class="flex flex-col h-full w-full justify-center  shadow-md border border-md border-gray-200 rounded-xl ">
                                <span
                                    class="font-sans text-gray-700 dark:text-gray-400 text-5xl font-semibold text-center items-center md:text-center lg:text-center">({{ articleLoadStatus }})</span>

                                <div class=" flex space-x-4 p-8 pt-12">
                                    <div class="flex-1 space-y-6 py-1">

                                        <div class="space-y-3">
                                            <div class="grid grid-cols-3 gap-4">
                                                <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                                <div class="h-2 bg-slate-400 rounded col-span-1"></div>
                                            </div>
                                            <div class="h-2 bg-slate-400 rounded"></div>
                                            <div class="grid grid-cols-3 gap-4">
                                                <div class="h-2 bg-slate-400 rounded col-span-1"></div>
                                                <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                            </div>
                                            <div class="grid grid-cols-4 gap-4">
                                                <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                                <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>



                    </div>


                </div>
            </div>



            <div class="flex flex-row items-center justify-center xl:pt-10  ">
                <hr class=" border border-black w-[50rem] sm:w-[40rem]">
            </div>

            <!--Events-->
            <div class="w-full  lg:h-screen xl:h-screen 2xl:h-screen">
                <div class="w-full h-full flex-col grid grid-cols-2 p-4">
                    <!--Events-->

                    <!--Event Card-->
                    <div class="justify-center lg:h-screen">
                        <div v-for="event in eventList">
                            <div class="flex flex-col rounded-2xl lg:rounded-2xl  shadow-lg">
                                <div class=" flex flex-col justify-center">


                                    <!--image-->
                                    <div class="w-full 2xl:h-[30rem] lg:h-[10rem] xl:h-[30rem]">
                                        <template v-if="event.image">
                                            <img :src="event.image ? event.image[0].thumbnails.medium : '@/assets/smokebaguio.png'"
                                                class="object-cover w-full h-full rounded-lg">
                                        </template>
                                        <template v-else>
                                            <img src="@/assets/smokebaguio.png" class="w-full h-full">
                                        </template>

                                    </div>

                                    <!--Date, Title, Image-->
                                    <div class="flex flex-col p-4 h-full justify-between">
                                        <div>
                                            <!-- <div
                                                class=" p-2 w-[8rem] text-center bg-green-600 rounded-2xl text-white float-right items-end justify-end fd-cl ">
                                                <span class="text-base  font-bold border-b border-white font-sans">
                                                    {{ article.created_at }}</span>

                                            </div> -->
                                            <div class="flex flex-col text-center ">
                                                <div class="text-green-600  text-3xl font-sans font-extrabold ">{{
                                                    event.event_title
                                                }}
                                                </div>
                                                <div class="text-gray-800  text-lg font-sans font-extrabold ">{{event.start_date}} - {{event.end_date}}
                                                </div>
                                                <p class="2xl:line-clamp-5 lg:line-clamp-3 xl:line-clamp-3 " data-aos="fade-down-left" data-aos-duration="1500"
                                                    v-html="event.description"></p>
                                            </div>
                                        </div>
                                        <!-- button -->
                                        <div class="flex flex-row justify-end pr-1 gap-2">
                                            <router-link
                                                :to="{ name: 'PublicViewEvent', params: { hash: event.hash } }">
                                                <div
                                                    class="font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                    <!-- <span class="font-sans">View Article</span> -->
                                                    <TIcon name="visibility" class="flex cursor-pointer"></TIcon>
                                                </div>
                                            </router-link>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="flex flex-col">
                            <!-- 3 recert articles  -->
                            <div class="pt-4 grid flex-col grid-cols-3 items-center justify-center gap-1">
                                <div v-for="article in articleListForBelow" class="">
                                    <router-link :to="{ name: 'PublicViewArticle', params: { hash: article.hash } }">
                                        <div
                                            class=" lg:w-full xl:max-w-sm xl:h-full lg:h-[8rem] p-6 cursor-pointer border border-gray-200 rounded-lg shadow  hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 hover:scale-110">
                                            <a href="#">
                                                <h5
                                                    class="mb-2 text-2xl xl:line-clamp-2 lg:line-clamp-1 font-bold font-sans tracking-tight text-gray-900 dark:text-white xl:text-lg">
                                                    {{ article.articleTitle }}</h5>
                                            </a>
                                            <p class="xl:line-clamp-2 lg:line-clamp-1 " data-aos="fade-down-left" data-aos-duration="1500"
                                                v-html="article.description"></p>
                                            <!-- button -->
                                        </div>
                                    </router-link>




                                </div>

                            </div>
                            <div v-if="ArticleSearchOptionsForBelow.total >= 1" >
                                <div class="flex flex-row justify-end items-center lg:p-2 ">
                                    <div class=" items-end justify-end">
                                        <router-link :to="{ name: 'publicArticles' }">
                                            <div
                                                class="w-[8rem] font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                <span class="font-sans">View More</span>
                                                <TIcon name="arrow_forward" class="flex cursor-pointer"></TIcon>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>




                        </div>

                        <div class="pb-8 items-center justify-center flex h-full w-full">
                            <div v-if="EventSearchOptions.total == 0"
                                class="flex flex-col h-full w-full justify-center  shadow-md border border-md border-gray-200 rounded-xl ">
                                <span
                                    class="font-sans text-gray-700 dark:text-gray-400 text-5xl font-semibold text-center items-center md:text-center lg:text-center">({{ eventLoadStatus }})</span>

                                <div class=" flex space-x-4 p-8 pt-12">
                                    <div class="flex-1 space-y-6 py-1">

                                        <div class="space-y-3">
                                            <div class="grid grid-cols-3 gap-4">
                                                <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                                <div class="h-2 bg-slate-400 rounded col-span-1"></div>
                                            </div>
                                            <div class="h-2 bg-slate-400 rounded"></div>
                                            <div class="grid grid-cols-3 gap-4">
                                                <div class="h-2 bg-slate-400 rounded col-span-1"></div>
                                                <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                            </div>
                                            <div class="grid grid-cols-4 gap-4">
                                                <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                                <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>



                    </div>



                    <div class="flex flex-col h-full w-full justify-center p-2">
                        <span
                        class="font-sans text-gray-700 dark:text-gray-400 font-semibold text-center items-center md:text-center lg:text-right xl:text-6xl md:text-5xl lg:text-3xl">UPCOMING EVENTS</span>
                    </div>









                </div>
            </div>

            <div class="flex flex-row items-center justify-center xl:pt-[10rem] lg:p-12 lg:pb-12">
                <hr class=" border border-black w-[50rem] sm:w-[40rem]">
            </div>

            <!-- ORDINANCE -->
            <div class="flex flex-col items-center justify-center pb-10">
                <Ordinance></Ordinance>
            </div>

            <!-- call file -->
            <div class="flex flex-col pb-10">
                <ContactUs></ContactUs>
            </div>
        </div>





        <!-- sm and md screen -->
        <div class="flex lg:hidden flex-col w-full motion-safe:animate-fadeIn">
            <!-- welcome -->
            <div class="flex flex-col items-center justify-center ">
                <div class="font-sans h-[12rem] w-full md:h-[20rem] lg:h-[40rem] items-center font-semibold p-10 lg:py-10">
                    <!-- main background image -->
                    <div class="shadow-lg bg-gray-600 flex flex-row antialiased relative w-full h-full p-8"
                        style="
                    background-image: url('/src/assets/smoke.jpg');background-repeat: no-repeat; background-size: cover; background-blend-mode: multiply; border-radius:2rem;">

                        <div class="flex flex-col items-center justify-center text-center h-full w-full ">
                            <span class="font-sans text-white font-semibold text-xs md:text-lg lg:text-7xl">WE ARE</span>
                            <span class="font-sans text-white font-semibold text-md md:text-3xl lg:text-8xl"> SMOKE-FREE
                                BAGUIO</span>

                        </div>
                    </div>
                </div>
            </div>
            <!-- buttons -->
            <div class="p-2">
                <div class="flex flex-col md:flex-col sm:flex-row xs:flex-col items-center justify-center gap-3 lg:gap-10">
                    <router-link :to="{ name: 'Quitter' }">
                        <div
                            class="font-sans flex font-bold  bg-gray-800  rounded-full cursor-pointer p-4 text-sm md:text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                            <span class="font-sans text-center">I WANT TO QUIT
                            </span>
                        </div>

                    </router-link>
                    <router-link :to="{ name: 'ReportViolation' }">
                        <div
                            class="font-sans flex font-bold bg-gray-800  rounded-full cursor-pointer p-4 text-sm md:text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                            <span class="font-sans text-center">REPORT VIOLATIONS
                            </span>
                        </div>
                    </router-link>
                    <router-link :to="{ name: 'EnforcersRoom' }">
                        <div
                            class="font-sans flex font-bold  bg-gray-800  rounded-full cursor-pointer p-4 text-sm md:text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                            <span class="font-sans text-center">ENFORCER'S ROOM
                            </span>
                        </div>
                    </router-link>

                </div>
            </div>

            <!-- border -->
            <div class="flex flex-row items-center justify-center ">
                <hr class="my-2 border border-black w-[10rem] sm:w-[40rem]">
            </div>

            <!-- Articles -->
            <div class="w-full h-screen">
                <div class="flex flex-col text-center font-sans text-3xl font-bold">
                    WHAT'S HAPPENING</div>


                <div class="w-full h-full">
                    <div class="flex-col items-center justify-center w-full p-4">
                        <div v-for="article in articleList">
                            <div class="flex flex-col rounded-2xl lg:rounded-2xl  shadow-lg">
                                <div class=" flex flex-col justify-center">


                                    <!--image-->
                                    <div class="w-full sm:h-[25rem] xs:h-[10rem]">
                                        <template v-if="article.image">
                                            <img :src="article.image ? article.image[0].thumbnails.medium : '@/assets/smokebaguio.png'"
                                                class="object-cover w-full h-full rounded-lg">
                                        </template>
                                        <template v-else>
                                            <img src="@/assets/smokebaguio.png" class="w-full h-full">
                                        </template>

                                    </div>

                                    <!--Date, Title, Image-->
                                    <div class="flex flex-col p-4 h-full justify-between">
                                        <div>
                                            <div
                                                class=" p-2 w-[8rem] hidden sm:flex text-center bg-green-600 rounded-2xl text-white float-right items-end justify-end fd-cl ">
                                                <span class="text-base  font-bold border-b border-white font-sans">
                                                    {{ article.created_at }}</span>

                                            </div>
                                            <div class="flex flex-col text-start ">
                                                <div class="text-gray-900  text-3xl font-sans font-extrabold ">{{
                                                    article.articleTitle
                                                }}
                                                </div>
                                                <p class="line-clamp-5" data-aos="fade-down-left" data-aos-duration="1500"
                                                    v-html="article.description"></p>
                                            </div>
                                        </div>
                                        <!-- button -->
                                        <div class="flex flex-row justify-end pr-1 gap-2">
                                            <router-link
                                                :to="{ name: 'PublicViewArticle', params: { hash: article.hash } }">
                                                <div
                                                    class="font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                    <!-- <span class="font-sans">View Article</span> -->
                                                    <TIcon name="visibility" class="flex cursor-pointer"></TIcon>
                                                </div>
                                            </router-link>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hidden sm:flex flex-col">
                            <!-- 3 recert articles  -->
                            <div class="pt-4 grid flex-col grid-cols-3 items-center justify-center gap-1">
                                <div v-for="article in articleListForBelow" class="">
                                    <router-link :to="{ name: 'PublicViewArticle', params: { hash: article.hash } }">
                                        <div
                                            class="max-w-sm h-full  p-6 cursor-pointer border border-gray-200 rounded-lg shadow  hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 hover:scale-110">
                                            <a href="#">
                                                <h5
                                                    class="mb-2 text-2xl line-clamp-2 font-bold font-sans tracking-tight text-gray-900 dark:text-white">
                                                    {{ article.articleTitle }}</h5>
                                            </a>
                                            <p class="line-clamp-3" data-aos="fade-down-left" data-aos-duration="1500"
                                                v-html="article.description"></p>
                                            <!-- button -->
                                        </div>
                                    </router-link>




                                </div>

                            </div>
                            <div v-if="ArticleSearchOptionsForBelow.total >= 1">
                                <div class="flex flex-row justify-end items-center p-2 ">
                                    <div class=" items-end justify-end">
                                        <router-link :to="{ name: 'publicArticles' }">
                                            <div
                                                class="w-[8rem] font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                <span class="font-sans">View More</span>
                                                <TIcon name="arrow_forward" class="flex cursor-pointer"></TIcon>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>




                        </div>

                        <div class="sm:hidden xs:flex flex-col">
                            <div v-if="ArticleSearchOptionsForBelow.total >= 1">
                                <div class="flex flex-row justify-end items-center p-2 ">
                                    <div class=" items-end justify-end">
                                        <router-link :to="{ name: 'publicArticles' }">
                                            <div
                                                class="w-[8rem] font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                <span class="font-sans">View More</span>
                                                <TIcon name="arrow_forward" class="flex cursor-pointer"></TIcon>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class=" items-center justify-center flex sm:h-[55rem] xs:h-[35rem] w-full px-4">
                        <div v-if="ArticleSearchOptions.total == 0"
                            class="flex flex-col h-full w-full justify-center  shadow-md border border-md border-gray-200 rounded-xl ">
                            <span
                                class="font-sans text-black dark:text-gray-400 font-semibold text-center items-center md:text-center lg:text-center">({{
                                    articleLoadStatus }})</span>

                            <div class="animate-pulse flex space-x-4 p-8 pt-12">
                                <div class="flex-1 space-y-6 py-1">

                                    <div class="space-y-3">
                                        <div class="grid grid-cols-3 gap-4">
                                            <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                            <div class="h-2 bg-slate-400 rounded col-span-1"></div>
                                        </div>
                                        <div class="h-2 bg-slate-400 rounded"></div>
                                        <div class="grid grid-cols-3 gap-4">
                                            <div class="h-2 bg-slate-400 rounded col-span-1"></div>
                                            <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                        </div>
                                        <div class="grid grid-cols-4 gap-4">
                                            <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                            <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <!-- border -->
            <div class="flex flex-row items-center justify-center ">
                <hr class="my-2 border border-black w-[10rem] sm:w-[40rem]">
            </div>

            <!-- Events -->
            <div class="w-full h-screen">
                <div class="flex flex-col text-center font-sans text-3xl font-bold">
                    UPCOMING EVENTS</div>


                <div class="w-full h-full">
                    <div class="flex-col items-center justify-center w-full p-4">
                        <div v-for="event in eventList">
                            <div class="flex flex-col rounded-2xl lg:rounded-2xl  shadow-lg">
                                <div class=" flex flex-col justify-center">


                                    <!--image-->
                                    <div class="w-full sm:h-[25rem] xs:h-[10rem]">
                                        <template v-if="event.image">
                                            <img :src="event.image ? event.image[0].thumbnails.medium : '@/assets/smokebaguio.png'"
                                                class="object-cover w-full h-full rounded-lg">
                                        </template>
                                        <template v-else>
                                            <img src="@/assets/smokebaguio.png" class="w-full h-full">
                                        </template>

                                    </div>

                                    <!--Date, Title, Image-->
                                    <div class="flex flex-col p-4 h-full justify-between">
                                        <div>
                                            <!-- <div
                                                class=" p-2 w-[8rem] sm:w-[15rem] hidden sm:flex text-center bg-green-600 rounded-2xl text-white float-right items-end justify-end fd-cl ">
                                                <span class="text-base  font-bold border-b border-white font-sans">
                                                    {{ event.start_date }} - {{event.end_date}}</span>
                                                   

                                            </div> -->
                                            <!-- <div class="absolute flex flex-col items-end justify-end p-2 ">
                                                <span class=" p-2 font-bold font-sans text-xl bg-green-600 text-white fd-cl group-hover:opacity-25 rounded-2xl shadow-md">
                                                    {{ event.start_date }} - {{event.end_date}}</span>
                                             
                                             </div> -->
                                            <div class="flex flex-col text-center ">
                                                <div class=" text-green-600 text-3xl font-sans font-extrabold ">{{
                                                    event.event_title
                                                }}
                                                </div>
                                                <div class=" text-gray-800 text-md  font-sans font-extrabold ">
                                                    {{ event.start_date }} - {{ event.end_date }}
                                                </div>
                                                <p class="line-clamp-5" data-aos="fade-down-left" data-aos-duration="1500"
                                                    v-html="event.description"></p>
                                            </div>
                                        </div>
                                        <!-- button -->
                                        <div class="flex flex-row justify-end pr-1 gap-2">
                                            <router-link :to="{ name: 'PublicViewEvent', params: { hash: event.hash } }">
                                                <div
                                                    class="font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                    <!-- <span class="font-sans">View Rvente</span> -->
                                                    <TIcon name="visibility" class="flex cursor-pointer"></TIcon>
                                                </div>
                                            </router-link>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hidden sm:flex flex-col">
                            <!-- 3 recert event  -->
                            <div class="pt-4 grid flex-col grid-cols-3 items-center justify-center gap-1">
                                <div v-for="event in eventListForBelow" class="">
                                    <router-link :to="{ name: 'PublicViewEvent', params: { hash: event.hash } }">
                                        <div
                                            class="max-w-sm h-full  p-6 cursor-pointer border border-gray-200 rounded-lg shadow  hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 hover:scale-110">
                                            <a href="#">
                                                <h5
                                                    class="mb-2 text-2xl line-clamp-2 font-bold font-sans tracking-tight text-gray-900 dark:text-white">
                                                    {{ event.event_title }}</h5>
                                            </a>
                                            <p class="line-clamp-3" data-aos="fade-down-left" data-aos-duration="1500"
                                                v-html="event.description"></p>
                                            <!-- button -->
                                        </div>
                                    </router-link>




                                </div>

                            </div>
                            <div v-if="EventSearchOptionsForBelow.total >= 1">
                                <div class="flex flex-row justify-end items-center p-2 ">
                                    <div class=" items-end justify-end">
                                        <router-link :to="{ name: 'publicEvents' }">
                                            <div
                                                class="w-[8rem] font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                <span class="font-sans">View More</span>
                                                <TIcon name="arrow_forward" class="flex cursor-pointer"></TIcon>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>




                        </div>

                        <div class="sm:hidden xs:flex flex-col">
                            <div v-if="EventSearchOptionsForBelow.total >= 1">
                                <div class="flex flex-row justify-end items-center p-2 ">
                                    <div class=" items-end justify-end">
                                        <router-link :to="{ name: 'publicEvents' }">
                                            <div
                                                class="w-[8rem] font-sans flex justify-end items-end font-bold bg-gray-800  rounded-full cursor-pointer p-3 text-sm  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">
                                                <span class="font-sans">View More</span>
                                                <TIcon name="arrow_forward" class="flex cursor-pointer"></TIcon>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class=" items-center justify-center flex sm:h-[55rem] xs:h-[35rem] w-full px-4">
                        <div v-if="EventSearchOptions.total == 0"
                            class="flex flex-col h-full w-full justify-center  shadow-md border border-md border-gray-200 rounded-xl ">
                            <span
                                class="font-sans text-black dark:text-gray-400 font-semibold text-center items-center md:text-center lg:text-center">({{
                                    eventLoadStatus }})</span>

                            <div class="animate-pulse flex space-x-4 p-8 pt-12">
                                <div class="flex-1 space-y-6 py-1">

                                    <div class="space-y-3">
                                        <div class="grid grid-cols-3 gap-4">
                                            <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                            <div class="h-2 bg-slate-400 rounded col-span-1"></div>
                                        </div>
                                        <div class="h-2 bg-slate-400 rounded"></div>
                                        <div class="grid grid-cols-3 gap-4">
                                            <div class="h-2 bg-slate-400 rounded col-span-1"></div>
                                            <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                        </div>
                                        <div class="grid grid-cols-4 gap-4">
                                            <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                            <div class="h-2 bg-slate-400 rounded col-span-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- border -->
            <div class="flex flex-row items-center justify-center pt-[5rem] pb-2">
                <hr class="my-2 border border-black w-[10rem] sm:w-[40rem]">
            </div>

            <!-- ORDINANCE -->
            <div class="flex flex-col items-center justify-center py-10">
                <Ordinance></Ordinance>
            </div>

            <!-- call file -->
            <div class="flex flex-col pb-10">
                <ContactUs></ContactUs>
            </div>


        </div>




    </Page>
</template>

<style>
.forImage {
    background-repeat: no-repeat;
    background-size: cover;
    background-blend-mode: multiply;
    border-radius: 1rem;
    align-items: center;
}
</style>

<script>
import { defineComponent } from 'vue';
import Ordinance from './ordinance.vue';
import ContactUs from './contactUs.vue';




export default defineComponent({
    name: 'publicHome',
    setup() { },
    components: {
        Ordinance,
        ContactUs,

    },
    data() {
        return {
            componentOptions: {
                selectedItem: null,
            },
            ArticleSearchOptions: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 1,
                offset: 0,
            },
            ArticleSearchOptionsForBelow: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 3,
                offset: 0,
            },
            EventSearchOptions: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 1,
                offset: 0,
            },
            EventSearchOptionsForBelow: {
                term: '',
                page: 1,
                pages: 1,
                total: 0,
                limit: 3,
                offset: 0,
            },
            articleList: null,
            articleListForBelow: null,
            eventList: null,
            eventListForBelow: null,
            articleLoadStatus: '',
            eventLoadStatus: '',

        };
    },
    mounted() {
        this.getArticles('');
        this.getArticleForBelow('');
        this.getEvents('');
        this.getEventsForBelow('');
    },
    methods: {
        getArticles(term = '') {
           
            this.articleLoadStatus = 'Fetching Data'
            this.$api.get('/articles/list', {
                params: {
                    term: term,
                    offset: this.ArticleSearchOptions.offset,
                    limit: this.ArticleSearchOptions.limit,
                }
            })
                .then((response) => {
                   
                    this.articleList = response.data.article
                    this.ArticleSearchOptions.total = response.data.total
                    this.articleList.created_at = response.data.date
                    this.articleLoadStatus = 'No Article Found'

                })
        },
        getArticleForBelow(term = '') {
          
            this.articleLoadStatus = 'Fetching Data'
            this.$api.get('/articles/list', {
                params: {
                    term: term,
                    offset: this.ArticleSearchOptionsForBelow.offset,
                    limit: this.ArticleSearchOptionsForBelow.limit,
                }
            })
                .then((response) => {
                   
                    this.articleListForBelow = response.data.article
                    this.ArticleSearchOptionsForBelow.total = response.data.total
                    this.articleListForBelow.created_at = response.data.date
                    this.articleLoadStatus = 'No Article Found'

                })
        },
        getEvents(term = '') {
            this.eventLoadStatus = 'Fetching Data'
            this.$api.get('/events/list', {
                params: {
                    term: term,
                    offset: this.EventSearchOptions.offset,
                    limit: this.EventSearchOptions.limit,
                }

            })
                .then((response) => {
                   
                    this.eventList = response.data.event
                    this.EventSearchOptions.total = response.data.total
                    this.eventList.start_date = response.data.date
                    this.eventList.end_date = response.data.date
                    this.eventLoadStatus = 'No Event Found'

                })
        },
        getEventsForBelow(term = '') {
            this.eventLoadStatus = 'Fetching Data'
            this.$api.get('/events/list', {
                params: {
                    term: term,
                    offset: this.EventSearchOptionsForBelow.offset,
                    limit: this.EventSearchOptionsForBelow.limit,
                }

            })
                .then((response) => {
                   
                    this.eventListForBelow = response.data.event
                    this.EventSearchOptionsForBelow.total = response.data.total
                    this.eventListForBelow.start_date = response.data.date
                    this.eventListForBelow.end_date = response.data.date
                    this.eventLoadStatus = 'No Event Found'

                })
        },

       
    }

   
})
</script>
