/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      // "./src/**/*.php",
      "./assets/**/*.js",
      "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
        transitionProperty: {
            'spacing': 'margin, padding, display',
            'width': 'width',
        },
        colors: {
            "transparent": "transparent",
            "white": "#ececed",
            "black": "#060d14",
            "light-text-base": "#274c77",
            "light-text-secondary": "#6096ba",
            // "light-text-highlight": "#6096ba",
            "dark-text-base": "#274c77",
            "dark-text-secondary": "#6096ba",
            // "dark-text-highlight": "#6096ba",
            "light-primary": "#a3cef1",
            "dark-primary": "#a3cef1",
            "light-neutral": "#e7ecef",
            // "light-neutral-light": "#f6fbfe",
            "dark-neutral": "#e7ecef",
            // "dark-neutral-light":  "#e9e9ee",

            // 'white': '#EBF3E8',
            // 'black': '#2a362aff',
            // 'primary': '#86A789',
            // 'primary-light': '#B2C8BA',
            // 'primary-light2': '#D2E3C8',
        },
    },
  },
  plugins: [],
}

