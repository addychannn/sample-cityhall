<template>
  <DatePicker
    v-model="data"
    v-bind="_attr"
    :readonly="readonly"
    :disabled="disabled"
    teleport
    ref="dp"
  >
    <template #dp-input="{ value, onInput, onEnter, onTab }">
      <TFieldWrapper
        :label="label"
        :hint="hint"
        :error="error"
        :errorMessage="errorMessage"
        :readonly="readonly"
        :disabled="disabled"
        :innerClass="innerClass"
      >
        <template v-for="(index, name) in slots" v-slot:[name]="data">
          <slot :name="name" v-bind="data"></slot>
        </template>
        <template #default="{ classNames, attr }">
          <input
            v-bind="attr"
            :class="classNames"
            :value="value"
            class="h-[22px]"
            type="text"
            @input="onInput"
            @keydown.enter="onEnter"
            @keydown.tab="onTab"
          />
        </template>
        <template #append>
          <template v-if="clearable && !!data">
            <TIcon
              name="close"
              size="sm"
              class="cursor-pointer text-gray-500"
              @click.prevent="data = null"
            />
          </template>
          <template v-else>
            <TIcon
              :name="type == 'time' ? 'schedule' : 'calendar_month'"
              size="sm"
              class="text-gray-500"
            />
          </template>
        </template>
      </TFieldWrapper>
    </template>
  </DatePicker>
</template>

<script setup>
import { computed, defineAsyncComponent, useSlots, ref } from "vue";

const DatePicker = defineAsyncComponent(() => import("@vuepic/vue-datepicker"));

const slots = useSlots();
const props = defineProps({
  modelValue: [Object, String],
  label: {
    type: String,
    default: null,
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
  clearable: {
    type: Boolean,
    default: false,
  },
  readonly: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: "date",
    validator: (val) => ["date", "time", "datetime"].indexOf(val) > -1,
  },
  innerClass: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);

const dp = ref(null);

const data = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const defaultAttr = {
  clearable: false,
  "text-input": true,
  "auto-apply": true,
  "week-start": 0,
  flow: ["year", "month", "calendar"],
};

const _attr = computed(() =>
  Object.assign(defaultAttr, {
    "enable-time-picker": ["time", "datetime"].indexOf(props.type) > -1,
    "min-date":
      ["datetime", "date"].indexOf(props.type) > -1 ? props.min : null,
    "max-date":
      ["datetime", "date"].indexOf(props.type) > -1 ? props.max : null,
    "min-time": props.type == "time" ? props.min : null,
    "max-time": props.type == "time" ? props.max : null,
    "time-picker": props.type == "time",
  })
);

defineExpose({ dp });
</script>
