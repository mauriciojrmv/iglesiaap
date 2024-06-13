<?php

require_once('../app/models/IglesiaDBProxy.php');

class MUsuario
{
    private IglesiaDBProxy $database;

    public function __construct()
    {
        $this->database = new IglesiaDBProxy();
    }

    public function agregarUsuario($nombre, $apellido, $email, $ci, $cargo_id): void
    {
        $bd = $this->database->getConnection();
        try {
            $query = "INSERT INTO " . $this->database->getTableUsuario() . " (nombre, apellido, email, ci, cargo_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("ssssi", $nombre, $apellido, $email, $ci, $cargo_id);
            if ($stmt->execute()) {
                error_log("Usuario insertado con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al insertar el usuario en la base de datos: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }

    public function mostrarUsuarios(): array
    {
        $bd = $this->database->getConnection();

        $usuarios = [];

        try {
            $result = $bd->query('SELECT * FROM ' . $this->database->getTableUsuario());

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $usuario = new Usuario($row['id'], $row['nombre'], $row['apellido'], $row['email'], $row['ci'], $row['cargo_id']);
                    $usuarios[] = $usuario;
                }
            }
        } catch (Exception $e) {
            error_log("Excepción en mostrarUsuarios: " . $e->getMessage());
        } finally {
            $bd->close();
        }

        return $usuarios;
    }

    public function buscarUsuario($id)
    {
        $bd = $this->database->getConnection();

        try {
            $query = "SELECT * FROM " . $this->database->getTableUsuario() . " WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $usuario = new Usuario($row['id'], $row['nombre'], $row['apellido'], $row['email'], $row['ci'], $row['cargo_id']);
                return $usuario;
            }
        } catch (Exception $e) {
            error_log("Excepción en buscarUsuario: " . $e->getMessage());
        } finally {
            $bd->close();
        }

        return null;
    }

    public function editarUsuario($id, $nombre, $apellido, $email, $ci, $cargo_id): void
    {
        $bd = $this->database->getConnection();

        try {
            $query = "UPDATE " . $this->database->getTableUsuario() . " SET nombre = ?, apellido = ?, email = ?, ci = ?, cargo_id = ? WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("ssssii", $nombre, $apellido, $email, $ci, $cargo_id, $id);

            if ($stmt->execute()) {
                error_log("Usuario editado con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al editar el usuario: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }

    public function eliminarUsuario($id): void
    {
        $bd = $this->database->getConnection();

        try {
            $query = "DELETE FROM " . $this->database->getTableUsuario() . " WHERE id = ?";
            $stmt = $bd->prepare($query);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                error_log("Usuario eliminado con éxito");
            }
        } catch (Exception $e) {
            error_log("Excepción al eliminar el usuario: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $bd->close();
        }
    }
}

class Usuario
{
    private $id;
    private $nombre;
    private $apellido;
    private $email;
    private $ci;
    private $cargo_id;

    public function __construct($id, $nombre, $apellido, $email, $ci, $cargo_id)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->ci = $ci;
        $this->cargo_id = $cargo_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getCI()
    {
        return $this->ci;
    }

    public function setCI($ci)
    {
        $this->ci = $ci;
    }

    public function getCargoId()
    {
        return $this->cargo_id;
    }

    public function setCargoId($cargo_id)
    {
        $this->cargo_id = $cargo_id;
    }

    public function __toString()
    {
        return "Usuario{" .
            "id=" . $this->id .
            ", nombre='" . $this->nombre . '\'' .
            ", apellido='" . $this->apellido . '\'' .
            ", email='" . $this->email . '\'' .
            ", ci='" . $this->ci . '\'' .
            ", cargo_id=" . $this->cargo_id .
            '}';
    }
}
