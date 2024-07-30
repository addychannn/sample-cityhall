<template>
  <TCard class="mb-2 bg-slate-100 dark:bg-slate-900">
    <TCardBody class="flex justify-center text-xs">
      <template
        v-for="(link, index) in visibleLinks"
        :key="`nav_link_${link.label}`"
      >
        <router-link :to="link.to" class="hover: flex items-center px-3">
          <TIcon :name="link.icon" size="sm" class="mr-1" /> {{ link.label }}
        </router-link>
        <div
          v-if="index != visibleLinks.length - 1"
          class="border-l border-slate-700/25"
        />
      </template>
    </TCardBody>
  </TCard>
</template>
<script setup>
import { computed, ref } from "vue";
import { useAuthStore } from "@/stores";

const authStore = useAuthStore();
const links = ref([
  {
    label: "Home",
    to: { name: "HomePage" },
    icon: "home",
    hidden: false,
  },
  {
    label: "Login",
    to: { name: "login" },
    icon: "home",
    hidden: authStore.isLoggedIn,
  },
]);

const visibleLinks = computed(() => {
  return links.value.filter((item) => !item.hidden);
});
</script>
