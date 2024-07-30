<template>
  <nav>
    <div class="flex items-center gap-3" :sm="size == 'sm'" :lg="size == 'lg'">
      <TIcon @click.prevent="__first(firstDisabled)" v-if="!firstDisabled || boundaryNav" name="first_page" class="cursor-pointer"/>
      <TIcon @click.prevent="__first(prevDisabled)" v-if="!prevDisabled || directionNav" name="chevron_left" class="cursor-pointer"/>

      <template v-for="(page, index) in pages" :key="`jdn_page${index}`">
        <span @click.prevent="__selectPage(page, __isActive(page))" v-if="page != '...'" class="font-sans" :class="__isActive(page) ? 'select-none cursor-pointer border-b' : 'select-none cursor-pointer'">
          {{page}}
        </span>
        <span v-else class="select-none cursor-pointer">
          ...
        </span>
      </template>

      <TIcon @click.prevent="__next(nextDisabled)" v-if="!nextDisabled || directionNav" name="chevron_right" class="cursor-pointer"/>
      <TIcon @click.prevent="__last(lastDisabled)" v-if="!lastDisabled || boundaryNav" name="last_page" class="cursor-pointer"/>
    </div>
    <!-- <p v-if="!hidePageText" class="m-0 text-center">
      <small>{{ paginationText }}</small>
    </p> -->
  </nav>
</template>

<script>
import { defineComponent } from "vue";
import { useModelWrapper } from "@/scripts/modelWrapper.js";

export default defineComponent({
  name: "Pagination",
  emits: [
    "update:modelValue",
    "update:offset",
    "update:limit",
    "update:totalPage",
    "paginate",
  ],
  props: {
    // Current page number
    modelValue: Number,

    // Total number records in the database
    total: Number,

    // Number of records per page to show
    limit: {
      type: Number,
      default: 10,
    },

    // Number of pages to show in the pagination
    maxPages: {
      type: Number,
      default: 5,
    },

    // Show/hide the pagination boundary links (First/Last page)
    boundaryNav: {
      type: Boolean,
      default: false,
    },

    // Show/hide the pagination direction links (Previous/Next page)
    directionNav: {
      type: Boolean,
      default: false,
    },

    // hide the pagination page text
    hidePageText: {
      type: Boolean,
      default: false,
    },

    // Show/hide the pagination limit selection
    hideLimitSelect: {
      type: Boolean,
      default: false,
    },

    // Limit selection options
    limitOptions: {
      type: Array,
      default: () => [5, 10, 20, 50, 100],
    },

    // Size of the pagination buttons
    size: {
      type: String,
      default: null,
      validator: (val) => {
        return ["sm", "lg"].indexOf(val) > -1;
      },
    },
  },
  setup(props, { emit }) {
    return {
      data: useModelWrapper(props, emit),
    };
  },
  computed: {
    pages() {
      return this.computePages();
    },

    lastPage() {
      let pages = Math.ceil(this.total / this.limit);
      this.$emit("update:totalPage", pages);
      return pages;
    },

    nextDisabled() {
      return this.data >= this.lastPage;
    },

    prevDisabled() {
      return this.data <= 1;
    },

    firstDisabled() {
      return this.data <= Math.ceil(this.maxPages / 2);
    },

    lastDisabled() {
      return this.data >= this.lastPage - Math.floor(this.maxPages / 2);
    },

    paginationText() {
      let tmp = `Showing ${this.offset + 1} to ${this.offset + this.limit} of ${
        this.total
      }`;
      return tmp;
    },
    limitSelect: {
      get() {
        return this.limit;
      },
      set(value) {
        this.$emit("update:limit", value);
        if (this.lastPage < this.data) {
          this.__last();
        } else {
          this.$emit("paginate");
        }
        this.computePages();
        this.offset = (this.data - 1) * this.limit;
        this.$emit("update:offset", this.offset);
      },
    },
  },
  data() {
    return {
      offset: 0,
      selectOptions: [],
    };
  },
  methods: {
    computePages() {
      const pages = [];

      const maxPages = this.maxPages - 1 > 0 ? this.maxPages - 1 : 5;

      const halfPages = Math.ceil(maxPages / 2);
      const startPage = Math.max(1, this.data - halfPages);
      const endPage = Math.min(this.lastPage, startPage + maxPages);

      for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
      }

      if (startPage > 1) {
        pages.unshift("...");
      }

      if (endPage < this.lastPage) {
        pages.push("...");
      }

      if (this.lastPage > 0 && this.lastPage < this.data) {
        this.data = this.lastPage;
      }
      this.offset = (this.data - 1) * this.limit;
      this.$emit("update:offset", this.offset);
      this.$emit("update:totalPage", pages.length ? pages.length : 1);
      return pages.length ? pages : [1];
    },
    __isActive(page) {
      return page === this.data;
    },

    __selectPage(page, disabled) {
      if (!disabled) {
        this.data = page;
        this.offset = (page - 1) * this.limit;
        this.$emit("update:offset", this.offset);
        this.$emit("paginate");
      }
    },
    __next(disabled) {
      let page = this.data + 1;
      if (page > this.lastPage) {
        page = this.lastPage;
      }
      this.__selectPage(page, disabled);
    },
    __prev(disabled) {
      let page = this.data - 1;
      if (page < 1) {
        page = 1;
      }
      this.__selectPage(page, disabled);
    },
    __first(disabled) {
      this.__selectPage(1, disabled);
    },
    __last(disabled) {
      this.__selectPage(this.lastPage, disabled);
    },
  },
});
</script>