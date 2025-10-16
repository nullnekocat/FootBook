<?php require('inc/head.inc.php'); ?>
<?php require('inc/comments.inc.php')?>
<body>
    <?php require('inc/navbar.inc.php'); ?>
    <div class="container my-4 center-maxw">
        <!-- Filters -->
        <section class="mb-4">
            <form class="row gx-2 align-items-center">
                <div class="col-5 col-auto">
                    <select class="form-select" name="worldcup-filter">
                        <option value="">Todas las copas</option>
                        <option value="2018">Rusia 2018</option>
                        <option value="2022">Qatar 2022</option>
                        <option value="2026">México/EUA/Canadá 2026</option>
                    </select>
                </div>
                <div class="col-5 col-auto">
                    <select class="form-select" name="order-by">
                        <option value="cronologico">Más reciente</option>
                        <option value="pais">País sede</option>
                        <option value="likes">Más likes</option>
                        <option value="comentarios">Más comentarios</option>
                    </select>
                </div>
                <div class="col-2 col-md-2 col-auto">
                    <button class="btn btn-success w-100" type="submit">Filtrar</button>
                </div>
            </form>
        </section>

        <?php require('inc/new_post.inc.php'); ?>

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
                        <p>Dummy: Texto de post en homepage</p>
                        <img src="img/demo<?php echo $i+1; ?>.jpg" class="img-fluid rounded mb-2" alt="Post image">
                        <div>
                            <button class="btn btn-sm btn-outline-success me-2">👍 5</button>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#commentsModal">💬 3</button>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </section>
    </div>
    <?php require('inc/footer.inc.php'); ?>
</body>