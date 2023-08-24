<template>
  <div class="word-counter-varchar">
    <span class="words">
      <u>{{ __("Mots") }}</u>
      &nbsp;:&nbsp;
      <b>{{ inTwords }}</b>
    </span>
    <span class="chars">
      <u>{{ __("Caractères") }}</u>
      &nbsp;:&nbsp;
      <b>{{ inTchars }}</b>
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import trans from "../modules/trans";

export default defineComponent({
  mixins: [trans],
  mounted() {
    const json = String(this.$attrs.json ?? "{}");
    const data = JSON.parse(json);
    this.inTid = String(this.id);
    if (data.id) {
      this.inTid = data.id;
    }
  },
  props: {
    id: {
      type: String,
      default: null,
    },
  },
  data(): {
    inTid: string;
    inTwords: number;
    inTchars: number;
    inTinput: HTMLInputElement | HTMLTextAreaElement | null;
  } {
    return {
      inTid: "",
      inTwords: 0,
      inTchars: 0,
      inTinput: null,
    };
  },
  watch: {
    id() {
      this.inTid = this.id;
    },
    inTid() {
      if (!this.inTid) {
        return;
      }
      this.$nextTick(() => {
        const input = document.getElementById(this.inTid);
        if (
          !input ||
          !(
            input instanceof HTMLInputElement ||
            input instanceof HTMLTextAreaElement
          )
        ) {
          throw new Error(`${this.inTid} is not a input or textarea`);
        }
        this.inTinput = input;
      });
    },
    inTinput() {
      if (
        !this.inTinput ||
        !(
          this.inTinput instanceof HTMLInputElement ||
          this.inTinput instanceof HTMLTextAreaElement
        )
      ) {
        throw new Error(`${this.inTid} is not a input or textarea`);
      }
      this.inTinput.addEventListener("input", (e) => {
        const el = e.target;
        if (
          !el ||
          !(el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement)
        ) {
          throw new Error(`${this.inTid} is not a input or textarea`);
        }
        this.updateValues(el.value);
      });
      this.updateValues(this.inTinput.value);
    },
  },
  methods: {
    updateValues(string: string) {
      let chars, words;
      [words, chars] = this.calculateValues(string);
      this.inTwords = words;
      this.inTchars = chars;
    },
    calculateValues(string: string) {
      const arr = string.split(" ");
      return [arr.filter((word) => word !== "").length, string.length];
    },
  },
});
</script>

<style lang="scss">
.word-counter-varchar {
    position: relative;
    display: inline-block;
    width: 100%;
    border: 1px solid rgb(196 196 196);
    border-bottom: 0;
    background-color: #fafafa;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
    color: initial;

    .words {
        position: relative;
        left: 2rem;
    }

    .chars {
        position: absolute;
        left: 9rem;
    }
}
</style>
