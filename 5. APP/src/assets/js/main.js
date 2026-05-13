$(function () {

    $('.toggle-password').on('click', function () {
        const input = $(this).closest('.input-icon-right').find('input');
        const icon  = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passRegex  = /.{8,}/;

    function showError(id, msg) {
        $('#' + id).text(msg).fadeIn(150);
    }

    function clearError(id) {
        $('#' + id).text('').hide();
    }

    function validateLogin() {
        let valid = true;

        const email    = $('#email').val().trim();
        const password = $('#password').val().trim();

        if (!emailRegex.test(email)) {
            showError('emailError', 'Introduce un email válido.');
            valid = false;
        } else {
            clearError('emailError');
        }

        if (password.length === 0) {
            showError('passwordError', 'La contraseña no puede estar vacía.');
            valid = false;
        } else {
            clearError('passwordError');
        }

        return valid;
    }

    function validateRegister() {
        let valid = true;

        const name     = $('#name').val().trim();
        const surname  = $('#surname').val().trim();
        const email    = $('#email').val().trim();
        const password = $('#password').val().trim();
        const confirm  = $('#confirm').val().trim();

        if (name.length < 2) {
            showError('nameError', 'El nombre debe tener al menos 2 caracteres.');
            valid = false;
        } else {
            clearError('nameError');
        }

        if (surname.length < 2) {
            showError('surnameError', 'Los apellidos deben tener al menos 2 caracteres.');
            valid = false;
        } else {
            clearError('surnameError');
        }

        if (!emailRegex.test(email)) {
            showError('emailError', 'Introduce un email válido.');
            valid = false;
        } else {
            clearError('emailError');
        }

        if (!passRegex.test(password)) {
            showError('passwordError', 'La contraseña debe tener al menos 8 caracteres.');
            valid = false;
        } else {
            clearError('passwordError');
        }

        if (confirm !== password) {
            showError('confirmError', 'Las contraseñas no coinciden.');
            valid = false;
        } else {
            clearError('confirmError');
        }

        return valid;
    }

    $('#loginForm').on('submit', function (e) {
        if (!validateLogin()) e.preventDefault();
    });

    $('#registerForm').on('submit', function (e) {
        if (!validateRegister()) e.preventDefault();
    });

    $('#email').on('input', function () {
        if (emailRegex.test($(this).val().trim())) clearError('emailError');
    });

    $('#password').on('input', function () {
        if ($('#loginForm').length && $(this).val().trim().length > 0) clearError('passwordError');
        if ($('#registerForm').length && passRegex.test($(this).val().trim())) clearError('passwordError');
    });

    $('#confirm').on('input', function () {
        if ($(this).val().trim() === $('#password').val().trim()) clearError('confirmError');
    });

    $('.nav-item').on('mouseenter', function () {
        $(this).find('i').addClass('fa-beat-fade');
    }).on('mouseleave', function () {
        $(this).find('i').removeClass('fa-beat-fade');
    });

});