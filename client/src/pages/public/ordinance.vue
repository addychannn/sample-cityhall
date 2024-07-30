<template>
    <Page class="flex flex-col h-full w-full justfiy-center  items-center">
        <div class="block bg-slate-200 border border-gray-200 rounded-2xl shadow-lg  items-center py-5">

            <div class="flex flex-row items-center justify-center">
                <span class="text-3xl md:text-5xl font-sans text-center text-green-600">Smoke-Free Baguio City Ordinance</span>
            </div>

            <div class="flex flex-row items-center justify-center my-2">
                <span class="text-lg md:text-2xl font-sans text-center">City Ordinance No. 34, Series of 2017</span>
            </div> 

            <div class="flex flex-row items-center justify-center my-2">
                <hr class="my-2 border border-black w-[10rem] sm:w-[40rem]">
            </div>

            <div class="flex flex-row items-center justify-center px-2">
                <a class="text-center text-md md:text-xl font-sans font-normal">Prohibiting the use, sale, distribution, and advertisement of ciggaretes and other tobacco products in certain places, imposing penalties for violations thereof and providing funds thereof, and for other purposes.</a>
            </div>

            <div class="flex flex-row items-center justify-center mt-2 px-2">
                <a class="text-center text-md md:text-xl font-sans font-normal">The ordinance resulted to the creation of the Smoke-Free Baguio Task Force. Since then, Smoke-Free Baguio supports the Smoke-Free Baguio City Ordinance through Enforcement, monitoring and inspection in different barangays in Baguio City Conducting advocacy and enforcement training, seminars and workshops</a>
            </div>

        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'Ordinance',
    setup() { },
    data() {
        return {
            componentOptions: {},

            enforcers:null
        }
    },


    methods:{

    }
})
</script>
