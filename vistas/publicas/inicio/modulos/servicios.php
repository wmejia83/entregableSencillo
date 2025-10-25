<!-- Sección: Servicios (centrado y vistoso) -->
<section id="servicios-centrado" class="py-5 position-relative text-center">
  <!-- Fondo con gradientes -->
  <div class="position-absolute top-0 start-0 w-100 h-100"
       style="background:
        radial-gradient(1200px 600px at 50% -10%, rgba(13,110,253,.15), transparent),
        radial-gradient(900px 500px at 80% 20%, rgba(111,66,193,.12), transparent),
        radial-gradient(800px 500px at 20% 30%, rgba(32,201,151,.12), transparent);
        pointer-events:none;"></div>

  <div class="container position-relative" style="max-width: 1100px;">
    <!-- Encabezado -->
    <div class="mb-5">
      <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,.6); backdrop-filter: blur(6px);">
        Servicios premium
      </span>
      <h2 class="fw-bold mt-3 mb-2">Potencia tu presencia digital</h2>
      <p class="text-muted mb-0">Soluciones integrales con diseño, tecnología y crecimiento en el centro.</p>
    </div>

    <!-- Grid centrado -->
    <div class="row justify-content-center g-4">
      <!-- Card 1 -->
      <div class="col-12 col-sm-10 col-md-6 col-lg-4">
        <div class="svcG-card h-100 mx-auto">
          <div class="svcG-border"></div>
          <div class="svcG-inner p-4 d-flex flex-column align-items-center">
            <div class="svcG-icon mb-3"><i class="bi bi-window-sidebar"></i></div>
            <h5 class="mb-1">Sitios & Landing Pages</h5>
            <p class="text-muted small mb-3">Diseño centrado en conversión, performance y accesibilidad.</p>
            <ul class="text-start small list-unstyled mb-4">
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> UI limpia y coherente</li>
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> Core Web Vitals</li>
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> SEO técnico</li>
            </ul>
            <a href="#contacto" class="btn btn-primary rounded-pill px-4">Empezar</a>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-12 col-sm-10 col-md-6 col-lg-4">
        <div class="svcG-card h-100 mx-auto">
          <div class="svcG-border"></div>
          <div class="svcG-inner p-4 d-flex flex-column align-items-center">
            <div class="svcG-icon mb-3"><i class="bi bi-cpu"></i></div>
            <h5 class="mb-1">Apps & Integraciones</h5>
            <p class="text-muted small mb-3">Backends robustos, APIs seguras y automatizaciones.</p>
            <ul class="text-start small list-unstyled mb-4">
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> Arquitectura modular</li>
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> Autenticación JWT/OAuth</li>
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> Observabilidad</li>
            </ul>
            <a href="#contacto" class="btn btn-outline-primary rounded-pill px-4">Consultar</a>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-12 col-sm-10 col-md-6 col-lg-4">
        <div class="svcG-card h-100 mx-auto">
          <div class="svcG-border"></div>
          <div class="svcG-inner p-4 d-flex flex-column align-items-center">
            <div class="svcG-icon mb-3"><i class="bi bi-graph-up-arrow"></i></div>
            <h5 class="mb-1">Growth & Analítica</h5>
            <p class="text-muted small mb-3">KPI, funnels y experimentos para escalar con datos.</p>
            <ul class="text-start small list-unstyled mb-4">
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> Paneles en tiempo real</li>
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> A/B testing</li>
              <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> Automatización MKT</li>
            </ul>
            <a href="#contacto" class="btn btn-primary rounded-pill px-4">Impulsar</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Nota CTA -->
    <div class="mt-5">
      <p class="text-muted mb-3">¿Listo para crear algo increíble juntos?</p>
      <a href="#contacto" class="btn btn-lg btn-primary rounded-pill px-5">Hablemos</a>
    </div>
  </div>
</section>

<!-- Estilos del segmento -->
<style>
  /* Contenedor “glass” con borde degradado animado */
  .svcG-card {
    position: relative;
    max-width: 360px;
    border-radius: 20px;
    transition: transform .35s ease, box-shadow .35s ease;
    will-change: transform;
  }
  .svcG-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 1.5rem 2.5rem rgba(0,0,0,.12);
  }
  .svcG-border {
    position: absolute; inset: 0;
    padding: 1px; border-radius: 20px;
    background: linear-gradient(120deg,
      rgba(13,110,253,.75),
      rgba(111,66,193,.75),
      rgba(32,201,151,.75));
    -webkit-mask: 
      linear-gradient(#000 0 0) content-box,
      linear-gradient(#000 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    animation: borderShift 8s linear infinite;
  }
  @keyframes borderShift {
    0%   { filter: hue-rotate(0deg); }
    100% { filter: hue-rotate(360deg); }
  }
  .svcG-inner {
    position: relative; z-index: 1;
    border-radius: 19px;
    background: rgba(255,255,255,.7);
    backdrop-filter: blur(10px);
  }

  /* Icono con micro-interacción */
  .svcG-icon {
    width: 68px; height: 68px;
    display: grid; place-items: center;
    border-radius: 16px;
    font-size: 1.6rem;
    background: linear-gradient(135deg, rgba(13,110,253,.15), rgba(111,66,193,.15));
    transition: transform .35s ease;
  }
  .svcG-card:hover .svcG-icon { transform: scale(1.07) rotate(-2deg); }

  /* Ajustes de tipografía y centrado */
  #servicios-centrado h2 { letter-spacing: .2px; }
  #servicios-centrado .btn { box-shadow: 0 .5rem 1rem rgba(13,110,253,.15); }
</style>

<!-- Requiere Bootstrap Icons para los <i class="bi ..."> -->
