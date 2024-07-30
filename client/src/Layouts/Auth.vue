<template>
  <Layout class="!relative">
    <PageContainer>
      <Page class="flex flex-auto items-center justify-center">
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
        <ThemeToggle class="absolute top-7 right-7" />
      </Page>
    </PageContainer>
    <TInnerLoading
      :active="systemStore.isLoading"
      :text="systemStore.loadingMessage"
    />
    <div
      class="pointer-events-none absolute bottom-0 right-0 select-none text-[11px] italic leading-none"
    >
      <div class="rounded-tl-lg bg-slate-900 px-2 leading-none text-slate-100">
        <span class="font-semibold"> Powered By: </span>
        Management Information Technology Division
      </div>
    </div>
  </Layout>
</template>
<script setup lang="ts">
import { onMounted } from "vue";
import { useSystemStore } from "@/stores";

const systemStore = useSystemStore();
</script>
