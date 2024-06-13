<?php

abstract class BaseController {

    public function __construct() {
        // Inicialización común si es necesaria
    }

    // Método plantilla que define el flujo de manejo de solicitudes
    public function handleRequest() {
        $this->validate();
        $this->process();
        $this->sendResponse();
    }

    abstract protected function validate();
    abstract protected function process();
    abstract protected function sendResponse();
}
