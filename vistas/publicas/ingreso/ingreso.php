<!-- ===== LOGIN CENTRADO (Bootstrap 5) ===== -->
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
  <div class="card shadow p-4" style="width: 100%; max-width: 400px; border-radius: 12px;">
    <div class="text-center mb-4">
      <img src="img/logo.png" alt="Logo" width="80" class="mb-3">
      <h4 class="fw-bold text-primary">Iniciar sesión</h4>
      <p class="text-muted mb-0">Accede a tu cuenta</p>
    </div>

    <form method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="usuario@empresa.com" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember" name="remember">
          <label class="form-check-label" for="remember">Recordarme</label>
        </div>
        <a href="#" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary">Acceder</button>
      </div>

      <div class="text-center">
        <span class="small">¿No tienes cuenta? <a href="#" class="text-decoration-none">Regístrate</a></span>
      </div>

      <div class="text-center">
        <span class="small"><a href="inicio" class="text-decoration-none">Regresar al Inicio</a></span>
      </div>

            <?php
              $login = new ControladorUsuarios();
              $login -> ingresoUsuario();
            ?>

    </form>
  </div>
</div>
