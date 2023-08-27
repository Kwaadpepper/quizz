<template>
  <HeaderLayout />
</template>

<script lang="ts">
import { defineComponent } from "vue";
import HeaderLayout from "./Views/Layout/HeaderLayout.vue";

export default defineComponent({
  name:"QuizzApp",
  mixins: [],
  components: {
    HeaderLayout
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
  },
  computed: {},
});
</script>
