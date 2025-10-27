<?php 
  require_once __DIR__ . '/../config.php';
  include __DIR__ . '/inc/head.inc.php'; 
  include __DIR__ . '/inc/navbar.inc.php'; 
?>
<body>

<div class="container py-5">
    <h1 class="mb-4 text-center">World Cups</h1>

    <!-- Cards container -->
    <div id="worldcupContainer" class="row g-4 justify-content-center">
        <!-- JS will inject cards here -->
    </div>
</div>

<!-- Modal Template -->
<div class="modal fade" id="worldcupModal" tabindex="-1" aria-labelledby="worldcupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="worldcupModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalBanner" class="img-fluid rounded mb-3" alt="World Cup Banner">
        <p><strong>Country:</strong> <span id="modalCountry"></span></p>
        <p><strong>Year:</strong> <span id="modalYear"></span></p>
        <p id="modalDescription"></p>
      </div>
    </div>
  </div>
</div>

<script src="<?= BASE_URL ?>views/js/wiki.js"></script>

<?php include __DIR__ . '/inc/footer.inc.php'; ?>
</body>