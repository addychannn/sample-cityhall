<template>
    <Page class="">

        <!-- sidebar -->

        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
            type="button"
            class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 bg-gray-300">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                </path>
            </svg>
        </button>

        <aside id="default-sidebar"
            class="fixed p-2 top-0 left-0 z-40 w-68 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-gray-300 shadow-lg"
            aria-label="Sidebar">

            <div class="flex items-center justify-center">

                <div class="p-2  ">
                    <a href="https://www.gov.ph/" class="font-sans font-bold text-2xl items-center justify-center">GOVPH</a>

                </div>

            </div>

            <div class="flex items-start justify-start">
                <img src="/src/assets/baguioseal.png" class="h-10 mr-2 md:h-12" alt="BaguioSeal" />
                <div class="hidden md:flex flex-col">
                    <span id="republic_text" class="font-sans  text-sm">Republic of the Philippines</span>
                    <div style="border-top: 1px solid black;"></div>
                    <span id="citygov" class=" font-sans font-semibold text-sm">City Government of Baguio</span>
                </div>
            </div>

            <div class="flex items-center justify-center p-3 ">

                <div style="border-top: 2px solid gray; border-bottom: 2px solid gray;" class="w-[15rem]">
                    <span class="flex items-center justify-center font-sans text-lg">Hello Admin</span>
                </div>
            </div>

            <div>
                <router-link v-for="link in links" :to="{ name: link?.name }">
                    <div class="flex items-center justify-center">
                        <button
                            class="font-sans text-lg font-bold py-2.5 relative rounded group overflow-hidden lg:text-md">
                            <span
                                class="font-sans px-5 font-bold absolute top-0 left-0 flex w-full h-0 mb-0 transition-all duration-300 ease-out transform translate-y-0 bg-green-600 group-hover:h-full opacity-90"></span>
                            <span class="font-sans gap-10 justify-between px-4 relative group-hover:text-white">{{
                                link?.alt }}</span>
                        </button>
                    </div>


                </router-link>



                <div class="flex  items-center justify-center h-[30rem]">

                    <div type="button" class="flex cursor-pointer items-center justify-center font-sans text-lg font-bold">
                        <TIcon @click.prevent.stop="authStore.logout()" name="logout"></TIcon>
                        Sign-Out
                    </div>


                </div>

            </div>
        </aside>

    </Page>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { useAuthStore } from "@/stores";

export default defineComponent({


    name: 'AdminNavBar',

    emits: ['mobileToggle', 'navigated'],
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
    setup(){},
    data(){
        return{
            componentOptions: {}
        }
    }
})

</script>


<!-- <script setup>
import { computed, onMounted, onBeforeUnmount, ref } from "vue";
import { useAuthStore } from "@/stores";
import { useRoute } from "vue-router";

const route = useRoute();
const props = defineProps({
    // menu: Object,
    // collapsed: {
    //     type: Boolean,
    //     default: null,
    // },
    hideCollapse: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:collapsed", "update:menu"]);

const authStore = useAuthStore();

const sbar = ref(null);
const transitioning = ref(false);
const scrolledDown = ref(false);
const scrollOffset = ref(0);

const _menu = computed({
    get: () => {
        return props.menu;
    },
    set: (val) => {
        emit("update:menu", val);
    },
});

const isCollpsed = computed({
    get: () => props.collapsed,
    set: (val) => {
        transitioning.value = true;
        emit("update:collapsed", val);
    },
});

const visibleLabel = computed(() => transitioning.value || !isCollpsed.value);

const onTransitionEnd = (e) => {
    transitioning.value = false;
};

const checkActiveSub = () => {
    props.menu.forEach((group) => {
        let tmp = group.links.filter(
            (item) =>
                !!item.sub && !!item.sub.find((sub) => sub.to.name == route.name)
        );
        tmp.forEach((parent) => {
            parent.collapse = true;
        });
    });
};

onMounted(() => {
    // checkActiveSub();
    sbar.value.addEventListener("transitionend", onTransitionEnd);
});

onBeforeUnmount(() => {
    sbar.value.removeEventListener("transitionend", onTransitionEnd);
});
</script> -->

<style scoped>
.router-link-exact-active {
    @apply underline underline-offset-8 decoration-green-600 decoration-4;
}
</style>