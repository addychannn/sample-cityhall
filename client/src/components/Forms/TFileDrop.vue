<template>
  <div
    class="group relative flex flex-col gap-1 border-4 border-dashed border-slate-400/50 pt-10 transition-colors hover:border-slate-700/50"
    :class="[disabled && 'pointer-events-none']"
    @dragenter.prevent
    @dragover.prevent
    @dragleave.prevent
    @drop.prevent="onDrop"
  >
    <transition
      enterActiveClass="transition-all"
      leaveActiveClass="transition-all"
      enterFromClass="opacity-0"
      leaveToClass="opacity-0"
    >
      <div
        class="pointer-events-none absolute flex select-none items-center justify-center font-bold uppercase text-slate-500/75 transition-all"
        :class="[
          files.length <= 0 && 'inset-0 flex-col',
          files.length > 0 &&
            'inset-x-0 top-0  rounded-lg border-b border-slate-500/50 px-2 py-2',
        ]"
      >
        <div class="font-sans leading-none transition-all">
          Drag N' Drop Files here
        </div>
        <div
          class="transition-all font-sans"
          :class="[files.length > 0 && 'flex-auto text-center']"
        >
          OR
        </div>
        <div
          class="cursor-pointer rounded-full border border-slate-500/50 px-3 transition-all font-sans"
          :class="[!disabled && 'pointer-events-auto']"
          @click="openFileDialog"
        >
          Browse
        </div>
      </div>
    </transition>
    <transition-group tag="div" name="files" class="relative flex flex-col">
      <div
        v-for="item in files"
        :key="item.id"
        v-show="item.progress < 100"
        class="w-full leading-none"
      >
        <slot
          :file="item.file"
          :readonly="disabled"
          :removeFn="() => removeFile(item.id)"
        >
          <FileInfo
            :rename="item.rename"
            @update:rename="(val) => onRename(val, item.id)"
            :file="item.file"
            @remove="removeFile(item.id)"
            :readonly="disabled"
            :progress="item.progress"
          />
        </slot>
      </div>
    </transition-group>
  </div>
</template>

<script>
const ACCEPT_CHECK_REG = /^\.[a-zA-Z]{3,4}(,\.[a-zA-Z]{3,4})*$/;
</script>

<script setup>
import { Helpers, MimeTypes } from "@/scripts";
import { computed } from "vue";
import FileInfo from "./TFileDrop/FileInfo.vue";

const fileStatuses = ["pending", "uploading", "uploaded", "failed"];

const props = defineProps({
  modelValue: Object,
  disabled: {
    type: Boolean,
    default: false,
  },

  /**
   * The accepted file types for the file selection module.
   * (Must only be file extentions separated by commas)
   */
  accept: {
    type: String,
    default: null,
    validator: (val) => {
      if (
        !ACCEPT_CHECK_REG.test(val.replaceAll(" ", "")) &&
        import.meta.env.DEV
      ) {
        console.warn(
          '[Vue Warn]: Invalid prop: "Accept" prop must be a collection of file extensions delimited by a ","'
        );
      }
      return true;
    },
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  allowDuplicates: {
    type: Boolean,
    default: false,
  },
  prepend: {
    type: Boolean,
    default: false,
  },
  alwaysReplace: {
    type: Boolean,
    default: false,
  },
  uploading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["update:modelValue"]);

const files = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const openFileDialog = () => {
  let input = document.createElement("input");
  input.type = "file";
  input.multiple = props.multiple;
  input.accept = props.accept ?? "*";
  input.onchange = (e) => {
    addFiles([...e.target.files]);
  };
  input.click();
};

const onDrop = (e) => {
  addFiles([...e.dataTransfer.files]);
};

const addFiles = (new_files) => {
  if (!props.disabled) {
    let _files =
      props.alwaysReplace || !props.multiple ? [] : [...props.modelValue];
    let tmp = selectValidFiles(new_files);
    props.prepend ? _files.unshift(...tmp.reverse()) : _files.push(...tmp);
    emit("update:modelValue", _files);
  }
};

const removeFile = (id) => {
  if (props.multiple) {
    let _files = [...props.modelValue];
    _files = _files.filter((f) => f.id !== id);
    emit("update:modelValue", _files);
  } else {
    emit("update:modelValue", []);
  }
};

const selectValidFiles = (files) => {
  let out = [];
  for (let file of files) {
    if (checkMime(file, props.accept) && !fileExists(file)) {
      out.push(constructFileObject(file));
    }
  }
  return out;
};

const fileExists = (file) => {
  return !!files.value.find((item) => item.file.name == file.name);
};

const onRename = (newName, id) => {
  let tmp = files.value.map((item) => {
    if (item.id == id) item.rename = newName;
    return item;
  });
  emit("update:modelValue", tmp);
};

const constructFileObject = (file) => {
  return {
    id: Helpers.uniqid(),
    progress: 0,
    rename: file.name,
    file: file,
  };
};

const checkMime = (file, mime) => {
  let exts = props.accept.replaceAll(" ", "").split(",");
  exts = exts.filter((ext) => ACCEPT_CHECK_REG.test(ext));
  let ext = MimeTypes.extractExtension(file.name);
  return !!exts.find((item) => item == `.${ext}`);
};
</script>

<style scoped>
.files-move,
.files-enter-active,
.files-leave-active {
  @apply transition-all duration-500;
}

.files-enter-from,
.files-leave-to {
  @apply translate-x-20 opacity-0;
}

.files-leave-active {
  @apply absolute;
}
</style>
