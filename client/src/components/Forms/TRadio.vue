<template>
  <div
    class="inline-flex cursor-pointer items-center outline-0"
    role="radio"
    :aria-label="label"
    :aria-checked="checked"
    tabindex="0"
    @click="select"
    @mouseenter="focused = true"
    @focus="focused = true"
    @blur="focused = false"
    @mouseleave="focused = false"
  >
    <div
      class="relative flex aspect-square w-8 items-center justify-center rounded-full before:absolute before:inset-0 before:origin-center before:scale-0 before:transform-gpu before:rounded-full before:bg-current before:opacity-[0.12] before:transition-transform before:content-['']"
      :class="[focused && 'before:scale-100']"
      aria-hidden="true"
    >
      <input type="radio" class="hidden" :checked="checked" :name="name" />
      <TIcon
        :name="checked ? checkedIcon : uncheckedIcon"
        :class="[checked && color, !checked && 'text-gray-700']"
      />
    </div>
    <div class="outline-0" tabindex="-1"></div>
    <div class="flex-auto font-sans">{{ label }}</div>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";

const props = defineProps({
  modelValue: [Number, String, Boolean, Object, null, undefined],
  value: [Number, String, Boolean, Object, null, undefined],
  name: {
    type: String,
    default: "",
  },
  label: {
    type: String,
    default: null,
  },
  checkedIcon: {
    type: String,
    default: "radio_button_checked",
  },
  uncheckedIcon: {
    type: String,
    default: "radio_button_unchecked",
  },
  color: {
    type: String,
    default: "text-blue-500",
  },
});

const emit = defineEmits(["update:modelValue"]);

const focused = ref(false);

const checked = computed(() => props.modelValue == props.value);

const select = () => {
  emit("update:modelValue", props.value);
};
</script>
