<template>
  <div class="button-multi-pdf-component">
    <button
      @click="ajaxMultiPdfAdd(intModel?.id as number)"
      class="btn btn-sm"
      :class="intIsAdded ? 'btn-dark' : 'btn-outline-dark'"
      :title="__('Sélectionner/Désélectionner ce Produit')"
      data-bs="tooltip"
      :disabled="intLoading ? true : false"
    >
      <template v-if="intIsAdded">
        <FontAwesomeIcon icon="fa-solid fa-minus" />
      </template>
      <template v-else>
        <FontAwesomeIcon icon="fa-solid fa-plus" />
      </template>
    </button>
  </div>
</template>

<script lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import Tooltip from "bootstrap/js/dist/tooltip";
import { defineComponent } from "vue";
import route from "./../../modules/route";
import trans from "./../../modules/trans";

export default defineComponent({
  mixins: [trans, route],
  components: {
    FontAwesomeIcon,
  },
  data(): {
    intId: string;
    intModel: LaravelModel | null;
    intLoading: boolean;
    intIsAdded: boolean;
    intPrintMultiProduct: number[];
    intTooltipList: HTMLButtonElement[];
  } {
    return {
      intId: "",
      intModel: null,
      intLoading: false,
      intIsAdded: false,
      intPrintMultiProduct: [],
      intTooltipList: [],
    };
  },
  mounted() {
    const json = String(this.$attrs.json ?? "{}");
    const data = JSON.parse(json);
    this.intId = data.id ? String(data.id) : this.id;
    this.intModel = data.model ? data.model : this.model;
    this.intPrintMultiProduct = data.printMultiProduct
      ? data.printMultiProduct
      : this.printMultiProduct;
    this.checkSessionId();
    this.$nextTick(() => {
      this.updateBootstrapTooltip();
    });
  },
  methods: {
    /**
     * Check if the id of the model is already added in the session,
     * if is it, check the button which correspond.
     */
    checkSessionId() {
      this.intPrintMultiProduct.forEach((productId) => {
        if (Number(this.intModel?.id) === productId) {
          this.intIsAdded = true;
        }
      });
    },
    /**
     * Return the route with the parameter id given.
     *
     * @param id Id of the product.
     * @return string
     */
    getMultiPdfAddRoute(id: number): string {
      const route = this.route("products.multipdf", {
        ID: id,
      });
      if (!route) {
        throw new Error("Undefined route products.multipdf");
      }
      return route;
    },
    /**
     * Add/Remove product's .pdf file to the selection.
     */
    ajaxMultiPdfAdd(id: number) {
      this.intLoading = true;
      this.$nextTick(() => {
        this.closeBootstrapTooltip();
      });
      window.axios
        .post(this.getMultiPdfAddRoute(id), {
          ID: id,
        })
        .then((reponse) => {
          this.intIsAdded = reponse.data;
          this.intLoading = false;
          this.$nextTick(() => {
            this.updateBootstrapTooltip();
          });
        });
    },
    /**
     * Update Bootstrap tooltips.
     */
    updateBootstrapTooltip() {
      let newTooltipList = [].slice.call(
        document.querySelectorAll(
          ".button-multi-pdf-component [data-bs=\"tooltip\"]"
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
  },
});
</script>
