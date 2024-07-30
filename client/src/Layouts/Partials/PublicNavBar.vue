<template>
    <div class="flex w-full p-3 box-shadow: none; border-radius: 10rem; bg-gray-300 shadow-md">
        <div class="flex justify-between w-full">
            <!--GOVPH,Logo and text -->
            <div class="flex flex-none gap-2">
                <div class="flex items-center">
                    <a href="https://www.gov.ph/" class="font-sans px-5 font-bold text-sm">GOVPH</a>
                </div>
                <div class="flex items-center">
                    <img src="/src/assets/baguioseal.png" class="h-10 mr-2 md:h-12" alt="BaguioSeal" />
                    <div class="hidden md:flex flex-col">
                        <span id="republic_text" class="font-sans text-sm">Republic of the Philippines</span>
                        <div style="border-top: 1px solid black;"></div>
                        <span id="citygov" class="font-extrabold font-sans text-sm">City Government of Baguio</span> 
                    </div>
                </div>
            </div>
            <!-- nav items -->
            <div class="hidden xl:flex">
                <router-link v-for="link in links" :to="{ name: link?.name }">
                    <div class="flex">
                        <button class="font-sans font-bold py-2.5 relative rounded group overflow-hidden text-xs">
                            <span
                            class="font-sans font-bold absolute top-0 left-0 flex w-full h-0 mb-0 transition-all duration-300 ease-out transform translate-y-0 bg-green-600 group-hover:h-full opacity-90"></span>
                            <span class="font-sans gap-10 justify-between px-4 relative group-hover:text-white">{{ link?.alt }}</span>
                        </button>
                    </div>
                </router-link>
            </div>

            <!--hamburger-->
            <div class="flex xl:hidden items-center">
                <TIcon name="menu" size="sm" @click="$emit('mobileToggle')" class="cursor-pointer"></TIcon>
                <div v-if="show" class="absolute z-50 top-12 left-0 bg-slate-200 w-full flex flex-col p-3 gap-3 rounded-lg">
                    <router-link v-for="link in links" :to="{ name: link?.name }">
                        <div class="flex py-2.5 justify-end relative rounded group overflow-hidden font-semibold text-sm transition-all duration-300 ease-out transform translate-y-0 hover:bg-green-600 group-hover:h-full opacity-90" @click="$emit('navigated')">
                            <span class="font-sans gap-10 justify-end items-end px-4 relative group-hover:text-white">{{ link?.alt }}</span>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'PublicNavBar',
    emits:['mobileToggle','navigated'],
    props: {
        links: {
            type: Array,
            default: [],
            required: true,
        },
        show: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            componentOptions: {}
        }
    }
})
</script>

<style scoped>
.router-link-exact-active{
    @apply underline underline-offset-8 decoration-green-600 decoration-4 ;
}
</style>