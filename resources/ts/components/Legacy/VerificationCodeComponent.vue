<template>
  <div :class="`verification-code-${intId}`">
    <label
      :for="intId"
      class="col-form-label"
    >
      <b>{{ intLabel }} {{ intRequired ? "*" : "" }}</b>
    </label>
    <div class="input-group">
      <input
        :id="intId"
        :name="intName"
        type="text"
        :value="intValue"
        :placeholder="__('112233445566')"
        class="form-control input-gencode"
        :class="[
          intResult != undefined ? [intResult ? 'is-invalid' : 'is-valid'] : '',
        ]"
        :aria-describedby="`${intId}Help`"
        :maxlength="typeof intMaxlength != 'undefined' ? intMaxlength : ''"
        :required="!!intRequired"
        :disabled="!!intLoader"
        @keyup="checkGencode()"
      >
      <a
        v-if="intResult"
        :href="getRouteProductDuplicate()"
        class="btn btn-secondary p-0"
        :title="__('Dupliquer ce produit')"
        data-bs="tooltip"
      >
        <button
          class="btn btn-secondary"
          type="button"
        >
          <FontAwesomeIcon icon="fa-solid fa-copy" />
        </button>
      </a>
    </div>
    <small class="form-text text-muted">{{ intHelper }}</small>
  </div>
</template>

<script lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { Tooltip } from "bootstrap";
import { defineComponent } from "vue";
import route from "./../../modules/route";
import trans from "./../../modules/trans";

export default defineComponent({
  mixins: [route, trans],
  components: {
    FontAwesomeIcon,
  },
  data(): {
    intId: string;
    intName: string;
    intLabel: string;
    intModel: LaravelModel | null;
    intValue: string | null;
    intMaxlength: number;
    intRequired: boolean;
    intHelper: string;
    intLoader: boolean;
    intResult: boolean | null;
    intResultId: string;
    intCode: string;
    intTooltipList: HTMLButtonElement[];
  } {
    return {
      intId: "",
      intName: "",
      intLabel: "",
      intModel: null,
      intValue: null,
      intMaxlength: 0,
      intRequired: true,
      intHelper: "",
      intLoader: false,
      intResult: null,
      intResultId: "",
      intCode: "",
      intTooltipList: [],
    };
  },
  mounted() {
    const json = String(this.$attrs.json ?? "{}");
    const data = JSON.parse(json);
    this.intId = data.id;
    this.intName = data.name;
    this.intLabel = data.label;
    this.intModel = data.model;
    this.intValue = data.value;
    this.intMaxlength = data.maxlength;
    this.intRequired = data.required;
    this.intHelper = data.helper;
    this.$nextTick(() => {
      this.updateBootstrapTooltip();
    });
  },
  methods: {
    /**
     * Update Bootstrap tooltips.
     */
    updateBootstrapTooltip() {
      let newTooltipList = [].slice.call(
        document.querySelectorAll(
          ".verification-code-" + this.intId + " [data-bs=\"tooltip\"]"
        )
      ) as HTMLButtonElement[];
      let tmp = newTooltipList.filter((x) => !this.intTooltipList.includes(x));
      tmp.map((tooltip) => {
        return new Tooltip(tooltip);
      });
      this.intTooltipList = newTooltipList;
      this.closeBootstrapTooltip();
    },
    /**
     * Close all Bootstrap tooltips.
     */
    closeBootstrapTooltip() {
      this.intTooltipList.forEach((tooltip) => {
        tooltip.blur();
        Tooltip.getInstance(tooltip)?.hide();
      });
    },
    /**
     * Return the route of gencode product.
     */
    getRouteProductGencode() {
      const checkProductGencode = route.methods.route("products.gencode");
      if (!checkProductGencode) {
        throw new Error("Undefined route products.gencode");
      }
      return checkProductGencode;
    },
    /**
     * Return the route of duplicate product.
     */
    getRouteProductDuplicate() {
      const checkProductDuplicate = route.methods.route("products.duplicate", {
        PRODUCT: this.intResultId,
      });
      if (!checkProductDuplicate) {
        throw new Error("Undefined route products.duplicate");
      }
      return checkProductDuplicate;
    },
    /**
     * Check if this gencode is already used.
     */
    checkGencode() {
      const inputCode = document.querySelector(
        ".input-gencode"
      ) as HTMLInputElement;
      if (inputCode.value.length >= 6) {
        this.intLoader = true;
        this.intResult = null;
        this.intCode = inputCode.value;
        window.axios
          .post(this.getRouteProductGencode(), {
            GENCODE: this.intCode,
            ID: this.intModel?.id,
          })
          .then((reponse) => {
            this.intResult = reponse.data.id === undefined ? false : true;
            this.intLoader = false;
            this.intResultId = reponse.data.id;
          })
          .finally(() => {
            inputCode.value = this.intCode;
            inputCode.focus();
            this.$nextTick(() => {
              this.updateBootstrapTooltip();
            });
          });
      }
    },
  },
});
</script>
