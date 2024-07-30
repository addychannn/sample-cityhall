import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import { useAuthStore, useSystemStore } from "@/stores";
import { guard } from "@/scripts";

const Router = createRouter({
  scrollBehavior: (to, from, savedPosition) => {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0, left: 0 };
    }
  },
  history: createWebHistory(),
  routes,
});

Router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const systemStore = useSystemStore();

  let _guard = new guard(authStore.getUserPermissions, authStore.getRoles);

  const nearestWithTitle = to.matched
    .slice()
    .reverse()
    .find((r) => r.meta && r.meta.title);

  const previousNearestWithMeta = from.matched
    .slice()
    .reverse()
    .find((r) => r.meta && r.meta.metaTags);

  if (nearestWithTitle) {
    document.title = nearestWithTitle.meta.title;
  } else if (previousNearestWithMeta) {
    document.title = previousNearestWithMeta.meta.title;
  }

  if (
    to.meta.startPageLoading === undefined ||
    to.meta.startPageLoading === true
  ) {
    systemStore.setLoading("Loading Page...");
  }
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!authStore.isLoggedIn) {
      next({
        name: "login",
        query: { redirect: to.fullPath },
      });
      // systemStore.setLoading(null);
    } else {
      if (
        to.matched.some((record) => record.meta.requiresVerified) &&
        !authStore.isVerified
      ) {
        next({
          name: "unverified",
          query: { redirect: to.fullPath },
        });
        // systemStore.setLoading(null);
      } else {
        if (to.meta.permissions) {
          if (
            _guard.hasAnyPermission(to.meta.permissions) ||
            _guard.isSuperAdmin()
          ) {
            next();
            if (
              to.meta.endPageLoading === undefined ||
              to.meta.endPageLoading === true
            ) {
              // systemStore.setLoading(null);
            }
            // systemStore.setLoading(null);
          } else {
            next({
              name: "unauthorized",
              query: { redirect: to.fullPath },
            });
            // systemStore.setLoading(null);
          }
        } else {
          next();
          // systemStore.setLoading(null);
        }
      }
    }
  } else if (to.matched.some((record) => record.meta.requiresVisitor)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (authStore.isLoggedIn) {
      next({
        name: "HomePage",
      });
      // systemStore.setLoading(null);
    } else {
      next();
      // systemStore.setLoading(null);
    }
  } else {
    next();
    // systemStore.setLoading(null);
  }
});
Router.afterEach((to, from) => {
  const systemStore = useSystemStore();
  systemStore.setLoading(null);
});
export default Router;
