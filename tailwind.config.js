/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#333333',
        'secondary': '#666666',
      },
      fontFamily: {
        'timesnewroman': ['Times New Roman', 'serif'],
      },
    },
  },
  plugins: [],
}

