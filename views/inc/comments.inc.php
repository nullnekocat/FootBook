<!-- /views/inc/comments.inc.php -->
<div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title" id="commentsModalLabel">Comentarios</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body pt-0">
        <div id="comments-list" class="comments-list" style="max-height:320px; overflow-y:auto;"></div>
        <div id="comments-empty" class="text-muted small d-none">Sé el primero en comentar</div>
      </div>

      <div class="modal-footer border-0 pt-0">
        <form id="comment-form" class="w-100 d-flex gap-2" onsubmit="event.preventDefault();">
          <input id="comment-input" type="text" class="form-control" placeholder="Agrega un comentario..." required>
          <button type="submit" class="btn btn-success">Publicar</button>
        </form>
      </div>
    </div>
  </div>
</div>
