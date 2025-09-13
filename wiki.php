<?php require('inc/head.inc.php'); ?>
<body>
    <?php require('inc/navbar.inc.php'); ?>
    <!-- World cup cards -->
    <div class="container my-4">
        <section class="mb-4">
            <h3 class="mb-3">Mundiales</h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
                <!-- 2018 -->
                <div class="col">
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modal2018">
                        <div class="card shadow-sm h-100">
                            <img src="img/russia2018.png" class="card-img-top" alt="Rusia 2018">
                            <div class="card-body p-2 text-center">
                                <small>Rusia 2018</small>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Modal Rusia 2018 -->
                <div class="modal fade" id="modal2018" tabindex="-1" aria-labelledby="modal2018Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header bg-secondary text-white">
                        <h4 class="modal-title" id="modal2018Label">
                          <img src="img/russia2018.png" alt="Rusia 2018" width="48" class="me-2 rounded">
                          Copa Mundial de la FIFA Rusia 2018
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img src="img/russia2018.png" alt="Rusia 2018" class="img-fluid rounded shadow" style="max-width: 180px;">
                            <p class="mt-2"><span class="badge bg-secondary">2018</span></p>
                            <p><strong>Sede:</strong> Rusia</p>
                          </div>
                          <div class="col-md-8">
                            <h5>Descripción</h5>
                            <p>
                              La Copa Mundial de la FIFA 2018 se celebró en Rusia del 14 de junio al 15 de julio de 2018. Fue la 21ª edición del torneo y la primera vez que se realizó en Europa Oriental. Francia se coronó campeón tras vencer a Croacia 4-2 en la final.
                            </p>
                            <ul>
                              <li><strong>Equipos participantes:</strong> 32</li>
                              <li><strong>Campeón:</strong> Francia</li>
                              <li><strong>Subcampeón:</strong> Croacia</li>
                              <li><strong>Goleador:</strong> Harry Kane (Inglaterra)</li>
                            </ul>
                            <blockquote class="blockquote">
                              "Un torneo lleno de sorpresas, goles y nuevas estrellas."
                            </blockquote>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <small class="text-muted">Fuente: Wikipedia, FIFA</small>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- 2022 -->
                <div class="col">
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modal2022">
                        <div class="card shadow-sm h-100">
                            <img src="img/qatar2022.png" class="card-img-top" alt="Qatar 2022">
                            <div class="card-body p-2 text-center">
                                <small>Qatar 2022</small>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Modal Qatar 2022 -->
                <div class="modal fade" id="modal2022" tabindex="-1" aria-labelledby="modal2022Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header bg-secondary text-white">
                        <h4 class="modal-title" id="modal2022Label">
                          <img src="img/qatar2022.png" alt="Qatar 2022" width="48" class="me-2 rounded">
                          Copa Mundial de la FIFA Qatar 2022
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img src="img/qatar2022.png" alt="Qatar 2022" class="img-fluid rounded shadow" style="max-width: 180px;">
                            <p class="mt-2"><span class="badge bg-secondary">2022</span></p>
                            <p><strong>Sede:</strong> Qatar</p>
                          </div>
                          <div class="col-md-8">
                            <h5>Descripción</h5>
                            <p>
                              La Copa Mundial de la FIFA 2022 se llevó a cabo en Qatar del 20 de noviembre al 18 de diciembre. Fue el primer mundial celebrado en Medio Oriente y el primero en invierno boreal. Argentina se consagró campeona tras vencer a Francia en penales.
                            </p>
                            <ul>
                              <li><strong>Equipos participantes:</strong> 32</li>
                              <li><strong>Campeón:</strong> Argentina</li>
                              <li><strong>Subcampeón:</strong> Francia</li>
                              <li><strong>Goleador:</strong> Kylian Mbappé (Francia)</li>
                            </ul>
                            <blockquote class="blockquote">
                              "Una final épica que quedará en la historia del fútbol."
                            </blockquote>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <small class="text-muted">Fuente: Wikipedia, FIFA</small>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- 2026 -->
                <div class="col">
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modal2026">
                        <div class="card shadow-sm h-100">
                            <img src="img/mx-usa-ca2026.png" class="card-img-top" alt="México/EUA/Canadá 2026">
                            <div class="card-body p-2 text-center">
                                <small>México/EUA/Canadá 2026</small>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Modal México/EUA/Canadá 2026 -->
                <div class="modal fade" id="modal2026" tabindex="-1" aria-labelledby="modal2026Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header bg-secondary text-white">
                        <h4 class="modal-title" id="modal2026Label">
                          <img src="img/mx-usa-ca2026.png" alt="México/EUA/Canadá 2026" width="48" class="me-2 rounded">
                          Copa Mundial de la FIFA México/EUA/Canadá 2026
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img src="img/mx-usa-ca2026.png" alt="México/EUA/Canadá 2026" class="img-fluid rounded shadow" style="max-width: 180px;">
                            <p class="mt-2"><span class="badge bg-secondary">2026</span></p>
                            <p><strong>Sede:</strong> México, EUA, Canadá</p>
                          </div>
                          <div class="col-md-8">
                            <h5>Descripción</h5>
                            <p>
                              La Copa Mundial de la FIFA 2026 será la 23ª edición del torneo y la primera en contar con 48 equipos participantes. Se celebrará en tres países: México, Estados Unidos y Canadá. Será la primera vez que el torneo se dispute en tres naciones diferentes y promete ser la edición más grande en la historia.
                            </p>
                            <ul>
                              <li><strong>Equipos participantes:</strong> 48</li>
                              <li><strong>Campeón:</strong> Por definirse</li>
                              <li><strong>Subcampeón:</strong> Por definirse</li>
                            </ul>
                            <blockquote class="blockquote">
                              "Un mundial histórico para todo el continente americano."
                            </blockquote>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <small class="text-muted">Fuente: Wikipedia, FIFA</small>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>

            </div>
        </section>
    </div>
</body>
<?php require('inc/footer.inc.php'); ?>