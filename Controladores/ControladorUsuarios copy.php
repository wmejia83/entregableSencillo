<?php
// ControladorUsuarios.php

require_once "Modelos/ModeloUsuarios.php";

class ControladorUsuarios
{
    /* =========================
       LOGIN
       ==========================*/
    public function ingresoUsuario(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (!isset($_POST['email'], $_POST['password'])) {
            echo '<div class="alert alert-danger">Por favor completa tus credenciales.</div>';
            return;
        }

        $email    = trim($_POST['email']);
        $password = (string)$_POST['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<div class="alert alert-danger">El correo electrónico no es válido.</div>';
            return;
        }

        if ($password === '') {
            echo '<div class="alert alert-danger">La contraseña no puede estar vacía.</div>';
            return;
        }

        $usuario = ModeloUsuarios::findByEmail($email);

        if (!$usuario) {
            echo '<div class="alert alert-danger">Credenciales inválidas, por favor verifica e inténtalo de nuevo.</div>';
            return;
        }

        if (isset($usuario['estado_usuario']) && (int)$usuario['estado_usuario'] !== 1) {
            echo '<div class="alert alert-warning">Tu usuario está inactivo. Contacta al administrador.</div>';
            return;
        }

        $hashBD = $usuario['password_usuario'] ?? '';

        if (!is_string($hashBD) || $hashBD === '' || !password_verify($password, $hashBD)) {
            echo '<div class="alert alert-danger">Credenciales inválidas, por favor verifica e inténtalo de nuevo.</div>';
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['admin']  = 'ok';
        $_SESSION['nombre'] = $usuario['nombre_usuario'] ?? 'Administrador';
        $_SESSION['uid']    = $usuario['id_usuario'] ?? null;
        $_SESSION['perfil'] = $usuario['perfil_usuario'] ?? 'administrador';

        if (!empty($_SESSION['uid'])) {
            ModeloUsuarios::actualizarUltimoLogin((int)$_SESSION['uid']);
        }

        header('Location: dashboard');
        exit;
    }

    /* =========================
       CRUD USUARIOS BACKOFFICE
       ==========================*/

    /**
     * Punto de entrada para manejar POST de crear/editar/cambiar estado.
     */
    public static function manejarPostUsuarios(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $self = new self();

        if (isset($_POST['id_usuario_eliminar'])) {
            $self->eliminarUsuario();
        } elseif (isset($_POST['id_usuario_estado'], $_POST['nuevo_estado'])) {
            $self->cambiarEstadoUsuario();
        } elseif (isset($_POST['id_usuario'])) {
            $self->editarUsuario();
        } elseif (isset($_POST['nombre'], $_POST['email'], $_POST['password'], $_POST['perfil'])) {
            $self->crearUsuario();
        }
    }



    private static function filtrarId($valor): ?int
    {
        $id = filter_var($valor, FILTER_VALIDATE_INT);
        return ($id && $id > 0) ? (int)$id : null;
    }

    private static function sanitizarTexto(?string $texto, int $min = 2, int $max = 120): ?string
    {
        if ($texto === null) return null;
        $texto = trim($texto);

        if ($texto === '') return null;
        if (mb_strlen($texto) < $min || mb_strlen($texto) > $max) return null;

        return $texto;
    }

    private static function validarPerfil(string $perfil): string
    {
        // Puedes ajustar los roles válidos
        $validos = ['administrador', 'usuario'];
        return in_array($perfil, $validos, true) ? $perfil : 'usuario';
    }

    private static function swal(string $title, string $text, string $icon, string $redirect = 'usuarios'): void
    {
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $text  = htmlspecialchars($text,  ENT_QUOTES, 'UTF-8');
        $redir = htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8');

        echo '<script>
            if (window.Swal) {
              Swal.fire({
                  title: "'.$title.'",
                  text: "'.$text.'",
                  icon: "'.$icon.'",
                  confirmButtonText: "Entendido"
              }).then(() => { window.location = "'.$redir.'"; });
            } else {
              alert("'.$title.'\n'.$text.'");
              window.location = "'.$redir.'";
            }
        </script>';
    }

    /* ===== Crear usuario ===== */
    public function crearUsuario(): void
    {
        $nombre   = self::sanitizarTexto($_POST['nombre'] ?? null);
        $email    = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $perfil   = self::validarPerfil($_POST['perfil'] ?? 'usuario');

        if ($nombre === null) {
            self::swal('Cuidado', 'El nombre no es válido.', 'error');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::swal('Cuidado', 'El correo electrónico no es válido.', 'error');
            return;
        }

        if (ModeloUsuarios::emailExiste($email)) {
            self::swal('Cuidado', 'Ya existe un usuario con ese correo.', 'error');
            return;
        }

        // Aquí sí podemos aplicar política de contraseña fuerte
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
            self::swal('Cuidado', 'La contraseña debe tener mínimo 8 caracteres, letras y números.', 'error');
            return;
        }

        $id = ModeloUsuarios::registrarUsuario($nombre, $email, $password, $perfil);

        if ($id) {
            self::swal('Usuario creado', 'El usuario ha sido registrado correctamente.', 'success');
        } else {
            self::swal('Error', 'No fue posible registrar el usuario.', 'error');
        }
    }

    /* ===== Editar usuario ===== */
    public function editarUsuario(): void
    {
        $id     = self::filtrarId($_POST['id_usuario'] ?? null);
        $nombre = self::sanitizarTexto($_POST['nombre'] ?? null);
        $email  = trim($_POST['email'] ?? '');
        $perfil = self::validarPerfil($_POST['perfil'] ?? 'usuario');
        $estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 1;

        if ($id === null) {
            self::swal('Cuidado', 'El identificador de usuario es inválido.', 'error');
            return;
        }

        if ($nombre === null) {
            self::swal('Cuidado', 'El nombre no es válido.', 'error');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::swal('Cuidado', 'El correo electrónico no es válido.', 'error');
            return;
        }

        if (ModeloUsuarios::emailExiste($email, $id)) {
            self::swal('Cuidado', 'Ya existe otro usuario con ese correo.', 'error');
            return;
        }

        $estado = $estado === 1 ? 1 : 0;

        $ok = ModeloUsuarios::actualizarUsuario($id, $nombre, $email, $perfil, $estado);

        if ($ok) {
            self::swal('Usuario actualizado', 'Los datos del usuario se han guardado correctamente.', 'success');
        } else {
            self::swal('Error', 'No fue posible actualizar el usuario.', 'error');
        }
    }

    /* ===== Cambiar estado (activar / desactivar) ===== */
    public function cambiarEstadoUsuario(): void
    {
        $id     = self::filtrarId($_POST['id_usuario_estado'] ?? null);
        $estado = isset($_POST['nuevo_estado']) ? (int)$_POST['nuevo_estado'] : 0;

        if ($id === null) {
            self::swal('Cuidado', 'El identificador de usuario es inválido.', 'error');
            return;
        }

        $estado = $estado === 1 ? 1 : 0;

        $ok = ModeloUsuarios::cambiarEstado($id, $estado);

        if ($ok) {
            $msg = $estado === 1 ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente.';
            self::swal('Estado actualizado', $msg, 'success');
        } else {
            self::swal('Error', 'No fue posible cambiar el estado del usuario.', 'error');
        }
    }

    /* ===== Listar para la vista ===== */
    public static function listarUsuarios(): array
    {
        return ModeloUsuarios::obtenerTodos();
    }


    /* ===== Eliminar usuario (hard delete) ===== */
    public function eliminarUsuario(): void
    {
        $id = self::filtrarId($_POST['id_usuario_eliminar'] ?? null);

        if ($id === null) {
            self::swal('Cuidado', 'El identificador de usuario es inválido.', 'error');
            return;
        }

        // 🚫 (Opcional) puedes impedir que un usuario se borre a sí mismo:
        // if (isset($_SESSION['uid']) && (int)$_SESSION['uid'] === $id) {
        //     self::swal('Operación no permitida', 'No puedes eliminar tu propio usuario desde aquí.', 'warning');
        //     return;
        // }

        $ok = ModeloUsuarios::eliminarUsuario($id);

        if ($ok) {
            self::swal('Usuario eliminado', 'El usuario ha sido eliminado correctamente.', 'success');
        } else {
            self::swal('Error', 'No fue posible eliminar el usuario.', 'error');
        }
    }

}
