import * as React from "react";
import { INavButtonProps, INavButtonStates, INavButtonTheme } from "./INavButton";
import styles from "./NavButton.module.scss";
export default class NavButton extends React.Component<INavButtonProps, INavButtonStates> {
    constructor(props: INavButtonProps) {
        super(props);
        this.state = {
            toto: "",
        };
    }

    public render(): React.ReactElement {
        const { children, classNames, style, onClick, disableAnimation, disabled, theme } = this.props;
        const anim = !(disableAnimation ?? false);
        const isLight: boolean = (theme ?? INavButtonTheme.Dark) == INavButtonTheme.Light;
        return (
            <button
                onClick={!(disabled ?? false) ? onClick : () => ""}
                disabled={disabled ?? false}
                className={[
                    styles.navButton,
                    anim ? styles.navButtonAnim : "",
                    isLight ? styles.navButtonLight : "",
                    classNames?.button,
                ].join(" ")}
                style={style?.button}
            >
                {children}
            </button>
        );
    }
}
