<template>
    <Page>
        <div class="flex flex-col md:px-4">
            <div class="block items-center justify-center rounded-3xl px-1 py-1 md:px-5 xl:px-10 md:py-10">
                <!--Header-->
                <div class="flex flex-row md:py-7 lg:px-5 justify-between relative">
                    <!--Page Title-->
                    <div class="flex flex-row items-center">
                        <TIcon name="gavel" size="xl" class="text-slate-800 pl-2 md:px-2 "></TIcon>
                        <span class="text-xl md:text-3xl lg:text-5xl font-sans text-slate-800 font-semibold">Ordinance Section Management</span>
                    </div>
                    <!--Actions for md and above screen-->
                    <div class="hidden md:flex flex-row items-end justify-center pr-4 gap-1">
                        <TIcon name="add" size="md"
                            class="cursor-pointer bg-green-600 text-white rounded-full p-0.5 p-2 shadow-md hover:scale-110 hover:bg-green-700">
                        </TIcon>
                    </div>
                    <!--Action for sm screen-->
                    <div class="flex md:hidden flex-col items-end justify-center ">
                        <TIcon @click="showActions" name="more_vert" size="md" class="cursor-pointer"></TIcon>
                        <!--Actions-->
                        <div v-if="componentOptions.openOptions" class="absolute z-50 top-10 right-3 w-3/5 flex flex-col bg-white shadow-md">
                            <div class="flex flex-row cursor-pointer hover:bg-gray-400 w-full justify-end pr-1">
                                <span class="font-sans">Add Section</span>
                            </div>

                            <div class="flex flex-row cursor-pointer hover:bg-gray-400 justify-end w-full pr-1">
                                <span class="font-sans">Arrange Section</span>
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

export default defineComponent({
    name: 'Ordinance',
    setup(){},
    data(){
        return{
            componentOptions: {}
        }
    }
})
</script>
