export interface IMainComponentProps {
    toto: string;
}
export interface IMainComponentStates {
    auth: boolean;
    bearerToken: string;
    user: Profile;
    page: Page;
}
export interface Auth {
    token: string;
    name: string;
}
export interface APIReturn {
    success: boolean;
    data: never;
    message: string;
}
export interface Profile {
    firstname: string;
    lastname: string;
    login: string;
    pseudo: string;
    email: string;
    profilePicture: string;
    description: string;
    token: string;
}
export interface GlobalProps {
    user: Profile;
    auth: boolean;
    loginF: (credential: string, password: string) => void;
    logoutF: () => void;
    registerF: (
        firstname: string,
        lastname: string,
        login: string,
        pseudo: string,
        email: string,
        password: string,
    ) => void;
    changePage: (page: Page) => void;
}
export enum Page {
    Main,
    ControlExemple,
}
