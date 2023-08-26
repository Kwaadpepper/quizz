import react from "@vitejs/plugin-react";
import laravel from "laravel-vite-plugin";
import path from "path";
import postcssDiscard from "postcss-discard-comments";
import { defineConfig } from "vite";
import checker from "vite-plugin-checker";
import stylelint from "vite-plugin-stylelint";

export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                entryFileNames: `assets/${process.env.NODE_ENV === "local" ? "[name]-" : ""}[hash].js`,
                chunkFileNames: `assets/${process.env.NODE_ENV === "local" ? "[name]-" : ""}[hash].js`,
                assetFileNames: `assets/${process.env.NODE_ENV === "local" ? "[name]-" : ""}[hash].[ext]`,
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
        checker({
            typescript: {
                root: ".",
                tsconfigPath: "tsconfig.json",
            },
            eslint: {
                lintCommand: 'eslint --fix --quiet -c .eslintrc.json "resources"',
            },
            stylelint: {
                lintCommand: "stylelint -q --fix resources/**/*.scss",
            },
        }),
        laravel({
            input: ["resources/sass/app.scss", "resources/ts/app.tsx", "resources/ts/unauth.ts"],
            refresh: true,
        }),
        react(),
        stylelint({
            dev: false,
            build: true,
            cache: false,
            chokidar: true,
            quiet: false,
            include: ["**/*.scss", "**/*.css"],
            exclude: ["node_modules/**/*.scss", "node_modules/**/*.css", "**/vendor/**/*.scss", "**/vendor/**/*.css"],
            lintOnStart: false,
            emitError: true,
            emitWarning: true,
            emitErrorAsWarning: false,
            emitWarningAsError: true,
        }),
    ],
    resolve: {
        extensions: ["*", ".js", ".ts", ".tsx"],
        alias: [
            {
                find: "@",
                replacement: "/resources/assets",
            },
            {
                find: "~",
                replacement: path.resolve(__dirname, "./node_modules"),
            },
        ],
    },
});
