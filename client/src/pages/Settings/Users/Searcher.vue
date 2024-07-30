<template>
  <TCard
    class="mt-20 max-h-[calc(95vh_-_8rem)] w-[95vw] max-w-[596px] bg-slate-50 dark:bg-slate-500"
  >
    <TCardHeader>
      <TInput
        v-model="data.search"
        label="Search Users"
        outlined
        bg="dark:bg-slate-700 bg-slate-100"
        color="dark:text-slate-100 text-slate-900/50"
        labelColor="dark:text-slate-100 text-slate-900/50 "
        borderColor="dark:border-slate-100  border-slate-900/50"
        class="!pb-0"
        clearable
        @keyup.enter="search(null)"
      >
        <template #prepend>
          <TIcon name="search" />
        </template>
        <template #append>
          <button
            class="rounded border px-1 text-xs uppercase shadow-inner hover:bg-gray-300/25"
            @click="emit('close')"
            role="button"
          >
            <span class="sr-only">Press escape to close</span>
            <span aria-hidden="true">esc</span>
          </button>
        </template>
      </TInput>
    </TCardHeader>
    <TCardBody class="min-h-[12rem] overflow-auto">
      <div class="mb-2 flex items-center text-sm leading-none">
        <div class="flex flex-auto gap-1">
          <span class="mr-2 border-r pr-2">
            {{ data.settings ? "Settings" : "Recent" }}
          </span>
          <span
            v-if="selectedFilter"
            class="origin-center select-none rounded-full border bg-gray-300 px-3 shadow-sm hover:scale-105 dark:bg-gray-500 dark:shadow-white"
          >
            {{ selectedFilter.label }}
          </span>
          <span
            v-if="selectedRole"
            class="origin-center select-none rounded-full border bg-gray-300 px-3 shadow-sm hover:scale-105 dark:bg-gray-500 dark:shadow-white"
          >
            {{ selectedRole.name }}
          </span>
        </div>
        <TIcon
          name="settings"
          size="sm"
          class="origin-center cursor-pointer select-none leading-none transition-all hover:scale-105"
          :class="[
            data.settings && 'text-gray-700 dark:text-gray-100',
            !data.settings && 'text-gray-400',
          ]"
          @click="data.settings = !data.settings"
        />
      </div>
      <div
        v-if="data.settings"
        class="flex flex-col gap-2 rounded-md border bg-slate-100 p-2 dark:bg-slate-700"
      >
        <div
          class="flex flex-col rounded-2xl border bg-slate-50 px-3 dark:bg-slate-500"
        >
          <div class="flex-auto select-none text-sm uppercase opacity-50">
            Search By:
          </div>
          <div class="flex flex-wrap justify-evenly gap-2 p-2">
            <template
              v-for="filter in filters"
              :key="`user_filter_${filter.value}`"
            >
              <TRadio
                v-model="data.filter"
                :value="filter.value"
                :label="filter.label"
              />
            </template>
          </div>
        </div>
        <div
          class="flex flex-col rounded-2xl border bg-slate-50 px-3 dark:bg-slate-500"
        >
          <div class="flex-auto select-none text-sm uppercase opacity-50">
            Filter by Roles:
          </div>
          <div class="flex flex-wrap justify-evenly gap-2 p-2">
            <template v-if="roles.length <= 0">
              <div
                class="flex-auto text-center font-semibold italic text-gray-400/75"
              >
                No roles found!
              </div>
            </template>
            <template v-else>
              <TRadio v-model="data.role" :value="null" label="None" />
            </template>
            <template
              v-for="role in roles"
              :key="`search_user_role_${role.id}_${role.name}`"
            >
              <TRadio v-model="data.role" :value="role.id" :label="role.name" />
            </template>
          </div>
        </div>
      </div>
      <div v-else class="flex flex-col gap-2 rounded-md border p-2">
        <template v-if="userStore.getSearchHistory?.length <= 0">
          <div
            class="flex-auto text-center font-semibold italic text-gray-400/75"
          >
            No recent search!
          </div>
        </template>
        <template
          v-for="history in userStore.getSearchHistory"
          :key="`user_search_history_${history.timestamp}`"
        >
          <div
            class="flex cursor-pointer items-center rounded-md border bg-slate-300 py-2 transition-colors hover:bg-slate-800/75 hover:text-slate-50 dark:bg-slate-600/75 dark:hover:bg-slate-900/75"
            @click="search(history)"
          >
            <div class="px-2">
              <TIcon name="history" size="lg" class="text-gray-400" />
            </div>
            <div class="flex flex-auto flex-col justify-center">
              <div
                :class="[
                  !history.search && 'text-xs italic leading-none',
                  !!history.search && 'text-xl font-semibold',
                ]"
              >
                {{ !history.search ? "(empty)" : history.search }}
              </div>
              <div
                class="flex flex-col-reverse gap-1 pr-3 md:flex-row md:items-center"
              >
                <div class="px-1 text-xs leading-none">
                  {{
                    dayjs(history.timestamp).format("MMMM DD, YYYY hh:mm:ss a")
                  }}
                </div>
                <div class="flex flex-wrap gap-2 px-1 md:border-l">
                  <span
                    v-if="!!getSelectedFilter(history.filter)"
                    class="rounded-full border bg-gray-500/50 px-2 py-1 text-xs leading-none shadow"
                  >
                    {{ getSelectedFilter(history.filter).label }}
                  </span>
                  <span
                    v-if="!!getSelectedRole(history.role)"
                    class="rounded-full border bg-gray-500/50 px-2 py-1 text-xs leading-none shadow"
                  >
                    {{ getSelectedRole(history.role).name }}
                  </span>
                </div>
              </div>
            </div>
            <div class="px-3">
              <button
                class="aspect-square rounded-full opacity-50 hover:opacity-100"
                @click.prevent.stop="userStore.removeHistory(history)"
              >
                <TIcon name="close" size="sm" />
              </button>
            </div>
          </div>
        </template>
      </div>
    </TCardBody>
    <TCardFooter class="rotate-180 border-t-0 shadow-md">
      <div class="flex rotate-180 items-center justify-end gap-2">
        <button
          @click="search(null)"
          class="rounded-full border bg-blue-800 px-3 py-1 text-white shadow-lg transition-colors hover:bg-blue-700 dark:bg-blue-800/75 dark:hover:bg-blue-700"
        >
          Search
          <TToolTip position="top"> Click to search </TToolTip>
        </button>
        <button
          @click="emit('close')"
          class="rounded-full px-3 py-1 text-slate-400 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-100"
        >
          Cancel
        </button>
      </div>
    </TCardFooter>
  </TCard>
</template>

<script setup>
import { computed, reactive } from "vue";
import { useUserStore } from "@/stores";
import dayjs from "dayjs";

const userStore = useUserStore();

const props = defineProps({
  roles: {
    type: Object,
    default: () => [],
  },
});

const emit = defineEmits(["search", "close"]);

const data = reactive({
  search: "",
  role: null,
  filter: "name",

  settings: false,
});
const filters = [
  {
    label: "Name",
    value: "name",
  },
  {
    label: "Username",
    value: "username",
  },
  {
    label: "Email",
    value: "email",
  },
];

const getSelectedRole = (val) => props.roles.find((role) => role.id == val);
const getSelectedFilter = (val) =>
  filters.find((filter) => filter.value == val);

const selectedRole = computed(() => getSelectedRole(data.role));
const selectedFilter = computed(() => getSelectedFilter(data.filter));

const search = (history = null) => {
  let searchData = {
    search: history?.search ?? data.search,
    role: history?.role ?? data.role,
    filter: history?.filter ?? data.filter,
  };
  userStore.addHistory(searchData);
  emit("search", searchData);
};
</script>
