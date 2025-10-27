<?php include __DIR__ . '/inc/head.inc.php'; ?>
<body>
    <?php include __DIR__ . '/inc/navbar.inc.php'; ?>

    <div class="container">
        <div class="row">
            <div class="middle login-size">
                <div class="card shadow">
                    <div class="card-header bg-dark text-light text-center">
                        <h4 class="mb-0">Iniciar sesión</h4>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" id="signupForm" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required autocomplete="username">
                                <div class="invalid-feedback">
                                    Ingresa tu nombre de usuario.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                                <div class="invalid-feedback">
                                    Ingresa tu contraseña.
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordarme</label>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Entrar</button>
                        </form>
                        <!--div class="text-center mt-3">
                            <a href="#">¿Olvidaste tu contraseña?</a>
                        </div-->
                        <p class="mt-3 mb-0 text-center">
                            ¿Aún no tienes cuenta? <a href="router.php?page=signup">Regístrate</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <?php require('inc/footer.inc.php'); ?>
</body>