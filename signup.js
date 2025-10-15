// Bootstrap custom validation para todos los campos EN VIVO
(() => {
    'use strict'
    const form = document.getElementById('signupForm');
     //const form = document.querySelector('.needs-validation');
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
    
    const pwdInput = document.getElementById('password');
    pwdInput.addEventListener('input', function() {
        const ok = validatePassword(pwdInput.value);

        // sincroniza HTML5 validity
        if (!ok) {
            pwdInput.setCustomValidity('La contraseña no cumple los requisitos');
        } else {
            pwdInput.setCustomValidity('');
        }

        // actualiza clases visuales
        pwdInput.classList.toggle('is-valid', ok);
        pwdInput.classList.toggle('is-invalid', !ok);
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
        e.preventDefault();
        let customValid = true;
          // Password
        const passwordOK = validatePassword(pwdInput.value);
        if (!passwordOK) {
            pwdInput.setCustomValidity('La contraseña no cumple los requisitos');
            pwdInput.classList.remove('is-valid');
            pwdInput.classList.add('is-invalid');
            customValid = false;
        } else {
            pwdInput.setCustomValidity('');
            pwdInput.classList.remove('is-invalid');
            pwdInput.classList.add('is-valid');
        }

        // Birthdate
        if (birthInput) {
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
        }

        // Verifica todo el formulario
        const html5Valid = form.checkValidity();
        console.log('checkValidity() =', html5Valid, '| customValid =', customValid);

        if (!html5Valid || !customValid) {
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }
        
        document.getElementById('signupSuccess').classList.remove('d-none');
        setTimeout(function(){
            window.location.href = "index.php";
        }, 2000);
    });
})();