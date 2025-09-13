<?php require('inc/head.inc.php'); ?>
<body>
    <?php require('inc/navbar.inc.php'); ?>
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow">
                    <div class="row card-header pt-3 bg-dark text-light " style="margin:0!important;">
                        <h4 class="col-5">Crear cuenta</h4>
                        <h6 class="col-7 pt-1 text-end">
                            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Alert de éxito oculto -->
                        <div class="alert alert-success d-none" id="signupSuccess" role="alert">
                            ¡Cuenta creada exitosamente! Redirigiendo...
                        </div>
                        <form class="needs-validation" id="signupForm" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="fullname" class="form-label">Nombre completo</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                                    <div class="invalid-feedback">Ingresa tu nombre completo.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="birthdate" class="form-label">Fecha de nacimiento</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" required min="1900-01-01" max="<?php echo date('Y-m-d'); ?>">
                                    <div class="invalid-feedback" id="birthdateFeedback">
                                        Debes tener al menos 12 años para registrarte.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="photo" class="form-label">Foto de perfil <span class="text-muted">(opcional)</span></label>
                                    <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                                    <!-- Elimina el invalid-feedback aquí -->
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Género</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Selecciona tu género</option>
                                        <option value="F">Femenino</option>
                                        <option value="M">Masculino</option>
                                        <option value="O">Otro</option>
                                    </select>
                                    <div class="invalid-feedback">Selecciona tu género.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="birthcountry" class="form-label">País de nacimiento</label>
                                    <input type="text" class="form-control" id="birthcountry" name="birthcountry" required>
                                    <div class="invalid-feedback">Ingresa tu país de nacimiento.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="nationality" class="form-label">Nacionalidad</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" required>
                                    <div class="invalid-feedback">Ingresa tu nacionalidad.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">Ingresa un correo válido.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Nombre de usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                    <div class="invalid-feedback">Usuario inválido</div>
                                </div>
                                <div class="col-md-12">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            required
                                            minlength="8"
                                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-=\[\]{};':\\|,.<>\/?]).{8,}$"
                                            aria-describedby="passwordHelpBlock">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                    <div id="passwordHelpBlock" class="form-text">
                                        Mínimo 8 caracteres, incluye mayúsculas, minúsculas, número y carácter especial.
                                    </div>
                                    <div class="invalid-feedback">
                                        La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, un número y un carácter especial.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-success w-100" type="submit">Registrarse</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src='signup.js'></script>
<?php require('inc/footer.inc.php'); ?>
</body>