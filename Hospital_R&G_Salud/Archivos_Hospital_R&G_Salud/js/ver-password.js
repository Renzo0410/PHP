    function togglePasswordVisibility(fieldId, toggleButtonId) {
        var passwordField = document.getElementById(fieldId);
        var toggleButton = document.getElementById(toggleButtonId);
        var icon = toggleButton.querySelector('i');
        
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove('bi-eye');  // Cambiar a ícono de "ocultar"
            icon.classList.add('bi-eye-slash');
        } else {
            passwordField.type = "password";
            icon.classList.remove('bi-eye-slash');  // Cambiar a ícono de "ver"
            icon.classList.add('bi-eye');
        }
    }

    document.getElementById('togglePassw1').addEventListener('click', function() {
        togglePasswordVisibility('passw1', 'togglePassw1');
    });

    document.getElementById('togglePassw2').addEventListener('click', function() {
        togglePasswordVisibility('passw2', 'togglePassw2');
    });
    
    
