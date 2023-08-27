import axios from "axios";

const metaCSRFToken = document.querySelector("meta[name='csrf-token']");

if (!metaCSRFToken) {
    throw new Error("Missing meta CSRF token");
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
axios.defaults.headers.common = {
    Accept: "application/json, text/plain, */*",
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": metaCSRFToken.getAttribute("content"),
};

window.axios = axios;
