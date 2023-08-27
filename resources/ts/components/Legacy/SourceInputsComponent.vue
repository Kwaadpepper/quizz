<template>
  <div class="row">
    <div class="col-12 col-md-6 mb-3">
      <label
        :for="intName"
        class="col-form-label"
      >
        <b>{{ intLabel }} *</b>
      </label>
      <select
        :id="intName"
        class="form-select source-select"
        :name="intName"
        :aria-describedby="`${intId}Help`"
        @change="setSelectedValue()"
      >
        <option
          v-for="(itemEnum, indexEnum) in intSourceEnum"
          :key="indexEnum"
          :value="itemEnum.value"
          :selected="itemEnum.value === intModel?.source"
        >
          {{ itemEnum.label }}
        </option>
      </select>
      <small class="form-text text-muted">{{ intHelper }}</small>
    </div>
    <!-- Datalist Catalogue -->
    <div
      v-if="intSourceCatalog"
      class="col-12 col-md-6 mb-3"
    >
      <label
        for="catalogue"
        class="col-form-label"
      >
        <b>{{ __("Détail de la source Catalogue") }} *</b>
      </label>
      <input
        class="form-control"
        name="catalogue"
        type="text"
        placeholder="Nom du catalogue"
        list="productsCatalog"
        :value="intModel?.catalogue"
      >
      <datalist id="productsCatalog">
        <option
          v-for="(item, itemIndex) in intProductsCatalog"
          :key="itemIndex"
        >
          {{ item }}
        </option>
      </datalist>
      <small class="form-text text-muted">
        {{
          __(
            "Doit décrire précisément la source Catalogue (3 caractères minimum, 255 maximum)."
          )
        }}
      </small>
    </div>
    <!-- Datalist Magazin -->
    <div
      v-if="intSourceMagazin"
      class="col-12 col-md-6 mb-3"
    >
      <label
        for="shop"
        class="col-form-label"
      >
        <b>{{ __("Détail de la source Magasin") }} *</b>
      </label>
      <input
        class="form-control"
        name="shop"
        type="text"
        placeholder="Nom du magasin"
        list="productsShop"
        :value="intModel?.shop"
      >
      <datalist id="productsShop">
        <option
          v-for="(item, itemIndex) in intProductsShop"
          :key="itemIndex"
        >
          {{ item }}
        </option>
      </datalist>
      <small class="form-text text-muted">
        {{
          __(
            "Doit décrire précisément la source Magasin (3 caractères minimum, 255 maximum)."
          )
        }}
      </small>
    </div>
    <!-- Datalist Fair -->
    <div
      v-if="intSourceShow"
      class="col-12 col-md-6 mb-3"
    >
      <label
        for="salon"
        class="col-form-label"
      >
        <b>{{ __("Détail de la source Salon") }} *</b>
      </label>
      <input
        class="form-control"
        name="fair"
        type="text"
        placeholder="Nom du salon"
        list="productsFair"
        :value="intModel?.fair"
      >
      <datalist id="productsFair">
        <option
          v-for="(item, itemIndex) in intProductsFair"
          :key="itemIndex"
        >
          {{ item }}
        </option>
      </datalist>
      <small class="form-text text-muted">
        {{
          __(
            "Doit décrire précisément la source Salon (3 caractères minimum, 255 maximum)."
          )
        }}
      </small>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import trans from "../../modules/trans";

export default defineComponent({
  mixins: [trans],
  mounted() {
    const json = String(this.$attrs.json ?? "{}");
    const data = JSON.parse(json);
    this.intId = data.id;
    this.intLabel = data.label;
    this.intName = data.name;
    this.intModel = data.model;
    this.intProductsFair = data.productsFair;
    this.intProductsShop = data.productsShop;
    this.intProductsCatalog = data.productsCatalog;
    this.intSourceEnum = data.sourceEnum;
    this.intHelper = data.helper;
    this.setSelectedValue();
  },
  data(): {
    intId: string;
    intLabel: string;
    intName: string;
    intModel: LaravelModel | null;
    intProductsFair: LaravelModel[];
    intProductsShop: LaravelModel[];
    intProductsCatalog: LaravelModel[];
    intSourceEnum: { label: string; value: number }[];
    intSelectedValue: number;
    intSourceCatalog: boolean;
    intSourceMagazin: boolean;
    intSourceShow: boolean;
    intHelper: string;
  } {
    return {
      intId: "",
      intLabel: "",
      intName: "",
      intModel: null,
      intProductsFair: [],
      intProductsShop: [],
      intProductsCatalog: [],
      intSourceEnum: [],
      intSelectedValue: 1,
      intSourceCatalog: false,
      intSourceMagazin: false,
      intSourceShow: false,
      intHelper: "",
    };
  },
  methods: {
    /**
     * Set selected source variables.
     */
    setSelectedValue() {
      this.$nextTick(() => {
        const select = document.querySelector(
          ".source-select"
        ) as HTMLSelectElement;
        this.intSelectedValue = Number(select.value);
        this.updateVue();
      });
    },
    updateVue() {
      this.intSourceCatalog =
        this.intSourceMagazin =
        this.intSourceShow =
        false;
      if (this.intSelectedValue === 1) {
        this.intSourceCatalog = true;
      } else if (this.intSelectedValue === 2) {
        this.intSourceMagazin = true;
      } else if (
        this.intSelectedValue === 4 ||
        this.intSelectedValue === 5 ||
        this.intSelectedValue === 6
      ) {
        this.intSourceShow = true;
      }
    },
  },
});
</script>
