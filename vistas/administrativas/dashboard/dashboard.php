<?php
// Vistas/modulos/dashboard.php

require_once "Controladores/ControladorDashboard.php";

// Puedes proteger aquí el acceso (ya lo tendrás en tu plantilla general)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$resumen          = ControladorDashboard::obtenerResumen();
$ultimosProductos = ControladorDashboard::ultimosProductos(5);
$ultimosUsuarios  = ControladorDashboard::ultimosUsuarios(5);
?>
<div class="container-fluid">

  <!-- Título -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-gauge me-2 text-primary"></i> Panel de control
    </h1>
  </div>

  <p class="mb-4">
    Resumen general del sistema de inventario: usuarios, categorías y productos registrados.
  </p>

  <!-- Row: tarjetas principales -->
  <div class="row">

    <!-- Total productos -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
              Productos registrados
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?= (int)($resumen['total_productos'] ?? 0) ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-boxes-stacked fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stock total -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
              Stock total
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?= (int)($resumen['total_stock'] ?? 0) ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-box-open fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Categorías -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
              Categorías
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?= (int)($resumen['total_categorias'] ?? 0) ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Usuarios activos -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
              Usuarios activos
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?= (int)($resumen['usuarios_activos'] ?? 0) ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-users fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Row: tarjetas secundarias -->
  <div class="row mb-4">

    <!-- Productos sin stock -->
    <div class="col-md-6 mb-3">
      <div class="card border-left-danger shadow h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
              Productos sin stock
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?= (int)($resumen['productos_sin_stock'] ?? 0) ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-triangle-exclamation fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Usuarios inactivos -->
    <div class="col-md-6 mb-3">
      <div class="card border-left-secondary shadow h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
              Usuarios inactivos
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?= (int)($resumen['usuarios_inactivos'] ?? 0) ?>
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-user-slash fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Row: tablas de últimos registros -->
  <div class="row">

    <!-- Últimos productos -->
    <div class="col-lg-7 mb-4">
      <div class="card shadow">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-boxes-stacked me-1"></i> Últimos productos
          </h6>
          <a href="productos" class="small text-primary text-decoration-none">
            Ver todos
          </a>
        </div>
        <div class="card-body">
          <?php if (!empty($ultimosProductos)): ?>
            <div class="table-responsive">
              <table class="table table-sm align-middle mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Stock</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ultimosProductos as $p): 
                    $codigo = htmlspecialchars($p['codigo_producto'] ?? '', ENT_QUOTES, 'UTF-8');
                    $nombre = htmlspecialchars($p['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
                    $cat    = htmlspecialchars($p['nombre_categoria'] ?? '', ENT_QUOTES, 'UTF-8');
                    $precio = number_format((float)($p['precio'] ?? 0), 2, '.', ',');
                    $stock  = (int)($p['stock'] ?? 0);
                  ?>
                  <tr>
                    <td><span class="badge bg-light text-dark"><?= $codigo ?></span></td>
                    <td><?= $nombre ?></td>
                    <td><?= $cat ?></td>
                    <td class="text-end">$ <?= $precio ?></td>
                    <td class="text-end"><?= $stock ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="text-muted mb-0">Aún no hay productos registrados.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Últimos usuarios -->
    <div class="col-lg-5 mb-4">
      <div class="card shadow">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-users me-1"></i> Últimos usuarios
          </h6>
          <a href="usuarios" class="small text-primary text-decoration-none">
            Ver todos
          </a>
        </div>
        <div class="card-body">
          <?php if (!empty($ultimosUsuarios)): ?>
            <div class="list-group list-group-flush">
              <?php foreach ($ultimosUsuarios as $u): 
                $nombre = htmlspecialchars($u['nombre_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
                $email  = htmlspecialchars($u['email_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
                $perfil = htmlspecialchars($u['perfil_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
                $estado = (int)($u['estado_usuario'] ?? 0);
                $badge  = $estado === 1
                  ? '<span class="badge bg-success">Activo</span>'
                  : '<span class="badge bg-secondary">Inactivo</span>';
                $creado = htmlspecialchars($u['fyh_creacion_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
              ?>
              <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-bold"><?= $nombre ?></div>
                  <div class="small text-muted"><?= $email ?></div>
                  <div class="small text-muted text-capitalize">
                    <i class="fas fa-id-badge me-1"></i><?= $perfil ?> · <span><?= $creado ?></span>
                  </div>
                </div>
                <div><?= $badge ?></div>
              </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="text-muted mb-0">Aún no hay usuarios registrados.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

</div>
<!-- /.container-fluid -->
