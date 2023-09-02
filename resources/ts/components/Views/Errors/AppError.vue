<template>
  <div class="bg-primary text-primary bg-opacity-25 bg-gradient d-flex flex-column align-items-start justify-content-center vh-100 align-items-sm-center pt-sm-0">
    <div class="mx-auto px-sm-5 mb-3">
      <div class="text-opacity-75 d-flex align-items-center pt-5 justify-content-sm-start pt-sm-0">
        <div class="px-4 display-5 border-primary-end-2 tracking-wider">
          {{ __('Erreur') }} {{ code }}
        </div>

        <div class="text-opacity-75 ms-4 fs-5 text-uppercase tracking-wider">
          {{ message }}
        </div>
      </div>
    </div>
    <div class="mx-auto px-sm-5">
      <div class="text-opacity-75 d-flex align-items-center pt-5 justify-content-sm-start pt-sm-0">
        <a
          class="btn btn-primary fw-bold text-uppercase tracking-wider py-3 px-5"
          role="button"
          href="/"
        >
          {{ __('Recharger la page') }}
        </a>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import trans from "./../../../modules/trans";

export default defineComponent({
  name:"AppError",
  mixins: [trans],
  components: {
  },
  props: {
    code: {
      type: Number,
      required: true
    },
    message: {
      type: String,
      required: true
    }
  },
  beforeMount() {

  },
  mounted() {

  },
  methods: {
  },
  computed: {},
});
</script>
