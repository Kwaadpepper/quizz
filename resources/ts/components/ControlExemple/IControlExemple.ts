import { ILangOptionsProps } from "../Controls/LangOptions/ILangOptions";
import { GlobalProps } from "../IMainComponent";

export interface IControlExempleProps extends GlobalProps {
    langProps: ILangOptionsProps;
}
export interface IControlExempleStates {
    page: SubPage;
}
export enum SubPage {
    Base,
    CheckBox,
    Toggle,
    TextBox,
    DropDown,
    SearchBox,
    SpinButton, //InputNumber
    ChoiceGroup, //RadioButton
    Slider,
    ColorPicker,
    DatePicker,
    TimePicker,

    Button,
    NavButton,
    Link,
    ToolTip,

    Label,
    Calendar,
    NavBar,
    Modal,
    Panel,
    Card,
    Notification,
    MessageBar,
    Spinner,

    Table,
    List,

    ItemPicker,
    PeoplePicker,
}
