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
                <form id="category-form" class="row g-3 mb-4" autocomplete="off">
                    <div class="col-auto flex-fill">
                        <input type="text" class="form-control" id="category-name" name="category_name" placeholder="Nombre de la categoría" required>
                    </div>
                    <!-- Add -->
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F3F3F3">
                                <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                            </svg>
                        </button>
                    </div>
                </form>
                <h6 class="text-dark">Categorías existentes</h6>
                <ul id="category-list" class="list-group mb-4">

                </ul>
            </div>
            <!-- Post approving -->
            <div class="tab-pane fade" id="admin-posts">
                <h5 class="text-dark">Aprobar publicaciones de usuarios</h5>
                <div class="list-group mb-4" id="admin-posts-list">
                    <!-- Se llena dinámicamente desde posts.js -->
                </div>
            </div>

            <!--div class="tab-pane fade" id="admin-wikis">
                <h5 class="text-dark">Administrar Wikis de Mundiales</h5>
                <div class="row row-cols-2 row-cols-md-3 g-3" id="worldcupContainer"></div>
            </div-->

            <div class="tab-pane fade" id="admin-wikis">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-dark mb-0">Administrar Wikis de Mundiales</h5>
                    <button class="btn btn-success"
                        id="createNewWorldCupBtn"
                        data-bs-toggle="modal"
                        data-bs-target="#editWikiModal">
                        <i class="bi bi-plus-circle"></i> Crear nuevo mundial
                    </button>
                </div>

                <div class="row g-3" id="worldcupContainer">
                    <!-- Cards se cargan dinámicamente -->
                </div>
            </div>

            <!-- Modal reutilizable -->
            <!--div class="modal fade" id="editWikiModal" tabindex="-1" aria-labelledby="editWikiModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <form class="modal-content">
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title" id="editWikiModalLabel">Editar Wiki</h5>
                            <button type="button" class="btn btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" rows="4" name="description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Países participantes (separados por coma)</label>
                                <input class="form-control" name="countries">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Imagen principal</label>
                                <input type="file" class="form-control" name="main_image">
                                <img class="mt-3 rounded shadow-sm" name="main_image_preview" width="200" alt="Preview">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div-->

            <!-- Modal de Edición/Creación de Wiki -->
            <div class="modal fade" id="editWikiModal" tabindex="-1" aria-labelledby="editWikiModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <form class="modal-content">
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title" id="editWikiModalLabel">Editar Wiki</h5>
                            <button type="button" class="btn btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nombre del Mundial <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" placeholder="Ej: Mundial Rusia 2018" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">País anfitrión <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="country" placeholder="Ej: Rusia" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Año <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="year" placeholder="Ej: 2018" min="1930" max="2100" required>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Imagen principal</label>
                                        <input type="file" class="form-control" name="main_image" accept="image/*">
                                        <small class="text-muted">Formatos: JPG, PNG, WebP, GIF</small>
                                    </div>

                                    <div class="mb-3">
                                        <img name="main_image_preview"
                                            class="img-fluid rounded shadow-sm"
                                            style="max-height: 200px; width: 100%; object-fit: cover;"
                                            alt="Preview">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Descripción</label>
                                <textarea class="form-control" rows="6" name="description"
                                    placeholder="Escribe una descripción del mundial..."></textarea>
                                <small class="text-muted">Información histórica, campeón, datos relevantes, etc.</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

    <?php include __DIR__ . '/inc/footer.inc.php'; ?>
    <script type="module" src="/FootBook/views/js/admin/index.js"></script>
</body>