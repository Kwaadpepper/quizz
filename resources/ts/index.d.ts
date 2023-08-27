export {};
import { AxiosStatic } from "axios";

// * Enum
interface EnumInterface {
    label: string;
    value: number | string;
}

// * Laravel Paginator

interface PaginatorLink {
    url: null | string;
    label: string;
    active: boolean;
}

interface Paginator {
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Array<PaginatorLink>;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

// * Recursive List<string>
type StringList = Record<PropertyKey, string>;
type NestedStringListInterface = Record<PropertyKey, NestedStringListInterface | StringList>;

// * Recursive List<number>
type NumberList = Record<PropertyKey, number>;
type NestedNumberListInterface = Record<PropertyKey, NestedNumberListInterface | NumberList>;

// * Recursive List<Model>
type Model = Record<
    string,
    string | boolean | number | object | NestedStringList | NestedNumberList | NestedModelInterface | ModelListInterface
>;
type NestedModelInterface = Model;

type NestedStringList = NestedStringListInterface | StringList | string;
type NestedNumberList = NestedNumberListInterface | NumberList | number;

type ModelList = Array<Model>;
type ModelListInterface = ModelList;

declare global {
    type CustomTag = LaravelModel;
    type LaravelModel = Model;
    type LaravelModelList = ModelList;
    type LaravelPaginator = Paginator;
    type LaravelPaginatorLink = PaginatorLink;
    type Enum = EnumInterface;

    interface Window {
        axios: AxiosStatic;
        __SYSTEM: {
            _asset: string;
            _config: NestedStringList;
            _locale: string;
            _routes: Record<string, string>;
            _translations: {
                json: NestedStringList;
                php: NestedStringList;
            };
        };
    }
}
