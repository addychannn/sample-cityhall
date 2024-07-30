<template>
  <div class="relative min-h-[2px] overflow-hidden rounded-full bg-gray-300/75">
    <TProgressBar :value="value" :indeterminate="indeterminate" />
  </div>
</template>

<script setup>
import { computed } from "vue";
import TProgressBar from "./TProgressBar.vue";

const props = defineProps({
  value: {
    type: Number,
    validator: (val) => val <= 100 && val >= 0,
    default: 0,
  },
  indeterminate: {
    type: Boolean,
    default: false,
  },
});
</script>
