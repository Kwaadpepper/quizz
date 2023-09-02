import { App, createApp } from "vue";
import QuizzAppVue from "./../../components/QuizzApp.vue";
import { store } from "./../../vuex/store";
import router from "./router";

document.addEventListener("DOMContentLoaded", () => {
    const setupApp = (app: App<Element>) => {
        app.config.globalProperties.window = window;
        app.config.globalProperties.document = document;
    };

    const appId = "quizz-app", bootstrapDom = document.getElementById(appId);

    if (!bootstrapDom) {
        throw new Error(`Cannot find Dom el id '${appId}'`);
    }

    // Register app Component
    const app = createApp(QuizzAppVue);
    setupApp(app);
    app.use(router);
    app.use(store);
    app.mount(bootstrapDom);
});
