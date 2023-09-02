import axios from "axios";
import { ActionContext, ActionTree } from "vuex";
import parseLaravelErrorMessages from "../../ts/modules/parseLaravelErrorMessages";
import toast from "../../ts/modules/toast";
import trans from "../../ts/modules/trans";
import { ActionTypes } from "./action-types";
import { ErrorType as AppErrorType, getError } from "./errors";
import { getRoute } from "./helper";
import { Paginator } from "./modelPaginator";
import { User } from "./models/user";
import { MutationTypes } from "./mutation-types";
import { Mutations } from "./mutations";
import { AppState } from "./state";

// * Pour avoir le contexte des mutations
// https://vuex.vuejs.org/guide/getters.html#method-style-access
// https://dev.to/3vilarthas/vuex-typescript-m4j
type AugmentedActionContext = {
    commit<K extends keyof Mutations>(
        key: K,
        payload: Parameters<Mutations[K]>[1]
    ): ReturnType<Mutations[K]>;
} & Omit<ActionContext<AppState, AppState>, "commit">;

// * Prototypes des actions
export interface Actions {
    [ActionTypes.LOAD_USER](
        context: AugmentedActionContext
    ): void;
    [ActionTypes.LOAD_RESOURCE]<M>(
        context: AugmentedActionContext,
        { resource, page }: { resource:string, page: number }
    ): Promise<Paginator<M>|boolean> ;
    [ActionTypes.INC_COUNTER](
        context: AugmentedActionContext,
        { value }: { value: number }
    ): void;
    [ActionTypes.TOAST](
        context: AugmentedActionContext,
        { message }: { message: string }
    ): void;
}

// * Code Des actions
export const actions: ActionTree<AppState, AppState> & Actions = {
    [ActionTypes.LOAD_USER](context) {
        const getLoggedUser = getRoute("ajax.users.getLogged");

        context.state.isLoading = true;
        axios
            .get(getLoggedUser)
            .then((response) => {
                const user = response.data.user as User;
                context.commit(MutationTypes.SET_USER, user);
                HandleMaxAttempts(context, true);
                setTimeout(() => {
                    context.commit(MutationTypes.APP_SET_LOADED, true);
                }, 1000);
            })
            .catch((e: any) => {
                ajaxErrorHandler(context, e);
                context.commit(MutationTypes.APP_SET_ERROR, getError(
                    AppErrorType.SERVER_ERROR,
                    trans.methods.__("Une erreur est survenue lors du chargement de l'application"))
                );
            })
            .then(() => (context.state.isLoading = false));
    },
    [ActionTypes.LOAD_RESOURCE]<M>(context: AugmentedActionContext, { resource, page }: { resource:string, page: number }): Promise<Paginator<M>|boolean> {
        const getTeams = getRoute(`ajax.${resource}.index`, {page: page});
        context.state.isLoading = true;
        return axios
            .get(getTeams)
            .then((response) => {
                const paginatedTeams = response.data as Paginator<M>;
                return paginatedTeams;
            })
            .catch((e: any) => {
                ajaxErrorHandler(context, e);
                context.commit(MutationTypes.APP_SET_ERROR, getError(
                    AppErrorType.SERVER_ERROR,
                    trans.methods.__("Une erreur est survenue lors du chargement de l'application"))
                );
                return false;
            })
            .then((response) => {context.state.isLoading = false; return response;});
    },
    [ActionTypes.INC_COUNTER](context, { value }) {
        context.state.counter += value;

        // const addToBasketRoute = route.methods.route("ajax.basket.add", {
        //     ID: value,
        // });
        // if (!addToBasketRoute) {
        //     throw Error("Missing route 'ajax.basket.add'");
        // }

        // axios
        //     .post(addToBasketRoute)
        //     .then((response) => {
        //         const basket = response.data as BasketItemList;
        //         context.commit(MutationTypes.UPDATE_BASKET, basket);
        //         context.dispatch(ActionTypes.TOAST, {
        //             message: trans.methods.__(
        //                 "Un exemplaire du produit a été ajouté au panier."
        //             ),
        //         });
        //         HandleMaxAttempts(context, true);
        //     })
        //     .catch((e: any) => {
        //         ajaxErrorHandler(context, e);
        //         context.dispatch(ActionTypes.RELOAD_BASKET);
        //     })
        //     .then(() => (context.state.isLoading = false));
    },
    [ActionTypes.TOAST](
        context,
        {
            message: message,
            error: error = false,
        }: { message: string; error: boolean }
    ) {
        if (error) {
            toast.methods.errorNotify(message, 1500);
        } else {
            toast.methods.toast(message, 1500);
        }
    },
};

const HandleMaxAttempts = function (
    context: ActionContext<AppState, AppState>,
    succeed: boolean = false
) {
    if (succeed) {
        context.state.attemptsCounter = 0;
        return;
    }
    context.state.attemptsCounter = context.state.attemptsCounter + 1;
    if (context.state.attemptsCounter === 3) {
        context.state.isLoading = false;

        context.state.attemptsCounter = 0;
        throw Error("Max attempts reached");
    }
};

const ajaxErrorHandler = function (
    context: ActionContext<AppState, AppState>,
    e: any,
    defaultMessage: string | null = null
) {
    HandleMaxAttempts(context);
    let message =
        defaultMessage ??
        trans.methods.__("Une erreur est survenue");
    if (e.response.status === 422) {
        message =
            parseLaravelErrorMessages.methods.parseValidationErrors(
                e.response?.data?.errors ?? {}
            ) || message;
        if (window.vueDebug) {
            console.warn(e.response.data.errors);
        }
    } else if (e.response.status === 419) {
        // * CSRF TOKEN MISMATCH (On ne pourrais plus faire d'appels AJAX sans recharger la page)
        context.dispatch(ActionTypes.TOAST, {
            message: trans.methods.__(
                "Votre session a expiré, la page va être rechargée"
            ),
            error: true,
        });
        setTimeout(() => window.location.reload(), 2000);
    } else if (e.response.status === 403) {
        // * Access denied
        context.dispatch(ActionTypes.TOAST, {
            message: trans.methods.__(
                "Vous n'êtes pas autorisé a effectuer cette action"
            ),
            error: true,
        });
        setTimeout(() => window.location.reload(), 2000);
    } else {
        if (window.vueDebug) {
            console.error(e);
        }
    }
    context.state.isLoading = false;
    context.dispatch(ActionTypes.TOAST, { message: message, error: true });
};
