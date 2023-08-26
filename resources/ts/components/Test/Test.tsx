import * as React from "react";

import { ITestProps, ITestStates } from "./ITest";

export default class Component extends React.Component<ITestProps, ITestStates> {
    constructor(props: ITestProps) {
        super(props);
        this.state = {
            toto: "",
        };
    }

    public render(): React.ReactElement {
        // const {} = this.state;
        // const {} = this.props;
        return <></>;
    }
}
