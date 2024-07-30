<template>
    <div class="flex flex-col items-center justify-center p-4 md:p-8 md:pt-4">
        <div class="flex justify-center">
            <div class="flex flex-col md:flex-row items-center text-center text-slate-800">
                <TIcon name="summarize" size="xl" ></TIcon>
                <span class="font-sans text-2xl font-semibold flex flex-col items-center justify-center md:px-2">
                    Monthly Report
                </span>
            </div>
        </div>

        <div class="flex-1 pt-2 md:p-4">
            <p class="py-1 font-sans bg-white" disabled>
                -Fill out Barangay Monitoring Tool
            </p>
            <p class="py-1 font-sans bg-white" disabled>
                -Attach photocopy of SF2 (Compliance Monitoring Tool)
            </p>
            <p class="py-1 font-sans bg-white" disabled>
                -Submit every 5th of the month at the SF office or through here
            </p>
            <p class="py-1 font-sans bg-white" disabled>
                -Barangay should have own copy
            </p>
            <p class="py-1 font-sans bg-white" disabled>
                -Post pictures at Smoke-Free Facebook page to include number of establishments monitored,
            </p>
            <p class="py-1 font-sans bg-white" disabled>
                -Issued citation ticket, violations and individual violators
            </p>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'MonthlyReport',
    setup(){},
    data(){
        return{
            componentOptions: {}
        }
    }
})
</script>
