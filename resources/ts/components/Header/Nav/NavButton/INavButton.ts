import React from "react";
import { IButtonClassNames, IButtonStyle } from "../../../Controls/Button/IButton";

export interface INavButtonProps {
    onClick: () => void; //Function called on click
    disableAnimation?: boolean; //Define if animations should be enabled
    disabled?: boolean; //Define if the button should be disabled
    children?: React.ReactNode | React.ReactNode[]; //Nothing will be displayed in the body otherwise
    style?: IButtonStyle; //Custom styles
    classNames?: IButtonClassNames; //Custom classNames
    theme?: INavButtonTheme; //Define light or dark
}
export interface INavButtonStates {
    toto: string;
}
export enum INavButtonTheme {
    Dark,
    Light,
}
export interface INavButtonStyle {
    button: React.CSSProperties;
}
export interface INavButtonClassNames {
    button: string;
}
