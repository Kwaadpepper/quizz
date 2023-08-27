import * as React from "react";
import { createRoot } from "react-dom/client";
import MainComponent from "./components/MainComponent";

import { Provider } from "react-redux";
import { store } from "./store";

console.log("Rendering React Main Component Class...");

const root = createRoot(document.getElementById("app"));
root.render(
    <Provider store={store}>
        <MainComponent toto="string" />
    </Provider>,
);
