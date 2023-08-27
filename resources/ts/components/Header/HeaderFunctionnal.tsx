import * as React from "react";

import { IHeaderProps } from "./IHeader";
// import Nav from "./Nav/Nav";

import { Container, Nav, NavDropdown, Navbar } from "react-bootstrap";
import { useDispatch, useSelector } from "react-redux";
import { RootState } from "resources/ts/store";
import config from "../Modules/config";
import route from "../Modules/route";
import __ from "../Modules/trans";
import { addTeam } from "../data/redux/teamsSlice";
import NavButton from "./Nav/NavButton/NavButton";

const Header: React.FC<IHeaderProps> = ({ fixed, children }) => {
    const [count, setCount] = React.useState<number>(0);

    // console.error(this.state);
    const teams = useSelector((state: RootState) => state.teams);
    const dispatch = useDispatch();
    console.log(teams);
    console.log(fixed);
    console.log(children);
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
                        <NavButton onClick={() => setCount(count + 1)}>{count}</NavButton>
                        <NavButton
                            onClick={() =>
                                dispatch(
                                    addTeam({
                                        id: 2,
                                        name: "sdsd",
                                        className: "sdsd",
                                    }),
                                )
                            }
                        >
                            Add Team
                        </NavButton>
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
};

export default Header;
