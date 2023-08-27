const route = {
    methods: {
        config(key: string, fallback: string|null = null): string | null {
            let config: string,
                configNotFound = true;

            try {
                config = key.split(".").reduce((t, i) => t[i as any] ?? null, window.__SYSTEM._config) as string;
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
        },
    },
};

export default route;
