<?php require('inc/head.inc.php'); ?>
<body>
<?php require('inc/navbar.inc.php'); ?>
<div class="container my-4">
    <section class="mb-4">
        <h3 class="mb-3">Mundiales</h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
            <!-- Rusia 2018 -->
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
            <!-- Quatar 2022 -->
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
            <!-- MX/USA/CA 2026 -->
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
        </div>
    </section>
</div>

<!-- Modal Rusia 2018 -->
<div class="modal fade" id="modal2018" tabindex="-1" aria-labelledby="modal2018Label" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-dark">
        <h4 class="modal-title text-light" id="modal2018Label">
          <img src="img/russia2018.png" alt="Rusia 2018" width="48" class="me-2 rounded">
          Copa Mundial de la FIFA Rusia 2018
        </h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="wikiTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="infografia-tab" data-bs-toggle="tab" data-bs-target="#infografia2018" type="button" role="tab">Infografía</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="videos-tab" data-bs-toggle="tab" data-bs-target="#videos2018" type="button" role="tab">Videos de Jugadas</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="paises-tab" data-bs-toggle="tab" data-bs-target="#paises2018" type="button" role="tab">Países Participantes</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="jugadores-tab" data-bs-toggle="tab" data-bs-target="#jugadores2018" type="button" role="tab">Jugadores Destacados</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="partidos-tab" data-bs-toggle="tab" data-bs-target="#partidos2018" type="button" role="tab">Partidos</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="entrevistas-tab" data-bs-toggle="tab" data-bs-target="#entrevistas2018" type="button" role="tab">Entrevistas</button>
          </li>
        </ul>
        <div class="tab-content" id="wikiTabContent">
          <!-- Info -->
          <div class="tab-pane fade show active text-dark" id="infografia2018" role="tabpanel">
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
          <!-- Videos -->
          <div class="tab-pane fade text-dark" id="videos2018" role="tabpanel">
            <h5>Videos destacados de jugadas</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="ratio ratio-16x9">
                  <iframe src="https://www.youtube.com/embed/Cz1RrG4xGho" title="Mejores jugadas Rusia 2018" allowfullscreen></iframe>
                </div>
                <small>Top 10 jugadas del mundial</small>
              </div>
              <div class="col-md-6">
                <div class="ratio ratio-16x9">
                  <iframe src="https://www.youtube.com/embed/ZZH5d9OAVlk" title="Los goles más espectaculares" allowfullscreen></iframe>
                </div>
                <small>Goles espectaculares</small>
              </div>
            </div>
          </div>
          <!-- Countries -->
          <div class="tab-pane fade text-dark" id="paises2018" role="tabpanel">
            <h5>Países participantes</h5>
            <div class="row row-cols-2 row-cols-md-4 g-2">
              <div class="col"><span class="badge bg-light text-dark">Alemania</span></div>
              <div class="col"><span class="badge bg-light text-dark">Argentina</span></div>
              <div class="col"><span class="badge bg-light text-dark">Brasil</span></div>
              <div class="col"><span class="badge bg-light text-dark">Francia</span></div>
              <div class="col"><span class="badge bg-light text-dark">Rusia</span></div>
              <div class="col"><span class="badge bg-light text-dark">España</span></div>
              <div class="col"><span class="badge bg-light text-dark">México</span></div>
              <div class="col"><span class="badge bg-light text-dark">Uruguay</span></div>
            </div>
          </div>
          <!-- Players -->
          <div class="tab-pane fade text-dark" id="jugadores2018" role="tabpanel">
            <h5>Jugadores destacados</h5>
            <ul>
              <li>Kylian Mbappé <span class="text-muted">(Francia)</span></li>
              <li>Luka Modrić <span class="text-muted">(Croacia)</span></li>
              <li>Harry Kane <span class="text-muted">(Inglaterra)</span></li>
              <li>Eden Hazard <span class="text-muted">(Bélgica)</span></li>
            </ul>
          </div>
          <!-- Games -->
          <div class="tab-pane fade text-dark" id="partidos2018" role="tabpanel">
            <h5>Partidos</h5>
            <ul>
              <li>Final: Francia 4 - 2 Croacia</li>
              <li>Semifinal: Francia 1 - 0 Bélgica</li>
              <li>Semifinal: Croacia 2 - 1 Inglaterra</li>
              <li>Cuartos: Rusia 2 - 2 Croacia (penales)</li>
            </ul>
          </div>
          <!-- Interviews -->
          <div class="tab-pane fade text-dark" id="entrevistas2018" role="tabpanel">
            <h5>Entrevistas y testimonios</h5>
            <div class="mb-3">
              <strong>Didier Deschamps (DT Francia):</strong>
              <blockquote class="blockquote">"Ganar como jugador y como entrenador es un sueño hecho realidad."</blockquote>
            </div>
            <div class="mb-3">
              <strong>Luka Modrić:</strong>
              <blockquote class="blockquote">"Dejamos todo en la cancha y estamos orgullosos de nuestro país."</blockquote>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <small class="text-muted">Fuente: Wikipedia, FIFA, YouTube</small>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php require('inc/footer.inc.php'); ?>
</body>