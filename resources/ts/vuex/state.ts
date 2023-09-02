import { QuizzAppError } from "./errors";
import { User } from "./models/user";

export const state: {
    /** Is the app ready to be shown */
    appLoaded: boolean;

    onError: QuizzAppError | false;

    /** L'utilisateur actuellement connecté */
    loggedUser: User;

    counter: number;

    /** Un chargement ajax est en cours */
    isLoading: boolean;

    /** Un compteur de tentatives de call ajax */
    attemptsCounter: number;
} = {
    appLoaded: false,
    onError: false,
    loggedUser: {
        id: 0,
        name: ""
    },
    counter: 0,
    isLoading: false,
    attemptsCounter: 0 as number,
};

export type AppState = typeof state;
