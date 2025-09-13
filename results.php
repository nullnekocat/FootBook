<?php require('inc/head.inc.php'); ?>
<?php require('inc/comments.inc.php')?>
<body>
    <?php require('inc/navbar.inc.php'); ?>

    <div class="container my-4">
        <!--h3 class="mb-4">Resultados</h3-->
        <!-- Active filters SM-XS -->
        <div class="mb-3 d-block d-md-none">
            <span class="badge bg-success me-1">Categoría:
                <?php echo htmlspecialchars($_GET['category'] ?? 'Todas'); ?>
            </span>
            <span class="badge bg-secondary me-1">Año:
                <?php echo htmlspecialchars($_GET['year'] ?? 'Todos'); ?>
            </span>
            <span class="badge bg-info me-1">País:
                <?php echo htmlspecialchars($_GET['country'] ?? 'Todos'); ?>
            </span>
            <span class="badge bg-success me-1">Usuario:
                <?php echo htmlspecialchars($_GET['user'] ?? 'Todos'); ?>
            </span>
            <span class="badge bg-dark me-1">Orden:
                <?php echo htmlspecialchars($_GET['order'] ?? 'Más reciente'); ?>
            </span>
        </div>

        <div class="row">
            <!-- Filters -->
            <div class="col-md-4">
                <div class="sticky-top" style="top: 20px">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light"><strong>Filtros de búsqueda</strong></div>
                        <div class="card-body">
                            <form method="get" action="results.php" id="sideFilterForm">
                                <div class="mb-2">
                                    <label for="category" class="form-label mb-0">Categoría</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Todas</option>
                                        <option value="Jugadas">Jugadas</option>
                                        <option value="Entrevistas">Entrevistas</option>
                                        <option value="Partidos">Partidos</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="year" class="form-label mb-0">Año de mundial</label>
                                    <input type="number" class="form-control" id="year" name="year" min="1930"
                                        max="2026" placeholder="Ej: 2014">
                                </div>
                                <div class="mb-2">
                                    <label for="country" class="form-label mb-0">País sede</label>
                                    <input type="text" class="form-control" id="country" name="country"
                                        placeholder="Ej: Brasil">
                                </div>
                                <div class="mb-2">
                                    <label for="user" class="form-label mb-0">Usuario</label>
                                    <input type="text" class="form-control" id="user" name="user"
                                        placeholder="Nombre de usuario">
                                </div>
                                <div class="mb-2">
                                    <label for="order" class="form-label mb-0">Ordenar por</label>
                                    <select class="form-select" id="order" name="order">
                                        <option value="recent">Más reciente</option>
                                        <option value="likes">Más likes</option>
                                        <option value="comments">Más comentarios</option>
                                    </select>
                                </div>
                                <button class="btn btn-success w-100 mt-2" type="submit">Aplicar filtros</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Results -->
            <div class="col-md-8 left-maxw">
                <!-- Static loop -->
                <?php for($i=0;$i<3;$i++): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <img src="img/user<?php echo $i+1; ?>.jpg" class="rounded-circle me-2" width="40"
                                height="40" alt="User">
                            <div>
                                <strong>Usuario
                                    <?php echo $i+1; ?>
                                </strong>
                                <span class="text-muted small">en Brasil 2014 · 10/07/2014</span>
                                <span class="badge bg-secondary ms-2">Entrevistas</span>
                            </div>
                        </div>
                        <p>¡Gran entrevista con un jugador legendario!</p>
                        <img src="img/demo<?php echo $i+2; ?>.jpg" class="img-fluid rounded mb-2" alt="Post image">
                        <div>
                            <button class="btn btn-sm btn-outline-success me-2">👍 15</button>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#commentsModal">💬 3</button>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>

                <!-- No results -->
                <?php if(false): // Change ?>
                <div class="alert alert-info text-center">
                    No se encontraron publicaciones con los filtros seleccionados.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require('inc/footer.inc.php'); ?>
</body>