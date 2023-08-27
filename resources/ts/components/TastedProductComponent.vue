<template>
  <div>
    <fieldset class="border h-100 bg-white p-3 mb-3">
      <legend>{{ intTitle1 }}</legend>
      <div class="row w-100 mx-auto">
        <div class="col-12 col-md-6 form-check form-switch p-0">
          <div class="form-check form-switch py-2">
            <label
              class="form-check-label"
              for="flexSwitchCheckDefault"
              role="button"
            >
              <b>{{ intLabel1 }} {{ intRequired1 ? "*" : "" }}</b>
            </label>
            <input
              @click="updateBoolean()"
              class="form-check-input"
              :name="intName1"
              type="checkbox"
              id="flexSwitchCheckDefault"
              role="button"
              :checked="intIsTasted ? true : false"
            >
            <br>
            <small class="form-text text-muted">{{ intHelper1 }}</small>
          </div>
        </div>
        <div
          v-if="intIsTasted"
          class="col-12 col-md-6"
        >
          <label
            :for="intId"
            class="col-form-label"
          >
            <b>{{ intLabel2 }} {{ intRequired2 ? "*" : "" }}</b>
          </label>
          <input
            :id="intId"
            :name="intName2"
            type="number"
            :value="
              intModel != null && intModel[intName2] != null
                ? intModel[intName2]
                : intOldModel != null && intOldModel[intName2] != undefined
                  ? intOldModel[intName2]
                  : 1
            "
            class="form-control"
            :aria-describedby="`${intId}Help`"
            step="1"
            :max="intMax"
            :min="intMin"
          >
          <small class="form-text text-muted">{{ intHelper2 }}</small>
        </div>
      </div>
    </fieldset>
    <fieldset
      v-if="intIsTasted"
      class="border h-100 bg-white p-3"
    >
      <legend>{{ intTitle2 }}</legend>
      <MultipleCommentsComponent
        :id="intModel !== null ? String(intModel.id) : ''"
        :title="intTitle2"
        :model="intCommentModel"
        :max="5"
        :min="0"
        :errors="intAllErrors"
      />
    </fieldset>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MultipleCommentsComponent from "./MultipleCommentsComponent.vue";
import trans from "../modules/trans";

export default defineComponent({
  components: {
    MultipleCommentsComponent,
  },
  mixins: [trans],
  data(): {
    intId: string;
    intTitle1: string;
    intTitle2: string;
    intLabel1: string;
    intName1: string;
    intName2: string;
    intModel: LaravelModel | null;
    intOldModel: LaravelModel;
    intHelper1: string;
    intLabel2: string;
    intHelper2: string;
    intIsTasted: boolean;
    intMax: number;
    intMin: number;
    intRequired1: boolean;
    intRequired2: boolean;
    intCommentModel: LaravelModelList;
    intAllErrors: Record<string, string[]>;
  } {
    return {
      intId: "",
      intTitle1: "",
      intTitle2: "",
      intLabel1: "",
      intLabel2: "",
      intName1: "",
      intName2: "",
      intModel: {},
      intOldModel: {},
      intHelper1: "",
      intHelper2: "",
      intIsTasted: false,
      intMax: 0,
      intMin: 0,
      intRequired1: false,
      intRequired2: false,
      intCommentModel: [],
      intAllErrors: {},
    };
  },
  mounted() {
    const json = String(this.$attrs.json ?? "{}");
    const data = JSON.parse(json);
    this.intId = data.id;
    this.intTitle1 = data.title1;
    this.intTitle2 = data.title2;
    this.intLabel1 = data.label1;
    this.intName1 = data.name1;
    this.intName2 = data.name2;
    this.intModel = data.model;
    this.intOldModel = data.oldModel;
    this.intHelper1 = data.helper1;
    this.intLabel2 = data.label2;
    this.intHelper2 = data.helper2;
    this.intMax = data.max;
    this.intMin = data.min;
    this.intRequired1 = data.required1;
    this.intRequired2 = data.required2;
    if (
      this.intModel
        ? this.intModel[this.intName1]
        : this.intOldModel
          ? this.intOldModel[this.intName1]
          : false
    ) {
      this.updateBoolean();
    }
    this.intCommentModel =
      data.commentOldModel.length > 0
        ? data.commentOldModel
        : data.commentModel;
    this.intAllErrors = data.errors;
  },
  methods: {
    updateBoolean() {
      this.intIsTasted = this.intIsTasted ? false : true;
    },
  },
});
</script>
