<?php
require_once "Modelos/Conexion.php";

class ModeloProductos
{
    /* ============================================================
       GENERADOR AUTOMÁTICO DE CÓDIGO (robusto y único)
       Ejemplo: PRD-2503-AB9F
    ============================================================ */
    private static function generarCodigoAutomatico(PDO $pdo): string
    {
        do {
            $parteFecha = date('dm'); // Día + Mes
            $random     = strtoupper(bin2hex(random_bytes(2))); // 4 caracteres hex

            // Código final
            $codigo = "PRD-{$parteFecha}-{$random}";

        } while (self::codigoExiste($codigo)); // Garantizar que no exista

        return $codigo;
    }

    /* ============================================================
       Validar duplicado
    ============================================================ */
    public static function codigoExiste(string $codigo, ?int $excluirId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total
                FROM productos
                WHERE codigo_producto = :codigo";

        if ($excluirId !== null) {
            $sql .= " AND id_producto <> :id";
        }

        try {
            $pdo  = Conexion::pdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':codigo', trim($codigo), PDO::PARAM_STR);
            if ($excluirId !== null) {
                $stmt->bindValue(':id', $excluirId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)($row['total'] ?? 0) > 0;

        } catch (PDOException $e) {
            error_log("ModeloProductos::codigoExiste => ".$e->getMessage());
            return false;
        }
    }

    /* ============================================================
       CREAR PRODUCTO
    ============================================================ */
    public static function crear(array $data): ?int
    {
        $sql = "INSERT INTO productos 
                (id_categoria, codigo_producto, nombre, descripcion, precio, stock, imagen)
                VALUES 
                (:id_categoria, :codigo_producto, :nombre, :descripcion, :precio, :stock, :imagen)";

        try {
            $pdo = Conexion::pdo();

            /* ---------- Código automático o manual ---------- */
            $codigo = trim($data['codigo_producto'] ?? '');

            if ($codigo === '') {
                // Si no se envió código → generar uno nuevo
                $codigo = self::generarCodigoAutomatico($pdo);
            }

            // Validar formato
            if (!preg_match('/^[A-Za-z0-9\-\_]+$/', $codigo)) {
                error_log("ModeloProductos::crear => código inválido: {$codigo}");
                return null;
            }

            // Evitar duplicado manualmente
            if (self::codigoExiste($codigo)) {
                error_log("ModeloProductos::crear => código duplicado: {$codigo}");
                return null;
            }

            /* ---------- Insertar ---------- */
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id_categoria', (int)$data['id_categoria'], PDO::PARAM_INT);
            $stmt->bindValue(':codigo_producto', $codigo, PDO::PARAM_STR);
            $stmt->bindValue(':nombre', trim($data['nombre']), PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $data['descripcion'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':precio', (string)($data['precio'] ?? '0'), PDO::PARAM_STR);
            $stmt->bindValue(':stock', (int)($data['stock'] ?? 0), PDO::PARAM_INT);
            $stmt->bindValue(':imagen', $data['imagen'] ?? null, PDO::PARAM_STR);

            if (!$stmt->execute()) return null;

            return (int)$pdo->lastInsertId();

        } catch (PDOException $e) {
            error_log("ModeloProductos::crear => ".$e->getMessage());
            return null;
        }
    }

    /* ============================================================
       ACTUALIZAR PRODUCTO
    ============================================================ */
    public static function actualizar(int $id, array $data): bool
    {
        $campos = [
            'id_categoria = :id_categoria',
            'codigo_producto = :codigo_producto',
            'nombre = :nombre',
            'descripcion = :descripcion',
            'precio = :precio',
            'stock = :stock'
        ];

        $conImagen = array_key_exists('imagen', $data);
        if ($conImagen) {
            $campos[] = 'imagen = :imagen';
        }

        $sql = "UPDATE productos SET ".implode(', ', $campos)." WHERE id_producto = :id";

        try {
            $pdo = Conexion::pdo();

            /* ---------- Código ---------- */
            $codigo = trim($data['codigo_producto'] ?? '');

            if ($codigo === '') {
                // Recuperar el anterior si lo dejan vacío
                $actual = self::obtenerPorId($id);
                if (!$actual || empty($actual['codigo_producto'])) {
                    return false;
                }
                $codigo = $actual['codigo_producto'];
            }

            if (!preg_match('/^[A-Za-z0-9\-\_]+$/', $codigo)) {
                error_log("ModeloProductos::actualizar => código inválido: {$codigo}");
                return false;
            }

            if (self::codigoExiste($codigo, $id)) {
                error_log("ModeloProductos::actualizar => código duplicado: {$codigo}");
                return false;
            }

            /* ---------- Ejecutar UPDATE ---------- */
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':id_categoria', (int)$data['id_categoria'], PDO::PARAM_INT);
            $stmt->bindValue(':codigo_producto', $codigo, PDO::PARAM_STR);
            $stmt->bindValue(':nombre', trim($data['nombre']), PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $data['descripcion'] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':precio', (string)($data['precio'] ?? '0'), PDO::PARAM_STR);
            $stmt->bindValue(':stock', (int)($data['stock'] ?? 0), PDO::PARAM_INT);

            if ($conImagen) {
                $stmt->bindValue(':imagen', $data['imagen'] ?: null, PDO::PARAM_STR);
            }

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("ModeloProductos::actualizar => ".$e->getMessage());
            return false;
        }
    }

    /* ============================================================
       ELIMINAR
    ============================================================ */
    public static function eliminar(int $id): bool
    {
        $sql = "DELETE FROM productos WHERE id_producto = :id";
        try {
            $pdo = Conexion::pdo();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e; // Lo maneja el controlador
        }
    }

    /* ============================================================
       OBTENER POR ID
    ============================================================ */
    public static function obtenerPorId(int $id): ?array
    {
        try {
            $pdo = Conexion::pdo();
            $stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto = :id LIMIT 1");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("ModeloProductos::obtenerPorId => ".$e->getMessage());
            return null;
        }
    }

    /* ============================================================
       LISTAR PRODUCTOS
    ============================================================ */
    public static function listar(): array
    {
        $sql = "SELECT p.*, c.nombre_categoria
                FROM productos p
                JOIN categorias c ON c.id_categoria = p.id_categoria
                ORDER BY p.id_producto DESC";

        try {
            $pdo = Conexion::pdo();
            return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("ModeloProductos::listar => ".$e->getMessage());
            return [];
        }
    }
}
