<?php
require_once __DIR__ . '/../config.php';
include __DIR__ . '/inc/head.inc.php'; 
include __DIR__ . '/inc/navbar.inc.php'; 
?>
<?php require('inc/comments.inc.php')?>

<body>

<div class="container my-4 center-maxw">
    <!-- Cover & avatar -->
    <div class="card mb-4 shadow-sm">
        <div class="profile-cover" style="background: linear-gradient(90deg, #4776E6 0%, #8e54e9 100%); height: 180px; position: relative;">
            <button class="btn btn-dark btn-sm position-absolute end-0 top-0 m-3">Agregar foto de portada</button>
        </div>
        <div class="d-flex flex-column flex-md-row align-items-center px-4 mb-md-3">
            <div class="position-relative mt-2" style="width:120px;">
                <img
                id="profileAvatar" 
                src="img/default.jpg" 
                alt="Profile Photo" class="rounded-circle border border-4 border-white shadow" 
                width="120" height="120" style="object-fit:cover;">
            </div>
            <div class="ms-md-4 text-center text-md-start mt-3 mt-md-0 flex-grow-1">
                <h3 id="profileUser" class="mb-0">Nombre de Usuario</h3>
                <span class="text-muted small">0 seguidores · 0 seguidos</span>
            </div>
            <!-- Edit profile -->
            <div class="ms-md-auto d-flex gap-2 mt-2 mb-3">
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h357l-80 80H200v560h560v-278l80-80v358q0 33-23.5 56.5T760-120H200Zm280-360ZM360-360v-170l367-367q12-12 27-18t30-6q16 0 30.5 6t26.5 18l56 57q11 12 17 26.5t6 29.5q0 15-5.5 29.5T897-728L530-360H360Zm481-424-56-56 56 56ZM440-440h56l232-232-28-28-29-28-231 231v57Zm260-260-29-28 29 28 28 28-28-28Z"/></svg>
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
                <!-- Posts -->
                <div class="col">
                    <?php require('inc/new_post.inc.php'); ?>

                    <!-- User's posts -->
                    <div class="mt-4">
                        <h5 class="mb-3 text-black">Tus publicaciones</h5>
                        <!-- Feed -->
                        <section class="mb-4" id="feed">
                            <!-- contenedor donde se insertarán las cards -->
                            <div id="feed-list"></div>

                            <!-- loader / fallback -->
                            <div id="feed-loading" class="text-center small text-muted my-3 d-none">
                                Cargando publicaciones...
                            </div>

                            <!-- botón de apoyo (por si no quieres usar scroll infinito) -->
                            <button id="feed-load-more" class="btn btn-outline-secondary w-100 d-none">
                                Cargar más
                            </button>
                        </section>
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
                        <li class="list-group-item"><strong>Nombre completo:</strong> <span id="fullname">Nombre completo</span></li>
                        <li class="list-group-item"><strong>Nombre de usuario:</strong> <span id="username">username</span></li>
                        <li class="list-group-item"><strong>Correo electrónico:</strong> <span id="email">example@email.com</span></li>
                        <li class="list-group-item"><strong>Fecha de nacimiento:</strong> <span id="birthday">01/01/2000</span></li>
                        <li class="list-group-item"><strong>Género:</strong> <span id="gender">Masculino</span></li>
                        <li class="list-group-item"><strong>País de nacimiento:</strong> <span id="birth_country">México</span></li>
                        <li class="list-group-item"><strong>Nacionalidad:</strong> <span id="country">Mexicana</span></li>

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
<?php require('inc/footer.inc.php'); ?>
</body>
<script type="module" src="/FootBook/views/js/profile.js"></script>