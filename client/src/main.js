import { createApp, computed } from "vue";
import "./css/index.scss";
import App from "./App.vue";
import MyComponents from "@/components";
import router from "@/router";

import { api, axios } from "./axios.js";
import { pinia, useAuthStore } from "./stores";
import PerfectScrollbar from "vue3-perfect-scrollbar";
import Notifications from "notiwind";

import TRipple from "@/plugins/vue/directives/TRipple";
import TrapFocus from "@/plugins/vue/directives/TrapFocus";
import VueApexCharts from "vue3-apexcharts";

import Modal from '@/components/Modal.vue';

import { guard } from "@/scripts";

import { breakpointsTailwind, useBreakpoints } from "@vueuse/core";



import Pagination from '@/components/Pagination.vue'
import VueDatePicker from '@vuepic/vue-datepicker';
// import '@vuepic/vue-datepicker/dist/main.scss'

import '@vuepic/vue-datepicker/src/VueDatePicker/style/main.scss';

async function init() {
  const app = createApp(App);
  const _guard = new guard([], []);

  for (let component in MyComponents) {
    app.component(component, MyComponents[component]);
  }

  pinia.use(() => ({
    $api: api,
    $axios: axios,
    $router: router,
    $guard: _guard,
  }));

  app.component('Pagination', Pagination)
  app.component('Modal',Modal)
  app.component('VueDatePicker', VueDatePicker);
  
  app.use(router);
  app.use(pinia);
 
  app.use(VueApexCharts);
  app.use(PerfectScrollbar);
  app.use(Notifications);


  app.directive("ripple", TRipple);
  app.directive("trapFocus", TrapFocus);

  const authStore = useAuthStore();
  if (authStore.isLoggedIn) {
    try {
      await authStore.getPermissions();
      _guard.setPermissions(authStore.getUserPermissions);
      _guard.setRoles(authStore.getRoles);
    } catch (e) {
      if (e.response?.status == 401) {
        authStore.$reset();
        router.push({ name: "login" });
      }
    }
  }

  app.config.globalProperties.$api = api;
  app.config.globalProperties.$axios = axios;
  app.config.globalProperties.$guard = _guard;
  app.config.globalProperties.$system = {
    product_name: import.meta.env.VITE_PRODUCT_NAME,
    short_name: import.meta.env.VITE_SHORT_NAME,
    description: import.meta.env.VITE_DESCRIPTION,
    office_name: import.meta.env.VITE_OFFICE_NAME,
  };

  for (let prop in app.config.globalProperties) {
    app.provide(prop, app.config.globalProperties[prop]);
  }
  const $screen = computed(() => useBreakpoints(breakpointsTailwind));
  app.provide("$screen", $screen);

  app.mount("#app");
}





init();

