import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary-container": "var(--primary-container)",
                secondary: "var(--secondary)",
                "secondary-container": "var(--secondary-container)",
                "on-primary-container": "var(--on-primary-container)",
                "on-secondary": "var(--on-secondary)",
                "on-secondary-container": "var(--on-secondary-container)",
                error: "var(--error)",
                "error-container": "var(--error-container)",
                "on-error-container": "var(--on-error-container)",
                "outline-variant": "var(--outline-variant)",
                outline: "var(--outline)",
                "on-surface-variant": "var(--on-surface-variant)",
                "on-surface": "var(--on-surface)",
                surface: "var(--surface)",
                "surface-container-lowest": "var(--surface-container-lowest)",
                "surface-container-low": "var(--surface-container-low)",
                "surface-container": "var(--surface-container)",
                "surface-container-high": "var(--surface-container-high)",
                "surface-container-highest": "var(--surface-container-highest)",
                background: "var(--background)",
                primary: "var(--primary)",
            },
            fontFamily: {
                "body-sm": ["Inter"],
                "display-lg": ["Inter"],
                "title-sm": ["Inter"],
                "headline-md": ["Inter"],
                "body-md": ["Inter"],
                "label-caps": ["Inter"],
                "data-tabular": ["Inter"],
                "code-inline": ["monospace"],
            },
            fontSize: {
                "code-inline": [
                    "13px",
                    { lineHeight: "18px", fontWeight: "400" },
                ],
                "body-sm": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                "display-lg": [
                    "30px",
                    {
                        lineHeight: "38px",
                        letterSpacing: "-0.02em",
                        fontWeight: "700",
                    },
                ],
                "title-sm": ["18px", { lineHeight: "28px", fontWeight: "600" }],
                "headline-md": [
                    "24px",
                    {
                        lineHeight: "32px",
                        letterSpacing: "-0.01em",
                        fontWeight: "600",
                    },
                ],
                "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                "label-caps": [
                    "12px",
                    {
                        lineHeight: "16px",
                        letterSpacing: "0.05em",
                        fontWeight: "700",
                    },
                ],
                "data-tabular": [
                    "13px",
                    { lineHeight: "18px", fontWeight: "400" },
                ],
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
