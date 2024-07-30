<template>
  <Menu>
    <Float
      :offset="8"
      :shift="6"
      flip
      portal
      :arrow="8"
      placement="bottom-end"
      enter="transition duration-100 ease-out"
      enter-from="scale-50 opacity-0"
      enter-to="scale-100 opacity-100"
      leave="transition duration-75 ease-in"
      leave-from="scale-100 opacity-100"
      leave-to="scale-50 opacity-0"
    >
      <MenuButton
        v-bind="$attrs"
        class="relative overflow-hidden rounded-md p-0"
        @click.prevent.stop
      >
        <FocusHelper dark />
        <slot></slot>
      </MenuButton>

      <MenuItems
        v-if="!!items"
        class="max-h-[75vh] min-w-[8rem] overflow-hidden rounded-xl border-x border-dark/25 bg-slate-100 text-dark shadow-md focus:outline-none"
      >
        <FloatArrow class="absolute h-5 w-5 rotate-45 bg-dark/25" />
        <div class="relative overflow-hidden rounded-md bg-slate-100">
          <MenuItem class="flex items-center bg-dark/25 py-0.5">
            <div></div>
          </MenuItem>
          <template
            v-for="(item, index) in items"
            :key="`menu_item_${index}_${item.label}`"
          >
            <template v-if="!!item.separator">
              <MenuItem
                class="flex items-center border-b"
                :class="[item.separatorColor ?? '']"
              >
                <div></div>
              </MenuItem>
            </template>
            <template v-else>
              <MenuItem
                v-if="!item.hidden"
                v-slot="{ active }"
                :disabled="item.disabled || !item.action"
                :class="[
                  (item.disabled || !item.action) &&
                    'cursor-not-allowed text-dark/50',
                ]"
              >
                <button
                  type="button"
                  class="flex w-full items-center px-2 py-1.5 text-left text-sm leading-none"
                  :class="[
                    active && (item.activeColor ?? 'bg-primary text-white'),
                  ]"
                  @click="item.action() ?? null"
                >
                  <TIcon
                    v-if="!!item.icon"
                    :name="item.icon"
                    size="sm"
                    class="mr-1"
                  />
                  <span
                    class="px-1 leading-none"
                    :class="[!!item.icon && 'border-l']"
                  >
                    {{ item.label }}
                  </span>
                </button>
              </MenuItem>
            </template>
          </template>
          <MenuItem class="flex items-center bg-dark/25 py-0.5">
            <div></div>
          </MenuItem>
        </div>
      </MenuItems>
    </Float>
  </Menu>
</template>

<script setup>
import { Float, FloatArrow } from "@headlessui-float/vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";

const props = defineProps({
  /**
   * label: String,
   * action: function,
   * icon: string,
   * disabled: Boolean
   * hidden: Boolean
   *
   * OR
   *
   * separator: Boolean,
   * separatorColor: String (Border color)
   */
  items: Object,
});
</script>
