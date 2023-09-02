import route from "../modules/route";

export const getRoute = (routeName: string, parameters: Record<string, string | number | Array<string | number>> = {}) => {
    const rte = route.methods.route(routeName, parameters);
    if (!rte) {
        throw Error(`Missing route '${routeName}'`);
    }
    return rte;
};
