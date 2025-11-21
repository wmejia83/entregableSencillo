<?php
// Modelos/ModeloDashboard.php

require_once "Modelos/Conexion.php";

class ModeloDashboard
{
    /**
     * Devuelve un resumen de indicadores para el dashboard.
     */
    public static function obtenerResumen(): array
    {
        $data = [
            'total_categorias'        => 0,
            'total_productos'         => 0,
            'total_stock'             => 0,
            'productos_sin_stock'     => 0,
            'total_usuarios'          => 0,
            'usuarios_activos'        => 0,
            'usuarios_inactivos'      => 0,
        ];

        try {
            $pdo = Conexion::pdo();

            // Total categorías
            $sql = "SELECT COUNT(*) AS total FROM categorias";
            $data['total_categorias'] = (int)($pdo->query($sql)->fetchColumn() ?: 0);

            // Productos: total, suma stock, sin stock
            $sql = "SELECT 
                        COUNT(*) AS total_productos,
                        COALESCE(SUM(stock),0) AS total_stock,
                        SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END) AS productos_sin_stock
                    FROM productos";
            $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC) ?: [];
            $data['total_productos']     = (int)($row['total_productos'] ?? 0);
            $data['total_stock']         = (int)($row['total_stock'] ?? 0);
            $data['productos_sin_stock'] = (int)($row['productos_sin_stock'] ?? 0);

            // Usuarios: total, activos, inactivos
            $sql = "SELECT
                        COUNT(*) AS total_usuarios,
                        SUM(CASE WHEN estado_usuario = 1 THEN 1 ELSE 0 END) AS activos,
                        SUM(CASE WHEN estado_usuario = 0 THEN 1 ELSE 0 END) AS inactivos
                    FROM usuarios";
            $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC) ?: [];
            $data['total_usuarios']   = (int)($row['total_usuarios'] ?? 0);
            $data['usuarios_activos'] = (int)($row['activos'] ?? 0);
            $data['usuarios_inactivos'] = (int)($row['inactivos'] ?? 0);

        } catch (PDOException $e) {
            error_log("ModeloDashboard::obtenerResumen => " . $e->getMessage());
        }

        return $data;
    }

    /**
     * Últimos productos registrados (para tabla en dashboard).
     */
    public static function obtenerUltimosProductos(int $limit = 5): array
    {
        $limit = max(1, min($limit, 20)); // entre 1 y 20

        $sql = "SELECT 
                    p.id_producto,
                    p.codigo_producto,
                    p.nombre,
                    p.precio,
                    p.stock,
                    p.imagen,
                    p.creado_en,
                    c.nombre_categoria
                FROM productos p
                JOIN categorias c ON c.id_categoria = p.id_categoria
                ORDER BY p.creado_en DESC, p.id_producto DESC
                LIMIT :limite";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':limite', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            error_log("ModeloDashboard::obtenerUltimosProductos => " . $e->getMessage());
            return [];
        }
    }

    /**
     * Últimos usuarios que se crearon (no necesariamente último login).
     */
    public static function obtenerUltimosUsuarios(int $limit = 5): array
    {
        $limit = max(1, min($limit, 20));

        $sql = "SELECT
                    id_usuario,
                    nombre_usuario,
                    email_usuario,
                    perfil_usuario,
                    estado_usuario,
                    ultimo_login,
                    fyh_creacion_usuario
                FROM usuarios
                ORDER BY fyh_creacion_usuario DESC, id_usuario DESC
                LIMIT :limite";

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':limite', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            error_log("ModeloDashboard::obtenerUltimosUsuarios => " . $e->getMessage());
            return [];
        }
    }
}
