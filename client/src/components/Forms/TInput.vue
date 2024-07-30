<template>
  <component :is="'TFieldWrapper'">
    <template v-for="(index, name) in slots" v-slot:[name]="data" class="font-sans">
      <slot :name="name" v-bind="data" class="font-sans"></slot>
    </template>
    <template #default="{ classNames, attr }">
      <input
        v-model="data"
        v-bind="attr"
        :name="name"
        :class="[...classNames, ...inputClass]"
        class="h-[22px] font-sans"
        :type="type"
        :maxlength="maxlength"
      />
    </template>
  </component>
</template>

<script setup>
import { computed, useSlots } from "vue";

const props = defineProps({
  modelValue: [String, Number],
  name: String,
  maxlength: {
    type: Number,
    default: 255,
  },
  type: {
    type: String,
    default: "text",
  },
  inputClass: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["update:modelValue"]);

const data = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const slots = useSlots();
</script>
