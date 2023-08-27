import * as React from "react";

import { IHeaderProps, IHeaderStates } from "./IHeader";
// import Nav from "./Nav/Nav";

import { Container, Nav, NavDropdown, Navbar } from "react-bootstrap";
import { connect } from "react-redux";
import config from "../Modules/config";
import route from "../Modules/route";
import __ from "../Modules/trans";
import Team from "../data/models/Team";
import { addTeam, teamsSliceActions, teamsSliceState } from "../data/redux/teamsSlice";

class Header extends React.Component<IHeaderProps, IHeaderStates> {
    private reduxProp: IHeaderProps & teamsSliceActions & teamsSliceState;

    constructor(props: IHeaderProps) {
        super(props);
        this.state = {
            toto: "",
        };
    }

    rProps() {
        this.reduxProp = this.props as never;
        return this.reduxProp;
    }

    componentDidMount(): void {
        console.error("tototootot");
        console.error(this.state);
        this.rProps().addTeam({
            id: 2,
            name: "toto",
            className: "sdsd",
        });
        console.error(this.state);
        // const teams = useSelector((state: RootState) => state.teams);
        // const dispatch = useDispatch();
    }

    public render(): React.ReactElement {
        console.error(this.rProps().teams);
        // const { children, fixed } = this.props;
        return (
            // <header className={[styles.header, fixed ?? false ? styles.fixed : styles.headerRelative].join(" ")}>
            //     <Nav {...this.props} {...langProps} strings={strings.Nav}>
            //         {" "}
            //         {children}{" "}
            //     </Nav>
            // </header>
            <Navbar expand="lg" className="bg-body-tertiary">
                <Container>
                    <Navbar.Brand href="#home">{config("app.name")}</Navbar.Brand>
                    <Navbar.Toggle aria-controls="basic-navbar-nav" />
                    <Navbar.Collapse id="basic-navbar-nav">
                        <Nav className="me-auto">
                            <NavDropdown title={__("Modes de Jeu")}>
                                <NavDropdown.Item href="#game-mode-plant">{__("Plantes")}</NavDropdown.Item>
                            </NavDropdown>
                            <Nav.Link href="#quizz">{__("Quizz")}</Nav.Link>
                            <Nav.Link href="#link">{__("Équipes")}</Nav.Link>
                            <NavDropdown title={__("Gestion")}>
                                <NavDropdown.Item href="#categories">{__("Catégories")}</NavDropdown.Item>
                                <NavDropdown.Item href="#action/3.2">{__("Étiquettes")}</NavDropdown.Item>
                                <NavDropdown.Item href="#action/3.2">{__("Questions")}</NavDropdown.Item>
                                <NavDropdown.Item href="#action/3.2">{__("Images")}</NavDropdown.Item>
                                <NavDropdown.Divider />
                                <NavDropdown.Item href="#action/3.2">{__("Types de nom")}</NavDropdown.Item>
                                <NavDropdown.Item href="#action/3.2">{__("Types d'images")}</NavDropdown.Item>
                            </NavDropdown>
                        </Nav>
                    </Navbar.Collapse>
                    <Nav>
                        <NavDropdown title="Username">
                            <NavDropdown.Item
                                onClick={() => (document.getElementById("logout-form") as HTMLFormElement).submit()}
                            >
                                {__("Se déconnecter")}
                            </NavDropdown.Item>
                            <form id="logout-form" action={route("logout")} method="POST" className="d-none">
                                @csrf
                            </form>
                        </NavDropdown>
                    </Nav>
                </Container>
            </Navbar>
        );
    }
}

const mapStateToProps = (state: { teams: Array<Team> }) => {
    console.info("state", state);
    return {
        teams: state.teams,
    };
};

const mapDispatchToProps = {
    addTeam,
};

export default connect(mapStateToProps, mapDispatchToProps)(Header);
