<template>
  <div class="select-multiple-enum user-select-none row w-100 mx-auto">
    <div
      :class="
        'col-12 ps-0 ' +
          (intSpecificItem ? 'col-md-6 ' : '') +
          (!intSimpleComponent ? 'mb-3' : '')
      "
    >
      <label
        v-if="!intSimpleComponent"
        :for="intName"
        class="col-form-label"
      >
        <b>{{ intLabel }} {{ intRequire ? "*" : "" }}</b>
      </label>
      <div :class="`d-inline-block tagInput-${intName} w-100`">
        <VueTagsInputCls
          v-model="intItem"
          :tags="intSelectedItem"
          :autocomplete-items="filteredItems"
          :autocomplete-min-length="0"
          @before-adding-tag="beforeTagSave"
          @tags-changed="changedTags"
          :max-tags="6"
          :placeholder="intPlaceholder"
        />
      </div>
      <template
        v-for="(sItem, index) in intSelectedItem"
        :key="index"
      >
        <p class="text-danger m-0">
          {{
            parseValidationInput(
              `${intName}[${String(index)}][name]`,
              intAllErrors
            )
          }}
        </p>
        <input
          :name="`${intName}[${String(index)}][id]`"
          type="hidden"
          :value="sItem.id"
        >
        <input
          :name="`${intName}[${String(index)}][value]`"
          type="hidden"
          :value="sItem.id"
        >
        <input
          :name="`${intName}[${String(index)}][label]`"
          type="hidden"
          :value="sItem.text"
        >
      </template>
      <small class="form-text text-muted">{{ intHelper }}</small>
    </div>
    <div
      v-if="intSpecificItem && intShowSpecificItem"
      class="col-12 col-md-6 mb-3"
    >
      <label
        for="shop"
        class="col-form-label"
      >
        <b>{{ intSpecificItem.label }} *</b>
      </label>
      <input
        class="form-control"
        :name="intSpecificItem.name"
        type="text"
        :value="intSpecificItem.value"
        :placeholder="intSpecificItem.placeholder"
      >
      <small class="form-text text-muted">{{ intSpecificItem.helper }}</small>
      <p class="text-danger m-0">
        {{ parseValidationInput(`${intSpecificItem.name}`, intAllErrors) }}
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import type VueTagsInput from "@sipec/vue3-tags-input";
import { VueTagsInput as VueTagsInputCls } from "@sipec/vue3-tags-input";
import slugify from "slugify";
import { defineComponent } from "vue";
import parseLaravelErrorMessages from "./../../modules/parseLaravelErrorMessages";
import route from "./../../modules/route";
import trans from "./../../modules/trans";

export default defineComponent({
  mixins: [route, trans, parseLaravelErrorMessages],
  components: {
    VueTagsInputCls,
  },
  data(): {
    intName: string;
    intLabel: string;
    intHelper: string;
    intItem: string;
    intSelectedItem: CustomTag[];
    intAllItems: CustomTag[];
    intPlaceholder: string;
    intItemLimit: number[];
    intAllErrors: Record<string, string[]>;
    intSpecificItem: {
      name: string;
      label: string;
      placeholder: string;
      value: string;
      showId: number;
      helper: string;
    } | null;
    intShowSpecificItem: boolean;
    intRequire: boolean;
    intSimpleComponent: boolean;
  } {
    return {
      intName: "",
      intLabel: "",
      intHelper: "",
      intItem: "",
      intSelectedItem: [],
      intAllItems: [],
      intPlaceholder: "",
      intItemLimit: [0, 0],
      intAllErrors: {},
      intSpecificItem: null,
      intShowSpecificItem: false,
      intRequire: false,
      intSimpleComponent: false,
    };
  },
  beforeMount() {
    if (!this.$attrs.json) {
      throw new Error("this component requires json");
    }
  },
  mounted() {
    const json = String(this.$attrs.json ?? "{}"),
          data = JSON.parse(json);
    this.intName = data.name;
    this.intLabel = data.label;
    this.intHelper = data.helper;
    this.intAllItems = this.parseTags(data.itemsList);
    this.intSelectedItem = this.parseTags(data.itemsSelected);
    this.intPlaceholder = data.placeholder;
    this.intItemLimit = data.itemLimit;
    this.intAllErrors = data.errors;
    this.intSpecificItem = data.showSpecificItem;
    this.intRequire = data.require;
    this.intSimpleComponent = data.simpleComponent;
    this.checkSpecificItem();
  },
  methods: {
    parseTags(intSelectedItem: CustomTag[]): CustomTag[] {
      return intSelectedItem.map((intItem: CustomTag) => {
        if (!intItem.value)
          intItem = this.intAllItems
            .filter((intItemEnum) => {
              return intItemEnum.value == intItem.id;
            })
            .shift() as CustomTag;
        intItem.id = String(intItem.value);
        intItem.text = String(intItem.label);
        return intItem;
      });
    },
    /**
     * Add item
     * @param obj
     */
    beforeTagSave(obj: VueTagsInput.IAddArgs): void {
      if (this.intSelectedItem.length < this.intItemLimit[1]) {
        let customTag = this.tagExists(this.intAllItems, obj.tag);
        if (customTag) {
          obj.tag = customTag as unknown as VueTagsInput.ITag;
          this.intSelectedItem.push(customTag);
          this.intItem = "";
          this.checkSpecificItem();
          return;
        }
      }
    },
    /**
     * Check if the specific item must be shown.
     * @param newItem
     */
    checkSpecificItem(): void {
      if (this.intSpecificItem) {
        this.intSelectedItem.some((item) => {
          return item.value == String(this.intSpecificItem?.showId);
        })
          ? (this.intShowSpecificItem = true)
          : (this.intShowSpecificItem = false);
      }
    },
    /**
     * Delete item.
     * @param newItem
     */
    changedTags(newItem: CustomTag[]): void {
      this.intSelectedItem = newItem as CustomTag[];
      this.checkSpecificItem();
    },
    /**
     * Verify if the tag exist.
     * @param newItem
     */
    tagExists(
      items: CustomTag[],
      tagToCheck: VueTagsInput.ITag
    ): CustomTag | null {
      let customTag: CustomTag | null = null;
      items.filter((intItem: CustomTag) => {
        if (
          intItem.value === (tagToCheck as unknown as CustomTag).value ||
          intItem.label === slugify(tagToCheck.text)
        ) {
          customTag = intItem;
        }
      });
      return customTag;
    },
  },
  computed: {
    filteredItems() {
      return this.intAllItems.filter((i) => {
        const label = i.label as string;
        return label.toLowerCase().indexOf(this.intItem.toLowerCase()) !== -1;
      });
    },
  },
});
</script>

<style lang="scss">
.select-multiple-enum {
  .vue-tags-input {
    max-width: 100% !important;
  }

  .ti- {
    &autocomplete {
      max-height: 10rem !important;
      border-radius: 0.375rem;
      box-shadow: 0 0 4px var(--bs-gray-400);
      overflow-y: auto !important;

      ul {
        padding: 0.25rem 0 !important;
      }
    }

    &input {
      padding: 0.375rem !important;
      border-radius: 0.375rem;
      overflow: hidden;
    }

    &tags {
      line-height: inherit !important;
    }

    &tag,
    &new-tag-input-wrapper {
      padding: 0.1rem !important;
      margin: 0.1rem !important;
    }

    &new-tag-input-wrapper {
      font-size: 1em !important;

      input {
        max-width: 100%;
        min-width: 17rem !important;
      }
    }

    &tag {
      padding: 0.1rem 0.3rem !important;
      background-color: #0d6efd !important;
      border-radius: 0.375rem !important;
    }

    &selected-item {
      background-color: var(--bs-gray-300) !important;
      color: var(--bs-gray-900) !important;
    }
  }
}
</style>
