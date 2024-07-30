<template>
  <teleport to="body" :disabled="contain">
    <transition
      enter-active-class="duration-300 ease-out"
      leave-active-class="duration-200 ease-in"
      enter-from-class="opacity-0"
      leave-to-class="opacity-0"
      enter-to-class="opacity-100"
      leave-from-class="opacity-100"
    >
      <component
        v-if="_open"
        :is="'div'"
        class="__modal-main pointer-events-none absolute top-0 left-0 z-[1030] h-full w-full"
        :class="[!contain && 'z-[1030]', contain && 'z-20']"
        ref="$el"
        v-trap-focus
        @keyup.esc="close(false)"
      >
        <div
          class="pointer-events-none relative flex h-full w-full items-center justify-center"
        >
          <div
            v-if="!seamless"
            class="pointer-events-auto absolute top-0 h-full w-full bg-slate-900/75 transition-opacity"
            @click.stop="close(false)"
          />
          <div
            v-bind="$attrs"
            class="pointer-events-auto absolute"
            :class="_wrapperClass"
            ref="$contentWrapper"
            @keyup.esc="close(false)"
          >
            <slot :close="() => close(true)"></slot>
          </div>
        </div>
      </component>
    </transition>
  </teleport>
</template>
<script lang="ts">
export default {
  inheritAttrs: false,
};
</script>
<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  contain: {
    type: Boolean,
    default: false,
  },
  persistent: {
    type: Boolean,
    default: false,
  },
  seamless: {
    type: Boolean,
    default: false,
  },
  fullScreen: {
    type: Boolean,
    default: false,
  },
  fullWidth: {
    type: Boolean,
    default: false,
  },
  fullHeight: {
    type: Boolean,
    default: false,
  },

  blur: {
    type: Boolean,
    default: false,
  },
  position: {
    type: String,
    default: "center",
    validator: (val: string) =>
      [
        "center",
        "top",
        "bottom",
        "left",
        "right",
        "top-left",
        "top-right",
        "bottom-left",
        "bottom-right",
      ].indexOf(val) > -1,
  },
});
const emits = defineEmits(["update:modelValue", "open"]);

const isOpen = ref<Boolean>(false);
const $el = ref(null);
const $contentWrapper = ref(null);

const _open = computed({
  get() {
    return props.modelValue ?? isOpen.value;
  },
  set(val: Boolean) {
    isOpen.value = val;
    emits("update:modelValue", val);
    if (val && !!$el.value) {
      $contentWrapper.value.focus();
    }
  },
});

const _wrapperClass = computed(() => {
  const _position = props.position;
  return [
    _position == "center" &&
      "top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2",
    _position == "top" && "top-0",
    _position == "left" && "left-0",
    _position == "right" && "right-0",
    _position == "bottom" && "bottom-0",
    _position == "top-left" && "top-0 left-0",
    _position == "top-right" && "top-0 right-0",
    _position == "bottom-left" && "bottom-0 left-0",
    _position == "bottom-right" && "bottom-0 right-0",
    props.fullWidth && "w-full",
    props.fullHeight && "h-full",
  ];
});

const _parentEl = computed(() => {
  return $el.value?.parentElement ?? null;
});

const close = (force: Boolean = true): void => {
  if (force || !props.persistent) {
    _open.value = false;
  } else {
    shake();
  }
};

const shake = () => {
  const childEl = $contentWrapper.value.firstElementChild;
  if (childEl && !childEl.classList.contains("animate-shake")) {
    childEl.classList.add("animate-shake");
    childEl.addEventListener("animationend", (e) => {
      childEl.classList.remove("animate-shake");
    });
  }
};

watch(_parentEl, (newVal) => {
  if (newVal && _open && !props.seamless) {
    $el.value.focus();
    _parentEl.value.classList.add("modal-open");
    if (props.blur) {
      [..._parentEl.value.children].forEach((child) => {
        if (!child.classList.contains("__modal-main")) {
          child.classList.add("modal-blur");
        }
      });
    }
  }
});
watch(_open, (newVal) => {
  if (!newVal && !props.seamless) {
    _parentEl.value.classList.remove("modal-open");
    [..._parentEl.value.children].forEach((child) => {
      if (!child.classList.contains("__modal-main")) {
        child.classList.remove("modal-blur");
      }
    });
  }
});
</script>
