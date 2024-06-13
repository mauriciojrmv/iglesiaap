<?php

declare(strict_types=1);

require_once('../app/models/MCargo.php');
require_once('../app/views/cargo/VCargo.php');
require_once('../app/controllers/BaseController.php');

class CCargo extends BaseController {
    private VCargo $vista;
    private MCargo $modelo;
    private string $action;
    private array $params;

    public function __construct(string $action, array $params) {
        $this->vista = new VCargo();
        $this->modelo = new MCargo();
        $this->action = $action;
        $this->params = $params;
    }

    protected function validate() {
        // Lógica de validación específica para cargos
    }

    protected function process() {
        // Lógica de procesamiento específica para cargos
        switch ($this->action) {
            case 'mostrar':
                $this->mostrarCargosC();
                break;
            case 'agregar':
                $this->agregarCargoC($this->params['nombre'], $this->params['descripcion']);
                break;
            case 'eliminar':
                $this->eliminarCargoC((int)$this->params['id']);
                break;
            case 'update':
                $this->updateCargoC((int)$this->params['id']);
                break;
            case 'editar':
                $this->editarCargoC((int)$this->params['id'], $this->params['nombre'], $this->params['descripcion']);
                break;
            default:
                echo "Acción no válida";
        }
    }

    protected function sendResponse() {
        // Lógica de respuesta específica para cargos
    }

    private function mostrarCargosC(): void {
        $cargos = $this->modelo->mostrarCargos();
        $this->vista->actualizar($cargos);
    }

    private function agregarCargoC(string $nombre, string $descripcion): void {
        $this->modelo->agregarCargo($nombre, $descripcion);
        $this->mostrarCargosC();
    }

    private function eliminarCargoC(int $id): void {
        $this->modelo->eliminarCargo($id);
        $this->mostrarCargosC();
    }

    private function updateCargoC(int $id): void {
        $cargo = $this->modelo->buscarCargo($id);
        $this->vista->mostrarFormularioEdicion($cargo);
    }

    private function editarCargoC(int $id, string $nombre, string $descripcion): void {
        $this->modelo->editarCargo($id, $nombre, $descripcion);
        $this->mostrarCargosC();
    }
}
