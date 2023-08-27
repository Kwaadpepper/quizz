import * as React from "react";
import { createRoot } from "react-dom/client";
import lang_en from "../lang/en.json";
import lang_fr from "../lang/fr.json";
import MainComponent from "./components/MainComponent";

console.log("Rendering React Main Component Class...");

const root = createRoot(document.getElementById("app"));
root.render(<MainComponent lang={{ fr: lang_fr, en: lang_en }} />);
