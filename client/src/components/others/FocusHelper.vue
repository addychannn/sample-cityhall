<template>
  <component
    :is="tag"
    class="pointer-events-none absolute inset-0 !block h-full w-full opacity-0 transition-opacity"
    :class="[
      color,
      hover && 'opacity-20',
      dark && !color && 'bg-dark',
      !dark && !color && 'bg-light',
    ]"
    ref="focushelperel"
  >
  </component>
</template>
<script setup>
import { onBeforeUnmount, onMounted, ref } from "vue";

const props = defineProps({
  tag: {
    type: String,
    default: "span",
  },
  dark: {
    type: Boolean,
    default: false,
  },
  color: {
    type: String,
    default: null,
  },
});

const events = {
  active: ["mouseenter", "focus"],
  inactive: ["mouseleave", "blur"],
};

const focushelperel = ref();

const hover = ref(false);

const onParentMouseEnter = () => {
  hover.value = true;
};

const onPrentMouseLeave = () => {
  hover.value = false;
};

onMounted(() => {
  events.active.forEach((event) => {
    focushelperel.value.parentElement.addEventListener(
      event,
      onParentMouseEnter
    );
  });
  events.inactive.forEach((event) => {
    focushelperel.value.parentElement.addEventListener(
      event,
      onPrentMouseLeave
    );
  });
});
onBeforeUnmount(() => {
  events.active.forEach((event) => {
    focushelperel.value.parentElement.removeEventListener(
      event,
      onParentMouseEnter
    );
  });
  events.inactive.forEach((event) => {
    focushelperel.value.parentElement.removeEventListener(
      event,
      onPrentMouseLeave
    );
  });
});
</script>
