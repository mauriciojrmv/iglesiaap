<?php

require_once('IglesiaDB.php');

class IglesiaDBProxy {
    private $realDB;
    private $cache = [];
    private $accessToken;

    public function __construct($accessToken = 'default_token') {
        $this->realDB = new IglesiaDB();
        $this->accessToken = $accessToken;
    }

    private function checkAccess() {
        // Ejemplo de control de acceso sencillo
        if ($this->accessToken !== 'valid_token' && $this->accessToken !== 'default_token') {
            throw new Exception("Acceso denegado.");
        }
    }

    public function getConnection() {
        $this->checkAccess();
        return $this->realDB->getConnection();
    }

    public function query($sql) {
        $this->checkAccess();

        if (isset($this->cache[$sql])) {
            return $this->cache[$sql];
        }

        $conn = $this->getConnection();
        $result = $conn->query($sql);

        // Cachear solo si no hay error en la consulta
        if ($result) {
            $this->cache[$sql] = $result;
        }

        return $result;
    }

    public function execute($sql) {
        $this->checkAccess();

        // Invalidar caché si se realiza una operación que cambia el estado de la base de datos
        $this->cache = [];
        $conn = $this->getConnection();
        return $conn->query($sql);
    }
    
    public function getTableCargo() {
        $this->checkAccess();
        return IglesiaDB::TABLE_CARGO;        
    }

    public function getTableUsuario(){
        $this->checkAccess();
        return IglesiaDB::TABLE_USUARIO;
    }

    public function getTableEvento() {
        $this->checkAccess();
        return IglesiaDB::TABLE_EVENTO;
    }

    public function getTableIngreso() {
        $this->checkAccess();
        return IglesiaDB::TABLE_INGRESO;
    }

    public function getTableRelacion() {
        $this->checkAccess();
        return IglesiaDB::TABLE_RELACION;
    }

    public function getTableTipoRelacion() {
        $this->checkAccess();
        return IglesiaDB::TABLE_TIPO_RELACION;
    }
}
