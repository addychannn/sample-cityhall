<template>
  <div
    v-if="indeterminate"
    class="absolute inset-y-0 left-[50%] w-1/3 animate-unprogress overflow-hidden rounded-full bg-primary transition"
  ></div>
  <div
    v-else
    class="absolute inset-y-0 left-0 animate-pulse overflow-hidden rounded-full bg-primary transition-all duration-500"
    :style="{ width: `${value}%` }"
  >
    <slot :value="value"></slot>
  </div>
</template>
<script setup>
const props = defineProps({
  value: Number,
  indeterminate: {
    type: Boolean,
    default: false,
  },
});
</script>
