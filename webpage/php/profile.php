<?php require('inc/head.inc.php'); ?>
<?php require('inc/comments.inc.php')?>
<body>
<?php require('inc/navbar.inc.php'); ?>

<div class="container my-4 center-maxw">
    <!-- Cover & avatar -->
    <div class="card mb-4 shadow-sm">
        <div class="profile-cover" style="background: linear-gradient(90deg, #4776E6 0%, #8e54e9 100%); height: 180px; position: relative;">
            <button class="btn btn-dark btn-sm position-absolute end-0 top-0 m-3">Agregar foto de portada</button>
        </div>
        <div class="d-flex flex-column flex-md-row align-items-center px-4 mb-md-3">
            <div class="position-relative mt-2" style="width:120px;">
                <img src="../../img/default.jpg" alt="Profile Photo" class="rounded-circle border border-4 border-white shadow" width="120" height="120" style="object-fit:cover;">
            </div>
            <div class="ms-md-4 text-center text-md-start mt-3 mt-md-0 flex-grow-1">
                <h3 class="mb-0">Nombre de Usuario</h3>
                <span class="text-muted small">0 seguidores · 0 seguidos</span>
            </div>
            <div class="ms-md-auto d-flex gap-2 mt-2 mb-3">
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil"></i> Editar perfil
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation tabs -->
    <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab">Publicaciones</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Información</button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Posts tab -->
        <div class="tab-pane fade show active" id="posts" role="tabpanel">
            <div class="row">
                <!-- Details -->
                <!-- div class="col-md-4 mb-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <strong>Detalles</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <i class="bi bi-person-fill"></i>
                                <span id="fullname">Nombre completo</span>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-calendar-event"></i>
                                <span id="birthdate">Fecha de nacimiento</span>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-gender-ambiguous"></i>
                                <span id="gender">Género</span>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-flag"></i>
                                <span id="birthcountry">País de nacimiento</span>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-globe"></i>
                                <span id="nationality">Nacionalidad</span>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-envelope"></i>
                                <span id="email">Correo electrónico</span>
                            </div>
                        </div>
                    </div>
                </div -->

                <!-- Posts -->
                <div class="col">

                    <?php require('inc/new_post.inc.php'); ?>

                    <!-- User's posts -->
                    <div class="mt-4">
                        <h5 class="mb-3 text-black">Tus publicaciones</h5>
                        <!-- Static loop -->
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="../../img/default.jpg" class="rounded-circle me-2" width="36" height="36" alt="User">
                                    <div>
                                        <strong>Nombre de Usuario</strong>
                                        <span class="text-muted small">· 05/09/2025</span>
                                    </div>
                                </div>
                                <p>¡Mi primer post sobre los mundiales!</p>
                                <img src="../../img/demo1.jpg" class="img-fluid rounded mb-2" alt="Post image">
                                <div>
                                    <button class="btn btn-sm btn-outline-success me-2">👍 10</button>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#commentsModal">💬 3</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info tab -->
        <div class="tab-pane fade" id="info" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Información del perfil</strong>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nombre completo:</strong> <span id="fullname-info">Nombre completo</span></li>
                        <li class="list-group-item"><strong>Nombre de usuario:</strong> <span id="username-info">username</span></li>
                        <li class="list-group-item"><strong>Correo electrónico:</strong> <span id="email-info">example@email.com</span></li>
                        <li class="list-group-item"><strong>Fecha de nacimiento:</strong> <span id="birthdate-info">01/01/2000</span></li>
                        <li class="list-group-item"><strong>Género:</strong> <span id="gender-info">Masculino</span></li>
                        <li class="list-group-item"><strong>País de nacimiento:</strong> <span id="birthcountry-info">México</span></li>
                        <li class="list-group-item"><strong>Nacionalidad:</strong> <span id="nationality-info">Mexicana</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal profile -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content needs-validation" novalidate>
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Editar perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Edit profile -->
        <div class="mb-3">
            <label for="edit_fullname" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="edit_fullname" name="fullname" required>
        </div>
        <div class="mb-3">
            <label for="edit_username" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" id="edit_username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="edit_email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="edit_email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="edit_birthdate" class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="edit_birthdate" name="birthdate" required>
        </div>
        <div class="mb-3">
            <label for="edit_gender" class="form-label">Género</label>
            <select class="form-select" id="edit_gender" name="gender" required>
                <option value="">Selecciona tu género</option>
                <option value="F">Femenino</option>
                <option value="M">Masculino</option>
                <option value="O">Otro</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="edit_birthcountry" class="form-label">País de nacimiento</label>
            <input type="text" class="form-control" id="edit_birthcountry" name="birthcountry" required>
        </div>
        <div class="mb-3">
            <label for="edit_nationality" class="form-label">Nacionalidad</label>
            <input type="text" class="form-control" id="edit_nationality" name="nationality" required>
        </div>
        <div class="mb-3">
            <label for="edit_photo" class="form-label">Foto de perfil</label>
            <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="edit_password" class="form-label">Contraseña (opcional, para cambiar)</label>
            <input type="password" class="form-control" id="edit_password" name="password" minlength="8">
            <div class="form-text">Mínimo 8 caracteres, incluye mayúsculas, minúsculas, número y carácter especial.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<script>
// Bootstrap custom validation
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

<?php require('inc/footer.inc.php'); ?>
</body>