import { createPinia } from "pinia";
import { createPersistedStatePlugin } from "pinia-plugin-persistedstate-2";
import { useAuthStore } from "@/stores/auth";
import { useSystemStore } from "@/stores/system";
import { useUserStore } from "@/stores/user";
import SecureLS from "secure-ls";

const pinia = createPinia();

let persistedStateOptions = {};
if (import.meta.env.VITE_STORE_ENCRYPT == "true") {
  const ls = new SecureLS({ isCompression: true });
  persistedStateOptions = {
    storage: {
      getItem: (key) => {
        let data = ls.get(key).replace(/^\s+|\s+$/gm, "");
        if (data == "") return "[]";
        return data;
      },
      setItem: (key, value) => ls.set(key, value),
      removeItem: (key) => ls.remove(key),
    },
  };
}
pinia.use(createPersistedStatePlugin(persistedStateOptions));

export { pinia, useAuthStore, useSystemStore, useUserStore };
