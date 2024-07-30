<template>
  <Layout class="!relative">
    <Header
      class="flex border-b border-foreground/50 bg-background p-0 text-foreground shadow-md transition-colors"
    >
      <SizeObserver @resize="onHeaderResize" />
      <div v-if="isScreenSmaller" class="flex min-h-full min-w-full items-center gap-1 px-4 bg-gray-300">
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
            class="flex items-center justify-center p-2 text-base font-semibold font-sans"
          >
            {{ $system.product_name }}
          </span>
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
import MainSideBar from "@/layouts/main/MainSideBar.vue";

const $screen = inject("$screen");
const $guard = inject("$guard");
const systemStore = useSystemStore();

const menu = reactive([
    {
      title: "Main Navigation",
      hidden: false,
      links: [
        {
          to: { name: "AdminHome" },
          label: "HOME",
          icon: "home",
          collapse: false,
        },
        {
          to: $guard.can(['about_section_list',
                    'about_section_add',
                    'about_section_edit',
                    'about_section_delete',
                    'about_section_visibility',
                    'about_section_arrange',]) ? { name: "AdminAbout" } : { name: "Unauthorized" },
          label: "ABOUT",
          icon: "info",
          collapse: false,
        },
        {
          to: $guard.can(['quitter_list',
                        'quitter_reject',
                        'quitter_accept',
                        'quitter_reschedule',]) ? { name: "QuitterManagement" } : { name: "Unauthorized" },
          label: "QUITTERS",
          icon: "smoke_free",
          collapse: false,
        },
        {
          to: $guard.can(['report_list',
                    'report_reject',
                    'report_resolve',]) ? { name: "ReportManagement" } : { name: "Unauthorized" },
          label: "REPORTS",
          icon: "report",
          collapse: false
        },
        {
          to: { name: "AdminTestimonials" },
          label: "TESTIMONIALS",
          icon: "record_voice_over",
          collapse: false,
        },
        {
          to: { name: "Events" },
          label: "EVENTS",
          icon: "calendar_month",
          collapse: false,
          
        },
        {
          to: { name: "Articles" },
          label: "ARTICLES",
          icon: "newspaper",
          collapse: false,
        },
        {
          to: $guard.can(['message_list',
                    'message_list-deleted',
                    'message_delete',]) ? { name: "Messages" } : { name: "Unauthorized" },
          label: "MESSAGE",
          icon: "email",
          collapse: false,
        },
        {
          to: $guard.can(['users_add',
                    'users_list',
                    'users_edit-profile',
                    'users_edit-account',
                    'users_edit-permission',
                    'users_change-status',
                    'users_give-direct-permissions',]) ? { name: "UserManagement" } : { name: "Unauthorized" },
          label: "USERS",
          icon: "people",
          collapse: false,
        },
        {
          to: $guard.can(['roles_list',
                    'roles_add',
                    'roles_edit',
                    'roles_delete']) ? { name: "RolesManagement" } : { name: "Unauthorized" },
          label: "ROLES",
          icon: "admin_panel_settings",
          collapse: false,
        },
        {
          to: $guard.can(['enforcer_view_submitted_forms',
            'enforcer_view_submitted_form_preview',
            'enforcer_delete_submitted_form',
            'enforcer_view_uploaded_forms',
            'enforcer_view_uploaded_form_preview',
            'enforcer_delete_uploaded_form',
            'enforcer_upload_new_form',
            'enforcer_view_deleted_uploaded_forms_list']) ? { name: "EnforcerRoom" } : { name: "Unauthorized" },
          label: "ENFORCER ROOM",
          icon: "engineering",
          collapse: false,
        },
      
        {
          to: $guard.hasRole('Admin') ? { name: "Logs" } : { name: "Unauthorized" },
          label: "LOGS",
          collapse: false,
        }
      ],
    },
  ]) 

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
