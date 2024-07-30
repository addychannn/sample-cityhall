<template>
  <transition
    enter-from-class="opacity-0"
    leave-to-class="opacity-0"
    enter-active-class="transition duration-300"
    leave-active-class="transition duration-300"
  >
    <div
      v-show="active"
      ref="el"
      class="inset-0 z-[9999] flex flex-col items-center justify-center font-sans bg-white/50 text-gray-900"
      :class="[isFullScreen && 'fixed', !isFullScreen && 'absolute']"
      :style="isFullScreen ? [] : styles"
    >
      <template v-if="active">
        <slot>
          <TSpinnerOrbit class="h-8 w-8" :color="'rgb(17 24 39)'" />
          <div v-if="text" class="font-sans">{{ text }}</div>
        </slot>
      </template>
    </div>
  </transition>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from "vue";
import { useSystemStore } from "@/stores";
const props = defineProps({
  text: {
    type: String,
    default: null,
  },
  active: {
    type: Boolean,
    default: false,
  },
  isFullScreen: {
    type: Boolean,
    default: false,
  },
});

let parentStyle = reactive({});
let styles = reactive({
  "border-top-left-radius": 0,
  "border-top-right-radius": 0,
  "border-bottom-left-radius": 0,
  "border-bottom-right-radius": 0,
});

const systemStore = useSystemStore();
const el = ref(null);

function getParentStyle() {
  return window.getComputedStyle(el.value.parentElement);
}

const setStyle = () => {
  styles["border-top-left-radius"] = parentStyle.borderTopLeftRadius ?? 0;
  styles["border-top-right-radius"] = parentStyle.borderTopRightRadius ?? 0;
  styles["border-bottom-left-radius"] = parentStyle.borderBottomLeftRadius ?? 0;
  styles["border-bottom-right-radius"] =
    parentStyle.borderBottomRightRadius ?? 0;
};

watch(
  () => props.active,
  (newValue, oldValue) => {
    if (newValue) {
      parentStyle = getParentStyle();
      setStyle();
      if (
        el.value.parentElement.nodeName != "BODY" &&
        parentStyle.position != "relative"
      ) {
        console.warn('Parent element must have a position of "relative"!');
      }
    }
  }
);
onMounted(() => {
  parentStyle = getParentStyle();
});
</script>
