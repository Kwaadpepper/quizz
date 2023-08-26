import * as React from "react";
import { ILangOptionsProps, ILangOptionsStates } from "./ILangOptions";
import styles from "./LangOptions.module.scss";
export default class LangOptions extends React.Component<ILangOptionsProps, ILangOptionsStates> {
    constructor(props: ILangOptionsProps) {
        super(props);

        this.state = {
            selectedLocale: this.props.selectedLocale,
            active: false,
        };

        this.changeSelectedLocale = this.changeSelectedLocale.bind(this);
        this.makeActive = this.makeActive.bind(this);
    }

    public render(): React.ReactElement {
        const { selectedLocale, active } = this.state;
        return (
            <div className={styles.languagePicker} onClick={this.makeActive}>
                {active || (selectedLocale == "fr" && !active) ? (
                    <div
                        onClick={active ? () => this.changeSelectedLocale("fr") : () => ""}
                        className={[styles.language, styles.france].join(" ")}
                    >
                        {" "}
                    </div>
                ) : (
                    ""
                )}
                {active || (selectedLocale == "en" && !active) ? (
                    <div
                        onClick={active ? () => this.changeSelectedLocale("en") : () => ""}
                        className={[styles.language, styles.english].join(" ")}
                    >
                        {" "}
                    </div>
                ) : (
                    ""
                )}
            </div>
        );
    }
    public makeActive() {
        if (!this.state.active) this.setState({ active: true });
    }
    public changeSelectedLocale(value: string) {
        this.setState({ selectedLocale: value, active: false }, () => {
            this.props.changeSelectedLocale(this.state.selectedLocale);
            console.log("Changing language to " + this.state.selectedLocale);
        });
    }
}
