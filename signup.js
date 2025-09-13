// Bootstrap custom validation para todos los campos EN VIVO
(() => {
    'use strict'
    const form = document.getElementById('signupForm');
    // Validación en vivo para todos los campos requeridos (excepto password y birthdate, que tienen lógica especial)
    form.querySelectorAll('input, select, textarea').forEach(input => {
        if (input.id === 'password' || input.id === 'birthdate') return;
        input.addEventListener('input', function() {
            if (input.checkValidity()) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
        });
    });

    // Mostrar/ocultar contraseña
    document.getElementById('togglePassword').addEventListener('click', function () {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === "password") {
            pwd.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            pwd.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    // Validación de password EN VIVO
    const pwdInput = document.getElementById('password');
    pwdInput.addEventListener('input', function() {
        if (validatePassword(pwdInput.value)) {
            pwdInput.classList.remove('is-invalid');
            pwdInput.classList.add('is-valid');
        } else {
            pwdInput.classList.remove('is-valid');
            pwdInput.classList.add('is-invalid');
        }
    });

    // Validación en vivo de fecha de nacimiento (mínimo 12 años)
    const birthInput = document.getElementById('birthdate');
    const birthFeedback = document.getElementById('birthdateFeedback');
    birthInput.addEventListener('change', validateBirthdate);
    birthInput.addEventListener('input', validateBirthdate);

    function validateBirthdate() {
        const birthDate = new Date(birthInput.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (birthInput.value && age < 12) {
            birthInput.setCustomValidity('Debes tener al menos 12 años para registrarte.');
            birthFeedback.textContent = "Debes tener al menos 12 años para registrarte.";
            birthInput.classList.add('is-invalid');
            birthInput.classList.remove('is-valid');
        } else if (birthInput.value) {
            birthInput.setCustomValidity('');
            birthFeedback.textContent = "Debes tener al menos 12 años para registrarte.";
            birthInput.classList.remove('is-invalid');
            birthInput.classList.add('is-valid');
        } else {
            // Si no hay fecha, no mostrar nada
            birthInput.classList.remove('is-valid', 'is-invalid');
        }
    }

    // Validación personalizada de contraseña (pattern + minlength)
    function validatePassword(pwd) {
        // 8+ chars, 1 minúscula, 1 mayúscula, 1 número, 1 especial
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-=\[\]{};':\\|,.<>\/?]).{8,}$/;
        return regex.test(pwd);
    }

    // Submit: solo se permite si TODO es válido (HTML5 y custom)
    form.addEventListener('submit', function(e) {
        let customValid = true;

        // Password
        if (!validatePassword(pwdInput.value)) {
            pwdInput.classList.add('is-invalid');
            customValid = false;
        }

        // Birthdate
        const birthDate = new Date(birthInput.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (!birthInput.value || age < 12) {
            birthInput.classList.add('is-invalid');
            customValid = false;
        }

        // Validación HTML5 de todos los campos
        if (!form.checkValidity() || !customValid) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated');
            // Marcar campos inválidos (en caso de que el usuario no haya interactuado con todos)
            form.querySelectorAll('input, select, textarea').forEach(input => {
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
                }
            });
            return false;
        }

        // Si todo es válido, simula éxito y redirige
        e.preventDefault();
        document.getElementById('signupSuccess').classList.remove('d-none');
        setTimeout(function(){
            window.location.href = "index.php";
        }, 2000);
    });
})();