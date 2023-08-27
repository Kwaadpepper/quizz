import Toastify, { Options } from "toastify-js";

export default {
    methods: {
        toast(message: string, duration = 4000, className = "notifications"): void {
            const options: Options = {
                duration: duration,
                newWindow: false,
                close: true,
                className: className,
                gravity: "top",
                offset: {
                    x: "2em",
                    y: "3em",
                },
                // * Prevents dismissing of toast on hover
                stopOnFocus: true,
            };
            options.text = message;
            Toastify(options).showToast();
        },
        notify(message: string, duration = 4000): void {
            this.toast(message, duration, "notifications");
        },
        errorNotify(message: string, duration = 4000): void {
            this.toast(message, duration, "notifications error");
        },
    },
};
