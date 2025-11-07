
<section class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center" data-new-post>
            <img id="avatarImg" src="img/default.jpg" class="rounded-circle me-3" width="48" height="48" alt="User">
            <button class="btn btn-light flex-grow-1 text-start" data-bs-toggle="modal" data-bs-target="#modal-post">
                ¿Qué quieres compartir sobre los mundiales?
            </button>
        </div>
    </div>
</section>

<!-- Modal post -->
<div class="modal fade" id="modal-post" tabindex="-1" aria-labelledby="modal-post-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="post_process.php" enctype="multipart/form-data">
        <div class="modal-header">
        <h5 class="modal-title" id="modal-post-label">Crear nueva publicación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <select class="form-select" name="category_id" id="post-category" required>
                <option value="">Cargando categorías...</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="worldcup" class="form-label">Mundial</label>
            <select class="form-select" name="worldcup_id" id="post-worldcup" required>
            <option value="">Cargando mundiales...</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="seleccion" class="form-label">Selección (opcional)</label>
            <input type="text" class="form-control" name="seleccion" id="seleccion" placeholder="Ej. Argentina">
        </div>
        <div class="mb-3">
            <label for="titulo" class="form-label">Titulo</label>
            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ej. Mi experiencia en el Mundial" maxlength="64" required>
        </div>
        <div class="mb-3">
            <label for="contenido" class="form-label">Contenido</label>
            <textarea class="form-control" name="contenido" id="contenido" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="media" class="form-label">Imagen o video</label>
            <input class="form-control" type="file" name="media" id="media" accept="image/*,video/*">
        </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Publicar</button>
        </div>
    </form>
    </div>
</div>