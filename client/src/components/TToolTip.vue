<template>
  <teleport to="body" :disabled="!teleport || contain">
    <component
      :is="tag"
      ref="tipRef"
      class="fixed top-0 left-0 z-[1027] transform rounded-lg border bg-dark py-1 px-2 text-light drop-shadow-md dark:border-0"
      :class="[!visible && 'hidden', !pointerEvents && 'pointer-events-none']"
      aria-hidden="true"
      v-bind="$attrs"
      @mouseenter="showTip"
      @mouseleave="hideTip"
      @click="hideOnClick && hideTip()"
    >
      <template v-if="visible">
        <slot :close="() => hideTip"></slot>
      </template>
      <div
        v-if="arrow"
        class="absolute h-2 w-2 border-t border-l bg-dark dark:border-0"
        ref="arrowRef"
      />
    </component>
  </teleport>
</template>

<script setup>
import { onMounted, ref } from "vue";
import {
  computePosition,
  shift,
  flip,
  offset,
  arrow as arrowMiddleware,
} from "@floating-ui/dom";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  tag: {
    type: String,
    default: "div",
  },
  position: {
    type: String,
    default: "bottom",
    validator: (val) =>
      [
        "left",
        "top",
        "right",
        "bottom",

        "left-start",
        "top-start",
        "right-start",
        "bottom-start",

        "left-end",
        "top-end",
        "right-end",
        "bottom-end",
      ].indexOf(val) > -1,
  },
  offset: {
    type: Number,
    default: 8,
  },
  arrow: {
    type: Boolean,
    default: false,
  },
  // Contain in immediate parent instead of moving the tooltip in the body
  contain: {
    type: Boolean,
    default: false,
  },
  pointerEvents: {
    type: Boolean,
    default: false,
  },
  hideDelay: {
    type: Number,
    default: 50, // milliseconds
  },
  hideOnClick: {
    type: Boolean,
    default: true,
  },
  activeEvents: {
    type: Array,
    default: [""],
  },
});

const emit = defineEmits(["update:modelValue"]);

const tipRef = ref();
const parentRef = ref();
const arrowRef = ref();
const teleport = ref(false);
const visible = ref(true);

const hideTimeOut = ref(null);

const computeTipPosition = async () => {
  if (!!parentRef.value && visible.value) {
    const middleware = [offset(props.offset), flip(), shift({ padding: 5 })];
    if (props.arrow) {
      let needsPadding = props.position.split("-").length > 1;
      middleware.push(
        arrowMiddleware({
          element: arrowRef.value,
          padding: needsPadding ? 7 : 0,
        })
      );
    }

    const { x, y, middlewareData, placement } = await computePosition(
      parentRef.value,
      tipRef.value,
      {
        strategy: "fixed",
        placement: props.position,
        middleware: middleware,
      }
    );
    Object.assign(tipRef.value.style, {
      left: `${x}px`,
      top: `${y}px`,
    });

    if (props.arrow) {
      const { x: arrowX, y: arrowY } = middlewareData.arrow;
      const opposedSide = {
        left: "right",
        right: "left",
        top: "bottom",
        bottom: "top",
      }[placement.split("-")[0]];

      Object.assign(arrowRef.value.style, {
        left: arrowX ? `${arrowX}px` : "",
        top: arrowY ? `${arrowY}px` : "",
        right: ``,
        bottom: ``,
        [opposedSide]: "-4px",
      });

      const rotation = {
        bottom: "-rotate-[135deg]",
        top: "rotate-45",
        left: "-rotate-45",
        right: "rotate-[135deg]",
      };
      arrowRef.value.classList.add(rotation[opposedSide]);
    }
  }
};

const showTip = (e) => {
  // if (!!hideTimeOut.value) {
  clearTimeout(hideTimeOut.value);
  // }
  visible.value = true;
  computeTipPosition();
};

const hideTip = () => {
  if (!!hideTimeOut.value) {
    clearTimeout(hideTimeOut.value);
  }
  hideTimeOut.value = setInterval(() => {
    visible.value = false;
    clearTimeout(hideTimeOut.value);
  }, props.hideDelay);
};

onMounted(() => {
  parentRef.value = tipRef.value.parentElement;
  parentRef.value.addEventListener("mouseenter", showTip);
  parentRef.value.addEventListener("resize", showTip);
  parentRef.value.addEventListener("mouseleave", hideTip);
  parentRef.value.addEventListener("focus", showTip);
  parentRef.value.addEventListener("blur", hideTip);
  teleport.value = true;
  visible.value = false;
});

onBeforeUnmount(() => {
  parentRef.value.removeEventListener("mouseenter", showTip);
  parentRef.value.removeEventListener("resize", showTip);
  parentRef.value.removeEventListener("mouseleave", hideTip);
  parentRef.value.removeEventListener("focus", showTip);
  parentRef.value.removeEventListener("blur", hideTip);
});
</script>

<script>
import { defineComponent, onBeforeUnmount, watch } from "vue";

export default defineComponent({
  name: "TToolTip",
  inheritAttrs: false,
});
</script>
