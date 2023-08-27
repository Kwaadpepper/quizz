import * as React from "react";
import { IDashboardProps, IDashboardStates } from "./IDashboard";

export default class Dashboard extends React.Component<IDashboardProps, IDashboardStates> {
    constructor(props: IDashboardProps) {
        super(props);
    }
    public render(): React.ReactElement {
        const { pageName } = this.state;

        return (
            <div>
                <h1>{pageName}</h1>
            </div>
        );
    }
}
