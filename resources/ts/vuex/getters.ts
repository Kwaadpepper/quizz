import { GetterTree } from "vuex";
import { User } from "./models/user";
import { AppState } from "./state";

export type Getters = {
    /**
     * Get the logged User
     * @param state
     */
    getUser(state: AppState): User;
};


export const getters: GetterTree<AppState, AppState> & Getters = {
    getUser: (state: AppState) => state.loggedUser,
    // getBasketTotalItems: (state: AppState) => {
    //     const items = Object.values(state.basket);
    //     return items.length
    //         ? items
    //             .map((basketItem: BasketItem) => basketItem.qty)
    //             .reduce(
    //                 (previous: undefined | number, basketItemCount: number) =>
    //                     (previous ?? 0) + basketItemCount
    //             )
    //         : 0;
    // },
};
