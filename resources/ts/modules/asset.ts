export default {
    methods: {
        asset(path: string) {
            return `${window.__SYSTEM._asset}${path}`;
        },
    },
};
