export interface ILinkProps {
    children?: React.ReactNode | React.ReactNode[]; //Nothing will be displayed in the body otherwise
    onClick: () => void; //Function called on click
    disabled?: boolean; //Define if the Link should be disabled
    style?: ILinkStyle; //Custom styles
    classNames?: ILinkClassNames; //Custom classNames
}
export interface ILinkStates {
    toto: string;
}
export interface ILinkStyle {
    root: React.CSSProperties;
}
export interface ILinkClassNames {
    root: string;
}
