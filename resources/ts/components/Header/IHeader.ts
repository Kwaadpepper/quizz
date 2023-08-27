import React from "react";
import { GlobalProps } from "../IMainComponent";

export interface IHeaderProps extends GlobalProps {
    fixed?: boolean;
    children?: React.ReactNode | React.ReactNode[];
}
export interface IHeaderStates {
    toto: string;
}

export interface HeaderLang {
    Nav: NavLang;
}
export interface NavLang {
    Login: string;
    Register: string;
    Logout: string;
    LogoutHeader: string;
    LoginInput: string;
    PasswordInput: string;
    Confirm: string;
    Cancel: string;
    FirstName: string;
    LastName: string;
    LoginF: string;
    Pseudo: string;
    Email: string;
    RegexPassword: string;
}
