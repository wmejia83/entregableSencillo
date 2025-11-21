<?php
// ModeloUsuarios.php

require_once "Modelos/Conexion.php";

class ModeloUsuarios
{
    /**
     * Busca un usuario por email (para login).
     */
    public static function findByEmail(string $email): ?array
    {
        $sql = "
            SELECT
                id_usuario,
                nombre_usuario,
                email_usuario,
                password_usuario,
                perfil_usuario,
                foto_usuario,
                estado_usuario,
                ultimo_login,
                fyh_creacion_usuario
            FROM usuarios
            WHERE email_usuario = :email
            LIMIT 1
        ";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', trim($email), PDO::PARAM_STR);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $usuario ?: null;

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::findByEmail - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene todos los usuarios para listarlos.
     */
    public static function obtenerTodos(): array
    {
        $sql = "
            SELECT
                id_usuario,
                nombre_usuario,
                email_usuario,
                perfil_usuario,
                foto_usuario,
                estado_usuario,
                ultimo_login,
                fyh_creacion_usuario
            FROM usuarios
            ORDER BY id_usuario DESC
        ";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows ?: [];

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::obtenerTodos - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca un usuario por ID.
     */
    public static function findById(int $id): ?array
    {
        if ($id <= 0) return null;

        $sql = "
            SELECT
                id_usuario,
                nombre_usuario,
                email_usuario,
                perfil_usuario,
                foto_usuario,
                estado_usuario,
                ultimo_login,
                fyh_creacion_usuario
            FROM usuarios
            WHERE id_usuario = :id
            LIMIT 1
        ";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $usuario ?: null;

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::findById - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza ultimo_login (para login).
     */
    public static function actualizarUltimoLogin(int $idUsuario): bool
    {
        $sql = "
            UPDATE usuarios
            SET ultimo_login = NOW()
            WHERE id_usuario = :id
            LIMIT 1
        ";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::actualizarUltimoLogin - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registra un nuevo usuario con contraseña hasheada.
     */
    public static function registrarUsuario(
        string $nombre,
        string $email,
        string $passwordPlano,
        string $perfil = 'administrador'
    ): ?int {
        $sql = "
            INSERT INTO usuarios
                (nombre_usuario, email_usuario, password_usuario, perfil_usuario, foto_usuario, estado_usuario, ultimo_login)
            VALUES
                (:nombre, :email, :password, :perfil, '', 1, '0000-00-00 00:00:00')
        ";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':nombre', trim($nombre), PDO::PARAM_STR);
            $stmt->bindValue(':email',  trim($email),  PDO::PARAM_STR);

            // Encriptar contraseña
            $hash = password_hash($passwordPlano, PASSWORD_DEFAULT);
            $stmt->bindValue(':password', $hash, PDO::PARAM_STR);

            $stmt->bindValue(':perfil', $perfil, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                return null;
            }

            $id = (int)$pdo->lastInsertId();
            return $id > 0 ? $id : null;

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::registrarUsuario - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza datos básicos del usuario (sin cambiar contraseña).
     */
    public static function actualizarUsuario(
        int $id,
        string $nombre,
        string $email,
        string $perfil,
        int $estado
    ): bool {
        if ($id <= 0) return false;

        $sql = "
            UPDATE usuarios
            SET
                nombre_usuario = :nombre,
                email_usuario  = :email,
                perfil_usuario = :perfil,
                estado_usuario = :estado
            WHERE id_usuario = :id
            LIMIT 1
        ";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':nombre', trim($nombre), PDO::PARAM_STR);
            $stmt->bindValue(':email',  trim($email),  PDO::PARAM_STR);
            $stmt->bindValue(':perfil', $perfil,       PDO::PARAM_STR);
            $stmt->bindValue(':estado', $estado,       PDO::PARAM_INT);
            $stmt->bindValue(':id',     $id,           PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::actualizarUsuario - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cambia estado (activo / inactivo).
     */
    public static function cambiarEstado(int $id, int $estado): bool
    {
        if ($id <= 0) return false;

        $sql = "
            UPDATE usuarios
            SET estado_usuario = :estado
            WHERE id_usuario = :id
            LIMIT 1
        ";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindValue(':id',     $id,     PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::cambiarEstado - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si ya existe un email (para evitar duplicados).
     */
    public static function emailExiste(string $email, ?int $excluirId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE email_usuario = :email";

        if ($excluirId !== null) {
            $sql .= " AND id_usuario <> :id";
        }

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', trim($email), PDO::PARAM_STR);
            if ($excluirId !== null) {
                $stmt->bindValue(':id', $excluirId, PDO::PARAM_INT);
            }
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0) > 0;

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::emailExiste - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina físicamente un usuario (hard delete).
     *
     * @param int $id
     * @return bool
     */
    public static function eliminarUsuario(int $id): bool
    {
        if ($id <= 0) return false;

        $sql = "DELETE FROM usuarios WHERE id_usuario = :id LIMIT 1";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute() && $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Error en ModeloUsuarios::eliminarUsuario - " . $e->getMessage());
            return false;
        }
    }



}
