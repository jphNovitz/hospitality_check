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
        }
    },
  },
  plugins: [],
}

