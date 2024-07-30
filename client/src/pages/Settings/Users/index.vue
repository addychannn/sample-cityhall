<template>
  <div
    class="relative flex min-h-full flex-auto flex-col rounded-2xl border border-slate-500/50 bg-slate-100 text-slate-800 shadow-md dark:bg-slate-400"
  >
    <div class="flex justify-start border-b p-2">
      <div class="flex-auto leading-none">
        <button
          class="flex w-64 items-center rounded-l-full rounded-r-full border-2 border-slate-400 py-1 px-3 text-slate-400 transition-colors hover:border-slate-600 hover:text-slate-600 dark:border-slate-300 dark:bg-slate-700/50 dark:text-slate-300 dark:hover:border-slate-100 dark:hover:bg-slate-700/25 dark:hover:text-slate-100"
          @click="data.searcher.show = true"
        >
          <div class="flex flex-auto items-center">
            <TIcon name="search" size="sm" class="mr-2" />
            Search
          </div>
          <button
            class="flex h-8 w-8 items-center justify-center rounded-full border transition-colors hover:bg-slate-600 hover:text-slate-300"
            @click.prevent.stop="resetSearch"
          >
            <TIcon name="refresh" />
          </button>
        </button>
      </div>
      <button
        class="overflow-none group relative flex w-12 items-center px-3 transition-all hover:w-32"
      >
        <TIcon name="add" />
        <span
          class="whitespace-nowrap opacity-0 transition-all group-hover:opacity-100"
        >
          Add User
        </span>
      </button>
    </div>
    <div
      class="flex w-full flex-auto content-start items-start overflow-y-auto overflow-x-hidden p-2"
    >
      <table
        class="flex-auto border-separate border-spacing-y-2 space-y-6 text-sm text-gray-400"
      >
        <thead
          class="block border bg-gray-500 text-slate-100 md:table-header-group"
        >
          <tr
            class="absolute -top-full -left-full block rounded-3xl border md:relative md:top-auto md:left-auto md:table-row"
          >
            <th class="rounded-l-2xl py-2 px-3 text-start"></th>
            <th class="py-2 px-3 text-start">Username</th>
            <th class="py-2 px-3 text-start">Email</th>
            <th class="py-2 px-3 text-start">Roles</th>
            <th class="rounded-r-2xl py-2 px-3 text-start">Actions</th>
          </tr>
        </thead>
        <tbody class="flex flex-wrap gap-1 text-slate-900 md:table-row-group">
          <template
            v-for="user in data.users"
            :key="`user_${user.id}_${user.username}`"
          >
            <tr
              class="flex-auto cursor-pointer select-none overflow-hidden rounded-3xl transition odd:bg-gray-100 even:bg-gray-300 hover:translate-x-5 hover:scale-x-105"
            >
              <td
                class="block w-[50px] rounded-l-2xl py-1 px-2 text-center md:table-cell"
              >
                <TImage
                  :src="user.profile?.image?.thumbnails.small"
                  class="h-12 w-12 rounded-full border-4 shadow-md"
                  :class="[
                    user.is_active && 'border-lime-700',
                    !user.is_active && 'border-rose-700',
                  ]"
                >
                  <template #error>
                    <div class="flex w-full items-center justify-center">
                      <TIcon
                        name="account_circle"
                        class="text-gray-500"
                        size="lg"
                      />
                    </div>
                  </template>
                </TImage>
              </td>
              <td class="block py-2 px-3 md:table-cell">{{ user.username }}</td>
              <td class="block py-2 px-3 md:table-cell">{{ user.email }}</td>
              <td class="block py-2 px-3 md:table-cell">
                <template
                  v-for="role in user.roles"
                  :key="`user_${user.id}_${user.username}_${role.name}_${role.id}`"
                >
                  <span class="">
                    {{ role.name }}
                  </span>
                </template>
              </td>
              <td class="block rounded-r-2xl py-2 px-3 md:table-cell">
                <button v-if="false">
                  <span class="sr-only">Edit</span>
                  <TIcon name="edit" size="sm" />
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <TInnerLoading
      :active="data.loading"
      :text="data.loadingMessage"
      isFullScreen
    />
    <TDialog v-model="data.searcher.show" position="top">
      <template #default="{ close }">
        <UserSearcher
          :roles="data.roles"
          @search="searchUsers"
          @close="close"
        />
      </template>
    </TDialog>
  </div>
</template>

<script setup>
import { reactive, inject, onMounted, defineAsyncComponent } from "vue";

const UserSearcher = defineAsyncComponent(() => import("./Searcher.vue"));

const $api = inject("$api");
const data = reactive({
  search: "",
  role: null,
  filter: "name",
  loading: false,
  loadingMessage: "",

  users: [],
  roles: [],

  searcher: {
    show: false,
  },
  pagination: {
    page: 1,
    pages: 1,
    total: 0,
    limit: 25,
    offset: 0,

    sort: "id",
    order: "asc",
  },
});

const searchUsers = (response) => {
  data.search = response.search;
  data.role = response.role;
  data.filter = response.filter;
  loadUsers();
};

const loadUsers = () => {
  if (!data.loading) {
    data.loading = true;
    data.loadingMessage = "Loading users, please wait...";
    data.searcher.show = false;

    $api
      .get("/users", {
        params: {
          search: data.search,
          offset: data.pagination.offset,
          limit: data.pagination.limit,
          order: data.pagination.order,
          orderBy: data.pagination.sort,
          column: data.filter,
          role: data.role,
        },
      })
      .then((response) => {
        data.users = response.data.data;
        data.roles = response.data.roles;
        data.pagination.total = response.data.count;
      })
      .finally(() => {
        data.loading = false;
      });
  }
};

const resetSearch = () => {
  data.search = "";
  data.role = null;
  data.filter = "name";
  loadUsers();
};

onMounted(() => {
  loadUsers();
});
</script>

<style scoped lang="scss">
.user-table {
  @apply border-spacing-y-2;
}

.user-table {
  tr {
    @apply rounded-3xl;
    td,
    th {
      @apply first:rounded-l-2xl last:rounded-r-2xl;
    }
  }
}
</style>
