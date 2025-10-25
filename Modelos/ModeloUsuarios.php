<?php
// =======================================
// ModeloUsuarios.php
// Ejemplo de clase modelo en PHP con PDO
// =======================================

// Importa la clase de conexión
require_once "Modelos/Conexion.php";

class ModeloUsuarios {

    /**
     * Buscar un usuario por su email
     * 
     * @param string $email Correo electrónico a buscar
     * @return array|null   Retorna un array asociativo con los datos del usuario o null si no existe
     */
    public static function findByEmail(string $email): ?array
    {
        // Consulta SQL con marcador (placeholder)
        $consultaSql = "
            SELECT 
                id_usuario, 
                nombre_usuario, 
                email_usuario, 
                password_usuario 
            FROM usuarios 
            WHERE email_usuario = :email_usuario
            LIMIT 1
        ";

        try {
            // Preparamos la consulta
            $stmt = Conexion::pdo()->prepare($consultaSql);

            // Enlazamos el parámetro para evitar inyección SQL
            $stmt->bindParam(":email_usuario", $email, PDO::PARAM_STR);

            // Ejecutamos
            $stmt->execute();

            // Obtenemos el resultado como array asociativo
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si no encuentra nada, fetch devuelve false → convertimos en null
            return $usuario ?: null;

        } catch (PDOException $e) {
            // Registramos el error en el log (nunca mostramos detalles al usuario)
            error_log("Error en findByEmail: " . $e->getMessage());
            return null;
        }
    }
}
