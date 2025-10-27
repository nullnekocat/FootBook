<? //php if(isset($_SESSION['user_id'])): ?>
<section class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center">
            <img src="/img/<?php echo $_SESSION['user_photo'] ?? 'default.jpg'; ?>" class="rounded-circle me-3" width="48" height="48" alt="User">
            <button class="btn btn-light flex-grow-1 text-start" data-bs-toggle="modal" data-bs-target="#modal-post">
                ¿Qué quieres compartir sobre los mundiales?
            </button>
        </div>
    </div>
</section>
<?//php endif; ?>

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
            <select class="form-select" name="categoria" id="categoria" required>
            <option value="Jugadas">Jugadas</option>
            <option value="Entrevistas">Entrevistas</option>
            <option value="Partidos">Partidos</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="worldcup" class="form-label">Mundial</label>
            <select class="form-select" name="worldcup" id="worldcup" required>
            <option value="2018">Rusia 2018</option>
            <option value="2022">Qatar 2022</option>
            <option value="2026">México/EUA/Canadá 2026</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="seleccion" class="form-label">Selección (opcional)</label>
            <input type="text" class="form-control" name="seleccion" id="seleccion" placeholder="Ej. Argentina">
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