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
                <div class="col-2 col-auto">
                    <button class="btn btn-success w-100" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F3F3F3"><path d="M440-160q-17 0-28.5-11.5T400-200v-240L168-736q-15-20-4.5-42t36.5-22h560q26 0 36.5 22t-4.5 42L560-440v240q0 17-11.5 28.5T520-160h-80Zm40-308 198-252H282l198 252Zm0 0Z"/></svg>
                    </button>
                </div>
            </form>
        </section>
        <?php include __DIR__ . '/inc/new_post.inc.php'; ?>
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
                            <button class="btn btn-sm btn-outline-success me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M720-120H280v-520l280-280 50 50q7 7 11.5 19t4.5 23v14l-44 174h258q32 0 56 24t24 56v80q0 7-2 15t-4 15L794-168q-9 20-30 34t-44 14Zm-360-80h360l120-280v-80H480l54-220-174 174v406Zm0-406v406-406Zm-80-34v80H160v360h120v80H80v-520h200Z"/></svg>
                                5
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#commentsModal">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M880-80 720-240H320q-33 0-56.5-23.5T240-320v-40h440q33 0 56.5-23.5T760-440v-280h40q33 0 56.5 23.5T880-640v560ZM160-473l47-47h393v-280H160v327ZM80-280v-520q0-33 23.5-56.5T160-880h440q33 0 56.5 23.5T680-800v280q0 33-23.5 56.5T600-440H240L80-280Zm80-240v-280 280Z"/></svg>
                                3
                            </button>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </section>
    </div>
    <?php require('inc/footer.inc.php'); ?>
    
    <script type="module" src="/FootBook/views/js/home.js"></script>
</body>