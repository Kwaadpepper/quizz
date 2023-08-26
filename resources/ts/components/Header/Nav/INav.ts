import React from "react";
import { ILangOptionsProps } from "../../Controls/LangOptions/ILangOptions";
import { GlobalProps } from "../../IMainComponent";
import { NavLang } from "../IHeader";

export interface INavProps extends GlobalProps {
    strings: NavLang;
    langProps: ILangOptionsProps;
    children?: React.ReactNode | React.ReactNode[];
}
export interface INavStates {
    showingLoginModal: boolean;
    showingRegisterModal: boolean;
    showingConfirmationLogoutModal: boolean;

    loginInput: string;
    passwordInput: string;
    canRegister: boolean;

    firstNameInput: string;
    lastNameInput: string;
    loginRInput: string;
    pseudoInput: string;
    emailRInput: string;
    passwordRInput: string;
}
