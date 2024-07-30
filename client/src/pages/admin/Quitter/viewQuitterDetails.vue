<template>
    <Page class="p-5 flex flex-col gap-5">
        <!--Heder-->
        <div class="flex flex-col">
            <div class="flex flex-col text-center">
                <span class="font-sans font-bold text-3xl text-green-600">{{ quitter.hash }}</span>
            </div>
            <div class="flex flex-col text-center">
                <span class="font-sans pt-2">Appointment Schedule:</span>
                <span class="font-sans text-slate-800 text-lg">{{ quitter.preferred_date }}</span>
            </div>
        </div>

        <div class="flex flex-row items-center justify-center">
            <hr class=" border border-black w-[10rem] sm:w-[40rem]">
        </div>
        <!--Quitter Details-->
        <div class="flex flex-col">
            <!-- Name-->
            <label class="font-sans font-bold text-base">Quitter Details</label>
            <div class="flex flex-row gap-1 items-center justify-between">
                <label class="font-sans font-bold items-start justify-start">Name:</label>
                <span class="font-sans items-end justify-end">
                    {{ quitter.first_name }}
                    {{ quitter.middle_name }}
                    {{ quitter.last_name }}
                    {{ quitter.suffix }}
                </span>
            </div>
            <!--Contact Number-->
            <div class="flex flex-row gap-1 items-center justify-between">
                <span class="font-sans font-bold items-start justify-start">
                    Contact Number: 
                </span>
                <span class="font-sans items-end justify-end">{{ quitter.contact_number }}</span>
            </div>
            <!--Email-->
            <div class="flex flex-row gap-1 items-center justify-between">
                <span class="font-sans font-bold items-start justify-start">
                    Email: 
                </span>
                <span class="font-sans items-end justify-end">{{ quitter.email ?? '(Not Provided)' }}</span>
            </div>

            <!--Address-->
            <div class="flex flex-row gap-1 items-center justify-between">
                <span class="font-sans font-bold items-start justify-start">
                    Address: 
                </span>
                <span class="font-sans items-end justify-end">{{ quitter.location}} {{ quitter.barangay }}</span>
            </div>
        </div>

        <!--Reason-->
        <div class="flex flex-col">
            <span class="font-sans font-bold">
                Reason: 
                </span>
                <span class="font-sans pl-2">{{ quitter.additional_notes ?? '(Reason not Provided)'}}</span>
        </div>

        <!--Remarks-->
        <div class="flex flex-col">
            <span class="font-sans font-bold">
                Remarks: 
                </span>
                <span class="font-sans pl-2">{{ quitter.admin_remarks ?? '(No remarks)'}}</span>
        </div>
</Page>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'ViewQuitterDetails',
    setup(){},
    props:{
        quitter:{
            type: Object,
            default: null
        }
    },
    data(){
        return{
            componentOptions: {},
        }
    }
})
</script> 
