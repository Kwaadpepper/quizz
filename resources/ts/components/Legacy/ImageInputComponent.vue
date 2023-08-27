<template>
  <div :class="`image-input image-input-${intId} p-0`">
    <div class="row">
      <div :class="`col-12 mb-3 ${intPreview ? 'col-md-6' : ''}`">
        <label
          v-if="intPreview"
          :for="intId"
          class="col-form-label"
        >
          <b v-if="!intRequired">{{ __("Choisir un fichier") }}</b>
          <b v-else>{{ __("Choisir un fichier *") }}</b>
        </label>
        <div class="input-group">
          <button
            @click.prevent="chooseAFile"
            class="btn btn-outline-secondary"
            type="button"
            data-bs="tooltip"
            :title="__('Modifier l\'image source')"
          >
            <FontAwesomeIcon icon="fa-solid fa-folder-open" />
          </button>
          <input
            :id="intId"
            ref="actualImage"
            @change="changedImage"
            type="file"
            class="d-none"
            :name="intName"
            accept=".jpg,.jpeg,.gif,.png"
            :aria-describedby="`Help${intId}`"
          >
          <input
            v-if="typeof intValue === 'string'"
            class="d-none"
            :name="intName"
            type="text"
            :value="intValue"
          >
          <input
            @click.prevent="chooseAFile"
            type="text"
            :value="intValueFileName"
            class="form-control right-aligned"
            data-bs="tooltip"
            :title="__('Modifier l\'image source')"
            :aria-describedby="`Help${intId}`"
            readonly
          >
          <button
            v-if="!intRequired && (intHasImage || intValue.length > 0)"
            @click.prevent="removeFile"
            class="btn btn-danger"
            data-bs="tooltip"
            :title="__('Supprimer l\'image enregistrée')"
            type="button"
          >
            <FontAwesomeIcon icon="fa-solid fa-eraser" />
          </button>
          <button
            v-if="!intPreview && intValue"
            class="btn btn-warning"
            type="button"
            data-bs-toggle="modal"
            :data-bs-target="`#ModalPreview${intId}`"
          >
            <span
              data-bs="tooltip"
              :title="__('Visualiser l\'image')"
            >
              <FontAwesomeIcon icon="fa-solid fa-eye" />
            </span>
          </button>
          <button
            @click.prevent="prepareImageEditor($event)"
            :disabled="!intHasImage"
            :class="`btn ${!intHasModdedImage ? 'btn-primary' : 'btn-success'}`"
            type="button"
            data-bs-toggle="modal"
            :data-bs-target="`#Modal${intId}`"
            data-bs="tooltip"
            :title="__('Redimensionner l\'image')"
          >
            <FontAwesomeIcon icon="fa-solid fa-crop" />
          </button>
          <span
            v-if="intHasModdedImage"
            class="input-group-text text-success"
            data-bs="tooltip"
            :title="__('Image redimensionée')"
          >
            <FontAwesomeIcon icon="fa-solid fa-wand-magic" />
          </span>
        </div>
        <small
          :id="`Help${intId}`"
          class="form-text text-muted"
        >
          {{ intHelper }}
        </small>
      </div>
      <div
        v-if="intPreview"
        :class="`col-12 ${intPreview ? 'col-md-6' : ''}`"
      >
        <label
          v-if="intValue"
          class="col-form-label w-100 fw-bold w-100"
        >
          {{ __("Prévisualisation de l'image") }}
        </label>
        <img
          v-if="intValue"
          class="img-fluid"
          :src="intValue"
          :alt="__('Image placeholder')"
          :aria-describedby="`PreviewHelp${intId}`"
        >
        <small
          v-if="intValue"
          :id="`PreviewHelp${intId}`"
          class="form-text text-muted d-block m-0"
        >
          <span v-if="intOriginalFile">
            {{ humanFileSize(intOriginalFile.size) }}
            {{ `largeur ${intOriginalFileDimensions.width}px` }}
            {{ `hauteur ${intOriginalFileDimensions.height}px` }}.
          </span>
        </small>
      </div>
    </div>
    <div
      :id="`Modal${intId}`"
      class="modal"
      tabindex="-1"
      role="dialog"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      @mousedown="preventMouseDown"
      @dragstart.prevent="() => false"
      @ondrop.prevent="() => false"
      @dragover.prevent="() => false"
    >
      <div
        class="modal-dialog modal-xl"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ __("Modification de l'image avant l'importation") }}
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              :aria-label="__('Fermer la fenêtre')"
              data-bs="tooltip"
              :title="__('Fermer la fenêtre sans valider le contenu')"
            />
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <div class="col text-center">
                  <div class="btn-group me-4 mt-2">
                    <button
                      class="btn btn-warning"
                      data-bs="tooltip"
                      :title="__('Réinitialiser l\'image')"
                      @click.prevent="reset()"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-sync-alt" />
                    </button>
                  </div>
                  <div class="btn-group me-4 mt-2">
                    <button
                      class="btn btn-primary"
                      data-bs="tooltip"
                      :title="__('Zoom avant')"
                      @click.prevent="zoom(0.1)"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-search-plus" />
                    </button>
                    <a
                      class="btn btn-primary"
                      data-bs="tooltip"
                      :title="__('Zoom arrière')"
                      @click.prevent="zoom(-0.1)"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-search-minus" />
                    </a>
                  </div>
                  <div class="btn-group me-4 mt-2">
                    <a
                      class="btn btn-primary"
                      data-bs="tooltip"
                      :title="__('Déplacer vers la gauche')"
                      @click.prevent="move(-10, 0)"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-arrow-left" />
                    </a>
                    <a
                      class="btn btn-primary"
                      data-bs="tooltip"
                      :title="__('Déplacer vers la droite')"
                      @click.prevent="move(10, 0)"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-arrow-right" />
                    </a>
                    <a
                      class="btn btn-primary"
                      data-bs="tooltip"
                      :title="__('Déplacer vers le haut')"
                      @click.prevent="move(0, -10)"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-arrow-up" />
                    </a>
                    <a
                      class="btn btn-primary"
                      data-bs="tooltip"
                      :title="__('Déplacer vers le bas')"
                      @click.prevent="move(0, 10)"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-arrow-down" />
                    </a>
                  </div>
                  <div class="btn-group me-4 mt-2">
                    <a
                      class="btn btn-primary"
                      data-bs="tooltip"
                      :title="__('Mirroir horizontal')"
                      @click.prevent="scale(true)"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-arrows-alt-h" />
                    </a>
                    <a
                      class="btn btn-primary"
                      data-bs="tooltip"
                      :title="__('Miroir vertical')"
                      @click.prevent="scale(false)"
                    >
                      <FontAwesomeIcon icon="fa-solid fa-arrows-alt-v" />
                    </a>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="d-flex flex-row">
                    <div class="rotate-button">
                      <span class="text-dark">{{ __("Rotation") }}&nbsp;:</span>
                      <div class="btn-group mt-1">
                        <a
                          class="btn btn-primary"
                          data-bs="tooltip"
                          :title="__('Rotation sens anti-horaire')"
                          @click.prevent="rotate(-45)"
                        >
                          <FontAwesomeIcon icon="fa-solid fa-undo-alt" />
                        </a>
                        <a
                          class="btn btn-primary"
                          data-bs="tooltip"
                          :title="__('Rotation sens horaire')"
                          @click.prevent="rotate(45)"
                        >
                          <FontAwesomeIcon icon="fa-solid fa-redo-alt" />
                        </a>
                      </div>
                    </div>
                    <div class="mt-1 ms-4 rotate-drag">
                      <label
                        for="range"
                        class="form-label me-4"
                      >
                        {{
                          __(":deg degrès", {
                            deg: String(intRotationText),
                          })
                        }}
                      </label>
                      <input
                        ref="rotationSlider"
                        @input.stop="rotationChange($event as Event)"
                        @change.stop="
                          rotateTo(
                            Number.parseInt(
                              ($event.target as HTMLInputElement).value
                            )
                          )
                        "
                        type="range"
                        class="form-range"
                        id="range"
                        min="0"
                        max="360"
                        value="0"
                      >
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-12 col-md-6 p-0 mb-3">
                  <img
                    class="image-modification"
                    ref="imgEditor"
                    src=""
                  >
                </div>
                <div
                  ref="previewBox"
                  class="col-12 col-md-6 p-0 bg-light preview-image"
                >
                  <div
                    :id="`Preview${intId}`"
                    :style="intPreviewStyle"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              :aria-label="__('Fermer la fenêtre sans valider le contenu')"
              data-bs="tooltip"
              :title="__('Fermer la fenêtre sans valider le contenu')"
            >
              {{ __("Fermer") }}
            </button>
            <button
              @click.prevent="exportToBlob"
              type="button"
              class="btn btn-primary"
              data-bs="tooltip"
              data-bs-dismiss="modal"
              :title="__('Valider le contenu et fermer la fenêtre')"
            >
              {{ __("Valider") }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="!intPreview && intValue"
      :id="`ModalPreview${intId}`"
      class="modal"
      tabindex="-1"
      role="dialog"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
    >
      <div
        class="modal-dialog modal-xl"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ __("Prévisualisation") }}
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              :aria-label="__('Fermer la fenêtre')"
              data-bs="tooltip"
              :title="__('Fermer la fenêtre sans valider le contenu')"
            />
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <div class="col-12 text-center">
                  <small
                    :id="`PreviewHelp${intId}`"
                    class="form-text text-muted d-block"
                  >
                    {{ __("Prévisualisation de l'image") }}
                  </small>
                  <img
                    class="img-fluid"
                    :src="intValue"
                    :alt="__('Image placeholder')"
                    :aria-describedby="`PreviewHelp${intId}`"
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { Tooltip } from "bootstrap";
import Cropper from "cropperjs";
import { defineComponent } from "vue";
import trans from "./../../modules/trans";

const UNITS = [
  "byte",
  "kilobyte",
  "megabyte",
  "gigabyte",
  "terabyte",
  "petabyte",
];
const BYTES_PER_KB = 1024;

export default defineComponent({
  mixins: [trans],
  emits: ["browseFile"],
  components: {
    FontAwesomeIcon,
  },
  props: {
    id: {
      type: String,
      default: "",
    },
    required: {
      type: Boolean,
      default: false,
    },
    name: {
      type: String,
      default: "",
    },
    helper: {
      type: String,
      default: "",
    },
    width: {
      type: Number,
      default: 0,
    },
    height: {
      type: Number,
      default: 0,
    },
    value: {
      type: String,
      default: "",
    },
    preview: {
      type: Boolean,
      default: true,
    },
    browseEvent: {
      type: Boolean,
      default: false,
    },
  },
  data(): {
    intId: string;
    intValue: string;
    intValueFileName: string;
    intHelper: string;
    intName: string;
    intRequired: boolean;
    intWidth: number;
    intHeight: number;
    intPreview: boolean;

    intBrowseEvent: boolean;

    intRotationText: number;
    intRotationValue: number;
    intScaleX: number;
    intScaleY: number;
    intHasImage: boolean;
    intHasModdedImage: boolean;
    intCropper: Cropper | null;

    intOriginalFile: File | null;
    intOriginalFileDimensions: {
      height: number;
      width: number;
    };
    intOriginalFileName: string;
    intPreviewStyle: string;
    tooltipList: HTMLButtonElement[];
  } {
    return {
      intId: "",
      intValue: "",
      intValueFileName: this.__("Sélectionnez une image"),
      intHelper: "",
      intName: "",
      intRequired: true,
      intWidth: 0,
      intHeight: 0,
      intPreview: true,

      intBrowseEvent: false,

      intRotationText: 0,
      intRotationValue: 0,
      intScaleX: 1,
      intScaleY: 1,
      intHasImage: false,
      intHasModdedImage: false,
      intCropper: null,

      intOriginalFile: null,
      intOriginalFileDimensions: {
        height: 0,
        width: 0,
      },
      intOriginalFileName: "",
      intPreviewStyle: "",
      tooltipList: [],
    };
  },
  mounted() {
    const json = String(this.$attrs.json ?? "");
    if (json.length) {
      // * Init uppon Laravel Blade
      const data = JSON.parse(json);
      this.intId = data.id;
      this.intName = data.name;
      this.intHelper = data.helper ?? "";
      this.intWidth = data.width;
      this.intHeight = data.height;
      this.intValue = String(data.value);
      if (this.intValue.length) {
        this.intValueFileName = this.intValue;
        this.intValue = this.intValue.replace(/^\/|^/, "/");
      }
      if (data.preview !== undefined) {
        this.intPreview = data.preview ? true : false;
      }
      if (data.required !== undefined) {
        this.intRequired = data.required ? true : false;
      }
      if (data.browseEvent !== undefined) {
        this.intBrowseEvent = data.browseEvent ? true : false;
      }
    } else {
      // * Init Uppon Vue component
      this.intId = this.id;
      this.intName = this.name;
      this.intHelper = this.helper;
      this.intWidth = this.width;
      this.intHeight = this.height;
      this.intValue = this.value;
      if (this.intValue.length) {
        this.intValueFileName = this.intValue;
        this.intValue = this.intValue.replace(/^\/|^/, "/");
      }
      this.intPreview = this.preview;
      this.intRequired = this.required;
      this.intBrowseEvent = this.browseEvent;
    }
    this.intHasImage = false;
    this.$nextTick(() => {
      this.updateBootstrapTooltip();
    });
  },
  computed: {
    imageRatio() {
      return this.intWidth / this.intHeight;
    },
    cropperOption(): Cropper.Options {
      return {
        autoCrop: true,
        background: true,
        autoCropArea: 1,
        viewMode: 0,
        dragMode: "move",
        movable: true,
        rotatable: true,
        scalable: true,
        zoomable: true,
        zoomOnTouch: false,
        zoomOnWheel: false,
        cropBoxMovable: true,
        cropBoxResizable: false,
        toggleDragModeOnDblclick: false,
        aspectRatio: this.intWidth / this.intHeight,
        preview: `#Preview${this.intId}`,
      };
    },
  },
  watch: {
    intRotationValue(value) {
      if (!(this.intCropper instanceof Cropper)) {
        throw new Error("cropped not initialized");
      }
      this.intCropper.rotateTo(value);
    },
    intValue() {
      if (this.intBrowseEvent) {
        this.intHasImage = true;
      }
    },
    value() {
      this.intValue = this.value.replace(/^\/|^/, "/");
      this.intValueFileName = this.value;
    },
  },
  methods: {
    /**
     * Cropper preview style.
     */
    previewStyleCompute() {
      const previewBox = this.$refs.previewBox as HTMLElement,
            style = window.getComputedStyle(previewBox),
            targetWidth = Number.parseFloat(style.width) / 1.2,
            targetHeight =
              (this.intHeight * Number.parseFloat(style.width)) /
              this.intWidth /
              1.2;
      this.intPreviewStyle = `width: ${targetWidth.toFixed(
        3
      )}px;height:${targetHeight.toFixed(3)}px;`;
    },
    /**
     * Cropper reset.
     */
    reset() {
      if (!(this.intCropper instanceof Cropper)) {
        return;
      }
      this.intCropper.reset();
    },
    /**
     * Cropper zoom.
     */
    zoom(v: number) {
      if (!(this.intCropper instanceof Cropper)) {
        return;
      }
      this.intCropper.zoom(v);
    },
    /**
     * Cropper move.
     */
    move(x: number, y: number) {
      if (!(this.intCropper instanceof Cropper)) {
        return;
      }
      this.intCropper.move(x, y);
    },
    /**
     * Cropper scale.
     */
    scale(horizontal: boolean) {
      if (!(this.intCropper instanceof Cropper)) {
        return;
      }
      if (horizontal) {
        this.intScaleX = -1 / this.intScaleX;
        this.intCropper.scaleX(this.intScaleX);
      } else {
        this.intScaleY = -1 / this.intScaleY;
        this.intCropper.scaleY(this.intScaleY);
      }
    },
    /**
     * Cropper rotate.
     */
    rotate(value: number) {
      const rotationSlider = this.$refs.rotationSlider as HTMLInputElement;
      let res = this.intRotationValue + value;
      res = res < 0 ? 0 : res;
      res = res > 360 ? 360 : res;
      this.intRotationValue = res;
      this.intRotationText = this.intRotationValue;
      rotationSlider.value = String(this.intRotationValue);
    },
    /**
     * Cropper rotate to.
     */
    rotateTo(value: number) {
      value = value < 0 ? 0 : value;
      value = value > 360 ? 360 : value;
      this.intRotationValue = value;
      this.intRotationText = this.intRotationValue;
    },
    /**
     * Cropper rotation input.
     */
    rotationChange(e: Event) {
      const el = e.target;
      if (!el || !(el instanceof HTMLInputElement)) {
        throw new Error("target element is not an input");
      }
      this.rotateTo(Number.parseFloat(el.value));
    },
    /**
     * Link button change image to the input file image.
     */
    chooseAFile() {
      const actualImage = this.$refs.actualImage as HTMLInputElement;
      if (this.intBrowseEvent) {
        this.$emit("browseFile", this.intId);
        return;
      }
      actualImage.click();
    },
    /**
     * Remove one specific image.
     */
    removeFile() {
      const actualImage = this.$refs.actualImage as HTMLInputElement;
      actualImage.value = "";
      this.intValueFileName = this.__("Sélectionnez une image");
      this.intValue = "";
      this.intHasModdedImage = this.intHasImage = false;
      this.closeBootstrapTooltip();
    },
    /**
     * Change one specific image.
     */
    changedImage(e: Event) {
      const self = this,
            reader = new FileReader();
      const el = e.target;
      if (!el || !(el instanceof HTMLInputElement)) {
        throw new Error(
          "confirmDoneJS can only be executed on an exising form"
        );
      }
      if (!el.files) {
        throw new Error();
      }
      self.intHasImage = true;
      reader.addEventListener(
        "load",
        () => {
          // On convertit l'image en une chaîne de caractères base64.
          self.intValue = String(reader.result);
        },
        false
      );
      if (el.files[0]) {
        self.intOriginalFile = el.files[0];
        reader.readAsDataURL(el.files[0]);
        reader.onloadend = () =>
          this.fetchDataUrlImageSizes(String(reader.result));
        this.intValueFileName = el.files[0].name;
        this.$nextTick(() => {
          this.updateBootstrapTooltip();
        });
      }
    },
    /**
     * Set cropper to edit a specific image.
     */
    prepareImageEditor(e: Event) {
      const self = this,
            actualImage = this.$refs.actualImage as HTMLInputElement,
            imgEditor = this.$refs.imgEditor as HTMLImageElement,
            createCropper = () => {
              if (self.intCropper) {
                self.intCropper.destroy();
              }
              self.intCropper = new Cropper(imgEditor, self.cropperOption);
            };

      if (!self.browseEvent) {
        if (!actualImage || !actualImage.files) {
          throw new Error("actualImage is not defined");
        }
        self.intOriginalFile = actualImage.files[0];
        self.intOriginalFileName = actualImage.files[0].name;
        imgEditor.src = URL.createObjectURL(actualImage.files[0]);

        self.previewStyleCompute();
        this.$nextTick(() => createCropper());
        return;
      }

      e.preventDefault();
      this.$nextTick(() => {
        fetch(self.value.replace(/^\/|^/, "/"))
          .then((response) => {
            self.intOriginalFileName = response.url.substring(
              response.url.lastIndexOf("/") + 1
            );
            return response.blob();
          })
          .then((blob: Blob) => {
            imgEditor.src = URL.createObjectURL(blob);

            self.previewStyleCompute();
            self.$nextTick(() => createCropper());
          });
      });
    },
    /**
     * Create a Blob from a specific url.
     */
    dataURLtoBlob(dataUrl: string) {
      const arr = dataUrl.split(",");
      if (!arr[0] || !arr[1]) {
        throw new Error("invalid data url");
      }
      const mime = arr[0].match(/:(.*?);/);
      if (!mime || mime.length < 2) {
        throw new Error("invalid mime");
      }
      const bstr = window.atob(arr[1]);
      let n = bstr.length;
      const u8arr = new Uint8Array(n);
      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }
      return new Blob([u8arr], { type: mime[1] });
    },
    /**
     * Update image sizes.
     */
    fetchDataUrlImageSizes(dataUrl: string) {
      const img = new Image(),
            self = this;
      try {
        img.onload = () => {
          self.intOriginalFileDimensions.width = img.width;
          self.intOriginalFileDimensions.height = img.height;
        };
        img.src = dataUrl;
      } catch (e) {
        self.intOriginalFileDimensions.width = img.width;
        self.intOriginalFileDimensions.height = img.height;
      }
    },
    /**
     * Set image after the edit in cropper.
     */
    exportToBlob() {
      const self = this,
            actualImage = this.$refs.actualImage as HTMLInputElement,
            container = new DataTransfer();
      if (!(self.intCropper instanceof Cropper)) {
        return;
      }
      if (!actualImage.files) {
        throw new Error();
      }
      self.intHasModdedImage = true;
      this.$nextTick(() => {
        this.updateBootstrapTooltip();
      });
      self.intValue = self.intCropper
        .getCroppedCanvas({
          fillColor: "rgba(0, 0, 0, 0)",
          width: self.intWidth,
          height: self.intHeight,
          imageSmoothingEnabled: true,
          imageSmoothingQuality: "high",
        })
        .toDataURL("image/png");
      this.fetchDataUrlImageSizes(self.intValue);
      const file = new File(
        [self.dataURLtoBlob(self.intValue)],
        self.intOriginalFileName,
        {
          type: "image/png",
          lastModified: new Date().getTime(),
        }
      );
      this.intOriginalFile = file;
      container.items.add(file);
      actualImage.files = container.files;
    },
    /**
     * Get size of a file.
     */
    humanFileSize(sizeBytes: number | bigint): string {
      let size = Math.abs(Number(sizeBytes));

      let u = 0;
      while (size >= BYTES_PER_KB && u < UNITS.length - 1) {
        size /= BYTES_PER_KB;
        ++u;
      }

      return new Intl.NumberFormat([], {
        style: "unit",
        unit: UNITS[u],
        unitDisplay: "short",
        maximumFractionDigits: 1,
      }).format(size);
    },
    /**
     * Update Bootstrap tooltips.
     */
    updateBootstrapTooltip() {
      let newTooltipList = [].slice.call(
        document.querySelectorAll(
          ".image-input-" + this.intId + " [data-bs=\"tooltip\"]"
        )
      ) as HTMLButtonElement[];
      let tmp = newTooltipList.filter((x) => !this.tooltipList.includes(x));
      tmp.map((tooltip) => {
        return new Tooltip(tooltip);
      });
      this.closeBootstrapTooltip();
      this.tooltipList = newTooltipList;
    },
    /**
     * Close all Bootstrap tooltips.
     */
    closeBootstrapTooltip() {
      this.tooltipList.forEach((tooltip) => {
        tooltip.blur();
        Tooltip.getInstance(tooltip)?.hide();
      });
    },
  },
});
</script>

<style lang="scss" scopped>
@import "./../../../node_modules/cropperjs/dist/cropper.css";
.image-input {
  .image-modification {
    display: block;
    max-width: 100%;
  }
  .rotate-button {
    display: flex;
    flex-direction: column;
  }
  .rotate-drag {
    display: flex;
    width: 100%;
    flex-direction: column-reverse;
    input {
      width: 100%;
    }
  }
  .preview-image {
    position: relative;
    div {
      position: relative;
      top: 50%;
      left: 50%;
      overflow: hidden;
      border: 1px solid #000;
      transform: translateX(-50%) translateY(-50%);
    }
  }
  .right-aligned {
    overflow: hidden !important;
    direction: ltr !important;
  }
  .right-aligned:not(:focus) {
    text-align: left !important;
    text-overflow: ellipsis !important;
  }
}
</style>
