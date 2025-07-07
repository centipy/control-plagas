<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Control Plagas</title>
    <link href="/control-plagas/public/css/output.css" rel="stylesheet">
    <style>
        /* Pequeños ajustes para centrar el formulario si Tailwind no es suficiente */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f3f4f6; /* bg-gray-100 */
        }
        .login-card {
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="login-card p-6 bg-white rounded-lg shadow-xl">
        <h2 class="text-3xl font-extrabold text-center mb-6 text-gray-800">Iniciar Sesión</h2>

        <?php
        require_once __DIR__ . '/../app/Helpers/Session.php';
        Session::start(); // Asegura que la sesión está iniciada para obtener el error
        $loginError = Session::get('login_error');
        if ($loginError) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">';
            echo '<strong class="font-bold">Error:</strong>';
            echo '<span class="block sm:inline"> ' . htmlspecialchars($loginError) . '</span>';
            echo '</div>';
            Session::set('login_error', null); // Limpiar el error después de mostrarlo
        }
        ?>

        <form action="/control-plagas/login" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Usuario:</label>
                <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required autocomplete="username">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required autocomplete="current-password">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Ingresar
                </button>
            </div>
        </form>
    </div>
</body>
</html>