<?php

declare(strict_types=1);

require_once('../app/models/MTipoRelacion.php');
require_once('../app/views/tipo_relacion/VTipoRelacion.php');
require_once('../app/controllers/BaseController.php');

class CTipoRelacion extends BaseController {
    private VTipoRelacion $vista;
    private MTipoRelacion $modelo;
    private string $action;
    private array $params;

    public function __construct(string $action, array $params) {
        $this->vista = new VTipoRelacion();
        $this->modelo = new MTipoRelacion();
        $this->action = $action;
        $this->params = $params;
    }

    protected function validate() {
        // Lógica de validación específica para tipos de relación
        switch ($this->action) {
            case 'agregar':
            case 'editar':
                if (!isset($this->params['nombre'])) {
                    throw new Exception("Faltan parámetros para {$this->action} tipo de relación.");
                }
                break;
            case 'eliminar':
            case 'update':
                if (!isset($this->params['id'])) {
                    throw new Exception("Faltan parámetros para {$this->action} tipo de relación.");
                }
                break;
        }
    }

    protected function process() {
        // Lógica de procesamiento específica para tipos de relación
        switch ($this->action) {
            case 'mostrar':
                $this->mostrarTiposRelacionC();
                break;
            case 'agregar':
                $this->agregarTipoRelacionC($this->params['nombre']);
                break;
            case 'eliminar':
                $this->eliminarTipoRelacionC((int)$this->params['id']);
                break;
            case 'update':
                $this->updateTipoRelacionC((int)$this->params['id']);
                break;
            case 'editar':
                $this->editarTipoRelacionC((int)$this->params['id'], $this->params['nombre']);
                break;
            default:
                echo "Acción no válida";
        }
    }

    protected function sendResponse() {
        // Lógica de respuesta específica para tipos de relación
        // Aquí puedes manejar la lógica de respuesta
    }

    private function mostrarTiposRelacionC(): void {
        $tiposRelacion = $this->modelo->mostrarTipos();
        $this->vista->actualizar($tiposRelacion);
    }

    private function agregarTipoRelacionC(string $nombre): void {
        $this->modelo->agregarTipo($nombre);
        $this->mostrarTiposRelacionC();
    }

    private function eliminarTipoRelacionC(int $id): void {
        $this->modelo->eliminarTipo($id);
        $this->mostrarTiposRelacionC();
    }

    private function updateTipoRelacionC(int $id): void {
        $tipoRelacion = $this->modelo->buscarTipo($id);
        $this->vista->mostrarFormularioEdicion($tipoRelacion);
    }

    private function editarTipoRelacionC(int $id, string $nombre): void {
        $this->modelo->editarTipo($id, $nombre);
        $this->mostrarTiposRelacionC();
    }
}
