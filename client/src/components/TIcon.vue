<template>
  <component :is="'span'" :class="className" aria-hidden="true">
    {{ name }}
  </component>
</template>

<script setup>
import { computed, ref } from "vue";

const props = defineProps({
  name: String,
  type: {
    type: String,
    default: "outlined",
    validator: (val) => {
      return (
        ["filled", "outlined", "round", "sharp", "two-tone"].indexOf(val) > -1
      );
    },
  },
  size: {
    type: String,
    default: "md",
    validator: (val) => {
      return ["sm", "md", "lg", "xl"].indexOf(val) > -1;
    },
  },
});

const className = computed({
  get() {
    return [
      "!leading-none select-none",
      props.type == "filled" && "material-icons",
      props.type == "outlined" && "material-icons-outlined",
      props.type == "round" && "material-icons-round",
      props.type == "sharp" && "material-icons-sharp",
      props.type == "two-tone" && "material-icons-two-tone",
      `mi-${props.size}`,
    ];
  },
});
</script>

<style scoped lang="scss">
.mi-sm {
  @apply text-lg;
}
.mi-md {
  @apply text-2xl;
}
.mi-lg {
  @apply text-4xl;
}
.mi-xl {
  @apply text-5xl;
}
</style>
