<template>
  <div class="belongs-to-many-dropdown">
    <Transition name="fade">
      <p
        v-if="message"
        class="message"
      >
        {{ message }}
      </p>
    </Transition>
    <div class="d-inline-block tagInput w-100">
      <VueTagsInputCls
        v-model="item"
        :tags="selectedItems"
        :autocomplete-items="filteredItems"
        :autocomplete-min-length="0"
        @before-adding-tag="beforeTagSave"
        @tags-changed="changedTags"
        :max-tags="max ?? 9999"
        :placeholder="placeholder"
      />
    </div>
    <input
      v-if="!selectedItems.length"
      :name="name"
      type="hidden"
      value=""
    >
    <template
      v-for="(sItem, index) in selectedItems"
      :key="index"
    >
      <p class="text-danger m-0">
        {{ parseValidationInput(`${name}[${String(index)}][name]`, allErrors) }}
      </p>
      <input
        :name="`${name}[${String(index)}][id]`"
        type="hidden"
        :value="sItem.id"
      >
      <input
        :name="`${name}[${String(index)}][value]`"
        type="hidden"
        :value="sItem.id"
      >
      <input
        :name="`${name}[${String(index)}][name]`"
        type="hidden"
        :value="sItem.text"
      >
      <input
        :name="`${name}[${String(index)}][label]`"
        type="hidden"
        :value="sItem.text"
      >
      <input
        :name="`${name}[${String(index)}][title]`"
        type="hidden"
        :value="sItem.text"
      >
    </template>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import type VueTagsInput from "@sipec/vue3-tags-input";
import { VueTagsInput as VueTagsInputCls } from "@sipec/vue3-tags-input";
import slugify from "slugify";
import route from "./../modules/route";
import trans from "./../modules/trans";
import parseLaravelErrorMessages from "./../modules/parseLaravelErrorMessages";

export default defineComponent({
  mixins: [route, trans, parseLaravelErrorMessages],
  components: {
    VueTagsInputCls,
  },
  beforeMount() {
    if (!this.$attrs.json) {
      throw new Error("this component requires json");
    }
  },
  mounted() {
    const json = String(this.$attrs.json ?? "{}"),
          data = JSON.parse(json);
    this.name = data.name;
    this.selectedItems = this.parseTags(data.value);
    this.allItems = this.parseTags(data.itemsList);
    this.placeholder = data.placeholder;
    // * Min and max calc.
    this.min = Number.parseInt((data?.itemLimit ?? [])[0] ?? 0);
    this.min = this.min < 0 ? 0 : this.min;
    this.max = Number.parseInt((data?.itemLimit ?? [])[1] ?? 0);
    this.max = this.max <= this.min ? null : this.max;
    // * Error parsing
    this.allErrors = data.errors;
  },
  data(): {
    name: string;
    item: string;
    selectedItems: Array<CustomTag>;
    allItems: Array<CustomTag>;
    placeholder: string;
    min: number;
    max: number | null;
    fieldName: string;
    waitingListObjects: Array<VueTagsInput.IAddArgs>;
    allErrors: Record<string, Array<string>>;
    message: string | null;
  } {
    return {
      name: "",
      item: "",
      selectedItems: [],
      allItems: [],
      placeholder: "",
      min: 0,
      max: null,
      fieldName: "",
      waitingListObjects: [],
      allErrors: {},
      message: null,
    };
  },
  methods: {
    parseTags(selectedItems: Array<CustomTag>): Array<CustomTag> {
      return selectedItems.map((item: CustomTag) => {
        item.text = String(item.label ?? item.name ?? item.title);
        item.slug = String(item.slug ?? slugify(item.text));
        return item;
      });
    },
    /**
     * Add item
     * @param obj
     */
    beforeTagSave(obj: VueTagsInput.IAddArgs): void {
      if (!this.max || this.selectedItems.length < this.max) {
        let customTag = this.tagExists(this.allItems, obj.tag);
        if (customTag) {
          obj.tag = customTag;
          this.selectedItems.push(customTag);
          this.item = "";
          return;
        }
      } else {
        this.message = this.__("Nombre d'élements maximal atteint.");
        setTimeout(() => (this.message = null), 1500);
      }
    },
    /**
     * Delete item.
     * @param newItems
     */
    changedTags(newItems: Array<CustomTag>): void {
      // * Si on enleve un élement
      if (this.selectedItems.length > newItems.length) {
        // * On respecte la validation minimale
        if (newItems.length >= this.min) {
          this.selectedItems = newItems as Array<CustomTag>;
        } else {
          // * Non respect de la validation minimale
          this.message = this.__("Nombre d'élements minimal atteint.");
          setTimeout(() => (this.message = null), 1500);
          // * Réassignation pour forcer la valeur minimale.
          this.selectedItems = Object.assign([], this.selectedItems);
        }
      }
    },
    tagExists(
      items: Array<CustomTag>,
      tagToCheck: VueTagsInput.ITag
    ): CustomTag | null {
      let customTag: CustomTag | null = null;
      items.filter((item: CustomTag) => {
        if (
          item.id === (tagToCheck as CustomTag).id ||
          item.slug === slugify(tagToCheck.text)
        ) {
          customTag = item;
        }
      });
      return customTag;
    },
  },
  computed: {
    filteredItems() {
      return this.allItems.filter((i) => {
        return i.text.toLowerCase().indexOf(this.item.toLowerCase()) !== -1;
      });
    },
  },
});
</script>

<style lang="scss">
.vue-tags-input {
  max-width: 100% !important;
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
        min-width: 165px !important;
      }
    }
    &tag {
      padding: 0.1rem 0.3rem !important;
      background-color: var(--bs-blue) !important;
      border-radius: 0.375rem !important;
    }
    &selected-item {
      background-color: var(--bs-gray-300) !important;
      color: var(--bs-gray-900) !important;
    }
  }

  .message {
    color: #ff0000;
  }
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
}
</style>
