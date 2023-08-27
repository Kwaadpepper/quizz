const flattenProperties = (
    obj: Record<PropertyKey, unknown>,
    parent: string | null = null,
    res: Record<PropertyKey, unknown> = {},
): Record<PropertyKey, unknown> => {
    for (const key of Object.keys(obj)) {
        const propName = parent ? parent + "." + key : key;
        if (obj[key] instanceof Object) {
            flattenProperties(obj[key] as Record<PropertyKey, unknown>, propName, res);
        } else {
            res[propName] = obj[key];
        }
    }
    return res;
};

/**
 * Same behavior as laravel route
 *
 * ! window.__SYSTEM._routes has to be defined
 *
 * @param routeName The route name.
 * @param parameters Parameters, if a parameter name matches a route path level,
 * it will be replaced, otherwise it will appear in query.
 * @param keepActualQueries Pass this to keep current url queries.
 * @param keepActualHashes Pass this to keep current url fragments
 * @returns The newly formated string
 */
const route = (
    routeName: string,
    parameters: Record<string, string | number | Array<string | number>> = {},
    keepActualQueries = false,
    keepActualHashes = false,
): string | null => {
    const routes = flattenProperties(window.__SYSTEM._routes);
    const routeUrl = Object.keys(routes).indexOf(routeName) !== -1 ? (routes[routeName] as string) : null;

    // * Return null if route url could not be found
    if (!routeUrl) {
        return null;
    }

    const queryParameters: Record<string, number | string> = {},
        url = new URL(window.location.origin),
        searchParams = new URL(String(window.location)).searchParams,
        hash = window.location.hash;
    let pathname = routeUrl.replace(origin, "");

    // * Replace parameters in path name.
    for (const paramName in parameters) {
        const paramValue = String(parameters[paramName]);
        if (pathname.indexOf(paramName) !== -1) {
            // * Replace pathname part with parameter
            pathname = pathname.replace(paramName, paramValue);
        } else {
            // * push parameter to be in queries
            queryParameters[paramName] = paramValue;
        }
    }
    url.pathname = pathname;

    // * Build query
    if (keepActualQueries) {
        // * Push actual queries
        for (const param of searchParams) {
            url.searchParams.set(param[0], param[1]);
        }
    }
    // * Push request queries
    for (const paramName in queryParameters) {
        url.searchParams.set(paramName, String(queryParameters[paramName]));
    }

    if (keepActualHashes) {
        url.hash = hash;
    }

    return String(url);
};

export default route;
