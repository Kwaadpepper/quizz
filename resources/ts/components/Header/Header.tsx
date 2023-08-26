import * as React from "react";

import { IHeaderProps, IHeaderStates } from "./IHeader";
// import Nav from "./Nav/Nav";

import { Container, Nav, NavDropdown, Navbar } from "react-bootstrap";

export default class Header extends React.Component<IHeaderProps, IHeaderStates> {
    constructor(props: IHeaderProps) {
        super(props);
        this.state = {
            toto: "",
        };
    }
    public render(): React.ReactElement {
        const { strings, langProps, children, fixed } = this.props;
        return (
            // <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            //     <div class="container">
            //         <a class="navbar-brand" href="{{ url('/') }}">
            //             {{ config('app.name', 'Laravel') }}
            //         </a>
            //         <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button"
            //             aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            //             <span class="navbar-toggler-icon"></span>
            //         </button>

            //         <div class="navbar-collapse collapse" id="navbarSupportedContent">
            //             <!-- Left Side Of Navbar -->
            //             <ul class="navbar-nav me-auto">

            //             </ul>

            //             <!-- Right Side Of Navbar -->
            //             <ul class="navbar-nav ms-auto">
            //                 <!-- Authentication Links -->
            //                 @guest
            //                     @if (Route::has('login'))
            //                         <li class="nav-item">
            //                             <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            //                         </li>
            //                     @endif

            //                     @if (Route::has('register'))
            //                         <li class="nav-item">
            //                             <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            //                         </li>
            //                     @endif
            //                 @else
            //                     <li class="nav-item dropdown">
            //                         <a class="nav-link dropdown-toggle" id="navbarDropdown" data-bs-toggle="dropdown" href="#" role="button"
            //                             aria-haspopup="true" aria-expanded="false" v-pre>
            //                             {{ Auth::user()->name }}
            //                         </a>

            //                         <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            //                             <a class="dropdown-item" href="{{ route('logout') }}"
            //                                 onclick="event.preventDefault();
            //                                             document.getElementById('logout-form').submit();">
            //                                 {{ __('Logout') }}
            //                             </a>

            //                             <form class="d-none" id="logout-form" action="{{ route('logout') }}" method="POST">
            //                                 @csrf
            //                             </form>
            //                         </div>
            //                     </li>
            //                 @endguest
            //             </ul>
            //         </div>
            //     </div>
            // </nav>

            // <header className={[styles.header, fixed ?? false ? styles.fixed : styles.headerRelative].join(" ")}>
            //     <Nav {...this.props} {...langProps} strings={strings.Nav}>
            //         {" "}
            //         {children}{" "}
            //     </Nav>
            // </header>
            <Navbar expand='lg' className='bg-body-tertiary'>
                <Container>
                    <Navbar.Brand href='#home'>React-Bootstrap</Navbar.Brand>
                    <Navbar.Toggle aria-controls='basic-navbar-nav' />
                    <Navbar.Collapse id='basic-navbar-nav'>
                        <Nav className='me-auto'>
                            <Nav.Link href='#home'>Home</Nav.Link>
                            <Nav.Link href='#link'>Link</Nav.Link>
                            <NavDropdown title='Dropdown' id='basic-nav-dropdown'>
                                <NavDropdown.Item href='#action/3.1'>Action</NavDropdown.Item>
                                <NavDropdown.Item href='#action/3.2'>Another action</NavDropdown.Item>
                                <NavDropdown.Item href='#action/3.3'>Something</NavDropdown.Item>
                                <NavDropdown.Divider />
                                <NavDropdown.Item href='#action/3.4'>Separated link</NavDropdown.Item>
                            </NavDropdown>
                        </Nav>
                    </Navbar.Collapse>
                </Container>
            </Navbar>
        );
    }
}
