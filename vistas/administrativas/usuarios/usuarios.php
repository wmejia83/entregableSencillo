<?php
// usuarios.php

require_once "Controladores/ControladorUsuarios.php";

// Procesar cualquier POST (crear/editar/cambiar estado/eliminar)
ControladorUsuarios::manejarPostUsuarios();

// Obtener usuarios
$usuarios = ControladorUsuarios::listarUsuarios();
?>
<div class="container-fluid">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-users me-2 text-primary"></i> Usuarios
    </h1>

    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUsuario">
      <i class="fas fa-user-plus me-1"></i> Nuevo usuario
    </button>
  </div>

  <p class="mb-4">
    Administra los usuarios del sistema, sus perfiles y estados de acceso.
  </p>

  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-list me-2"></i> Listado de usuarios
      </h6>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered align-middle tablas" id="tablaUsuarios" width="100%" cellspacing="0">
          <thead class="thead-light">
            <tr>
              <th style="width: 60px;">#</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Perfil</th>
              <th>Estado</th>
              <th>Último login</th>
              <th style="width: 200px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($usuarios)) {
              $i = 1;
              foreach ($usuarios as $u) {
                $id     = (int)($u['id_usuario'] ?? 0);
                $nombre = htmlspecialchars($u['nombre_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
                $email  = htmlspecialchars($u['email_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
                $perfil = htmlspecialchars($u['perfil_usuario'] ?? '', ENT_QUOTES, 'UTF-8');
                $estado = (int)($u['estado_usuario'] ?? 0);
                $ultimo = $u['ultimo_login'] ?? '';

                $badgeEstado = $estado === 1
                  ? '<span class="badge bg-success">Activo</span>'
                  : '<span class="badge bg-secondary">Inactivo</span>';

                echo '<tr data-id="'.$id.'"
                          data-nombre="'.$nombre.'"
                          data-email="'.$email.'"
                          data-perfil="'.$perfil.'"
                          data-estado="'.$estado.'">
                        <td class="text-center">'.$i.'</td>
                        <td>'.$nombre.'</td>
                        <td>'.$email.'</td>
                        <td>'.ucfirst($perfil).'</td>
                        <td class="text-center">'.$badgeEstado.'</td>
                        <td>'.htmlspecialchars($ultimo, ENT_QUOTES, 'UTF-8').'</td>
                        <td class="text-nowrap text-center">
                          <button class="btn btn-outline-primary btn-sm me-1"
                                  data-bs-toggle="tooltip"
                                  title="Editar usuario"
                                  data-action="edit">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-outline-warning btn-sm me-1"
                                  data-bs-toggle="tooltip"
                                  title="Cambiar estado"
                                  data-action="toggle-estado">
                            <i class="fas fa-toggle-on"></i>
                          </button>
                          <button class="btn btn-outline-danger btn-sm"
                                  data-bs-toggle="tooltip"
                                  title="Eliminar usuario"
                                  data-action="delete">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>
                      </tr>';
                $i++;
              }
            } else {
              echo '<tr>
                      <td colspan="7" class="text-center text-muted">
                        No hay usuarios registrados.
                      </td>
                    </tr>';
            }
            ?>
          </tbody>
          <tfoot class="thead-light">
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Perfil</th>
              <th>Estado</th>
              <th>Último login</th>
              <th>Acciones</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- ========== MODAL: CREAR USUARIO ========== -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formUsuario" class="modal-content" method="post" autocomplete="off">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUsuarioLabel">
          <i class="fas fa-user-plus me-1 text-primary"></i> Nuevo usuario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label for="inputNombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="inputNombre" name="nombre" required>
        </div>
        <div class="mb-3">
          <label for="inputEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="inputEmail" name="email" required>
        </div>
        <div class="mb-3">
          <label for="inputPassword" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="inputPassword" name="password" required>
          <small class="form-text text-muted">Mínimo 8 caracteres, combina letras y números.</small>
        </div>
        <div class="mb-3">
          <label for="selectPerfil" class="form-label">Perfil</label>
          <select id="selectPerfil" name="perfil" class="form-select" required>
            <option value="administrador">Administrador</option>
            <option value="usuario">Usuario</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ========== MODAL: EDITAR USUARIO ========== -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditarUsuario" class="modal-content" method="post" autocomplete="off">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarUsuarioLabel">
          <i class="fas fa-user-edit me-1 text-primary"></i> Editar usuario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="id_usuario" id="editIdUsuario">

        <div class="mb-3">
          <label for="editNombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="editNombre" name="nombre" required>
        </div>
        <div class="mb-3">
          <label for="editEmail" class="form-label">Email</label>
          <input type="email" class="form-control" id="editEmail" name="email" required>
        </div>
        <div class="mb-3">
          <label for="editPerfil" class="form-label">Perfil</label>
          <select id="editPerfil" name="perfil" class="form-select" required>
            <option value="administrador">Administrador</option>
            <option value="usuario">Usuario</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="editEstado" class="form-label">Estado</label>
          <select id="editEstado" name="estado" class="form-select">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Actualizar
        </button>
      </div>
    </form>
  </div>
</div>

<!-- FORM OCULTO CAMBIO DE ESTADO -->
<form id="formEstadoUsuario" method="post" class="d-none">
  <input type="hidden" name="id_usuario_estado" id="inputUsuarioEstado">
  <input type="hidden" name="nuevo_estado" id="inputNuevoEstado">
</form>

<!-- FORM OCULTO ELIMINAR USUARIO -->
<form id="formEliminarUsuario" method="post" class="d-none">
  <input type="hidden" name="id_usuario_eliminar" id="inputUsuarioEliminar">
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.forEach(function (el) { new bootstrap.Tooltip(el); });

  const tabla = document.getElementById('tablaUsuarios');

  const formEstado       = document.getElementById('formEstadoUsuario');
  const inputIdEstado    = document.getElementById('inputUsuarioEstado');
  const inputNuevoEstado = document.getElementById('inputNuevoEstado');

  const formEliminar     = document.getElementById('formEliminarUsuario');
  const inputEliminar    = document.getElementById('inputUsuarioEliminar');

  // Un solo listener para: editar / cambiar estado / eliminar
  tabla?.addEventListener('click', function (e) {
    const btnEdit   = e.target.closest('button[data-action="edit"]');
    const btnToggle = e.target.closest('button[data-action="toggle-estado"]');
    const btnDelete = e.target.closest('button[data-action="delete"]');

    // --- Editar usuario ---
    if (btnEdit) {
      const tr = btnEdit.closest('tr');
      const id     = tr.getAttribute('data-id');
      const nombre = tr.getAttribute('data-nombre');
      const email  = tr.getAttribute('data-email');
      const perfil = tr.getAttribute('data-perfil');
      const estado = tr.getAttribute('data-estado');

      document.getElementById('editIdUsuario').value = id;
      document.getElementById('editNombre').value    = nombre;
      document.getElementById('editEmail').value     = email;
      document.getElementById('editPerfil').value    = perfil;
      document.getElementById('editEstado').value    = estado;

      const modalEl = document.getElementById('modalEditarUsuario');
      const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
      return;
    }

    // --- Cambiar estado (activar/desactivar) ---
    if (btnToggle) {
      const tr     = btnToggle.closest('tr');
      const id     = tr.getAttribute('data-id');
      const nombre = tr.getAttribute('data-nombre');
      const estado = parseInt(tr.getAttribute('data-estado'), 10);

      const nuevoEstado = estado === 1 ? 0 : 1;
      const textoAccion = nuevoEstado === 1 ? 'activar' : 'desactivar';

      if (window.Swal) {
        Swal.fire({
          title: '¿Cambiar estado?',
          text: `Se va a ${textoAccion} al usuario "${nombre}".`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Sí, continuar',
          cancelButtonText: 'Cancelar'
        }).then((r) => {
          if (!r.isConfirmed) return;
          inputIdEstado.value    = id;
          inputNuevoEstado.value = nuevoEstado;
          formEstado.submit();
        });
      } else {
        if (confirm(`¿Seguro que deseas ${textoAccion} al usuario "${nombre}"?`)) {
          inputIdEstado.value    = id;
          inputNuevoEstado.value = nuevoEstado;
          formEstado.submit();
        }
      }
      return;
    }

    // --- Eliminar usuario (hard delete) ---
    if (btnDelete) {
      const tr     = btnDelete.closest('tr');
      const id     = tr.getAttribute('data-id');
      const nombre = tr.getAttribute('data-nombre');

      if (window.Swal) {
        Swal.fire({
          title: '¿Eliminar usuario?',
          text: `Se eliminará al usuario "${nombre}". Esta acción no se puede deshacer.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((r) => {
          if (!r.isConfirmed) return;
          inputEliminar.value = id;
          formEliminar.submit();
        });
      } else {
        if (confirm(`¿Seguro que deseas eliminar al usuario "${nombre}"? Esta acción no se puede deshacer.`)) {
          inputEliminar.value = id;
          formEliminar.submit();
        }
      }
    }
  });
});
</script>
