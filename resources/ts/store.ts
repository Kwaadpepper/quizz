import { configureStore } from "@reduxjs/toolkit";
import teamsSlice from "./components/data/redux/teamsSlice";

export const store = configureStore({
    reducer: {
        teams: teamsSlice.reducer,
    },
});

// Infer the `RootState` and `AppDispatch` types from the store itself
export type RootState = ReturnType<typeof store.getState>;
// Inferred type: {posts: PostsState, comments: CommentsState, users: UsersState}
export type AppDispatch = typeof store.dispatch;
