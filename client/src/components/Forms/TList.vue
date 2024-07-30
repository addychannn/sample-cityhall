<template>
  <Combobox
    :modelValue="searchValue(data)"
    @update:modelValue="(val) => $emit('update:modelValue', val.value)"
    nullable
  >
    <Float
      as="div"
      class="relative"
      portal
      flip
      strategy="fixed"
      placement="bottom-start"
      :offset="4"
      leave="transition ease-in duration-100"
      leave-from="opacity-100"
      leave-to="opacity-0"
      floating-as="template"
      @hide="query = ''"
    >
      <TFieldWrapper
        :label="label"
        :error="error"
        :errorMessage="errorMessage"
        :disabled="disabled"
        :readonly="readonly"
        :hint="hint"
        :cursor="cursor"
        class="relative"
        :onResize="onResize"
      >
        <template v-for="(index, name) in slots" v-slot:[name]="data">
          <slot :name="name" v-bind="data"></slot>
        </template>
        <template #default="{ classNames, attr }">
          <ComboboxInput
            :display-value="(item) => item?.label"
            @change="query = $event.target.value"
            v-bind="attr"
            :class="[...classNames, clearable && !!data ? 'pr-14' : 'pr-7']"
          />
        </template>
        <template #append>
          <div class="flex items-center">
            <TIcon
              v-if="clearable && !!data"
              name="close"
              size="sm"
              class="mr-2 cursor-pointer text-gray-400"
              @click="data = null"
            />
            <ComboboxButton class="flex items-center">
              <TIcon class="text-gray-400" name="unfold_more" />
            </ComboboxButton>
          </div>
          <slot name="append"></slot>
        </template>
      </TFieldWrapper>
      <ComboboxOptions
        class="relative max-h-60 w-full overflow-hidden rounded-lg border border-gray-500/25 bg-white shadow-lg focus:outline-none sm:text-sm"
        :style="`max-width: ${comboSize.width}px`"
      >
        <div
          v-if="filtered.length === 0 && query !== ''"
          class="relative cursor-default select-none py-2 px-4 text-gray-700"
        >
          Nothing found.
        </div>
        <perfect-scrollbar tag="ul" class="grid max-h-60 grid-cols-1 gap-1 p-1">
          <ComboboxOption
            v-for="item in filtered"
            v-slot="{ selected, active }"
            :key="item.value"
            :value="item"
            as="template"
          >
            <li
              class="relative col-span-1 flex cursor-pointer select-none items-center rounded-md p-2 leading-none"
              :class="
                active || selected ? 'bg-secondary text-white' : 'text-gray-900'
              "
            >
              <div
                class="block flex-auto truncate leading-none"
                :class="selected ? 'font-medium' : 'font-normal'"
              >
                <div class="leading-none">
                  {{ item.label }}
                </div>
                <div
                  class="text-xs italic leading-none"
                  :class="[
                    active || selected ? 'text-white' : 'text-gray-500/75',
                  ]"
                >
                  {{ item.description }}
                </div>
              </div>
              <span
                v-if="selected"
                class="flex items-center pl-3"
                :class="active || selected ? 'text-white' : 'text-secondary'"
              >
                <TIcon name="check" size="sm" />
              </span>
            </li>
          </ComboboxOption>
        </perfect-scrollbar>
      </ComboboxOptions>
    </Float>
  </Combobox>
</template>

<script setup>
import {
  Combobox,
  ComboboxButton,
  ComboboxInput,
  ComboboxOption,
  ComboboxOptions,
} from "@headlessui/vue";
import { Float } from "@headlessui-float/vue";
import { computed, reactive, ref, useSlots } from "vue";

const slots = useSlots();

const props = defineProps({
  modelValue: [Object, String],
  options: Array,
  name: String,
  label: {
    type: String,
    default: null,
  },
  readonly: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  hint: {
    type: String,
    default: null,
  },
  error: {
    type: Boolean,
    default: null,
  },
  errorMessage: {
    type: String,
    default: null,
  },
  cursor: {
    type: String,
    default: "cursor-default",
    validator: (val) => /^cursor-/.test(val),
  },
  clearable: {
    type: Boolean,
    default: false,
  },
  searchable: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["update:modelValue"]);

const data = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const query = ref("");
const comboSize = reactive({
  width: 0,
  height: 0,
});

const filtered = computed(() =>
  query.value === ""
    ? props.options
    : props.options.filter((item) =>
        item.label
          .toLowerCase()
          .replace(/\s+/g, "")
          .includes(query.value.toLowerCase().replace(/\s+/g, ""))
      )
);

const searchValue = (val) => {
  return props.options?.find((item) => item.value == val) ?? null;
};

const onResize = (size) => {
  comboSize.width = size.width;
  comboSize.height = size.height;
};
</script>
