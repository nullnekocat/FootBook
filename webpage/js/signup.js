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
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validaciones finales
        const passwordOK = validatePassword(pwdInput.value);
        if (!passwordOK) {
            pwdInput.setCustomValidity('La contraseña no cumple los requisitos');
            pwdInput.classList.add('is-invalid');
            return;
        } else {
            pwdInput.setCustomValidity('');
        }

        const birthDateValid = birthInput.value && new Date(birthInput.value) <= new Date(new Date().setFullYear(new Date().getFullYear() - 12));
        if (!birthDateValid) {
            birthInput.classList.add('is-invalid');
            return;
        }

        // Mapear género a INT
        const genderSelect = document.getElementById('gender');
        let genderValue;
        switch(genderSelect.value){
            case 'F': genderValue = 1; break;
            case 'M': genderValue = 2; break;
            case 'O': genderValue = 3; break;
            default: genderValue = null;
        }

        const photoInput = document.getElementById('photo');
        let avatarBase64 = null;
        if (photoInput.files.length) {
            const file = photoInput.files[0];
            avatarBase64 = await new Promise(resolve => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result.split(',')[1]); // Base64 sin prefijo
                reader.readAsDataURL(file);
            });
        }

        const data = {
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            password: pwdInput.value,
            fullName: document.getElementById('fullname').value,
            birthday: birthInput.value,
            gender: genderValue,
            birth_country: document.getElementById('birthcountry').value,
            country: document.getElementById('nationality').value,
            avatar: avatarBase64,
            admin: 0
        };

        try {
            const res = await fetch('/FootBook/api/users.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const resp = await res.json();

            if (resp.message) {
                document.getElementById('signupSuccess').classList.remove('d-none');
                setTimeout(()=> window.location.href = "index.php", 2000);
            } else {
                alert(resp.error || 'Error desconocido');
            }
        } catch (err) {
            alert('Error: ' + err.message);
        }
    });
})();