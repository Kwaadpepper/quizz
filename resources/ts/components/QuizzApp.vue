<template>
  <template v-if="!appError">
    <template v-if="!isAppLoaded">
      <LoadingPage />
    </template>
    <template v-else>
      <HeaderLayout />
      <div class="py-4">
        <div class="container">
          <RouterView />
        </div>
      </div>
    </template>
  </template>
  <template v-else>
    <AppError
      :code="appError.type"
      :message="appError.message"
    />
  </template>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { RouterView } from "vue-router";
import { ActionTypes } from "../vuex/action-types";
import AppError from "./Views/Errors/AppError.vue";
import HeaderLayout from "./Views/Layout/HeaderLayout.vue";
import LoadingPage from "./Views/Pages/LoadingPage.vue";

export default defineComponent({
  name:"QuizzApp",
  mixins: [],
  components: {
    AppError,
    LoadingPage,
    HeaderLayout,
    RouterView
  },
  beforeMount() {
    this.$store.dispatch(ActionTypes.LOAD_USER);
  },
  mounted() {

  },
  data(): {
    name: string;
  } {
    return {
      name: "",
    };
  },
  methods: {
    test() {}
  },
  computed: {
    isAppLoaded(): boolean {
      return this.$store.state.appLoaded;
    },
    appError(): QuizzAppError|false {
      return this.$store.state.onError;
    },
  },
});
</script>
