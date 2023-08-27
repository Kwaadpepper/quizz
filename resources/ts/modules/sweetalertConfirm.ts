// SweetAlert
import Swal, { SweetAlertOptions, SweetAlertResult } from "sweetalert2";

import * as trans from "../modules/trans";

export default {
    methods: {
        confirm(
            title: string,
            text = "",
            self: HTMLElement,
            after: (response: SweetAlertResult<string>) => void = () => "",
            options: SweetAlertOptions = {},
        ) {
            if (!self) {
                throw Error("self is needed to set \"this\" on callback");
            }
            if (!after) {
                throw Error("callback is needed when using confirm");
            }
            const icon = options.icon ?? "warning";
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: options.confirmButtonText ?? trans.default.methods.__("Oui"),
                cancelButtonText: options.cancelButtonText ?? trans.default.methods.__("Annuler"),
                reverseButtons: true,
                // * Bootstrap Styling
                customClass: {
                    confirmButton: "btn btn-lg btn-success  ms-3 me-3",
                    cancelButton: "btn btn-lg btn-danger ms-3 me-3",
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    if (after) {
                        after.apply(self, [result]);
                    }
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    Swal.fire({
                        title: trans.default.methods.__("L'opération n'as pas été effectuée"),
                        confirmButtonText: trans.default.methods.__("Ok"),
                        // * Bootstrap Styling
                        customClass: {
                            confirmButton: "btn btn-lg btn-primary",
                        },
                        buttonsStyling: false,
                    });
                }
            });
            return false;
        },
        alert(
            title: string,
            text = "",
            self: HTMLElement | null = null,
            after: ((response: SweetAlertResult<string>) => void) | null = null,
            options: SweetAlertOptions = {},
        ) {
            const icon = options.icon ?? "info";
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                // * Bootstrap Styling
                customClass: {
                    confirmButton: "btn btn-lg btn-primary",
                },
                buttonsStyling: false,
            }).then((result) => {
                if (after) {
                    if (self) {
                        after.apply(self, [result]);
                    } else {
                        after(result);
                    }
                }
            });
            return false;
        },
    },
};
