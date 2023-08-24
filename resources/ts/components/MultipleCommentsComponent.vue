<template>
  <div class="multiple-comments">
    <input
      class="d-none"
      name="product_id"
      type="text"
      :value="intId"
    >
    <div class="row mb-3">
      <div class="col-12 col-md-6">
        <label
          class="col-form-label"
          for="commentNumber"
        >
          <b>{{ __("Nombre de commentaire concernant le produit") }}</b>
        </label>
        <input
          @change="updateNumberOfComments($event)"
          for="commentNumber"
          class="form-control"
          name="nb_comments"
          type="number"
          :max="intMax"
          :min="intMin"
          :value="intModel.length"
          :placeholder="__('Nombre de commentaires')"
        >
      </div>
    </div>
    <div
      v-for="(comment, commentIndex) in intModel"
      :key="commentIndex"
      class="row mb-3"
    >
      <div class="col-12">
        <button
          class="btn btn-primary dropdown-toggle rounded-0 w-100"
          type="button"
          data-bs-toggle="collapse"
          :data-bs-target="'#comment' + commentIndex"
          aria-expanded="false"
          :aria-controls="'comment' + commentIndex"
        >
          {{ __("Commentaire n°" + (commentIndex + 1)) }}
        </button>
        <div
          class="border collapse"
          :id="'comment' + commentIndex"
        >
          <div class="row w-100 mx-auto mb-3">
            <div class="col-12">
              <label
                class="col-form-label"
                for="commentEvaluation"
              >
                <b>{{ __("Évaluation du produit") }} *</b>
              </label>
              <InputTextAutoComponent
                :id="'commentEvaluation' + commentIndex"
                :name="'evaluation[' + commentIndex + ']'"
                class="form-control"
                :value="comment?.evaluation"
                :placeholder="__('Un peu caoutchouteux')"
                :ariadescribedby="'productcommentEvaluation' + commentIndex"
              />
              <p class="text-danger m-0">
                {{
                  parseValidationInput(
                    `evaluation.${String(commentIndex)}`,
                    intAllErrors
                  )
                }}
              </p>
              <small
                :id="`commentAspectHelp${commentIndex}`"
                class="form-text text-muted"
              >
                {{
                  __(
                    "Doit détailler l'évaluation du produit (3 caractères minimum, 255 maximum)."
                  )
                }}
              </small>
            </div>
            <div class="col-12 col-md-6">
              <label
                class="col-form-label"
                for="commentAspect"
              >
                <b>{{ __("Aspect du produit") }}</b>
              </label>
              <InputTextAutoComponent
                :id="'commentAspect' + commentIndex"
                :name="'aspect[' + commentIndex + ']'"
                class="form-control"
                :value="comment?.aspect"
                :placeholder="__('Un peu caoutchouteux')"
                :ariadescribedby="'productcommentAspect' + commentIndex"
              />
              <p class="text-danger m-0">
                {{
                  parseValidationInput(
                    `aspect.${String(commentIndex)}`,
                    intAllErrors
                  )
                }}
              </p>
              <small
                :id="`commentAspectHelp${commentIndex}`"
                class="form-text text-muted"
              >
                {{
                  __(
                    "Doit décrire précisémment l'aspect du produit (3 caractères minimum, 255 maximum)."
                  )
                }}
              </small>
            </div>
            <div class="col-12 col-md-6">
              <label
                class="col-form-label"
                for="commentTaste"
              >
                <b>{{ __("Goût du produit") }}</b>
              </label>
              <InputTextAutoComponent
                :id="'commentTaste' + commentIndex"
                :name="'taste[' + commentIndex + ']'"
                class="form-control"
                :value="comment?.taste"
                :placeholder="__('Un peu caoutchouteux')"
                :ariadescribedby="'productcommentTaste' + commentIndex"
              />
              <p class="text-danger m-0">
                {{
                  parseValidationInput(
                    `taste.${String(commentIndex)}`,
                    intAllErrors
                  )
                }}
              </p>
              <small
                :id="`commentAspectHelp${commentIndex}`"
                class="form-text text-muted"
              >
                {{
                  __(
                    "Doit décrire précisémment le goût du produit (3 caractères minimum, 255 maximum)."
                  )
                }}
              </small>
            </div>
            <div class="col-12 col-md-6">
              <label
                class="col-form-label"
                for="commentOdor"
              >
                <b>{{ __("Odeur du produit") }}</b>
              </label>
              <InputTextAutoComponent
                :id="'commentOdor' + commentIndex"
                :name="'odor[' + commentIndex + ']'"
                class="form-control"
                :value="comment?.odor"
                :placeholder="__('Un peu caoutchouteux')"
                :ariadescribedby="'productcommentOdor' + commentIndex"
              />
              <p class="text-danger m-0">
                {{
                  parseValidationInput(
                    `odor.${String(commentIndex)}`,
                    intAllErrors
                  )
                }}
              </p>
              <small
                :id="`commentAspectHelp${commentIndex}`"
                class="form-text text-muted"
              >
                {{
                  __(
                    "Doit décrire précisémment l'\odeur du produit (3 caractères minimum, 255 maximum)."
                  )
                }}
              </small>
            </div>
            <div class="col-12 col-md-6">
              <label
                class="col-form-label"
                for="commentTexture"
              >
                <b>{{ __("Texture du produit") }}</b>
              </label>
              <InputTextAutoComponent
                :id="'commentTexture' + commentIndex"
                :name="'texture[' + commentIndex + ']'"
                class="form-control"
                :value="comment?.texture"
                :placeholder="__('Un peu caoutchouteux')"
                :ariadescribedby="'productCommentTexture' + commentIndex"
              />
              <p class="text-danger m-0">
                {{
                  parseValidationInput(
                    `texture.${String(commentIndex)}`,
                    intAllErrors
                  )
                }}
              </p>
              <small
                :id="`commentAspectHelp${commentIndex}`"
                class="form-text text-muted"
              >
                {{
                  __(
                    "Doit décrire précisémment la texture du produit (3 caractères minimum, 255 maximum)."
                  )
                }}
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import InputTextAutoComponent from "./InputTextAutoComponent.vue";
import parseLaravelErrorMessages from "../modules/parseLaravelErrorMessages";
import trans from "../modules/trans";

export default defineComponent({
  components: {
    InputTextAutoComponent,
  },
  mixins: [parseLaravelErrorMessages, trans],
  props: {
    id: {
      type: String,
      default: null,
    },
    title: {
      type: String,
      default: null,
    },
    model: {
      type: Object,
      default: null,
    },
    max: {
      type: Number,
      default: null,
    },
    min: {
      type: Number,
      default: null,
    },
    errors: {
      type: Object,
      default: null,
    },
  },
  data(): {
    intId: string;
    intTitle: string;
    intModel: {
      evaluation: string;
      aspect: string;
      taste: string;
      odor: string;
      texture: string;
    }[];
    intMax: number;
    intMin: number;
    intAllErrors: Record<string, string[]>;
  } {
    return {
      intId: "",
      intTitle: "",
      intModel: [],
      intMax: 0,
      intMin: 0,
      intAllErrors: {},
    };
  },
  mounted() {
    const json = String(this.$attrs.json ?? "{}");
    const data = JSON.parse(json);
    this.intMax = data.max ? Number(data.max) : this.max;
    this.intMin = data.min ? Number(data.min) : this.min;
    this.intId = data.id ? String(data.id) : this.id;
    this.intTitle = data.title ? String(data.title) : this.title;
    this.intModel = data.model
      ? data.model
      : (this.model as {
        evaluation: string;
        aspect: string;
        taste: string;
        odor: string;
        texture: string;
      }[]);
    // * Error parsing
    this.intAllErrors = data.errors ? data.errors : this.errors;
  },
  watch: {
    id() {
      this.intId = this.id;
    },
  },
  methods: {
    updateNumberOfComments(e: Event) {
      const target = e.target as HTMLInputElement;
      this.intModel.length = Number(target.value);
    },
  },
});
</script>
