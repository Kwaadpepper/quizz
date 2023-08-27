<template>
  <div
    :class="`container multiple-documents-input multiple-documents-input-${intId} p-0`"
  >
    <!-- Main buttons -->
    <div class="row">
      <div class="col-12 d-flex form-group">
        <div class="input-group">
          <button
            @click.prevent="chooseFiles"
            class="btn btn-secondary"
            type="button"
            :title="__('Modifier les documents sources')"
            data-bs-tooltip="tooltip"
            :disabled="intValues.length >= intItemLimit[1]"
          >
            <i class="fa-solid fa-folder-open" />
          </button>
          <input
            :id="intId"
            ref="actualDocuments"
            @change="changedDocuments"
            type="file"
            class="d-none"
            name="multipleInputDocuments"
            accept=".pdf"
            :aria-describedby="`Help${intId}`"
            multiple
          >
          <input
            @click.prevent="chooseFiles"
            type="text"
            :value="
              'Actuellement ' +
                intValues.length +
                '/' +
                intItemLimit[1] +
                ' documents sont présents'
            "
            class="form-control right-aligned"
            :title="__('Modifier les documents sources')"
            data-bs-tooltip="tooltip"
            :aria-describedby="`Help${intId}`"
            :disabled="intValues.length >= intItemLimit[1]"
            readonly
          >
          <button
            class="btn btn-primary btn-collapse collapsed"
            type="button"
            :title="__('Rouler/Dérouler la liste des documents')"
            data-bs-tooltip="tooltip"
            data-bs-toggle="collapse"
            data-bs-target="#multiple-documents"
            aria-expanded="false"
            aria-controls="multiple-documents"
            :disabled="!intHasDocuments"
          >
            <i class="fa fa-arrow-down" />
          </button>
          <button
            v-if="intValues.length > intItemLimit[0]"
            @click.prevent="removeFiles"
            class="btn btn-danger"
            type="button"
            :title="__('Supprimer tous les documents')"
            data-bs-tooltip="tooltip"
            :disabled="intValues.length <= 0"
          >
            <i class="fas fa-eraser" />
          </button>
        </div>
      </div>
    </div>
    <!-- End main buttons -->
    <!-- Content collapse -->
    <div
      class="collapse"
      id="multiple-documents"
    >
      <div class="row w-100 mx-auto my-2">
        <div
          v-for="n = 0 in intValues.length"
          :key="n"
          class="col-12 form-group text-center px-1"
        >
          <div
            class="d-flex justify-content-between align-items-center pb-1"
            :class="n != 1 ? 'border-top' : ''"
          >
            <label
              v-if="intValues[n - 1]"
              class="col-form-label d-flex"
            >
              <span class="text-start text-ellipsis">
                {{ intOriginalFileName[n - 1] }}
              </span>
            </label>
            <small
              v-if="intValues[n - 1]"
              :id="`PreviewHelp${intId}`"
              class="form-text text-muted d-block m-0"
            >
              <span v-if="intOriginalFile[n - 1]">
                {{ humanFileSize(intOriginalFile[n - 1].size) }}.
              </span>
            </small>
            <div class="input-group justify-content-center mt-1 w-auto">
              <button
                @click.prevent="viewDocumentSource(n - 1)"
                class="btn btn-warning"
                type="button"
                data-bs-toggle="modal"
                :data-bs-target="`#modalViewer${intId}`"
                :title="__('Afficher le document')"
                data-bs-tooltip="tooltip"
              >
                <i class="fa-solid fa-eye" />
              </button>
              <button
                @click.prevent="chooseAFile(n - 1)"
                class="btn btn-secondary"
                type="button"
                :title="__('Modifier le document source')"
                data-bs-tooltip="tooltip"
              >
                <i class="fa-solid fa-folder-open" />
              </button>
              <input
                :id="intId"
                ref="actualDocument"
                @change="changedDocument($event, n - 1)"
                type="file"
                class="d-none"
                :name="intName"
                accept=".pdf"
                :aria-describedby="`Help${intId}`"
              >
              <button
                v-if="intValues.length > intItemLimit[0]"
                @click.prevent="removeFile(n - 1)"
                class="btn btn-danger"
                type="button"
                :title="__('Supprimer le document')"
                data-bs-tooltip="tooltip"
              >
                <i class="fas fa-trash" />
              </button>
            </div>
          </div>
          <p class="text-danger m-0">
            {{
              parseValidationInput(
                `${intName.replace(/[\[\]']+/g, "")}.${n - 1}`,
                intAllErrors
              )
            }}
          </p>
        </div>
        <!-- Viewer modal -->
        <div
          :id="`modalViewer${intId}`"
          class="modal"
          tabindex="-1"
          role="dialog"
          data-bs-backdrop="static"
          data-bs-keyboard="false"
        >
          <div
            class="modal-dialog modal-xl d-flex align-items-center h-100 my-0 py-4"
          >
            <div class="modal-content h-100">
              <div class="modal-header">
                <h5 class="modal-title">
                  {{ __("Prévisualisation du document") }}
                </h5>
                <button
                  @click.prevent="removeDocumentSource"
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  :aria-label="__('Fermer la fenêtre')"
                  :title="__('Fermer la fenêtre')"
                  data-bs-tooltip="tooltip"
                />
              </div>
              <div class="modal-body text-center">
                <div
                  v-if="intViewerLoadDocument"
                  class="text-center w-100 h-100"
                >
                  <div
                    class="spinner-border text-secondary"
                    role="status"
                  >
                    <span class="visually-hidden">
                      {{ __("Chargement du document") }}
                    </span>
                  </div>
                </div>
                <object
                  class="w-100 h-100"
                  :class="intViewerLoadDocument ? 'd-none' : ''"
                  type="application/pdf"
                  data=""
                  ref="documentViewer"
                  :title="__('Document enregistré')"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End content collapse -->
    <small
      :id="`Help${intId}`"
      class="form-text text-muted"
    >
      {{ intHelper }}
    </small>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { Tooltip } from "bootstrap";
import error from "./../modules/parseLaravelErrorMessages";
import trans from "./../modules/trans";

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
  mixins: [error, trans],
  data(): {
    intId: string;
    intName: string;
    intHasDocument: boolean[];
    intHasDocuments: boolean;
    intOriginalFile: File[];
    intOriginalFileName: string[];
    intValues: string[];
    intHelper: string;
    intTooltipList: HTMLButtonElement[];
    intBtnCollapse: HTMLButtonElement | null;
    intLoopLoadDocuments: number;
    intViewerLoadDocument: boolean;
    intInputDocuments: HTMLInputElement[];
    intItemLimit: number[];
    intAllErrors: Record<string, string[]>;
  } {
    return {
      intId: "",
      intName: "",
      intHasDocument: [],
      intHasDocuments: false,
      intOriginalFile: [],
      intOriginalFileName: [],
      intValues: [],
      intHelper: "",
      intBtnCollapse: null,
      intTooltipList: [],
      intLoopLoadDocuments: 0,
      intViewerLoadDocument: false,
      intInputDocuments: [],
      intItemLimit: [0, 0],
      intAllErrors: {},
    };
  },
  mounted() {
    const json = String(this.$attrs.json ?? "");
    const data = JSON.parse(json);
    this.intId = data.id;
    this.intName = data.name;
    this.intValues = data.value;
    this.intHelper = data.helper ?? "";
    this.intItemLimit = data.limit;
    this.intAllErrors = data.errors ?? {};
    this.$nextTick(() => {
      this.initComponent();
      this.updateBootstrapTooltip();
    });
  },
  methods: {
    /**
     * Initialise the component with previously documents registered.
     */
    initComponent() {
      if (this.intValues.length > 0) {
        this.intBtnCollapse = document.querySelector(
          "[data-bs-target=\"#multiple-documents\"]"
        );
        this.intValues.forEach((element, index) => {
          element.charAt(0) != "/" ? "/".concat(element) : "";
          this.intValues.splice(index, 1, element.replace(/^\/|^/, "/"));
        });
        this.intHasDocument = Array(this.intValues.length).fill(false);
        this.intHasDocuments = true;
        this.initDocumentsSaved();
      } else {
        this.intHasDocuments = false;
      }
    },
    /**
     * Assign previously documents registered to respective input.
     */
    initDocumentsSaved() {
      var actualDocuments = this.$refs.actualDocument as HTMLInputElement[];
      this.intValues.forEach((element, index) => {
        this.createFile(
          element,
          "document_" + (index + 1) + ".pdf",
          "application/pdf"
        ).then((file: File) => {
          const container = new DataTransfer();
          container.items.add(file);
          actualDocuments[index].files = container.files;
          this.intOriginalFile[index] = container.files[0];
          this.intOriginalFileName[index] = container.files[0].name;
        });
      });
    },
    /**
     * Create a file from a path.
     */
    async createFile(path: string, name: string, type: string): Promise<File> {
      let response = await fetch(
        window.location.protocol + "//" + window.location.host + "/" + path
      );
      let data = await response.blob();
      let metadata = {
        type: type,
      };
      return new File([data], name, metadata);
    },
    /**
     * Update Bootstrap tooltips.
     */
    updateBootstrapTooltip() {
      let newTooltipList = [].slice.call(
        document.querySelectorAll(
          ".multiple-documents-input-" +
            this.intId +
            " [data-bs-tooltip=\"tooltip\"]"
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
     * Link button change document to the input file document.
     */
    chooseAFile(n: number) {
      var actualDocuments = this.$refs.actualDocument as HTMLInputElement[],
          actualDocument = actualDocuments[n] as HTMLInputElement;
      actualDocument.click();
    },
    /**
     * Link button change documents to the input file documents.
     */
    chooseFiles() {
      const actualDocuments = this.$refs.actualDocuments as HTMLInputElement;
      actualDocuments.click();
    },
    /**
     * Remove one specific document.
     */
    removeFile(n: number) {
      this.intValues.splice(n, 1);
      this.intHasDocument.splice(n, 1);
      this.intOriginalFile.splice(n, 1);
      this.intOriginalFileName.splice(n, 1);
      if (this.intValues.length <= 0) {
        this.intHasDocuments = false;
      }
      this.intInputDocuments = this.$refs.actualDocument as HTMLInputElement[];
      this.intInputDocuments.forEach((element, index) => {
        if (this.intOriginalFile[index] != undefined) {
          var dt = new DataTransfer();
          dt.items.add(this.intOriginalFile[index]);
          element.files = dt.files;
        }
      });
      this.closeBootstrapTooltip();
    },
    /**
     * Remove all documents.
     */
    removeFiles() {
      this.intValues.length = this.intItemLimit[0];
      this.intHasDocument.length = this.intItemLimit[0];
      this.intOriginalFile.length = this.intItemLimit[0];
      this.intOriginalFileName.length = this.intItemLimit[0];
      this.intItemLimit[0] > 0
        ? (this.intHasDocuments = true)
        : (this.intHasDocuments = false);
      this.intAllErrors = {};
      this.closeBootstrapTooltip();
    },
    /**
     * Change one specific document.
     */
    changedDocument(e: Event, n: number) {
      const reader = new FileReader();
      const el = e.target;
      if (!el || !(el instanceof HTMLInputElement)) {
        throw new Error(
          "confirmDoneJS can only be executed on an exising form"
        );
      }
      if (!el.files) {
        throw new Error();
      }
      this.intHasDocument[n] = true;
      reader.addEventListener(
        "load",
        () => {
          // On convertit le document en une chaîne de caractères base64.
          this.intValues[n] = String(reader.result);
        },
        false
      );
      if (el.files[0]) {
        this.intOriginalFile[n] = el.files[0];
        this.intOriginalFileName[n] = el.files[0].name;
        reader.readAsDataURL(el.files[0]);
      }
    },
    /**
     * Add documents to exists document(s).
     */
    changedDocuments(e: Event) {
      const el = e.target;
      if (!el || !(el instanceof HTMLInputElement)) {
        throw new Error(
          "confirmDoneJS can only be executed on an exising form"
        );
      }
      if (!el.files) {
        throw new Error();
      }
      this.intLoopLoadDocuments = this.intValues.length;
      this.loadDocuments([].slice.call(el.files));
      this.intHasDocuments = true;
      if (this.intBtnCollapse?.classList.contains("collapsed")) {
        this.intBtnCollapse?.click();
      }
    },
    /**
     * Update inputs file.
     */
    loadDocuments(files: Array<File>) {
      if (files.length > 0) {
        if (this.intValues.length + files.length <= this.intItemLimit[1]) {
          let actualFile = files.shift() as File;
          const reader = new FileReader();
          reader.readAsDataURL(actualFile);
          reader.onload = () => {
            // On convertit l'document en une chaîne de caractères base64.
            this.intValues.push(String(reader.result));
          };
          reader.onloadend = () => {
            this.intInputDocuments = this.$refs
              .actualDocument as HTMLInputElement[];
            var dt = new DataTransfer();
            dt.items.add(actualFile);
            this.intInputDocuments[this.intLoopLoadDocuments].files = dt.files;
            this.intOriginalFile[this.intLoopLoadDocuments] = actualFile;
            this.intHasDocument[this.intValues.length - 1] = true;
            this.intOriginalFileName[this.intValues.length - 1] =
              actualFile.name;
            this.intLoopLoadDocuments++;
            this.loadDocuments(files);
          };
        }
      } else {
        this.updateBootstrapTooltip();
        this.intLoopLoadDocuments = this.intValues.length;
      }
    },
    /**
     * Update document source in the viewer.
     */
    viewDocumentSource(n: number) {
      const documentViewer = this.$refs.documentViewer as HTMLObjectElement;
      this.intViewerLoadDocument = true;
      setTimeout(() => {
        documentViewer.data = this.intValues[n];
        documentViewer.onload = () => {
          this.intViewerLoadDocument = false;
        };
      }, 10);
    },
    /**
     * Remove document source in the viewer.
     */
    removeDocumentSource() {
      this.intViewerLoadDocument = true;
      const documentViewer = this.$refs.documentViewer as HTMLObjectElement;
      documentViewer.data = "";
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
  },
});
</script>
