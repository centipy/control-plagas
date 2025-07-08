<?php
// views/admin/admin_dashboard_sidebar.php
// Asume que $basePath y $currentUri ya están definidos en el archivo principal que lo incluye.
// Asume que Session::start() ya ha sido llamado.

// Nota: $currentUri debe ser la URI procesada por el router, ej: '/admin/dashboard'
// Los $basePath en los hrefs son para el navegador, ej: '/control-plagas/admin/dashboard'
?>

<div class="sidebar bg-indigo-800 text-white flex flex-col p-4 shadow-lg h-screen">
    <div class="logo text-2xl font-bold mb-8 text-center">
        <i class="fas fa-user-shield text-indigo-300 mr-2"></i> Administrador
    </div>
    <nav class="flex flex-col space-y-3">

        <?php
        // Función auxiliar para determinar si un enlace está activo
        function isActive($linkUri, $currentUri) {
            // Asegura que ambas URIs estén normalizadas para la comparación
            $linkUri = '/' . ltrim($linkUri, '/');
            $currentUri = '/' . ltrim($currentUri, '/');

            // Compara la URI del enlace con la URI actual
            return $linkUri === $currentUri;
        }
        ?>

        <a href="<?php echo $basePath; ?>/admin/dashboard"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/dashboard', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-house mr-3"></i> Inicio
        </a>
        <a href="<?php echo $basePath; ?>/admin/aniadir_cliente"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/aniadir_cliente', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-user-plus mr-3"></i> Añadir Nuevo Cliente
        </a>
        <a href="<?php echo $basePath; ?>/admin/ver_clientes_registrados"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/ver_clientes_registrados', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-users mr-3"></i> Ver Clientes Registrados
        </a>
        <a href="<?php echo $basePath; ?>/admin/nuevo_servicio"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/nuevo_servicio', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-concierge-bell mr-3"></i> Registrar nuevo servicio
        </a>
        <a href="<?php echo $basePath; ?>/admin/ver_servicios"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/ver_servicios', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-tasks mr-3"></i> Ver Servicios
        </a>
        <a href="<?php echo $basePath; ?>/admin/listado_usuarios"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/listado_usuarios', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-users mr-3"></i> Listado de Usuarios
        </a>
        <a href="<?php echo $basePath; ?>/admin/registrar"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/registrar', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-user-plus mr-3"></i> Registrar Nuevo Usuario
        </a>
        <a href="<?php echo $basePath; ?>/admin/aprobar_acceso"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/aprobar_acceso', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-user-plus mr-3"></i> Aprobar Acceso
        </a>
        <a href="<?php echo $basePath; ?>/admin/servicios_prestados"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/servicios_prestados', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-clipboard-list mr-3"></i> Servicios Que Se Prestan
        </a>
        <a href="<?php echo $basePath; ?>/admin/localidades"
           class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-700 rounded-lg transition-colors duration-200
                  <?php echo isActive('/admin/localidades', $currentUri) ? 'bg-indigo-700' : ''; ?>">
            <i class="fas fa-map-marker-alt mr-3"></i> Localidades
        </a>
    </nav>
    <div class="mt-auto">
        <a href="<?php echo $basePath; ?>/logout" class="flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors duration-200">
            <i class="fas fa-sign-out-alt mr-3"></i> Salir
        </a>
    </div>
</div>