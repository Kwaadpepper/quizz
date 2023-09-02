<template>
  <h1>LOADING</h1>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name:"LoadingPage",
  mixins: [],
  components: {
  },
  beforeMount() {
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
  computed: {},
});
</script>

