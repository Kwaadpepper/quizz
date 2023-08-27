const config = (key: string, fallback: string = null): string | null => {
    let config: string,
        configNotFound = true;

    try {
        config = key.split(".").reduce((t, i) => t[i] ?? null, window.__SYSTEM._config);
        if (config) {
            configNotFound = false;
        }
    } catch (e) {
        config = key;
    }

    if (configNotFound) {
        return fallback;
    }

    return config;
};

export default config;
