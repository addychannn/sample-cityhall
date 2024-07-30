<template>
  <TCard
    tag="form"
    class=" relative w-[95vw] max-w-[300px] rounded-2xl border border-slate-500/50 bg-slate-100 p-2 text-slate-900 shadow-md font-sans"
    @submit.prevent="login"
  >
    <TCardBody class="h-12 font-sans"> </TCardBody>
    <TCardBody class="pt-5 font-sans">
      <TInput
        :label="form.email.name"
        v-model="form.email.value"
        :error="form.email.error"
        :errorMessage="form.email.errorMessage"
        dense
        bg="bg-slate-100"
        class="font-sans"
      >
        <template #prepend>
          <TIcon name="account_circle" class="select-none text-gray-500 font-sans" />
        </template>
      </TInput>
      <TInput
       
        :label="form.password.name"
        v-model="form.password.value"
        :error="form.password.error"
        :errorMessage="form.password.errorMessage"
        dense
        bg="bg-slate-100"
        class="font-sans"
        :type="showPass ? 'text' : 'password'"
      >
        <template #prepend>
          <TIcon name="lock" class="select-none text-gray-500 font-sans" />
        </template>
        <template #append>
          <TIcon
            :name="showPass ? 'visibility_off' : 'visibility'"
            class="cursor-pointer select-none font-sans"
            @click="showPass = !showPass"
          />
        </template>
      </TInput>
    </TCardBody>
    <TCardFooter>
      <button
        class="relative w-full overflow-hidden rounded-r-full rounded-l-full border bg-primary py-1 uppercase text-slate-100 font-sans"
        type="submit"
        v-ripple
      >
        <FocusHelper />
        Login
      </button>
    </TCardFooter>
    <TCardFooter v-if="false" class="text-center font-sans">
      <span> Don't have an account? </span>
    </TCardFooter>
    <router-link
      :to="{ name: 'AdminHome'}"
      class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 font-sans"
    >
      <TImage
        src="/favicons/baguioseal-animated.svg"
        class="aspect-square h-32 w-32 rounded-full border-8 border-slate-300 bg-slate-300 transition-all hover:scale-105 hover:border-0 dark:border-slate-700 dark:bg-slate-700"
      />
    </router-link>
    <TInnerLoading :active="loading" :text="loadingMessage" isFullScreen />
  </TCard>
</template>

<script setup>
import { onMounted, ref, reactive } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores";
import { InputField } from "@/scripts";

const authStore = useAuthStore();
const $router = useRouter();
const $route = useRoute();

const showPass = ref(false);
const loading = ref(false);
const loadingMessage = ref("");

const form = reactive({
  email: new InputField().setName("Username/Email"),
  password: new InputField().setName("Password"),
});

const login = (e) => {
  if (!loading.value && validate()) {
    loading.value = true;
    loadingMessage.value = "Logging in, please wait...";
    authStore
      .login({
        email: form.email.value,
        password: form.password.value,
      })
      .then((response) => {
        $router.push($route.query.redirect || { name: "AdminHome" });
      })
      .finally(() => {
        loading.value = false;
      });
  }
};
const validate = () => {
  form.email.validate("required");
  form.password.validate("required|minLength:8");
  return !(form.email.error || form.password.error);
};
onMounted(() => {});
</script>

<style>

</style>
