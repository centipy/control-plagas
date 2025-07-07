// C:\laragon\www\control-plagas\run-tailwind.js (ACTUALIZADO)
const tailwindcss = require('tailwindcss'); // <--- ¡CAMBIO AQUÍ! Requiere el paquete principal
const path = require('path');
const fs = require('fs');
const postcss = require('postcss');
const autoprefixer = require('autoprefixer');

const command = process.argv[2];

async function runTailwind() {
    try {
        switch (command) {
            case 'build':
                const inputCssPath = path.resolve(__dirname, 'assets', 'css', 'input.css');
                const outputCssPath = path.resolve(__dirname, 'public', 'css', 'output.css');
                const configPath = path.resolve(__dirname, 'tailwind.config.js');

                if (!fs.existsSync(inputCssPath)) {
                    console.error(`Error: No se encontró el archivo de entrada CSS en ${inputCssPath}`);
                    process.exit(1);
                }
                if (!fs.existsSync(configPath)) {
                    console.error(`Error: No se encontró el archivo de configuración de Tailwind en ${configPath}`);
                    console.error("Por favor, crea manualmente 'tailwind.config.js' antes de intentar compilar.");
                    process.exit(1);
                }

                const tailwindConfig = require(configPath); // Carga la configuración de Tailwind

                console.log(`Compilando Tailwind CSS desde ${inputCssPath} a ${outputCssPath}...`);

                const css = fs.readFileSync(inputCssPath, 'utf8');

                // Aquí se utiliza tailwindcss como un plugin de PostCSS, que es su uso principal.
                const result = await postcss([
                    tailwindcss(tailwindConfig), // <-- AQUÍ SE USA EL MÓDULO tailwindcss
                    autoprefixer
                ]).process(css, { from: inputCssPath, to: outputCssPath });

                // Asegúrate de que el directorio de salida exista
                const outputDir = path.dirname(outputCssPath);
                if (!fs.existsSync(outputDir)) {
                    fs.mkdirSync(outputDir, { recursive: true });
                }

                fs.writeFileSync(outputCssPath, result.css);

                if (result.map) {
                    fs.writeFileSync(outputCssPath + '.map', result.map.toString());
                }

                console.log('¡Compilación de Tailwind CSS finalizada con éxito!');
                break;

            default:
                console.log('Uso: node run-tailwind.js [build]');
                console.log('   - build: Compila el CSS de Tailwind para producción.');
                console.log('   (Crea tailwind.config.js y assets/css/input.css manualmente)');
                break;
        }
    } catch (error) {
        console.error('Error al ejecutar Tailwind CSS:', error.message);
        console.error(error);
        process.exit(1);
    }
}

runTailwind();