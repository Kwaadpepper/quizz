<template>
  <WordCounter
    :id="intId"
    class="ck-word-count"
  />
  <textarea
    ref="textarea"
    :id="intId"
    :class="`input-text-auto-component need-word-counter ${intClass}`"
    :name="intName"
    :aria-describedby="intAriaDescribedby"
    :placeholder="intPlaceholder"
    :required="intRequired"
    @input="onInput"
    @blur="onBlur"
    v-model="intValue"
  />
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import trans from "./../modules/trans";
import WordCounter from "./WordCounterComponent.vue";

const textarea = ref<HTMLTextAreaElement | null>(null);

export default defineComponent({
  inheritAttrs: false,
  mixins: [trans],
  components: {
    WordCounter,
  },
  props: {
    id: {
      type: String,
      default: null,
    },
    name: {
      type: String,
      default: null,
    },
    value: {
      type: String,
      default: null,
    },
    class: {
      type: String,
      default: null,
    },
    placeholder: {
      type: String,
      default: null,
    },
    ariadescribedby: {
      type: String,
      default: null,
    },
  },
  data(): {
    intId: string;
    intClass: string;
    intName: string;
    intPlaceholder: string;
    intAriaDescribedby: string;
    intRequired: boolean;
    intValue: string;
  } {
    return {
      intId: "",
      intClass: "",
      intName: "",
      intPlaceholder: "",
      intAriaDescribedby: "",
      intRequired: false,
      intValue: "",
    };
  },
  setup() {
    return { textarea };
  },
  mounted() {
    const json = String(this.$attrs.json ?? "{}");
    const data = JSON.parse(json);
    this.intId = data.id ? String(data.id) : this.id;
    this.intClass = data.class ? String(data.class) : this.class;
    this.intName = data.name ? String(data.name) : this.name;
    this.intPlaceholder = data.placeholder
      ? String(data.placeholder)
      : this.placeholder;
    this.intAriaDescribedby = data.ariaDescribedby
      ? String(data.ariaDescribedby)
      : this.ariadescribedby;
    this.intValue = data.value ? String(data.value) : this.value;
    this.intRequired = data.required ? data.required : this.required;
    if (this.textarea) {
      // Default input init
      this.textarea.setAttribute("style", "height: 1rem;overflow-y: hidden;");
    }
    // * Init input
    this.$nextTick(this.onInput);
  },
  methods: {
    onBlur() {
      const el = textarea.value;
      if (!el) {
        throw new Error("undefined textarea element");
      }
      // * Remove every new line char
      el.value = el.value.replace(/[\n\r]/g, "");
      this.$nextTick(() => {
        el.style.height = "auto";
        el.style.height = `${el.scrollHeight}px`;
      });
    },
    onInput() {
      const el = textarea.value;
      if (!el) {
        throw new Error("undefined textarea element");
      }
      el.style.height = "auto";
      el.style.height = `${el.scrollHeight}px`;
    },
  },
});
</script>

<style lang="scss">
.input-text-auto-component {
  resize: none;
}
</style>
