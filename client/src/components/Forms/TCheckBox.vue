<template>
  <component :is="'TButton'" v-bind="attr" @click="toggleValue">
    <TIcon
      :name="iconName"
      :class="iconClass"
      :size="iconSize"
      :type="iconType"
    />
    {{ label }}
  </component>
</template>

<script setup>
import { computed } from "vue";
import { Helpers } from "@/scripts";

const props = defineProps({
  modelValue: Boolean,
  label: {
    type: String,
    default: "",
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  iconSize: {
    type: String,
    default: "md",
    validator: (val) =>
      ["sm", "md", "lg", "xl"].indexOf(val.toLowerCase()) > -1,
  },
  iconType: {
    type: String,
    default: "outlined",
    validator: (val) =>
      ["filled", "outlined", "round", "sharp", "two-tone"].indexOf(
        val.toLowerCase()
      ) > -1,
  },
  iconClass: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);

const data = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const attr = computed(() => ({
  id: Helpers.uniqid(),
  disabled: props.disabled,
  role: "checkbox",
  "aria-checked": data.value,
  class: [
    "flex items-center gap-1",
    props.disabled && "cursor-not-allowed text-gray-500/50",
  ],
}));

const iconName = computed(() => {
  let name = data.value ? "check_box" : "check_box_outline_blank";
  if (data.value === undefined) {
    name = "indeterminate_check_box";
  }
  return name;
});

const toggleValue = () => {
  data.value = data.value === undefined || data.value === false;
};
</script>
