import { MutationTree } from "vuex";
import { QuizzAppError } from "./errors";
import { User } from "./models/user";
import { MutationTypes } from "./mutation-types";
import { AppState } from "./state";

export type Mutations<S = AppState> = {
    [MutationTypes.APP_SET_LOADED](state: S, payload: boolean): void;
    [MutationTypes.APP_SET_ERROR](state: S, payload: QuizzAppError): void;
    [MutationTypes.SET_USER](state: S, payload: User): void;
};

export const mutations: MutationTree<AppState> & Mutations = {
    [MutationTypes.APP_SET_LOADED](state, payload: boolean) {
        state.appLoaded = payload;
    },
    [MutationTypes.APP_SET_ERROR](state, payload: QuizzAppError) {
        state.onError = payload;
    },
    [MutationTypes.SET_USER](state, payload: User) {
        state.loggedUser = payload;
    },
};
