// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",     // Escanea todos los archivos .php en la raíz y subdirectorios
    "./**/*.html",    // Si también usas archivos .html
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}