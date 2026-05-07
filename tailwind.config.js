import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
                "primary-container": "#131b2e",
                secondary: "#0058be",
                "secondary-container": "#2170e4",
                "on-primary-container": "#7c839b",
                "on-secondary": "#ffffff",
                "on-secondary-container": "#fefcff",
                error: "#ba1a1a",
                "error-container": "#ffdad6",
                "on-error-container": "#93000a",
                "outline-variant": "#c6c6cd",
                outline: "#76777d",
                "on-surface-variant": "#45464d",
                "on-surface": "#191c1e",
                surface: "#f7f9fb",
                "surface-container-lowest": "#ffffff",
                "surface-container-low": "#f2f4f6",
                "surface-container": "#eceef0",
                "surface-container-high": "#e6e8ea",
                "surface-container-highest": "#e0e3e5",
                background: "#f7f9fb",
                primary: "#000000",
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
