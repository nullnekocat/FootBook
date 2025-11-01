<!-- Comments modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title" id="commentsModalLabel">Comentarios</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body pt-0">
        <div class="comments-list" style="max-height: 320px; overflow-y: auto;">
          <!-- Dummy comments -->
          <div class="d-flex align-items-center mb-3">
            <img src="/Footbook/img/user1.jpg" class="rounded-circle me-2" width="36" height="36" alt="User">
            <div>
              <strong>Usuario1</strong>
              <span class="text-muted small ms-1">Qué buena jugada!</span>
              <div class="text-muted small">Hace 2 horas</div>
            </div>
          </div>
          <div class="d-flex align-items-center mb-3">
            <img src="/Footbook/img/user2.jpg" class="rounded-circle me-2" width="36" height="36" alt="User">
            <div>
              <strong>Usuario2</strong>
              <span class="text-muted small ms-1">¡Impresionante final!</span>
              <div class="text-muted small">Hace 30 minutos</div>
            </div>
          </div>
          <div class="d-flex align-items-center mb-3">
            <img src="/Footbook/img/default.jpg" class="rounded-circle me-2" width="36" height="36" alt="User">
            <div>
              <strong>Usuario3</strong>
              <span class="text-muted small ms-1">👏👏👏</span>
              <div class="text-muted small">Hace 10 minutos</div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <form class="w-100 d-flex gap-2" onsubmit="event.preventDefault();">
          <input type="text" class="form-control" placeholder="Agrega un comentario..." required>
          <button type="submit" class="btn btn-success">Publicar</button>
        </form>
      </div>
    </div>
  </div>
</div>