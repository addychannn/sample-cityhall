<template>
  <div class="flex flex-col">
    <div class="relative mb-2 flex max-w-xs flex-col overflow-hidden">
      <ErrorTemplate :code="code" :error="error" :description="description" />
    </div>
    <NavLinks />
  </div>
</template>

<script setup>
import NavLinks from "./links.vue";

const props = defineProps({
  code: { type: Number, default: null },
  error: { type: String, default: null },
  description: { type: String, default: null },
});
</script>
