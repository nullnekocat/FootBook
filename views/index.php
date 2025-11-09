<?php 
require_once __DIR__ . '/../config.php';
include __DIR__ . '/inc/head.inc.php'; 
include __DIR__ . '/inc/navbar.inc.php'; 
require('inc/comments.inc.php');
?>
<body>
    <div class="container my-4 center-maxw">
        <!-- Filters -->
        <section class="mb-4">
            <form class="row gx-2 align-items-center" id="feed-filters-form">
                <div class="col-5 col-auto">
                    <select class="form-select" name="worldcup-filter" id ="filter-worldcup">
                        <option value="">Todas las copas</option>
                        <!-- Se llena dinamicamente-->
                    </select>
                </div>
                <div class="col-5 col-auto">
                    <select class="form-select" name="order-by" id="filter-order">
                        <option value="cronologico">Más reciente</option>
                        <option value="pais">País sede</option>
                        <option value="likes">Más likes</option>
                        <option value="comentarios">Más comentarios</option>
                    </select>
                </div>
                <div class="col-2 col-auto">
                    <button class="btn btn-success w-100" type="submit" id="filter-apply">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F3F3F3"><path d="M440-160q-17 0-28.5-11.5T400-200v-240L168-736q-15-20-4.5-42t36.5-22h560q26 0 36.5 22t-4.5 42L560-440v240q0 17-11.5 28.5T520-160h-80Zm40-308 198-252H282l198 252Zm0 0Z"/></svg>
                    </button>
                </div>
            </form>
        </section>
        <?php include __DIR__ . '/inc/new_post.inc.php'; ?>
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
    <?php require('inc/footer.inc.php'); ?>
    
    <script type="module" src="/FootBook/views/js/home.js"></script>
</body>