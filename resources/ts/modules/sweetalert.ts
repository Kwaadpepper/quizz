import { SweetAlertResult } from "sweetalert2";
import confirm from "./sweetalertConfirm";

window.addEventListener("DOMContentLoaded", () => {
    let elements = document.getElementsByClassName("confirmDeleteJS");
    for (const element of elements) {
        element.addEventListener("submit", function (e) {
            e.preventDefault();
            const el = e.target;
            if (!el || !(el instanceof HTMLFormElement)) {
                throw new Error("confirmDoneJS can only be executed on an exising form");
            }
            confirm.methods.confirm(
                "Confirmez",
                "Voulez-vous vraiment supprimer cet élément ?",
                el as HTMLFormElement,
                function () {
                    (el as HTMLFormElement).submit();
                },
                { icon: "error" },
            );
            return false;
        });
    }
    elements = document.getElementsByClassName("confirmDoneJS");
    for (const element of elements) {
        element.addEventListener("submit", function (e) {
            e.preventDefault();
            const el = e.target;
            if (!el || !(el instanceof HTMLFormElement)) {
                throw new Error("confirmDoneJS can only be executed on an exising form");
            }
            confirm.methods.confirm(
                "Confirmez",
                "Voulez-vous vraiment marquer cet élément comme traité ?",
                el as HTMLFormElement,
                function () {
                    (el as HTMLFormElement).submit();
                },
                { icon: "info" },
            );
            return false;
        });
    }
    elements = document.getElementsByClassName("confirmSubmitJS");
    for (const element of elements) {
        element.addEventListener("submit", function (e) {
            e.preventDefault();
            const el = e.target;
            if (!el || !(el instanceof HTMLFormElement)) {
                throw new Error("confirmSubmitJS can only be executed on an exising form");
            }
            confirm.methods.confirm(
                "Confirmez",
                "Voulez-vous enregistrer cet élément tel-quel ?",
                el as HTMLFormElement,
                function () {
                    (el as HTMLFormElement).submit();
                },
                { icon: "warning" },
            );
            return false;
        });
    }
    elements = document.getElementsByClassName("confirmJS");
    for (const element of elements) {
        element.addEventListener(
            "click",
            function (e) {
                if (!element.getAttribute("confirmJS")) {
                    e.preventDefault();
                    const el = e.target;
                    if (!el || !(el instanceof HTMLElement)) {
                        throw new Error("confirmJS can only be executed on a html element");
                    }
                    (async () => {
                        const promise = new Promise((resolve: (value: boolean) => void) => {
                            confirm.methods.confirm(
                                "Confirmez",
                                "Êtes vous sure ?",
                                el,
                                function (response: SweetAlertResult<string>) {
                                    resolve(response.isConfirmed);
                                },
                                { icon: "warning" },
                            );
                            return false;
                        });
                        if (await promise) {
                            element.setAttribute("confirmJS", "true");
                            el.click();
                            element.removeAttribute("confirmJS");
                        }
                    })();
                }
            },
            false,
        );
    }
});
