import { Paginator as iPaginator } from "./vuex/modelPaginator";
export { };

import type VueTagsInput from "@sipec/vue3-tags-input";
import { AxiosStatic } from "axios";
import { ErrorType as ErrorTypeEnum, QuizzAppError as QAppError } from "./vuex/errors";
import { Team as TeamModel } from "./vuex/models/team";
import { Store } from "./vuex/store";

// * Recursive List<string>
type StringList = Record<PropertyKey, string>;
interface NestedStringListInterface
    extends Record<PropertyKey, NestedStringListInterface | StringList> {}

// * Recursive List<number>
type NumberList = Record<PropertyKey, number>;
interface NestedNumberListInterface
    extends Record<PropertyKey, NestedNumberListInterface | NumberList> {}

// * Recursive List<Model>
type Model = Record<
    string,
    | string
    | number
    | object
    | NestedStringList
    | NestedNumberList
    | NestedModelInterface
>;
interface NestedModelInterface extends Model {}

type NestedStringList = NestedStringListInterface | StringList | string;
type NestedNumberList = NestedNumberListInterface | NumberList | number;
type ModelList = Array<Model>;

declare global {
    type CustomTag = LaravelModel & VueTagsInput.ITag;
    type LaravelModel = Model;
    type LaravelModelList = ModelList;

    // * Errors
    type QuizzAppError = QAppError;
    type ErrorType = ErrorTypeEnum;

    // * MODELS
    type Paginator<T> = iPaginator<T>;
    type Team = TeamModel;

    interface Window {
        axios: AxiosStatic;
        Swal: Function;
        vueDebug: boolean|undefined;
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

declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        $store: Store;
    }
}
