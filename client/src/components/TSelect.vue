<template>
  <Listbox v-model="data">
    <Float
      as="div"
      class="relative"
      placement="bottom"
      portal
      :offset="4"
      :flip="10"
      floating-as="template"
    >
      <ListboxButton
        class="relative w-full rounded-md border border-gray-200 bg-white py-2 pl-3.5 pr-10 text-left  leading-5 text-dark font-sans"
      >
        {{ data.label }}
        <span
          class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400"
        >
          <TIcon name="unfold_more" class="text-gray-400" />
        </span>
      </ListboxButton>

      <ListboxOptions
        class="max-h-60 overflow-x-hidden rounded-md border border-gray-200 bg-white shadow-lg focus:outline-none"
      >
        <ListboxOption
          v-for="option in options"
          v-slot="{ active, selected }"
          :key="`${option.label}_${option.value}`"
          :value="option"
          :disabled="option.disabled"
          as="template"
        >
          <li
            class="relative block w-full cursor-default py-2 px-3 text-left text-sm text-gray-600"
            :class="{
              'bg-secondary/50': active,
              'bg-secondary font-medium text-light': selected,
              'text-gray-300': option.disabled,
            }"
          >
            <span class="block truncate font-sans">
              {{ option.label }}
            </span>
          </li>
        </ListboxOption>
      </ListboxOptions>
    </Float>
  </Listbox>
</template>

<script setup>
import { computed, ref } from "vue";
import {
  Listbox,
  ListboxButton,
  ListboxOption,
  ListboxOptions,
} from "@headlessui/vue";
import { Float } from "@headlessui-float/vue";

const props = defineProps({
  modelValue: Object,
  options: Array,
});

const emit = defineEmits(["update:modelValue"]);

const data = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});
</script>
