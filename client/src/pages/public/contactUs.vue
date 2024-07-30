<template>
    <Page >
        <div class="grid grid-cols:1 lg:grid-cols-2 w-full h-full gap-10 ">
            <!--Contact Us-->
            <div class="flex flex-col h-full bg-slate-200 border border-gray-200 rounded-3xl shadow-lg p-3 ">
                <div class="flex flex-row py-2 md:py-7 items-start w-full">
                    <TIcon name="connect_without_contact" size="lg" class="text-green-600 px-2"></TIcon>
                    <span class="text-2xl md:text-5xl font-sans text-center text-green-600">Contact Us</span>
                </div>

                <div class="flex flex-row pb-3">
                    <span class="text-md font-sans text-center text-gray-600">We want to hear from you! For further information, inquiries, comments, suggestions, and insights, please message us through this form. Don’t forget to click “Submit” to send your message. Your personal information is safe with us.</span>
                </div>

                 <!-- FORM -->

                 <div class="pb-6">
                    <form class="h-full w-full">
                        <div class="mb-6">
                            <label for="text"
                                class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                name</label>
                            <input  v-model="form.firstname" type="text" id="text"
                                class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="First name (Required)" required>
                        </div>
                        <div class="mb-6">
                            <label for="text"
                                class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                name</label>
                            <input  v-model="form.lastname" type="text" id="text"
                                class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Last name (Required)" required>
                        </div>
                        <div class="mb-6">
                            <label for="email"
                                class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input v-model="form.email" type="email" id="email"
                                class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Email">
                        </div>
                        <div class="mb-6">
                            <label for="contact"
                                class="block font-sans mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact</label>
                            <input v-model="form.contact" type="number" id="contact"
                                class="bg-gray-50 font-sans border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Contact (Required)" required>
                        </div>
                        <div class="mb-6">
                            <label for="message"
                                class="font-sans block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                message</label>
                            <textarea v-model="form.message" id="message" rows="4"
                                class="font-sans block p-2.5 w-full h-[5rem] text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>


                        <div class="flex justify-end">
                            <div
                                class="w-[7rem] font-sans flex justify-end items-center font-bold  bg-gray-800  rounded-full cursor-pointer p-2 text-lg  text-white transition-all duration-150 hover:scale-110 hover:bg-gray-900 bg-gradient-to-r from-gray-500 to-gray-500 hover:from-green-500 hover:to-green-600 shadow-xl ">

                                <span @click="submit" class="font-sans">Submit
                                </span>
                                <TIcon name="arrow_forward" class="flex cursor-pointer"></TIcon>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>

            <!--Contact Details-->
            <div class="flex flex-col w-full h-full">
                <div class="h-full w-full items-center justify-center bg-slate-200 border border-gray-200 rounded-3xl shadow-lg p-3">

                    <div class="flex flex-row p-5 py-7 items-start justify-start">
                        <TIcon name="import_contacts" size="lg" class="text-green-600 px-2"></TIcon>
                        <span class="text-2xl md:text-5xl font-sans text-center text-green-600">Contact Details</span>
                    </div>

                    <!-- CONTACT DEETS -->
                    <form class="p-2 ">
                        <div class="flex flex-row items-center justify-center pb-3">
                            <span class="text-md font-sans text-center text-gray-600">We would like to hear from you! For further information and inquiries, please contact the following:</span>
                        </div>
                        <!--Contact No.-->
                        <div class="flex-col p-2 grid sm:grid-cols-1 md:grid-cols-3 items-center justify-center text-center gap-1">
                            <!-- hospital services -->
                            <div class="flex flex-col items-center justify-center w-full h-full">
                                <a href="#"
                                    class="w-full h-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 ">
                                    <div class="flex flex-col items-center justify-center">
                                        <TIcon name="local_hospital" size="xl" class=""></TIcon>
                                    </div>

                                    <h5
                                        class="flex items-center justify-center font-sans mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white ">
                                        Health Services Office</h5>
                                    <p
                                        class="flex items-center justify-center font-sans text-gray-700 dark:text-gray-400">
                                        442-4542</p>
                                </a>

                            </div>
                            <!-- sanitation dept -->
                            <div class="flex flex-col items-center justify-center w-full h-full">
                                <a href="#"
                                    class="w-full h-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 ">
                                    <div class="flex flex-col items-center justify-center">
                                        <TIcon name="apartment" size="xl" class=""></TIcon>
                                    </div>

                                    <h5
                                        class="flex items-center justify-center font-sans mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white ">
                                        Sanitation Department</h5>
                                    <p
                                        class="flex items-center justify-center font-sans text-gray-700 dark:text-gray-400">
                                        (074)-444-9217</p>
                                    <p
                                        class="flex items-center justify-center font-sans text-gray-700 dark:text-gray-400">
                                        300-3895</p>


                                </a>

                            </div>
                            <!-- hepo -->
                            <div class="flex flex-col items-center justify-center w-full h-full">
                                <a href="#"
                                    class="w-full h-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 ">
                                    <div class="flex flex-col items-center justify-center">
                                        <TIcon name="medical_information" size="xl" class=""></TIcon>
                                    </div>

                                    <h5
                                        class="flex items-center justify-center font-sans mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white ">
                                        HEPO</h5>
                                    <p
                                        class="flex items-center justify-center font-sans text-gray-700 dark:text-gray-400">
                                        300-3953</p>


                                </a>

                            </div>
                        </div>

                        <!-- address eme eme -->
                        <div
                            class="flex-col p-2 grid grid-cols-1 md:grid-cols-3 items-center justify-center text-center gap-1">
                            <!-- hospital services -->
                            <div class="flex flex-col items-center justify-center w-full h-full">
                                <a target="_blank" href="https://www.google.com/maps/place/Baguio+City+Health+Department+(Health+Services+Office)/@16.4170865,120.5917035,17z/data=!4m10!1m2!2m1!1scity+health+office+baguio!3m6!1s0x3391a160873b0311:0x47692ffe03a2901!8m2!3d16.4203527!4d120.5965263!15sChljaXR5IGhlYWx0aCBvZmZpY2UgYmFndWlvkgEKZ292ZXJubWVudOABAA!16s%2Fg%2F1tlmkvvj"
                                    class="w-full h-full max-w-sm p-3 bg-white border border-gray-200 rounded-lg shadow hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 ">
                                    <div class="flex flex-col items-center justify-center">
                                        <TIcon name="location_on" size="xl" class=""></TIcon>
                                    </div>
                                    <p
                                        class="flex items-center justify-center text-sm font-sans text-gray-700 font-bold dark:text-gray-400">
                                        3rd floor, City Health Services Office, T. Alonzo, Baguio City</p>

                                </a>

                            </div>
                            <!-- sanitation dept -->
                            <div class="flex flex-col items-center justify-center w-full h-full">
                                <a href="#"
                                    class="w-full h-full  max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 ">
                                    <div class="flex flex-col items-center justify-center">
                                        <TIcon name="call" size="xl" class=""></TIcon>
                                    </div>
                                    <p
                                        class="flex items-center justify-center text-sm font-sans text-gray-700 font-bold dark:text-gray-400">
                                        0927 549 2321</p>
                                    <p
                                        class="flex items-center justify-center text-sm font-sans text-gray-700 font-bold dark:text-gray-400">
                                        Mon - Fri, 9:00 a.m. to 4:00 p.m.
                                    </p>
                                </a>
                            </div>
                            <!-- hepo -->
                            <div class="flex flex-col items-center justify-center w-full h-full">
                                <a target="_blank" href="https://mail.google.com/mail/u/0/#inbox?compose=DmwnWtMgCNGnFkCtLnfrfKQDsbPLmDkgxhHdfbNfJbHDBSwZMNpDpFQXzQQGFnVlFlbBpSnMDSrl"
                                    class="w-full h-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-green-600 ">
                                    <div class="flex flex-col items-center justify-center">
                                        <TIcon name="email" size="xl" class=""></TIcon>
                                    </div>
                                    <p
                                        class="flex items-center justify-center text-xs md:text-sm font-sans text-gray-700 font-bold dark:text-gray-400">
                                        sfbaguiocity@gmail.com
                                    </p>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!--Soc Med-->
                    <div class="px-4 flex flex-col items-center justify-center ">
                        <div class="flex flex-row items-center justify-center pb-3">
                            <span class="text-md font-sans text-center text-gray-600">You may also follow and message us
                                on:</span>
                        </div>
                        <div class="flex flex-col md:flex-row lg:flex-row xl:flex-row sm:flex-row gap-5 items-center justify-center">
                            <a target="_blank" href="https://twitter.com/BaguioSmokeFree">

                                <button type="button"
                                    class="text-white bg-[#1da1f2] hover:bg-[#1da1f2]/90 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#1da1f2]/55 mr-2 mb-2 shadow-xl">
                                    <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false"
                                        data-prefix="fab" data-icon="twitter" role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M459.4 151.7c.325 4.548 .325 9.097 .325 13.65 0 138.7-105.6 298.6-298.6 298.6-59.45 0-114.7-17.22-161.1-47.11 8.447 .974 16.57 1.299 25.34 1.299 49.06 0 94.21-16.57 130.3-44.83-46.13-.975-84.79-31.19-98.11-72.77 6.498 .974 12.99 1.624 19.82 1.624 9.421 0 18.84-1.3 27.61-3.573-48.08-9.747-84.14-51.98-84.14-102.1v-1.299c13.97 7.797 30.21 12.67 47.43 13.32-28.26-18.84-46.78-51.01-46.78-87.39 0-19.49 5.197-37.36 14.29-52.95 51.65 63.67 129.3 105.3 216.4 109.8-1.624-7.797-2.599-15.92-2.599-24.04 0-57.83 46.78-104.9 104.9-104.9 30.21 0 57.5 12.67 76.67 33.14 23.72-4.548 46.46-13.32 66.6-25.34-7.798 24.37-24.37 44.83-46.13 57.83 21.12-2.273 41.58-8.122 60.43-16.24-14.29 20.79-32.16 39.31-52.63 54.25z">
                                        </path>
                                    </svg>
                                    <span class="font-sans">Twitter</span>
                                </button>

                            </a>

                            <a target="_blank" href="https://www.facebook.com/smokefreebaguiocity">

                                <button type="button"
                                    class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55 mr-2 mb-2 shadow-xl">
                                    <svg class="w-4 h-4 mr-2 -ml-1" aria-hidden="true" focusable="false"
                                        data-prefix="fab" data-icon="facebook-f" role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                        <path fill="currentColor"
                                            d="M279.1 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.4 0 225.4 0c-73.22 0-121.1 44.38-121.1 124.7v70.62H22.89V288h81.39v224h100.2V288z">
                                        </path>
                                    </svg>


                                    <span class="font-sans">Facebook</span>
                                </button>
                            </a>

                            <a target="_blank" href="https://www.facebook.com/messages/t/1635822326638141">

                                <button type="button"
                                    class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-sm px-3 py-2.5 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55 mr-2 mb-2 shadow-xl">
                                    <div
                                        class="font-sans flex justify-center items-center font-bold  cursor-pointer px-1 text-md  text-white transition-all duration-150 hover:scale-110  shadow-xl ">

                                        <TIcon name="messenger" size="sm"></TIcon>

                                    </div>


                                    <span class="font-sans">Messenger</span>
                                </button>

                            </a>




                        </div>
                    </div>
                </div>
            </div>
        </div>

</Page>
</template>

<script>
import { defineComponent } from 'vue';
import imageCompression from 'browser-image-compression';
import TFileDrop from '@/components/Forms/TFileDrop.vue';
import { notify } from 'notiwind';

export default defineComponent({
    name: 'ContactUs',
    setup() { },
    components: {
        TFileDrop
    },
    data() {
        return {
            componentOptions: {},
            form: {
                firstname: '',
                lastname: '',
                email: '',
                contact: '',
                message: '',

            },
            files: []



        }
    },
    methods: {
         submit() {
            let token = null
             this.$api.post('public/home/create', this.form)
                .then((response) => {
                   
                    token = response.data.message.hash
                    notify({group:"main", title:"Message Sent", type:"positive"},'3000')
                })
                this.clearForm()
        },
        clearForm(){
            this.form.firstname =''
            this.form.lastname =''
            this.form.email =''
            this.form.contact =''
            this.form.message =''
        }

    }
})
</script>
