<template>
    <Page>
        <div class="flex flex-col xl:flex-row items-center justify-center w-full h-full">
            <div class="flex flex-col">
                <img src="@/assets/smokebaguio.png" class="h-[12rem] w-[12rem] md:h-[20rem] md:w-[20rem] lg:h-[24rem] lg:w-[24rem] xl:w-[28rem]">
            </div>
            <div class="flex flex-col text-center">
                <span class="font-sans text-2xl font-bold">Unauthorized</span>
                <span class="font-sans">You are not authorized to view the requested page/data!</span>
            </div>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'Unauthorized',
    setup(){},
    data(){
        return{
            componentOptions: {}
        }
    }
})
</script>