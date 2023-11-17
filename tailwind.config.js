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
            'white': '#EBF3E8',
            'black': '#2a362aff',
            'primary': '#86A789',
            'primary-light': '#B2C8BA',
            'primary-light2': '#D2E3C8',
        },
    },
  },
  plugins: [],
}

