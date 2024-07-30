<template>
  <div
    class="group/file relative grid w-full flex-auto select-none grid-cols-12 overflow-hidden border-y bg-gray-100 py-1 pr-10 leading-none transition md:pr-0"
  >
    <div class="col-span-2 flex justify-center px-2 leading-none">
      <TFileIcon :name="getFileExt(file)" class="w-8" />
    </div>
    <div class="col-span-10 flex-shrink leading-none">
      <div
        class="overflow-x-hidden text-ellipsis whitespace-nowrap text-xs font-semibold leading-tight"
      >
        <input v-model="_rename" class="w-full bg-inherit" maxlength="255" />
      </div>
      <div class="text-xs leading-none">
        <span class="font-semibold">Size: </span>
        {{ Helpers.formatFileSize(file.size) }}
      </div>
      <div
        class="overflow-x-hidden text-ellipsis whitespace-nowrap text-xs leading-none"
      >
        <span class="font-semibold">Type: </span>
        {{ getMimeType(file) }}
      </div>
    </div>
    <div
      v-if="!readonly"
      class="absolute inset-y-0 right-0 flex flex-col items-stretch overflow-x-hidden whitespace-nowrap rounded-l-lg bg-slate-400 transition group-hover/file:translate-x-0 md:translate-x-full"
    >
      <TButton
        icon="delete"
        class="flex-auto px-2 text-negative/75"
        tabindex="-1"
        @click="emit('remove')"
      />
    </div>
    <div class="absolute inset-x-0 bottom-0 leading-none">
      <TProgress :value="progress" class="h-1" />
    </div>
  </div>
</template>

<script setup>
import { Helpers, MimeTypes } from "@/scripts";
import { computed } from "vue";

const props = defineProps({
  file: File,
  rename: {
    type: String,
    default: null,
  },
  readonly: {
    type: Boolean,
    default: false,
  },
  progress: {
    type: Number,
    default: 0,
    validator: (val) => val >= 0 && val <= 100,
  },
});

const emit = defineEmits(["remove", "update:rename"]);

const _rename = computed({
  get: () => props.rename,
  set: (val) => emit("update:rename", Helpers.filenameValidator(val)),
});

const getFileExt = (file) => {
  return MimeTypes.extractExtension(file.name);
};

const getMimeType = (file) => {
  if (!file.type) {
    let mime = MimeTypes.lookup(file.name);
    return !!mime ? mime : "UNKNOWN";
  } else {
    return file.type;
  }
};
</script>
