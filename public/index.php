<?php
require_once('../app/controllers/CHome.php');
require_once('../app/controllers/CTipoRelacion.php');
require_once('../app/controllers/CCargo.php');
require_once('../app/controllers/CEvento.php');
require_once('../app/controllers/CUsuario.php');
require_once('../app/controllers/CRelacion.php');
require_once('../app/controllers/CIngreso.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/') {
    $home = new CHome();
    $home->index();
    return;
}

$action = $_GET['action'] ?? 'mostrar'; // Asignar un valor por defecto 'mostrar' si 'action' no está definido
$params = array_merge($_GET, $_POST);  // Combinar GET y POST params

switch (true) {
    case ($_SERVER['REQUEST_URI'] == '/tipo_relacion'):
        $tipo_relacion = new CTipoRelacion($action, $params);
        $tipo_relacion->handleRequest();
        break;
    case ($_SERVER['REQUEST_URI'] == '/eliminar_tipo_relacion'):
        $categoria = new CTipoRelacion('eliminar', $params);
        $categoria->handleRequest();
        break;
    case preg_match('/\/editar_tipo_relacion\?id=\d+/', $_SERVER['REQUEST_URI']) ? true : false:
        $params = $_GET;
        $action = 'update';
        $categoria = new CTipoRelacion($action, $params);
        $categoria->handleRequest();
        break;
    case ($_SERVER['REQUEST_URI'] == '/actualizar_tipo_relacion'):
        $categoria = new CTipoRelacion('editar', $params);
        $categoria->handleRequest();
        break;

    case ($_SERVER['REQUEST_URI'] == '/cargos'):
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = 'agregar';
        } else {
            $action = 'mostrar';
        }
        $cargo = new CCargo($action, $params);
        $cargo->handleRequest();
        break;
    case ($_SERVER['REQUEST_URI'] == '/eliminar_cargo'):
        $cargo = new CCargo('eliminar', $params);
        $cargo->handleRequest();
        break;
    case preg_match('/\/editar_cargo\?id=\d+/', $_SERVER['REQUEST_URI']) ? true : false:
        $params = $_GET;
        $action = 'update';
        $cargo = new CCargo($action, $params);
        $cargo->handleRequest();
        break;
    case ($_SERVER['REQUEST_URI'] == '/actualizar_cargo'):
        $cargo = new CCargo('editar', $params);
        $cargo->handleRequest();
        break;

    // Manteniendo la lógica original para los demás controladores
    case ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/usuarios'):
        $usuario = new CUsuario();
        $usuario->mostrarUsuariosC();
        break;
    case ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/usuarios'):
        $usuario = new CUsuario();
        $usuario->agregarUsuarioC($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['ci'], $_POST['cargo']);
        break;
    case ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/eliminar_usuario'):
        $usuario = new CUsuario();
        $usuario->eliminarUsuarioC($_POST['id']);
        break;
    case preg_match('/\/editar_usuario\?id=\d+/', $_SERVER['REQUEST_URI']) ? true : false:
        $params = $_GET;
        $id = $params['id'];
        $usuario = new CUsuario();
        $usuario->updateUsuarioC($id);
        break;
    case ($_SERVER['REQUEST_URI'] == '/actualizar_usuario'):
        $usuario = new CUsuario();
        $usuario->editarUsuarioC($_POST['id'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['ci'], $_POST['cargo']);
        break;

    case ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/eventos'):
        $evento = new CEvento();
        $evento->mostrarEventosC();
        break;
    case ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/eventos'):
        $evento = new CEvento();
        $evento->agregarEventoC($_POST['nombre'], $_POST['fecha'], $_POST['descripcion'], $_POST['usuario_id']);
        break;
    case ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/eliminar_evento'):
        $evento = new CEvento();
        $evento->eliminarEventoC($_POST['id']);
        break;
    case preg_match('/\/editar_evento\?id=\d+/', $_SERVER['REQUEST_URI']) ? true : false:
        $params = $_GET;
        $id = $params['id'];
        $evento = new CEvento();
        $evento->updateEventoC($id);
        break;
    case ($_SERVER['REQUEST_URI'] == '/actualizar_evento'):
        $evento = new CEvento();
        $evento->editarEventoC($_POST['id'], $_POST['nombre'], $_POST['fecha'], $_POST['descripcion'], $_POST['usuario_id']);
        break;

    case ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/relaciones'):
        $relacion = new CRelacion();
        $relacion->mostrarRelacionesC();
        break;
    case ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/relaciones'):
        $relacion = new CRelacion();
        $relacion->agregarRelacionC($_POST['usuarioA'], $_POST['usuarioB'], $_POST['tipoRelacionA'], $_POST['tipoRelacionB']);
        break;
    case ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/eliminar_relacion'):
        $relacion = new CRelacion();
        $relacion->eliminarRelacionC($_POST['id']);
        break;
    case preg_match('/\/editar_relacion\?id=\d+/', $_SERVER['REQUEST_URI']) ? true : false:
        $params = $_GET;
        $id = $params['id'];
        $relacion = new CRelacion();
        $relacion->updateRelacionC($id);
        break;
    case ($_SERVER['REQUEST_URI'] == '/actualizar_relacion'):
        $relacion = new CRelacion();
        $relacion->editarRelacionC($_POST['id'], $_POST['usuarioA'], $_POST['usuarioB'], $_POST['tipoRelacionA'], $_POST['tipoRelacionB']);
        break;

    case ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/ingresos'):
        $ingreso = new CIngreso();
        $ingreso->mostrarIngresosC();
        break;
    case ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/ingresos'):
        $ingreso = new CIngreso();
        $ingreso->agregarIngresoC($_POST['tipoIngreso'], $_POST['monto'], $_POST['evento_id']);
        break;
    case ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/eliminar_ingreso'):
        $ingreso = new CIngreso();
        $ingreso->eliminarIngresoC($_POST['id']);
        break;
    case preg_match('/\/editar_ingreso\?id=\d+/', $_SERVER['REQUEST_URI']) ? true : false:
        $params = $_GET;
        $id = $params['id'];
        $ingreso = new CIngreso();
        $ingreso->updateIngresoC($id);
        break;
    case ($_SERVER['REQUEST_URI'] == '/actualizar_ingreso'):
        $ingreso = new CIngreso();
        $ingreso->editarIngresoC($_POST['id'], $_POST['tipoIngreso'], $_POST['monto'], $_POST['evento_id']);
        break;

    default:
        echo "Acción no válida";
        break;
}
