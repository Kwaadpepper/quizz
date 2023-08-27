<template>
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      <a
        class="navbar-brand"
        :href="String(route('home'))"
      >
        {{ config('app.name', 'Laravel') }}
      </a>
      <button
        class="navbar-toggler"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        type="button"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        :aria-label="__('Toggle navigation')"
      >
        <span class="navbar-toggler-icon" />
      </button>

      <div
        class="navbar-collapse collapse"
        id="navbarSupportedContent"
      >
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav me-auto" />

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              id="navbarDropdown"
              data-bs-toggle="dropdown"
              href="#"
              role="button"
              aria-haspopup="true"
              aria-expanded="false"
              v-pre
            >
              {{ "USername" }}
            </a>

            <div
              class="dropdown-menu dropdown-menu-end"
              aria-labelledby="navbarDropdown"
            >
              <a
                class="dropdown-item"
                :href="String(route('logout'))"
                @click.prevent="logout()"
              >
                {{ __('Logout') }}
              </a>

              <form
                ref="logoutForm"
                class="d-none"
                id="logout-form"
                :action="String(route('logout'))"
                method="POST"
              >
                @csrf
              </form>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import config from "./../../../modules/config";
import route from "./../../../modules/route";
import trans from "./../../../modules/trans";

export default defineComponent({
  name:"HeaderLayout",
  mixins: [config, route, trans],
  components: {
  },
  beforeMount() {
    if (!this.$attrs.json) {
      throw new Error("this component requires json");
    }
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
    logout() {
      // this.$refs.logoutForm.submit();
      // document
      console.log(this);
    }
  },
  computed: {},
});
</script>
