<template>
  <Page class="flex flex-col items-stretch p-4">
    <div class="flex items-center border-b pb-4">
      <div class="px-2">
        <img src="/favicons/baguioseal.svg" class="w-24" />
      </div>
      <div class="px-2">
        <div class="border-b text-sm uppercase">
          Republic of the Philippines
        </div>
        <div class="text-4xl">City Government of Baguio</div>
      </div>
    </div>
    <div class=""></div>
  </Page>
</template>

<script setup lang="ts">
import { ref } from "vue";
const openDialog = ref<Boolean>(false);
</script>
