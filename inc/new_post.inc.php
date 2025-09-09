<? //php if(isset($_SESSION['user_id'])): ?>
<section class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center">
            <img src="img/<?php echo $_SESSION['user_photo'] ?? 'default.jpg'; ?>" class="rounded-circle me-3" width="48" height="48" alt="User">
            <button class="btn btn-light flex-grow-1 text-start" data-bs-toggle="modal" data-bs-target="#modal-post">
                ¿Qué quieres compartir sobre los mundiales?
            </button>
        </div>
    </div>
</section>
<?//php endif; ?>