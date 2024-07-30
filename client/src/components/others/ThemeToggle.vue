<template>
  <button
    class="my-1 aspect-square w-10 rounded-full leading-none hover:bg-foreground/25"
    @click="systemStore.toggleTheme(false)"
  >
    <div class="group relative flex items-center justify-center leading-none">
      <span class="sr-only">
        {{ systemStore.theme.dark ? "light" : "dark" }} mode
      </span>
      <TIcon :name="systemStore.theme.dark ? 'light_mode' : 'dark_mode'" />
    </div>
  </button>
</template>

<script setup>
import { onMounted } from "vue";
import { useSystemStore } from "@/stores";

const systemStore = useSystemStore();

onMounted(() => {
  systemStore.toggleTheme(true);
});
</script>
