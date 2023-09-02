import {
    CommitOptions,
    DispatchOptions,
    Store as VuexStore,
    createStore,
} from "vuex";
import { Actions, actions } from "./actions";
import { Getters, getters } from "./getters";
import { Mutations, mutations } from "./mutations";
import { AppState, state } from "./state";

export const store = createStore({
    state,
    getters,
    mutations,
    actions,
});

export type Store = Omit<
    VuexStore<AppState>,
    "getters" | "commit" | "dispatch"
> & {
    commit<K extends keyof Mutations, P extends Parameters<Mutations[K]>[1]>(
        key: K,
        payload: P,
        options?: CommitOptions
    ): ReturnType<Mutations[K]>;
} & {
    dispatch<K extends keyof Actions>(
        key: K,
        payload?: Parameters<Actions[K]>[1],
        options?: DispatchOptions
    ): ReturnType<Actions[K]>;
} & {
    getters: {
        [K in keyof Getters]: ReturnType<Getters[K]>;
    };
};
