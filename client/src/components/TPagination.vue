<template>
  <nav class="flex select-none items-stretch">
    <template v-if="data.pages[0] > 1">
      <slot name="first_page" :page="1" :change="() => changePage(1)">
        <TButton
          @click="changePage(1)"
          icon="keyboard_double_arrow_left"
          :class="linkClass"
        />
      </slot>
      <slot
        name="prev_page"
        :page="data.prev"
        :change="() => changePage(data.prev)"
      >
        <TButton
          @click="changePage(data.prev)"
          icon="navigate_before"
          :class="linkClass"
        />
      </slot>
      <slot v-if="!hideEllipsis" name="ellipsis">
        <span :class="ellsipsisClass">{{ ellipsis }}</span>
      </slot>
    </template>
    <template v-for="page in data.pages" :key="`page_${page}`">
      <slot
        :page="page"
        :active="data.currentPage == page"
        :change="() => changePage(page)"
      >
        <TButton
          @click="changePage(page)"
          :label="`${page}`"
          :class="[linkClass, data.currentPage == page && activeClass]"
        />
      </slot>
    </template>
    <template v-if="data.pages[data.pages.length - 1] < data.totalPages">
      <slot v-if="!hideEllipsis" name="ellipsis">
        <span :class="ellsipsisClass">{{ ellipsis }}</span>
      </slot>
      <slot
        name="next_page"
        :page="data.next"
        :change="() => changePage(data.next)"
      >
        <TButton
          @click="changePage(data.next)"
          icon="navigate_next"
          :class="linkClass"
        />
      </slot>
      <slot
        name="last_page"
        :page="data.totalPages"
        :change="() => changePage(data.totalPages)"
      >
        <TButton
          @click="changePage(data.totalPages)"
          icon="keyboard_double_arrow_right"
          :class="linkClass"
        />
      </slot>
    </template>
  </nav>
</template>

<script setup>
import { onMounted, ref } from "vue";

const props = defineProps({
  // Current page number
  modelValue: {
    type: Number,
    default: 1,
    validator: (val) => val > 0,
  },

  total: {
    type: Number,
    default: 1,
  },
  // Number of records per page to show
  limit: {
    type: Number,
    default: 10,
    validator: (val) => val > 0,
  },

  // index offset (only used for binding offset)
  offset: {
    type: Number,
    default: 0,
    validator: (val) => val >= 0,
  },

  // Number of pages to show in the pagination
  maxPages: {
    type: Number,
    default: 5,
    validator: (val) => val > 0,
  },

  linkFormat: {
    type: Function,
    default: (page) => page,
  },

  ellipsis: {
    type: String,
    default: "...",
  },

  hideEllipsis: {
    type: Boolean,
    default: false,
  },

  linkClass: {
    type: String,
    default: "p-1 w-8 h-8",
  },

  activeClass: {
    type: String,
    default: "bg-primary text-light",
  },
  ellsipsisClass: {
    type: String,
    default:
      "p-1 aspect-square w-4 h-8 flex items-center justify-center text-center leading-none",
  },
});

const emit = defineEmits([
  "update:modelValue",
  "update:offset",
  "update:limit",
  "update:totalPage",
  "paginate",
]);

const data = ref({
  start: 0,
  end: 10,
  pages: 0,

  currentPage: 1,
  next: null,
  prev: null,

  totalPages: 0,
  pageLinks: [],
});

const changePage = (page) => {
  paginate(page, props.total, props.limit, props.maxPages);
};

const paginate = (currentPage, total, limit, maxPages) => {
  // Calculate the total number of pages
  const totalPages = Math.ceil(total / limit);

  // Ensure the current page is within bounds
  currentPage = Math.min(Math.max(1, currentPage), totalPages);

  // Calculate the start and end indices for the current page
  const startIndex = (currentPage - 1) * limit;
  const endIndex = Math.min(startIndex + limit, total);

  // Calculate the start and end page numbers for the pagination links
  const startPage = Math.max(1, currentPage - Math.floor(maxPages / 2));
  const endPage = Math.min(startPage + maxPages - 1, totalPages);

  // Ensure the pagination links are within bounds
  const startOffset = Math.max(0, maxPages - (endPage - startPage + 1));
  const newStartPage = Math.max(1, startPage - startOffset);
  const newEndPage = Math.min(newStartPage + maxPages - 1, totalPages);

  const pages = Array.from(
    { length: newEndPage - newStartPage + 1 },
    (_, i) => i + newStartPage
  );

  // Return an object containing the pagination information
  data.value.start = startIndex;
  data.value.end = endIndex;
  data.value.pages = pages;
  data.value.currentPage = currentPage;
  data.value.totalPages = totalPages;
  data.value.pageLinks = [];

  pages.forEach((page) => {
    data.value.pageLinks.push(props.linkFormat(page));
  });

  // Add ellipsis at the beginning of the pagination links if necessary
  if (newStartPage > 1) {
    const firstPage = 1;
    const prevPage = currentPage - 1;
    const prevEllipsis = prevPage > 1 ? props.ellipsis : "";
    data.value.prev = prevPage;
    data.value.pageLinks.unshift(firstPage, prevPage, prevEllipsis);
  }

  // Add ellipsis at the end of the pagination links if necessary
  if (newEndPage < totalPages) {
    const lastPage = totalPages;
    const nextPage = currentPage + 1;
    const nextEllipsis = nextPage < totalPages ? props.ellipsis : "";
    data.value.next = nextPage;
    data.value.pageLinks.push(nextEllipsis, nextPage, lastPage);
  }

  emit("paginate", data.value);
  return data.value;
};

onMounted(() => {
  paginate(props.modelValue, props.total, props.limit, props.maxPages);
});
</script>
