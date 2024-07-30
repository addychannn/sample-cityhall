<template>
  <perfect-scrollbar class="flex-auto px-3 py-2" :options="psOptions">
    <slot></slot>
  </perfect-scrollbar>
</template>

<script setup>
const props = defineProps({
  psOptions: {
    type: Object,
    default: () => ({}),
  },
});
</script>
