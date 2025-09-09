<?php require('inc/head.inc.php'); ?>
<body>
    <?php require('inc/navbar.inc.php'); ?>
    <<!-- World cup cards -->
    <div class="container my-4">
        <section class="mb-4">
            <h3 class="mb-3">Mundiales</h3>
            <div class="row row-cols-md-5 g-2">
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
    </div>
</body>
<?php require('inc/footer.inc.php'); ?>