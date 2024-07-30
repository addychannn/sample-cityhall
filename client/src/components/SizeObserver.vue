<template>
  <component :is="tag" ref="observe" style="display: none"></component>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
const tag = "div";

const observe = ref(null);
let ro = new ResizeObserver(onResize);

const emit = defineEmits(["resize"]);

function onResize() {
  let size = {
    width: observe.value.parentElement.offsetWidth ?? 0,
    height: observe.value.parentElement.offsetHeight ?? 0,
  };
  emit("resize", size);
}

onMounted(() => {
  ro = new ResizeObserver(onResize);
  ro.observe(observe.value.parentElement);
});
onBeforeUnmount(() => {
  ro.unobserve(observe.value.parentElement);
});
</script>
