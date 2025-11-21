<!-- ===== LOGIN PRO · CENTRADO ===== -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$oldEmail = htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<style>
  body.login-bg {
    min-height: 100vh;
    background: radial-gradient(circle at top left, #4e73df 0, #224abe 35%, #1b1e2f 100%);
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  }
  .login-card {
    width: 100%;
    max-width: 420px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,.08);
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,.96);
  }
  .login-logo {
    width: 82px;
    height: 82px;
    border-radius: 20px;
    object-fit: contain;
    background: #f8f9fc;
    border: 1px solid rgba(0,0,0,.06);
  }
  .form-control:focus {
    box-shadow: 0 0 0 .15rem rgba(78,115,223,.25);
    border-color: #4e73df;
  }
  .input-group-text {
    background: #f8f9fc;
  }
  .btn-primary {
    border-radius: 999px;
  }
  .small-link {
    font-size: .85rem;
  }
</style>

<body class="login-bg">

<div class="d-flex align-items-center justify-content-center vh-100">
  <div class="card shadow login-card p-4">
    <div class="text-center mb-4">
      <img src="public/img/logo.png" alt="Logo" class="login-logo mb-3">
      <h4 class="fw-bold text-primary mb-1">Bienvenido de nuevo</h4>
      <p class="text-muted mb-0">Ingresa tus credenciales para continuar</p>
    </div>

    <form method="POST" novalidate id="formLogin">
      <?php
        // Aquí se pintan los mensajes de error del controlador
        $login = new ControladorUsuarios();
        $login->ingresoUsuario();
      ?>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-envelope"></i>
          </span>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="usuario@empresa.com"
            value="<?= $oldEmail ?>"
            required
          >
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-lock"></i>
          </span>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="••••••••"
            required
          >
          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
            <i class="fas fa-eye-slash"></i>
          </button>
        </div>
        <small class="text-muted d-block mt-1">
          Mínimo 8 caracteres, combina letras y números.
        </small>
      </div>

      <!-- Remember + Forgot -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember" name="remember">
          <label class="form-check-label" for="remember">Recordarme</label>
        </div>
        <a href="#" class="text-decoration-none small-link">¿Olvidaste tu contraseña?</a>
      </div>

      <!-- Botón -->
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary" id="btnLogin">
          <span class="me-2"><i class="fas fa-sign-in-alt"></i></span>
          <span>Acceder</span>
        </button>
      </div>

      <!-- Enlaces inferiores -->
      <div class="text-center small mb-1">
        ¿No tienes cuenta?
        <a href="#" class="text-decoration-none">Contacta al administrador</a>
      </div>

      <div class="text-center small">
        <a href="inicio" class="text-decoration-none">← Regresar al inicio</a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput  = document.getElementById('password');
  const btnLogin       = document.getElementById('btnLogin');
  const formLogin      = document.getElementById('formLogin');

  if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      const icon = togglePassword.querySelector('i');
      if (icon) {
        icon.classList.toggle('fa-eye-slash');
        icon.classList.toggle('fa-eye');
      }
    });
  }

  // Pequeño estado de "cargando" en el botón
  if (formLogin && btnLogin) {
    formLogin.addEventListener('submit', () => {
      btnLogin.disabled = true;
      btnLogin.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Ingresando...';
    });
  }
});
</script>

</body>
