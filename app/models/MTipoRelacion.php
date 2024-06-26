<?php

require_once('../app/models/IglesiaDBProxy.php');

class MTipoRelacion
{
    private IglesiaDBProxy $database;

    public function __construct()
    {
        $this->database = new IglesiaDBProxy();
    }

    public function agregarTipo($nombre): void
    {
        $bd = $this->database->getConnection();
        try {
            $query = "INSERT INTO " . $this->database->getTableTipoRelacion() . " (nombre) VALUES (?)";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("s", $nombre);
            if ($stmt->execute()) {
                error_log("Tipo de relación insertada con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al insertar el tipo de relación a la base de datos: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }

    public function mostrarTipos(): array
    {
        $bd = $this->database->getConnection();

        $tiposRelacion = []; // Un arreglo para almacenar objetos TipoRelacion

        try {
            $result = $bd->query('SELECT * FROM ' . $this->database->getTableTipoRelacion());

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    // Crear un objeto TipoRelacion a partir de los datos de la fila
                    $tipoRelacion = new TipoRelacion($row['id'], $row['nombre']);
                    $tiposRelacion[] = $tipoRelacion; // Agregar el objeto al arreglo
                }
            }
        } catch (Exception $e) {
            error_log("Excepción en getTipoRelacion: " . $e->getMessage());
        } finally {
            $bd->close();
        }

        return $tiposRelacion;
    }

    public function buscarTipo($id)
    {
        $bd = $this->database->getConnection();

        try {
            $query = "SELECT * FROM " . $this->database->getTableTipoRelacion() . " WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Crear y devolver un objeto TipoRelacion a partir de los datos de la fila
                $tipoRelacion = new TipoRelacion($row['id'], $row['nombre']);
                return $tipoRelacion;
            }
        } catch (Exception $e) {
            error_log("Excepción en buscarTipo: " . $e->getMessage());
        } finally {
            $bd->close();
        }

        // Si no se encontró una coincidencia, puedes devolver un valor nulo
        return null;
    }

    public function editarTipo($id, $nombre): void
    {
        $bd = $this->database->getConnection();

        try {
            $query = "UPDATE " . $this->database->getTableTipoRelacion() . " SET nombre = ? WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("si", $nombre, $id);

            if ($stmt->execute()) {
                error_log("Tipo de relación editada con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al editar el tipo de relación: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }

    public function eliminarTipo($id): void
    {
        $bd = $this->database->getConnection();

        try {
            $query = "DELETE FROM " . $this->database->getTableTipoRelacion() . " WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                error_log("Tipo de relación eliminada con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al eliminar el tipo de relación: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }
}

class TipoRelacion {
    
    private $id;
    private $nombre;
    
    public function __construct($id, $nombre) {
        $this->id = $id;
        $this->nombre = $nombre;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function __toString() {
        return "TipoRelacion{" .
            "id=" . $this->id .
            ", nombre='" . $this->nombre . '\'' .
            '}';
    }
}
