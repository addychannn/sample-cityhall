<template>
    <Page class="h-full w-full p-5 ">
        <div class="flex flex-col p-10  border rounded-2xl">
            <span class="text-2xl font-sans font-bold">Name of Ordinance</span>
            <input class="h-10 rounded-xl mb-5 font-sans px-5 bg-slate-300" type="text" placeholder="EG. Smoke Free Baguio Ordinance">

            <span class="text-2xl font-sans font-bold mt-5">City Ordinance Number and Series</span>
            <input class="h-14 rounded-xl mb-5 font-sans  px-5 bg-slate-300" type="text" placeholder="EG. City Ordinance No. 34 Series of 2017">

            <span class="text-2xl font-sans font-bold mt-5">Ordinance Description</span>
            <input class="h-40 rounded-xl mb-5 font-sans px-5 bg-slate-300" type="text" placeholder="EG. Prohiting the use, sale, distribution.... ">
            
            <div class="flex flex-col justify-center items-center p-5 w-full">
                <hr class="border border-amber-600 w-full">
            </div>

            <div class="flex flex-row justify-center gap-7">
             <span class="px-5 py-2 bg-green-600 rounded-full text-3xl font-sans text-white">Create</span>
             <span class="px-5 py-2 bg-green-600 rounded-full text-3xl font-sans text-white">Cancel</span>
            </div>
        </div>
        
    </Page>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'CreateOrdinance',
    setup(){},
    data(){
        return{
            componentOptions: {}
        }
    }
})
</script>
