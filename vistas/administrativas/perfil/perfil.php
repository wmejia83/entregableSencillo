<?php
// Vistas/perfil.php

require_once "Controladores/ControladorUsuarios.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = ControladorUsuarios::obtenerPerfilActual();
if (!$usuario) {
    header('Location: login');
    exit;
}

$nombre = htmlspecialchars($usuario['nombre_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
$email  = htmlspecialchars($usuario['email_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
$perfil = htmlspecialchars($usuario['perfil_usuario'] ?? '', ENT_QUOTES, 'UTF-8');

// Foto: si no trae nada o viene vacía, usamos la foto por defecto
$fotoBD   = $usuario['foto_usuario'] ?? '';
$fotoPath = $fotoBD !== '' ? $fotoBD : 'public/img/default-user.png';
$foto     = htmlspecialchars($fotoPath, ENT_QUOTES, 'UTF-8');
?>
<div class="container-fluid">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-user-circle me-2 text-primary"></i> Mi perfil
    </h1>
  </div>

  <div class="row">
    <!-- Columna izquierda: info -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <img src="<?= $foto; ?>"
               alt="Foto de perfil"
               class="rounded-circle mb-3"
               style="width:120px;height:120px;object-fit:cover;">

          <h5 class="card-title mb-0"><?= $nombre ?></h5>
          <p class="text-muted mb-1"><?= $email ?></p>
          <span class="badge bg-info text-dark text-capitalize">
            <i class="fas fa-id-badge me-1"></i><?= $perfil ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Columna derecha: formularios -->
    <div class="col-md-8 mb-4">

      <!-- Cambiar contraseña -->
      <div class="card shadow-sm mb-4">
        <div class="card-header">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-key me-1"></i> Cambiar contraseña
          </h6>
        </div>
        <div class="card-body">
          <form method="post" autocomplete="off">
            <input type="hidden" name="accion" value="cambiar_password">

            <div class="mb-3">
              <label class="form-label">Contraseña actual</label>
              <input type="password" class="form-control" name="password_actual" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" name="password_nueva" required>
                <div class="form-text">
                  Mínimo 8 caracteres, combina letras y números.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Confirmar nueva contraseña</label>
                <input type="password" class="form-control" name="password_confirm" required>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Actualizar contraseña
            </button>
          </form>
        </div>
      </div>

      <!-- Cambiar foto -->
      <div class="card shadow-sm">
        <div class="card-header">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-image me-1"></i> Cambiar foto de perfil
          </h6>
        </div>
        <div class="card-body">
          <form method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="accion" value="cambiar_foto">

            <div class="mb-3">
              <label class="form-label">Seleccionar nueva foto</label>
              <input type="file"
                     class="form-control"
                     name="foto"
                     accept=".jpg,.jpeg,.png,.webp"
                     required>
              <div class="form-text">
                Formatos permitidos: JPG, PNG, WEBP. Máx 2 MB.
              </div>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="fas fa-upload me-1"></i> Actualizar foto
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<?php
// Procesar acciones del perfil (después del HTML para que SWAL funcione bien)
ControladorUsuarios::actualizarPasswordPerfil();
ControladorUsuarios::actualizarFotoPerfil();
?>
