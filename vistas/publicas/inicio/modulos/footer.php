<!-- Footer -->
<footer class="footer-modern pt-5 position-relative text-center text-lg-start">
  <!-- Fondo con gradientes -->
  <div class="position-absolute top-0 start-0 w-100 h-100"
       style="background:
        radial-gradient(1200px 600px at 50% -10%, rgba(13,110,253,.14), transparent),
        radial-gradient(900px 600px at 90% 20%, rgba(111,66,193,.10), transparent),
        radial-gradient(800px 500px at 10% 40%, rgba(32,201,151,.10), transparent);
        pointer-events:none;"></div>

  <div class="container position-relative">
    <div class="row g-4 align-items-start">
      <!-- Marca / descripción -->
      <div class="col-12 col-lg-4 text-center text-lg-start">
        <a href="#" class="d-inline-flex align-items-center mb-2 text-decoration-none">
          <span class="fw-bold h4 mb-0">MiLanding</span>
        </a>
        <p class="text-muted mb-3">
          Construimos experiencias digitales con rendimiento, diseño y crecimiento al centro.
        </p>
        <!-- Redes -->
        <div class="d-flex gap-2 justify-content-center justify-content-lg-start">
          <a class="btn btn-outline-dark btn-sm rounded-circle" href="#" aria-label="Twitter">
            <i class="bi bi-twitter"></i>
          </a>
          <a class="btn btn-outline-dark btn-sm rounded-circle" href="#" aria-label="LinkedIn">
            <i class="bi bi-linkedin"></i>
          </a>
          <a class="btn btn-outline-dark btn-sm rounded-circle" href="#" aria-label="Instagram">
            <i class="bi bi-instagram"></i>
          </a>
          <a class="btn btn-outline-dark btn-sm rounded-circle" href="#" aria-label="YouTube">
            <i class="bi bi-youtube"></i>
          </a>
        </div>
      </div>

      <!-- Enlaces rápidos -->
      <div class="col-6 col-lg-2">
        <h6 class="fw-bold mb-3">Enlaces</h6>
        <ul class="list-unstyled small mb-0">
          <li class="mb-2"><a class="text-decoration-none link-body-emphasis" href="#inicio">Inicio</a></li>
          <li class="mb-2"><a class="text-decoration-none link-body-emphasis" href="#servicios">Servicios</a></li>
          <li class="mb-2"><a class="text-decoration-none link-body-emphasis" href="#team">Equipo</a></li>
          <li><a class="text-decoration-none link-body-emphasis" href="#contacto">Contacto</a></li>
        </ul>
      </div>

      <!-- Recursos -->
      <div class="col-6 col-lg-2">
        <h6 class="fw-bold mb-3">Recursos</h6>
        <ul class="list-unstyled small mb-0">
          <li class="mb-2"><a class="text-decoration-none link-body-emphasis" href="#">Blog</a></li>
          <li class="mb-2"><a class="text-decoration-none link-body-emphasis" href="#">Guías</a></li>
          <li class="mb-2"><a class="text-decoration-none link-body-emphasis" href="#">Soporte</a></li>
          <li><a class="text-decoration-none link-body-emphasis" href="#">Estatus</a></li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="col-12 col-lg-4">
        <h6 class="fw-bold mb-3 text-center text-lg-start">Suscríbete al boletín</h6>
        <form class="needs-validation" novalidate>
          <div class="input-group input-group-lg mb-2">
            <span class="input-group-text bg-white border-0 shadow-sm">
              <i class="bi bi-envelope"></i>
            </span>
            <input type="email" class="form-control border-0 shadow-sm" placeholder="tu@email.com"
                   aria-label="Correo electrónico" required>
            <button class="btn btn-primary" type="submit">Unirme</button>
          </div>
          <small class="text-muted d-block">
            Al suscribirte aceptas nuestras <a href="#" class="link-primary">Políticas</a>.
          </small>
          <div class="invalid-feedback d-block small mt-2" style="display:none;">Por favor, ingresa un email válido.</div>
        </form>
      </div>
    </div>

    <hr class="my-4">

    <!-- Legal / copy -->
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-2 pb-4">
      <p class="mb-0 small text-muted">
        © <span id="year"></span> MiLanding. Todos los derechos reservados.
      </p>
      <ul class="list-inline mb-0 small">
        <li class="list-inline-item"><a href="#" class="text-decoration-none link-body-emphasis">Términos</a></li>
        <li class="list-inline-item"><span class="text-muted">•</span></li>
        <li class="list-inline-item"><a href="#" class="text-decoration-none link-body-emphasis">Privacidad</a></li>
        <li class="list-inline-item"><span class="text-muted">•</span></li>
        <li class="list-inline-item"><a href="#" class="text-decoration-none link-body-emphasis">Cookies</a></li>
      </ul>
    </div>
  </div>

  <!-- Botón volver arriba -->
  <button id="btnTop" class="btn btn-dark rounded-circle position-absolute end-0 me-3 mb-3"
          style="bottom: 1rem; width: 44px; height: 44px; display:none;" aria-label="Volver arriba">
    <i class="bi bi-arrow-up"></i>
  </button>
</footer>

<!-- Estilos del segmento -->
<style>
  .footer-modern {
    background: linear-gradient(180deg, rgba(255,255,255,.75), rgba(255,255,255,.9));
    backdrop-filter: blur(8px);
    overflow: hidden;
  }
  .footer-modern .btn.rounded-circle { width: 38px; height: 38px; display: grid; place-items: center; }
  .footer-modern a.link-body-emphasis { color: rgba(0,0,0,.75); }
  .footer-modern a.link-body-emphasis:hover { color: var(--bs-primary); }
  .footer-modern .input-group .form-control:focus { box-shadow: none; }
  .footer-modern .input-group-text, .footer-modern .form-control { border-radius: .75rem; }
  .footer-modern .input-group .btn { border-radius: .75rem; }
</style>

<!-- Interacciones mínimas -->
<script>
  // Año automático
  document.getElementById('year').textContent = new Date().getFullYear();

  // Validación simple del newsletter
  (function () {
    const form = document.querySelector('.footer-modern form');
    const email = form.querySelector('input[type="email"]');
    const feedback = form.querySelector('.invalid-feedback');
    form.addEventListener('submit', function (e) {
      const ok = email.checkValidity();
      if (!ok) {
        e.preventDefault();
        feedback.style.display = 'block';
        email.classList.add('is-invalid');
      } else {
        feedback.style.display = 'none';
        email.classList.remove('is-invalid');
      }
    }, false);
  })();

  // Botón volver arriba
  (function () {
    const btn = document.getElementById('btnTop');
    const toggle = () => {
      if (window.scrollY > 300) btn.style.display = 'grid';
      else btn.style.display = 'none';
    };
    window.addEventListener('scroll', toggle, { passive: true });
    toggle();
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  })();
</script>
