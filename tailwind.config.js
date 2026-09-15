/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./app/Views/**/*.php",
        "./public/**/*.js"
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "inverse-surface": "#2d3133",
                "secondary-container": "#d5e3fc",
                "background": "#f7f9fb",
                "primary-fixed-dim": "#b4c5ff",
                "on-surface": "#191c1e",
                "outline-variant": "#c2c6d6",
                "outline": "#727785",
                "error-container": "#ffdad6",
                "surface-container-lowest": "#ffffff",
                "tertiary-fixed": "#ffdcc6",
                "on-tertiary-fixed": "#311400",
                "error": "#ba1a1a",
                "inverse-on-surface": "#eff1f3",
                "secondary-fixed": "#d5e3fc",
                "surface-tint": "#0053db",
                "surface-variant": "#e0e3e5",
                "surface-container-high": "#e6e8ea",
                "on-secondary-fixed-variant": "#3a485b",
                "tertiary": "#924700",
                "on-tertiary-fixed-variant": "#723600",
                "surface-container-low": "#f2f4f6",
                "surface-container": "#eceef0",
                "on-tertiary": "#ffffff",
                "on-secondary": "#ffffff",
                "on-primary-fixed": "#00174b",
                "surface-bright": "#f7f9fb",
                "surface": "#f7f9fb",
                "primary": "#0051d5",
                "on-primary": "#ffffff",
                "surface-dim": "#d8dadc",
                "on-surface-variant": "#424754",
                "on-secondary-container": "#57657a",
                "on-primary-fixed-variant": "#003ea8",
                "on-background": "#191c1e",
                "secondary": "#515f74",
                "on-primary-container": "#fefcff",
                "on-secondary-fixed": "#0d1c2e",
                "inverse-primary": "#b4c5ff",
                "tertiary-fixed-dim": "#ffb786",
                "secondary-fixed-dim": "#b9c7df",
                "tertiary-container": "#b75b00",
                "on-tertiary-container": "#fffbff",
                "primary-fixed": "#dbe1ff",
                "surface-container-highest": "#e0e3e5",
                "on-error-container": "#93000a",
                "primary-container": "#316bf3",
                "on-error": "#ffffff"
            },
            borderRadius: {
                "DEFAULT": "0.125rem",
                "lg": "0.25rem",
                "xl": "0.5rem",
                "full": "0.75rem"
            },
            fontFamily: {
                headline: ["Manrope", "sans-serif"],
                body: ["Inter", "sans-serif"],
                label: ["Inter", "sans-serif"]
            }
        }
    },
    plugins: [
        require('@tailwindcss/container-queries')
    ]
};
