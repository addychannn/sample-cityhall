<template>
  <Page class="p-3" :class="[transitioning && 'overflow-hidden']">
    <router-view v-slot="{ Component }">
      <transition
        enter-from-class="opacity-0"
        leave-to-class="opacity-0"
        enter-active-class="transition duration-300 delay-300"
        leave-active-class="transition duration-300"
        @before-leave="transitioning = true"
        @after-enter="transitioning = false"
      >
        <component :is="Component"> </component>
      </transition>
    </router-view>
  </Page>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "SettingsIndex",
  data() {
    return {
      transitioning: false,
    };
  },
});
</script>
