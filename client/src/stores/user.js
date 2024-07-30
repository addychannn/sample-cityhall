import { defineStore } from "pinia";
import { state, getters, actions } from "./SearchHistory";

export const useUserStore = defineStore("user", {
  state: () => ({
    ...state,
  }),
  getters: {
    ...getters,
  },
  actions: {
    ...actions,
  },
});
