<?php 
require_once __DIR__ . '/../config.php';
include __DIR__ . '/inc/head.inc.php'; 
include __DIR__ . '/inc/navbar.inc.php'; 
?>
<body>

<div class="container my-4 center-maxw">
    <h3 class="mb-4">Panel de administración</h3>
    <!-- Nav buttons -->
    <div class="d-flex justify-content-between mb-4">
        <button class="btn btn-dark flex-fill mx-1 admin-tab-btn active" data-target="#admin-categories">
            <i class="bi bi-tags"></i> Categorías
        </button>
        <button class="btn btn-dark flex-fill mx-1 admin-tab-btn" data-target="#admin-posts">
            <i class="bi bi-check-circle"></i> Publicaciones
        </button>
        <button class="btn btn-dark flex-fill mx-1 admin-tab-btn" data-target="#admin-wikis">
            <i class="bi bi-journal-text"></i> Wikis
        </button>
        <button class="btn btn-dark flex-fill mx-1 admin-tab-btn" data-target="#admin-users">
            <i class="bi bi-people"></i> Usuarios
        </button>
    </div>
    <!-- Sections -->
    <div class="tab-content">
        <!-- Categories -->
        <div class="tab-pane fade show active" id="admin-categories">
            <h5 class="text-dark">Crear nueva categoría</h5>
            <form class="row g-3 mb-4" autocomplete="off">
                <div class="col-auto flex-fill">
                    <input type="text" class="form-control" id="category-name" name="category_name" placeholder="Nombre de la categoría" required>
                </div>
                <!-- Add -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F3F3F3"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
                    </button>
                </div>
            </form>
            <h6 class="text-dark">Categorías existentes</h6>
            <ul class="list-group mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Jugadas
                    <span>
                        <!-- Edit & Delete-->
                        <button class="btn btn-sm btn-outline-warning me-1">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                        </button>
                    </span>
                </li>
            </ul>
        </div>
        <!-- Post approving -->
        <div class="tab-pane fade" id="admin-posts">
            <h5 class="text-dark">Aprobar publicaciones de usuarios</h5>
            <div class="list-group mb-4">
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Usuario123 <span class="badge bg-info">Jugadas</span> <span class="badge bg-secondary">Qatar 2022</span></h6>
                            <small>05/09/2025</small>
                            <p class="mb-1">Dummy: Texto de post en las herramientas de administrador</p>
                            <img src="img/demo1.jpg" class="img-fluid rounded mb-2" style="max-width:220px;" alt="Post image">
                        </div>
                        <div class="ms-3">
                            <button class="btn btn-success btn-sm mb-2 w-100"> 
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>
                            </button>
                            <button class="btn btn-danger btn-sm w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wiki admin -->
        <div class="tab-pane fade" id="admin-wikis">
            <h5 class="text-dark">Administrar Wikis de Mundiales</h5>
            <div class="row row-cols-2 row-cols-md-3 g-3">
                <div class="col">
                    <div class="card h-100">
                        <img src="/Footbook/img/russia2018.png" class="card-img-top" alt="Rusia 2018">
                        <div class="card-body">
                            <h6 class="card-title mb-0">Rusia 2018</h6>
                            <button class="btn btn-outline-success btn-sm mt-2 w-100" data-bs-toggle="modal" data-bs-target="#editWikiModal2018">
                                <i class="bi bi-pencil"></i> Editar wiki
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="/Footbook/img/qatar2022.png" class="card-img-top" alt="Qatar 2022">
                        <div class="card-body">
                            <h6 class="card-title mb-0">Quatar 2022</h6>
                            <button class="btn btn-outline-success btn-sm mt-2 w-100" data-bs-toggle="modal" data-bs-target="#editWikiModal2018">
                                <i class="bi bi-pencil"></i> Editar wiki
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal de edición de wiki de ejemplo -->
            <div class="modal fade" id="editWikiModal2018" tabindex="-1" aria-labelledby="editWikiModal2018Label" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <form class="modal-content">
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title" id="editWikiModal2018Label">Editar Wiki - Rusia 2018</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" rows="4" name="description">La Copa Mundial de la FIFA 2018 se celebró en Rusia...</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Videos de jugadas (URLs, uno por línea)</label>
                                <textarea class="form-control" rows="2" name="videos">https://youtube.com/demo1</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Países participantes (separados por coma)</label>
                                <input class="form-control" name="countries" value="Rusia, Francia, Croacia, Brasil, Alemania">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jugadores destacados</label>
                                <input class="form-control" name="players" value="Mbappé, Modric, Kane">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Partidos importantes</label>
                                <textarea class="form-control" rows="2" name="matches">Francia 4-2 Croacia (Final)</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Entrevistas (opcional)</label>
                                <textarea class="form-control" rows="2" name="interviews"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Juegos / Trivias (opcional)</label>
                                <textarea class="form-control" rows="2" name="games"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Imagen principal</label>
                                <input type="file" class="form-control" name="main_image">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Users -->
        <div class="tab-pane fade" id="admin-users">
            <h5 class="text-dark">Usuarios registrados</h5>
            <div class="table-responsive">
                <table class="table table-striped" id="admin-users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filled dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>views/js/admin.js"> </script>

<?php include __DIR__ . '/inc/footer.inc.php'; ?>
</body>