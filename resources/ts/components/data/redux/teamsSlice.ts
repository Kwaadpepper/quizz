import { createSlice } from "@reduxjs/toolkit";
import Team from "../models/Team";

const initialState: Array<Team> = new Array<Team>();

export const teamsSlice = createSlice({
    name: "teams",
    initialState: initialState,
    reducers: {
        addTeam(state, action) {
            state.push({
                id: action.payload.id,
                name: action.payload.name,
                className: action.payload.className,
            });
        },
    },
});

export type teamsSliceActions = typeof teamsSlice.actions;
export type teamsSliceState = {
    teams: typeof initialState;
};

export const { addTeam } = teamsSlice.actions;
export default teamsSlice;
