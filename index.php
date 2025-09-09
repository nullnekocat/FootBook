<?php require('inc/head.inc.php'); ?>
<body>
    <?php require('inc/navbar.inc.php'); ?>
    
    <div class="container my-4">
        <!-- World cup carousel -->
        <section class="mb-4">
            <h3 class="mb-3">Mundiales</h3>
            <div class="row row-cols-2 row-cols-md-5 g-2">
                <!-- Ejemplo estático -->
                <div class="col">
                    <a href="wiki.php?worldcup=2018" class="text-decoration-none">
                        <div class="card shadow-sm h-100">
                            <img src="img/russia2018.png" class="card-img-top" alt="Rusia 2018">
                            <div class="card-body p-2 text-center">
                                <small>Rusia 2018</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="text-decoration-none">
                        <div class="card shadow-sm h-100">
                            <img src="img/qatar2022.png" class="card-img-top" alt="Qatar 2022">
                            <div class="card-body p-2 text-center">
                                <small>Qatar 2022</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="text-decoration-none">
                        <div class="card shadow-sm h-100">
                            <img src="img/mx-usa-ca2026.png" class="card-img-top" alt="México/EUA/Canadá 2026">
                            <div class="card-body p-2 text-center">
                                <small>México/EUA/Canadá 2026</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Filters -->
        <section class="mb-4">
            <form class="row gx-2 align-items-center">
                <div class="col-md-3">
                    <select class="form-select" name="worldcup-filter">
                        <option value="">Todos los mundiales</option>
                        <option value="2018">Rusia 2018</option>
                        <option value="2022">Qatar 2022</option>
                        <option value="2026">México/EUA/Canadá 2026</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="order-by">
                        <option value="cronologico">Orden cronológico</option>
                        <option value="pais">País sede</option>
                        <option value="likes">Más likes</option>
                        <option value="comentarios">Más comentarios</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Filtrar</button>
                </div>
            </form>
        </section>

        <!-- User post -->
        <? //php if(isset($_SESSION['user_id'])): ?>
        <section class="mb-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <img src="img/<?php echo $_SESSION['user_photo'] ?? 'default.jpg'; ?>" class="rounded-circle me-3" width="48" height="48" alt="User">
                    <button class="btn btn-light flex-grow-1 text-start" data-bs-toggle="modal" data-bs-target="#modal-post">
                        ¿Qué quieres compartir sobre los mundiales?
                    </button>
                </div>
            </div>
        </section>
        <?//php endif; ?>

        <!-- Feed -->
        <section class="mb-4">
            <!-- Posts loop -->
            <?php for($i = 0; $i < 4; $i++): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex mb-2 align-items-center">
                            <img src="img/user<?php echo $i+1; ?>.jpg" class="rounded-circle me-2" width="40" height="40" alt="User">
                            <div>
                                <strong>Usuario <?php echo $i+1; ?></strong>
                                <span class="text-muted small">en Qatar 2022 · 05/09/2025</span>
                                <span class="badge bg-secondary ms-2">Jugadas</span>
                            </div>
                        </div>
                        <p>¡Qué jugadón en la final!</p>
                        <img src="img/demo<?php echo $i+1; ?>.jpg" class="img-fluid rounded mb-2" alt="Post image">
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2">👍 5</button>
                            <button class="btn btn-sm btn-outline-secondary">💬 2</button>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </section>
    </div>

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
    <?php require('inc/footer.inc.php'); ?>
</body>