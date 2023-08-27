import type * as bootstrap from "bootstrap";

let tooltips: Array<bootstrap.Tooltip> = [];

export default {
    methods: {
        initTooltips() {
            // Disable tooltips on reboot.
            tooltips.forEach((tooltip) => {
                tooltip.disable();
                tooltip.hide();
            });
            tooltips = [].slice
                .call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                .map(function (tooltipTriggerEl) {
                    return new window.bootstrap.Tooltip(tooltipTriggerEl, {
                        customClass: "large-tooltip",
                        delay: {
                            show: 1250,
                            hide: 0,
                        },
                    });
                });
        },
    },
};
