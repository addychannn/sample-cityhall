<template>
    <Page class="p-5 flex flex-col gap-5">
        <!--Heder-->
        <div class="flex flex-col">
            <div class="flex flex-col text-center">
                <span class="font-sans font-bold text-3xl text-green-600">{{ report.id }}</span>
            </div>
            <div class="flex flex-col text-center">
                <span class="font-sans pt-2">Date Reported:</span>
                <span class="font-sans text-slate-800 text-lg">{{ report.created_at }}</span>
            </div>
        </div>

        <div class="flex flex-row items-center justify-center">
            <hr class=" border border-black w-[10rem] sm:w-[40rem]">
        </div>

        


        <!--Complainant Details-->
        <div class="flex flex-col">
            <label class="font-sans font-bold text-base">Complainant Details</label>
            <!--Complainant Name-->
            <div v-if="report.first_name||report.middle_name||report.last_name||report.suffix" class="flex flex-row gap-1 pl-2 items-center justify-between">
                <label class="font-sans font-bold items-start justify-start">Name:</label>
                <span class="font-sans items-end justify-end">
                    {{ report.first_name }}
                    {{ report.middle_name }}
                    {{ report.last_name }}
                    {{ report.suffix }}
                </span>
            </div>
            <div v-else class="flex flex-row gap-1 pl-2 items-start justify-between">
                <span class="font-sans font-bold items-start justify-start">Name: </span>
                <span class="font-sans items-end justify-end">(Not Provided)</span>
            </div>
            <!--Complainant Contact Number-->
            <div class="flex flex-row gap-1 pl-2 items-center justify-between">
                <span class="font-sans font-bold items-start justify-start">
                    Contact Number: 
                </span>
                <span class="font-sans pl-2 md:pl-0 items-end justify-end">{{ report.contact_number }}</span>
            </div>
            <!--Complainant Email-->
            <div class="flex flex-row gap-1 pl-2 items-center justify-between">
                <span class="font-sans font-bold items-start justify-start">
                    Email: 
                </span>
                <span class="font-sans pl-2 md:pl-0 items-end justify-end">{{ report.email ?? '(Not Provided)' }}</span>
            </div>
        </div>

        

        <!--Violator Details-->
        <div class="flex flex-col">
            <label class="font-sans font-bold text-base text-center md:text-start">Violator Details</label>
            <!--Violator Name-->
            <div v-if="report.violator_name" class="flex flex-row gap-1 pl-2 items-center justify-between">
                <label class="font-sans font-bold items-start justify-start">Name:</label>
                <span class="font-sans pl-2 md:pl-0 items-end justify-end">
                    {{ report.violator_name ?? '(Not Provided)'}}
                </span>
            </div>

            <!--Location-->
            <div class="flex flex-row gap-1 pl-2 items-center justify-between">
                <span class="font-sans font-bold items-start justify-start">Location: </span>
                <span class="font-sans items-end justify-end">
                    {{ report.violator_location }}
                    {{ report.barangay }}
                </span>
            </div>

            <!--Violator Landmark-->
            <div class="flex flex-row gap-1 pl-2  justify-between">
                <span class="font-sans font-bold items-start justify-start">Landmark: </span>
                <span class="font-sans items-end justify-end text-right">{{ report.landmark }}</span>
            </div>

            <!--Date of Violation-->
            <div class="flex flex-row gap-1 pl-2 items-center justify-between">
                <span class="font-sans font-bold items-start justify-start">Date of Violation: </span>
                <span class="font-sans items-end justify-end">{{report.violation_date}}</span>
            </div>

            

        
        </div>
        <!--Violation-->
        <div class="flex flex-row gap-1 pl-2 justify-between">
            <div class="flex flex-col">
                <span class="font-sans font-bold items-start justify-start">Violation: </span>
            </div>
           <div class="flex flex-col">
            <span class="font-sans items-end justify-end text-right">{{ report.violation_description }}</span>
           </div>
        
        </div>

        <!--Images-->
        <div  v-if="gallery" class="flex flex-col">
            <label class="font-sans font-bold">Photo Evidence:</label>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                <div class="flex flex-col w-full h-[15rem] rounded-2xl" v-for="image in gallery">
                    <img :src="image.thumbnails.medium" class="rounded-2xl hover:scale-110 w-full h-full">
                </div>
            </div>
        </div>
        <div v-else class="flex flex-col">
            <label class="font-sans font-bold">Images:</label>
            <span class="font-sans text-slate-800 pl-2">(No Submitted Images)</span>
        </div>
    </Page>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'ViewReportDetails',
    setup(){},
    mounted(){
        this.getReportDetails()
    },
    props:{
        report:{
            type:Object,
            default:null
        }
    },
    data(){
        return{
            componentOptions: {},
            gallery:[]
        }
    },
    methods:{
        getReportDetails(){
            this.gallery = this.report.image
        }
    }
})
</script>
