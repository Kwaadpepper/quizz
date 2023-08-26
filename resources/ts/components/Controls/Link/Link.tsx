import * as React from "react";
import { ILinkProps, ILinkStates } from "./ILink";
import styles from "./Link.module.scss";
export default class Link extends React.Component<ILinkProps, ILinkStates> {
    constructor(props: ILinkProps) {
        super(props);
        this.state = {
            toto: "",
        };
    }

    public render(): React.ReactElement {
        const { children, onClick, disabled, style, classNames } = this.props;
        return (
            <a
                aria-disabled={disabled ?? false}
                className={[styles.link, classNames?.root ?? ""].join(" ")}
                style={style?.root ?? undefined}
                onClick={disabled ?? false ? () => undefined : onClick}
            >
                {children}
            </a>
        );
    }
}
