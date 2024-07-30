<template>
  <Layout class="!relative">
    <Header
      class="flex rounded-b-3xl border-b border-foreground/50 bg-background p-0 text-foreground shadow-md transition-colors"
    >
      <SizeObserver @resize="onHeaderResize" />
      <div class="flex min-h-full min-w-full items-center gap-1 px-4">
        <TButton
          v-if="isScreenSmaller"
          icon="menu"
          class="my-1 aspect-square w-10 rounded-full leading-none hover:bg-foreground/25"
          @click="_showMenu = true"
        />
        <div class="flex shrink grow items-center">
          <TImage
            src="/favicons/baguioseal.svg"
            class="h-12 w-12 rounded-full border-4 border-light"
          />
          <span
            class="flex items-center justify-center p-2 text-lg font-semibold md:text-2xl"
          >
            {{ $system.product_name }}
          </span>
        </div>
        <div>
          <nav class="h-full">
            <ul class="flex h-full items-center gap-2">
              <li class="h-full">
                <ThemeToggle />
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </Header>
    <PageContainer>
      <MainSideBar
        v-if="!isScreenSmaller"
        v-model:menu="menu"
        v-model:collapsed="sideCollapsed"
        class="fixed bottom-16 top-16"
        :style="sidebarStyle"
      />
      <Layout
        class="transition-all duration-300"
        :class="[
          !isScreenSmaller &&
            sideCollapsed &&
            'ml-14 !max-w-[calc(100vw_-_3.5rem)]',
          !isScreenSmaller &&
            !sideCollapsed &&
            'ml-[16.5rem] !max-w-[calc(100vw_-_16.5rem)]',
        ]"
      >
        <PageContainer>
          <router-view v-slot="{ Component }">
            <transition
              enter-from-class="opacity-0"
              leave-to-class="opacity-0"
              enter-active-class="transition duration-300 delay-300"
              leave-active-class="transition duration-300"
            >
              <component :is="Component"> </component>
            </transition>
          </router-view>
        </PageContainer>
      </Layout>
    </PageContainer>
    <TScrollUp
      class="bottom-14 right-8 z-50 rounded-2xl bg-gray-900/75 py-2 px-5 text-gray-100 shadow-md dark:bg-gray-500/75"
    >
      <TIcon name="keyboard_double_arrow_up" size="sm" />
    </TScrollUp>
    <TInnerLoading
      :active="systemStore.isLoading"
      :text="systemStore.loadingMessage"
    />
    <TDialog
      v-if="isScreenSmaller"
      v-model="_showMenu"
      position="left"
      full-height
      class="py-2"
    >
      <MainSideBar
        v-model:menu="menu"
        :collapsed="false"
        hideCollapse
        class="h-full"
      />
    </TDialog>
  </Layout>
</template>

<script setup>
import {
  computed,
  inject,
  onBeforeUnmount,
  onMounted,
  reactive,
  ref,
} from "vue";
import { useRoute } from "vue-router";
import { useSystemStore } from "@/stores";
import MainSideBar from "./Main/MainSideBar.vue";

const $screen = inject("$screen");
const systemStore = useSystemStore();
const menu = reactive([
  {
    title: "Main Navigation",
    hidden: false,
    links: [
      {
        to: { name: "settings-carousel" },
        label: "Carousel",
        description: "Carousel",
        icon: "view_carousel",
        collapse: false,
        sub: [
          {
            to: { name: "settings-carousel" },
            label: "Carousel",
            description: "Carousel",
            icon: "source",
          },
        ],
      },
    ],
  },
  {
    title: "Administrative Settings",
    hiden: false,
    links: [
      {
        to: { name: "settings-users" },
        label: "Users",
        description: "User Management",
        icon: "people",
      },
    ],
  },
]);
const route = useRoute();
const contain = ref(false);
const expand = ref(false);
const _showMenu = ref(false);
const headerSize = ref({
  width: 0,
  height: 0,
});
const scrolledDown = ref(false);
const scrollOffset = ref(0);

const isScreenSmaller = $screen.value.smaller("md");

const sideCollapsed = computed({
  get: () => systemStore.settings.sidebar.collapsed,
  set: (val) => {
    systemStore.settings.sidebar.collapsed = val;
  },
});

const sidebarStyle = computed(() => {
  return {
    height: `calc(100% - ${
      scrolledDown.value
        ? 24
        : headerSize.value.height + 24 - scrollOffset.value
    }px)`,
    top: `${
      scrolledDown.value
        ? 12
        : headerSize.value.height + 12 - scrollOffset.value
    }px`,
  };
});

const onHeaderResize = (size) => {
  headerSize.value = size;
};

const onBodyScroll = (e) => {
  scrollOffset.value = Math.floor(e.target.scrollTop);
  scrolledDown.value = e.target.scrollTop > 57;
};

onMounted(() => {
  document.body.addEventListener("scroll", onBodyScroll);
});
onBeforeUnmount(() => {
  document.body.removeEventListener("scroll", onBodyScroll);
});
</script>
