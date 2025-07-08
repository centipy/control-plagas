<?php
// views/admin/admin_dashboard.php
require_once __DIR__ . '/../../app/Helpers/Session.php';
Session::start();

$userRole = Session::get('user_role');
$userName = Session::get('user_full_name');
$basePath = '/control-plagas';

if ($userRole !== 'administrador') {
    header('Location: ' . $basePath . '/login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es-en">
<head>
    <meta charset="UTF-8">
    <title>Registro de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/add_cliente.css">
    <script src="../../js/jquery3.7.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDELM9qyzUru0atagXXuC2kLqzL011nw8o&libraries=places" async defer></script>
    
    <link href="<?php echo $basePath; ?>/public/css/output.css" rel="stylesheet">
    <script src="../../js/autocompletar_direc.js"></script>
    <script src="../../js/registro_nuevo_cliente_sectores.js"></script>
    <script src="../../js/lector_documento_asesor.js"></script>
    <script src="../../js/lector_numero_asesor.js"></script>
    <style>
        .sidebar { width: 250px; }
        body { min-height: 100vh; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/admin_dashboard_sidebar.php'; ?>
        <div class="container mt-5">
            <h2>Registro de cliente nuevo</h2>
            <form action="../../controller/registrar_nuevo_cliente.php" method="POST" class="mt-3">
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="noConcretadoCheckbox">
                <label class="form-check-label" for="noConcretadoCheckbox">
                    No Concretado
                </label>
            </div>
                <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono:</label>
            <input type="number" class="form-control" id="telefono" name="telefono" required oninput="verificarTelefono()">
            <div id="advertencia_telefono" class="form-text text-warning"></div>
        </div>
        <div class="mb-3">
            <label for="nombrePersona" class="form-label">Nombre o razon social:</label>
            <input type="text" class="form-control" id="nombrePersona" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido o persona a cargo:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="mb-3">
            <label for="telefono_segundo" class="form-label">Otro número de teléfono:</label>
            <input type="text" class="form-control" id="telefono_segundo" name="telefono_segundo">
        </div>
        <div class="mb-3">
            <label for="genero" class="form-label">Género:</label>
            <select id="genero" class="form-select" name="genero">
                <option selected value="">Seleccione un género</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="Empresa">Empresa</option>
                <option value="otro">Otro</option>
                <option value="NO CONCRETADO">NO CONCRETADO</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="tipo_documento" class="form-label">Tipo de documento:</label>
            <select id="tipo_documento" class="form-select" name="tipo_documento">
                <option selected value="">Escoja un tipo de documento</option>
                <option value="cedula">Cédula</option>
                <option value="pasaporte">Pasaporte</option>
                <option value="cedula_extranjera">Cédula extranjera</option>
                <option value="ppt">P.P.T</option>
                <option value="nit">NIT</option>
                <option value="NO CONCRETADO">NO CONCRETADO</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="numero_documento" class="form-label">Número de documento:</label>
            <input type="text" class="form-control" id="numero_documento" name="numero_documento" autocomplete="off" oninput="verificarDocumento()">
            <div id="error_documento" class="text-danger"></div>
        </div>
        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo electrónico:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="emailName" placeholder="nombreusuario" aria-label="Nombre de usuario">
                <span class="input-group-text">@</span>
                <select class="form-select" id="emailDomain" name="emailDomain">
                    <option value="gmail.com">gmail.com</option>
                    <option value="yahoo.com">yahoo.com</option>
                    <option value="outlook.com">outlook.com</option>
                    <option value="hotmail.com">hotmail.com</option>
                    <option value="hotmail.es">hotmail.es</option>
                    <option value="gmail.es">gmail.es</option>
                    <option value="edu.co">edu.co</option>
                    <option value="NO CONCRETADO">NO CONCRETADO</option>
                </select>
                <input type="hidden" id="correo_electronico" name="correo_electronico">
            </div>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección del cliente:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Escribe la dirección" required>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="piso" class="form-label">Piso:</label>
                <div class="input-group">
                    <select class="form-select" id="tipoPiso" name="tipoPiso" onchange="updatePisoValue()">
                        <option value="Apartamento">Apartamento</option>
                        <option value="Local Comercial">Local Comercial</option>
                        <option selected value="Piso">Número de Piso</option>
                        <option value="Casa">Casa</option>
                        <option value="Bodega">Bodega</option>
                        <option value="Oficina">Oficina</option>
                        <option value="Interior">Interior</option>
                        <option value="NO APLICA PISO">NO APLICA PISO</option>
                        <option value="NO CONCRETADO">NO CONCRETADO</option>
                    </select>
                    <input type="text" class="form-control" id="numeroPiso" placeholder="Número" oninput="updatePisoValue()">
                    <input type="hidden" id="piso" name="piso">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="bloque" class="form-label">Bloque:</label>
                <div class="input-group">
                    <select class="form-select" id="tipoBloque" name="tipoBloque" onchange="updateBloqueValue()">
                        <option value="Torre">Torre</option>
                        <option value="Bloque">Bloque</option>
                        <option value="Conjunto">Conjunto</option>
                        <option value="Casa">Casa</option>
                        <option value="NO APLICA BLOQUE/TORRE">NO APLICA BLOQUE/TORRE</option>
                        <option value="NO CONCRETADO">NO CONCRETADO</option>
                    </select>
                    <input type="text" class="form-control" id="detalleBloque" placeholder="Especifica el número o nombre" oninput="updateBloqueValue()">
                    <input type="hidden" id="bloque" name="bloque">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="unidad_residencial" class="form-label">Unidad Residencial:</label>
                <div class="input-group">
                    <select class="form-select" id="tipoUnidad" name="tipoUnidad" onchange="updateUnidadResidencialValue()">
                        <option value="Unidad residencial">Unidad residencial</option>
                        <option value="Vereda">Vereda</option>
                        <option value="Conjunto residencial">Conjunto residencial</option>
                        <option value="Urbanización">Urbanización</option>
                        <option value="Edificio">Edificio</option>
                        <option value="Nombre comercial">Nombre comercial</option>
                        <option value="Local comercial">Local comercial</option>
                        <option value="Vehiculo-placa">Vehiculo-placa</option>
                        <option value="NO APLICA UNIDAD.">NO APLICA UNIDAD</option>
                        <option value="NO CONCRETADO">NO CONCRETADO</option>
                    </select>
                    <input type="text" class="form-control" id="detalleUnidad" placeholder="Especifica el nombre" oninput="updateUnidadResidencialValue()">
                    <input type="hidden" id="unidad_residencial" name="unidad_residencial">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="departamentos" class="form-label">Departamento:</label>
            <select id="departamentos" name="departamentos" class="form-select" onchange="cargarMunicipios(this.value)">
                <option value="">Seleccione un Departamento</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="municipios" class="form-label">Municipio:</label>
            <select id="municipios" name="municipios" class="form-select" onchange="cargarBarrios(this.value)">
                <option value="">Seleccione un Municipio</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="searchBarrio" class="form-label">Barrio:</label>
            <input type="text" id="searchBarrio" placeholder="Escribe el barrio" class="form-select" autocomplete="off">
            <input type="hidden" id="id_barrio" name="id_barrio">
            <ul id="barrioResults" class="autocomplete-results list-group position-absolute" style="width: calc(100% - 30px); z-index: 1;"></ul>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Cliente</button>
        <a class="btn btn-secondary" href="dashborad_admin.php">Cancelar</a>
    </form>
</div>
</body>
</html>