<?php

require_once('../app/models/IglesiaDBProxy.php');

class MRelacion
{
    private IglesiaDBProxy $database;

    public function __construct()
    {
        $this->database = new IglesiaDBProxy();
    }

    // Función para agregar una nueva relación
    public function agregarRelacion($usuarioA, $usuarioB, $tipoRelacionA, $tipoRelacionB): void
    {
        $bd = $this->database->getConnection();
        try {
            $query = "INSERT INTO " . $this->database->getTableRelacion() . " (usuario_a, usuario_b, tipo_relacion_a, tipo_relacion_b) VALUES (?, ?, ?, ?)";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("iiii", $usuarioA, $usuarioB, $tipoRelacionA, $tipoRelacionB);
            if ($stmt->execute()) {
                error_log("Relación insertada con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al insertar la relación a la base de datos: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }

    // Función para obtener todas las relaciones
    public function mostrarRelaciones(): array
    {
        $bd = $this->database->getConnection();
        $relaciones = [];

        try {
            $result = $bd->query('SELECT * FROM ' . $this->database->getTableRelacion());

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $relacion = new Relacion($row['id'], $row['usuario_a'], $row['usuario_b'], $row['tipo_relacion_a'], $row['tipo_relacion_b']);
                    $relaciones[] = $relacion;
                }
            }
        } catch (Exception $e) {
            error_log("Excepción en obtenerRelaciones: " . $e->getMessage());
        } finally {
            $bd->close();
        }

        return $relaciones;
    }

    // Función para buscar una relación por su ID
    public function buscarRelacion($id)
    {
        $bd = $this->database->getConnection();
        try {
            $query = "SELECT * FROM " . $this->database->getTableRelacion() . " WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $relacion = new Relacion($row['id'], $row['usuario_a'], $row['usuario_b'], $row['tipo_relacion_a'], $row['tipo_relacion_b']);
                return $relacion;
            }
        } catch (Exception $e) {
            error_log("Excepción en buscarRelacion: " . $e->getMessage());
        } finally {
            $bd->close();
        }

        return null;
    }

    // Función para editar una relación
    public function editarRelacion($id, $usuarioA, $usuarioB, $tipoRelacionA, $tipoRelacionB): void
    {
        $bd = $this->database->getConnection();
        try {
            $query = "UPDATE " . $this->database->getTableRelacion() . " SET usuario_a = ?, usuario_b = ?, tipo_relacion_a = ?, tipo_relacion_b = ? WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("iiiii", $usuarioA, $usuarioB, $tipoRelacionA, $tipoRelacionB, $id);

            if ($stmt->execute()) {
                error_log("Relación editada con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al editar la relación: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }

    // Función para eliminar una relación por su ID
    public function eliminarRelacion($id): void
    {
        $bd = $this->database->getConnection();
        try {
            $query = "DELETE FROM " . $this->database->getTableRelacion() . " WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                error_log("Relación eliminada con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al eliminar la relación: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }
}

class Relacion {
    private $id;
    private $usuarioA;
    private $usuarioB;
    private $tipoRelacionA;
    private $tipoRelacionB;

    public function __construct($id, $usuarioA, $usuarioB, $tipoRelacionA, $tipoRelacionB) {
        $this->id = $id;
        $this->usuarioA = $usuarioA;
        $this->usuarioB = $usuarioB;
        $this->tipoRelacionA = $tipoRelacionA;
        $this->tipoRelacionB = $tipoRelacionB;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsuarioA() {
        return $this->usuarioA;
    }

    public function setUsuarioA($usuarioA) {
        $this->usuarioA = $usuarioA;
    }

    public function getUsuarioB() {
        return $this->usuarioB;
    }

    public function setUsuarioB($usuarioB) {
        $this->usuarioB = $usuarioB;
    }

    public function getTipoRelacionA() {
        return $this->tipoRelacionA;
    }

    public function setTipoRelacionA($tipoRelacionA) {
        $this->tipoRelacionA = $tipoRelacionA;
    }

    public function getTipoRelacionB() {
        return $this->tipoRelacionB;
    }

    public function setTipoRelacionB($tipoRelacionB) {
        $this->tipoRelacionB = $tipoRelacionB;
    }

    public function __toString() {
        return "Relación [ID: {$this->id}, Usuario A: {$this->usuarioA}, Usuario B: {$this->usuarioB}, Tipo de Relación A: {$this->tipoRelacionA}, Tipo de Relación B: {$this->tipoRelacionB}]";
    }
}
