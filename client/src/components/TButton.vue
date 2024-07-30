<template>
  <component
    :is="tag"
    class="relative overflow-hidden leading-none"
    :class="[disabled && 'pointer-events-none text-gray-400']"
    v-ripple="ripple"
    v-bind="$attrs"
    :disabled="disabled"
  >
    <FocusHelper :color="focusClass" />
    <slot>
      <div
        class="pointer-events-none flex flex-auto items-center justify-center font-semibold leading-none"
      >
        <TIcon
          v-if="!!icon"
          :name="icon"
          :size="iconSize"
          :class="[!!label && 'mr-1']"
        />
        <span v-if="!!label" class="leading-none">
          {{ label }}
        </span>
      </div>
    </slot>
  </component>
</template>

<script setup>
import { useSlots } from "vue";

const slots = useSlots();
const props = defineProps({
  tag: {
    type: String,
    default: "button",
  },
  label: {
    type: String,
    default: "",
  },
  icon: {
    type: String,
    default: null,
  },
  iconSize: {
    type: String,
    default: "md",
    validator: (val) => {
      return ["sm", "md", "lg", "xl"].indexOf(val) > -1;
    },
  },
  focusClass: {
    type: String,
    default: "bg-foreground",
  },
  ripple: {
    type: Object,
    default: () => ({}),
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});
</script>
