import * as React from "react";
import Highlight from "react-highlight";
import { /*HTMLElement, HTMLTypes, ICodeLanguage, */ ICodeProps, ICodeStates } from "./ICode";

export default class Code extends React.Component<ICodeProps, ICodeStates> {
    constructor(props: ICodeProps) {
        super(props);

        this.state = {
            renderedCode: "",
        };
    }

    public render(): React.ReactElement {
        const { code } = this.props;
        // const renderedCode = this.state.renderedCode;
        return <Highlight>{code}</Highlight>;
    }
}
