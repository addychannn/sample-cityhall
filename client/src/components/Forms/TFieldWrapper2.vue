<template>
  <component
    :is="tag"
    :for="attr.id"
    class="group/outer relative flex flex-nowrap items-start leading-none"
    :class="[
      (error != null || hint != null) && 'mb-5',
      disabled && '!cursor-not-allowed',
    ]"
    :aria-disabled="disabled"
    :aria-readonly="readonly"
  >
    <SizeObserver v-if="!!onResize" @resize="onResize" />
    <div v-if="!!slots.before" class="flex items-center self-stretch pr-1.5">
      <slot name="before" :error="error"></slot>
    </div>
    <div
      class="group/inner relative box-border flex flex-auto rounded-lg border border-gray-500/25 px-2 shadow-md outline-1 outline-primary transition-all"
      :class="[
        innerClass,
        cursor,
        error && '!border-negative outline-negative',
        !readonly && 'focus-within:outline',
        readonly && 'border-dashed border-gray-700 focus-within:border-primary',
        disabled &&
          'pointer-events-none !cursor-not-allowed select-none bg-gray-200',
      ]"
    >
      <div v-if="!!slots.prepend" class="flex items-center self-stretch pr-1.5">
        <slot name="prepend" :error="error"></slot>
      </div>
      <div
        class="relative flex-auto pb-2 pt-2 leading-none"
        :class="[!!label && '!pb-1 !pt-1.5']"
      >
        <slot
          :attr="attr"
          :classNames="[
            'peer w-full bg-inherit placeholder-transparent outline-none placeholder:select-none',
            !!label && 'mt-1.5',
          ]"
          :error="error"
        ></slot>
        <div
          v-if="!!label || !!slots.label"
          class="pointer-events-none absolute left-0 top-3 origin-top-left -translate-y-[60%] scale-75 select-none text-sm leading-none transition-all duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:-translate-y-[60%] peer-focus:scale-75"
        >
          <div
            class="font-semibold leading-none text-gray-500/75 group-focus-within/inner:text-primary"
            :class="[error && 'animate-shake !text-negative']"
          >
            <slot name="label" :error="error">
              {{ label }}
            </slot>
          </div>
        </div>
      </div>
      <div
        v-if="!!slots.append || error"
        class="flex items-center self-stretch pl-1.5"
      >
        <slot name="append"></slot>
        <TIcon v-if="error" name="error" class="text-negative" />
      </div>
      <div
        v-if="error != null || hint != null"
        class="absolute inset-x-0 bottom-0 translate-y-full px-2 pt-1 text-[11px] leading-none"
      >
        <transition
          enter-from-class="-translate-y-full opacity-0"
          leave-to-class="-translate-y-full opacity-0"
          enter-active-class="transition duration-300"
          leave-active-class="transition duration-300"
        >
          <div
            v-if="error"
            class="text-[11px] font-semibold leading-none text-negative"
          >
            {{ errorMessage }}
          </div>
          <div v-else class="text-[11px] leading-none text-gray-400">
            {{ hint }}
          </div>
        </transition>
      </div>
    </div>
    <div v-if="!!slots.after" class="flex items-center self-stretch pl-1.5">
      <slot name="after" :error="error"></slot>
    </div>
  </component>
</template>

<script setup>
import { computed, useSlots } from "vue";
import { Helpers } from "@/scripts";

const props = defineProps({
  tag: {
    type: String,
    default: "label",
  },
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
    default: "cursor-text",
    validator: (val) => /^cursor-/.test(val),
  },
  onResize: {
    type: Function,
    default: null,
  },
  innerClass: {
    type: String,
    default: "",
  },
});

const slots = useSlots();

const attr = computed(() => ({
  id: Helpers.uniqid(),
  placeholder: " ",
  "aria-label": props.label,
  readonly: props.readonly,
  disabled: props.disabled,
}));
</script>
