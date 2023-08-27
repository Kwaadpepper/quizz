import axios from "axios";
import * as React from "react";
import ControlExemple from "./ControlExemple/ControlExemple";
import Button from "./Controls/Button/Button";
import Header from "./Header/Header";
import NavButton from "./Header/Nav/NavButton/NavButton";
import {
    APIReturn,
    Auth,
    GlobalProps,
    IMainComponentProps,
    IMainComponentStates,
    Page,
    Profile,
} from "./IMainComponent";
import styles from "./MainComponent.module.scss";

export default class MainComponent extends React.Component<IMainComponentProps, IMainComponentStates> {
    constructor(props: IMainComponentProps) {
        super(props);
        this.state = {
            user: undefined,
            bearerToken: "",
            auth: false,
            page: Page.ControlExemple,
        };
        this.initLanguage();
        this.setLanguage = this.setLanguage.bind(this);
        this.login = this.login.bind(this);
        this.logout = this.logout.bind(this);
        this.register = this.register.bind(this);
        this.changePage = this.changePage.bind(this);
    }
    public getAPIHeader() {
        return { headers: { Authorization: "Bearer " + this.state.bearerToken } };
    }
    public componentDidMount() {
        this.rememberLogin();
    }
    public render(): React.ReactElement {
        const { auth, user, page } = this.state;

        const global: GlobalProps = {
            user: user,
            auth: auth,
            loginF: this.login,
            logoutF: this.logout,
            registerF: this.register,
            changePage: this.changePage,
        };
        switch (page) {
            default:
                return (
                    <div className={styles.main}>
                        <Header fixed={true} {...global} />
                        <p>coucou</p>
                        <Button onClick={() => this.changePage(Page.ControlExemple)}>Control Exemple</Button>
                    </div>
                );
            case Page.ControlExemple:
                return (
                    <div className={styles.main}>
                        <Header fixed={true} {...global}>
                            <NavButton onClick={() => this.changePage(Page.Main)}> Return to main page </NavButton>
                        </Header>
                        <ControlExemple {...global} />
                    </div>
                );
        }
    }
    public getCookie(cookieName: string): string | undefined {
        if (document.cookie.indexOf(cookieName + "=") != -1) {
            return (
                document.cookie
                    .split("; ")
                    .find((row) => row.startsWith(cookieName + "="))
                    ?.split("=")[1] ?? undefined
            );
        } else {
            return undefined;
        }
    }
    public setCookie(cookieName: string, cookieValue: string, expirationDay = 10): void {
        const d = new Date();
        d.setTime(d.getTime() + expirationDay * 24 * 60 * 60 * 1000);
        const expires = ";expires=" + d.toUTCString();
        document.cookie = cookieName + "=" + cookieValue + ";SameSite=Lax" + expires + ";path=/";
    }
    public deleteCookie(cookieName: string): void {
        document.cookie = cookieName + "=;expires=Thu, 01 Jan 1970 00:00:00 UTC;SameSite=Lax; path=/";
    }
    private initLanguage(): string {
        if (this.getCookie("currentLocale") != undefined) {
            return this.getCookie("currentLocale");
        } else {
            this.setCookie("currentLocale", "fr", 30);
            return "fr";
        }
    }
    public setLanguage(): void {
        this.setCookie("currentLocale", "fr", 30);
    }
    public rememberLogin(): void {
        const token = this.getCookie("bearerToken");
        if (!(token == "undefined" || token == undefined)) {
            console.log("Authentification Token Found, logging in...");
            this.setState({ bearerToken: this.getCookie("bearerToken"), auth: true }, () => this.getUser());
        }
    }

    public login(emailOrLogin: string, password: string): void {
        let obj: APIReturn = undefined;
        axios
            .post("api/login", {
                emailOrLogin: emailOrLogin,
                password: password,
            })
            .then((response) => {
                obj = response.data;
                if (obj.success) {
                    const auth: Auth = obj.data;
                    this.setCookie("bearerToken", auth.token, 30);
                    console.log("Logging in...");
                    this.setState({ bearerToken: auth.token, auth: auth.token != "" }, () => {
                        this.getUser();
                    });
                } else {
                    console.log("Login Failed.");
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }
    public logout(): void {
        this.deleteCookie("bearerToken");
        this.setState({ bearerToken: "", auth: false, user: undefined });
        axios.get("api/logout", this.getAPIHeader()).then((response) => {
            console.log(response.data.data.message);
        });
    }

    private getUser(): void {
        axios
            .get("api/user", this.getAPIHeader())
            .then((response) => {
                const res: APIReturn = response.data;
                const user: Profile = res.data;
                user.token = this.state.bearerToken;
                this.setState({ user: user });
            })
            .catch(() => {
                console.log("Failed to retrieve user from your token.");
                this.logout();
            });
    }
    public register(
        firstname: string,
        lastname: string,
        login: string,
        pseudo: string,
        email: string,
        password: string,
    ): void {
        let obj: APIReturn = undefined;
        axios
            .post("api/register", {
                firstname: firstname,
                lastname: lastname,
                login: login,
                pseudo: pseudo,
                email: email,
                password: password,
            })
            .then((response) => {
                obj = response.data;
                if (obj.success) {
                    const auth: Auth = obj.data;
                    this.setCookie("bearerToken", auth.token, 30);
                    console.log("Registering...");
                    this.setState({ bearerToken: auth.token, auth: auth.token != "" }, () => {
                        this.getUser();
                    });
                } else {
                    console.log("Registering Failed.");
                }
            })
            .catch(() => {
                console.log("Failed to register");
            });
    }
    public changePage(page: Page) {
        this.setState({ page: page });
    }
}
