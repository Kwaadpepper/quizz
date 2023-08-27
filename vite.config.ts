import basicSsl from "@vitejs/plugin-basic-ssl";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import postcssDiscard from "postcss-discard-comments";
import { defineConfig } from "vite";
import babel from "vite-plugin-babel";
import checker from "vite-plugin-checker";
import eslint from "vite-plugin-eslint";
import stylelint from "vite-plugin-stylelint";


export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                entryFileNames: `assets/${
                    process.env.NODE_ENV === "local" ? "[name]-" : ""
                }[hash].js`,
                chunkFileNames: `assets/${
                    process.env.NODE_ENV === "local" ? "[name]-" : ""
                }[hash].js`,
                assetFileNames: `assets/${
                    process.env.NODE_ENV === "local" ? "[name]-" : ""
                }[hash].[ext]`,
            },
        },
        chunkSizeWarningLimit: 700,
        emptyOutDir: true,
        sourcemap: process.env.NODE_ENV === "local" ? "inline" : false,
    },
    css: {
        devSourcemap: process.env.NODE_ENV === "local" ? true : false,
        postcss: {
            map: {
                inline: true,
            },
            plugins: [
                postcssDiscard({
                    removeAll: true,
                }),
            ],
        },
    },
    plugins: [
        basicSsl(),
        checker({
            vueTsc: {
                root: ".",
                tsconfigPath: "tsconfig.json",
            },
            typescript: {
                root: ".",
                tsconfigPath: "tsconfig.json",
            },
            eslint: {
                lintCommand:
                    "eslint --fix --quiet -c .eslintrc.json \"./resources\"",
            },
            stylelint: {
                lintCommand:
                    "stylelint -q --fix **/resources/**/*.scss",
            },
        }),
        stylelint({
            dev: false,
            build: true,
            cache: false,
            chokidar: true,
            quiet: false,
            include: ["**/resources/**/*.scss", "**/resources/**/*.css"],
            exclude: [
                "**/node_modules/**/*.scss",
                "**/node_modules/**/*.css",
                "**/vendor/**/*.scss",
                "**/vendor/**/*.css",
            ],
            lintOnStart: false,
            emitError: true,
            emitWarning: true,
            emitErrorAsWarning: false,
            emitWarningAsError: true,
        }),
        eslint({
            cache: false,
            extensions: ["js", "ts"],
            include: "**/*.ts",
            lintOnStart: false,
            emitWarning: true,
            emitError: true,
            failOnWarning: false,
            failOnError: false,
        }),
        babel({
            babelConfig: {
                targets: "last 3 version, not dead, >0.3%"
            }
        }),
        vue({
            isProduction: process.env.NODE_ENV !== "development" ? true : false,
        }),
        laravel({
            input: [
                "resources/sass/app.scss", "resources/ts/app.ts",
                "resources/sass/unauth.scss", "resources/ts/unauth.ts",
            ]
        }),
    ],
    server: {
        watch: {
            usePolling: true,
        },
    },
    resolve: {
        extensions: ["*", ".js", ".ts", ".vue"],
        alias: [
            {
                find: "vue",
                replacement: "vue/dist/vue.runtime.esm-bundler.js",
            },
            {
                find: "@",
                replacement: "/resources/assets",
            }
        ],
    }
});
