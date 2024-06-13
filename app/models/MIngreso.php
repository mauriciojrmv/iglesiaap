<?php

require_once('../app/models/IglesiaDBProxy.php');

class MIngreso
{
    private IglesiaDBProxy $database;

    public function __construct()
    {
        $this->database = new IglesiaDBProxy();
    }

    public function agregarIngreso($tipoIngreso, $monto, $evento_id): void
    {
        $bd = $this->database->getConnection();
        try {
            $query = "INSERT INTO " . $this->database->getTableIngreso() . " (tipo_ingreso, monto, evento_id) VALUES (?, ?, ?)";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("sdi", $tipoIngreso, $monto, $evento_id);
            if ($stmt->execute()) {
                error_log("Ingreso insertado con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al insertar el ingreso en la base de datos: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }

    public function mostrarIngresos(): array
    {
        $bd = $this->database->getConnection();

        $ingresos = [];

        try {
            $result = $bd->query('SELECT * FROM ' . $this->database->getTableIngreso());

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $ingreso = new Ingreso($row['id'], $row['tipo_ingreso'], $row['monto'], $row['evento_id']);
                    $ingresos[] = $ingreso;
                }
            }
        } catch (Exception $e) {
            error_log("Excepción en mostrarIngresos: " . $e->getMessage());
        } finally {
            $bd->close();
        }

        return $ingresos;
    }

    public function buscarIngreso($id)
    {
        $bd = $this->database->getConnection();

        try {
            $query = "SELECT * FROM " . $this->database->getTableIngreso() . " WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $ingreso = new Ingreso($row['id'], $row['tipo_ingreso'], $row['monto'], $row['evento_id']);
                return $ingreso;
            }
        } catch (Exception $e) {
            error_log("Excepción en buscarIngreso: " . $e->getMessage());
        } finally {
            $bd->close();
        }

        return null;
    }

    public function editarIngreso($id, $tipoIngreso, $monto, $evento_id): void
    {
        $bd = $this->database->getConnection();

        try {
            $query = "UPDATE " . $this->database->getTableIngreso() . " SET tipo_ingreso = ?, monto = ?, evento_id = ? WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("sdii", $tipoIngreso, $monto, $evento_id, $id);

            if ($stmt->execute()) {
                error_log("Ingreso editado con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al editar el ingreso: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }

    public function eliminarIngreso($id): void
    {
        $bd = $this->database->getConnection();

        try {
            $query = "DELETE FROM " . $this->database->getTableIngreso() . " WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                error_log("Ingreso eliminado con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al eliminar el ingreso: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }
}

class Ingreso
{
    private $id;
    private $tipoIngreso;
    private $monto;
    private $eventoId;

    public function __construct($id, $tipoIngreso, $monto, $eventoId)
    {
        $this->id = $id;
        $this->tipoIngreso = $tipoIngreso;
        $this->monto = $monto;
        $this->eventoId = $eventoId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTipoIngreso()
    {
        return $this->tipoIngreso;
    }

    public function setTipoIngreso($tipoIngreso)
    {
        $this->tipoIngreso = $tipoIngreso;
    }

    public function getMonto()
    {
        return $this->monto;
    }

    public function setMonto($monto)
    {
        $this->monto = $monto;
    }

    public function getEventoId()
    {
        return $this->eventoId;
    }

    public function setEventoId($eventoId)
    {
        $this->eventoId = $eventoId;
    }
}
