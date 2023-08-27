// SweetAlert
import Swal, { SweetAlertResult } from "sweetalert2";
import { SweetAlertOptions } from "sweetalert2";

import * as trans from "../modules/trans";

export default {
    methods: {
        confirm(
            title: string,
            text: string = "",
            self: Object,
            after: (response: SweetAlertResult<any>) => void = () => {},
            options: SweetAlertOptions = {}
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
                confirmButtonText:
                    options.confirmButtonText ??
                    trans.default.methods.__("Oui"),
                cancelButtonText:
                    options.cancelButtonText ??
                    trans.default.methods.__("Annuler"),
                // * Bootstrap Styling
                customClass: {
                    confirmButton: "btn btn-primary mx-1",
                    cancelButton: "btn btn-danger mx-1",
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
                        title: trans.default.methods.__(
                            "L'opération n'as pas été effectuée"
                        ),
                        confirmButtonText: trans.default.methods.__("Ok"),
                        // * Bootstrap Styling
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                        buttonsStyling: false,
                    });
                }
            });
            return false;
        },
    },
};
