const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                surface: {
                    base: "rgb(var(--color-surface-base) / <alpha-value>)",
                    raised: "rgb(var(--color-surface-raised) / <alpha-value>)",
                    overlay: "rgb(var(--color-surface-overlay) / <alpha-value>)",
                    border: "rgb(var(--color-surface-border) / <alpha-value>)",
                    "border-light": "rgb(var(--color-surface-border-light) / <alpha-value>)",
                },
                content: {
                    primary: "rgb(var(--color-content-primary) / <alpha-value>)",
                    secondary: "rgb(var(--color-content-secondary) / <alpha-value>)",
                    tertiary: "rgb(var(--color-content-tertiary) / <alpha-value>)",
                    inverse: "rgb(var(--color-content-inverse) / <alpha-value>)",
                },
                primary: {
                    DEFAULT: "rgb(var(--color-primary) / <alpha-value>)",
                    light: "rgb(var(--color-primary-light) / <alpha-value>)",
                    dark: "rgb(var(--color-primary-dark) / <alpha-value>)",
                    subtle: "var(--color-primary-subtle)",
                },
                gain: "rgb(var(--color-gain) / <alpha-value>)",
                loss: "rgb(var(--color-loss) / <alpha-value>)",
                warning: "rgb(var(--color-warning) / <alpha-value>)",
                info: "rgb(var(--color-info) / <alpha-value>)",
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
