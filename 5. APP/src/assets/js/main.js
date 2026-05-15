/**
 * Lógica global para Modales Personalizados
 */
function openModal(title, text, onAccept) {
    const modal = $('#customModal');
    if (!modal.length) return;

    $('#modalTitle').text(title);
    $('#modalText').text(text);

    modal.css('display', 'flex').hide().fadeIn(200);

    const close = () => modal.fadeOut(200);

    $('#modalClose, #modalReject').off('click').on('click', close);

    $('#modalAccept').off('click').on('click', function () {
        onAccept();
        close();
    });

    modal.off('click').on('click', function (e) {
        if (e.target === this) close();
    });
}

$(function () {

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passRegex = /.{8,}/;

    function showError(id, msg) {
        $('#' + id).text(msg).fadeIn(150);
    }

    function clearError(id) {
        $('#' + id).text('').hide();
    }

    function timeAgo(dateStr) {
        const diff = Math.floor((new Date() - new Date(dateStr)) / 1000);
        if (diff < 60) return 'hace un momento';
        if (diff < 3600) return 'hace ' + Math.floor(diff / 60) + ' min';
        if (diff < 86400) return 'hace ' + Math.floor(diff / 3600) + ' h';
        return 'hace ' + Math.floor(diff / 86400) + ' días';
    }

    function validateLogin() {
        let valid = true;
        const email = $('#email').val().trim();
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
        const username = $('#username').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();
        const confirm = $('#confirm').val().trim();

        if (username.length < 2) {
            showError('usernameError', 'El nombre de usuario debe tener al menos 2 caracteres.');
            valid = false;
        } else { clearError('usernameError'); }

        if (!emailRegex.test(email)) {
            showError('emailError', 'Introduce un email válido.');
            valid = false;
        } else { clearError('emailError'); }

        if (!passRegex.test(password)) {
            showError('passwordError', 'La contraseña debe tener al menos 8 caracteres.');
            valid = false;
        } else { clearError('passwordError'); }

        if (confirm !== password) {
            showError('confirmError', 'Las contraseñas no coinciden.');
            valid = false;
        } else { clearError('confirmError'); }

        return valid;
    }

    $('.toggle-password').on('click', function () {
        const input = $(this).closest('.input-icon-right').find('input');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

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

    $('#createPostForm').on('submit', function (e) {
        let valid = true;
        if ($('#title').val().trim().length < 5) {
            showError('titleError', 'El título debe tener al menos 5 caracteres.');
            valid = false;
        } else { clearError('titleError'); }

        if ($('#content').val().trim().length < 10) {
            showError('contentError', 'El contenido debe tener al menos 10 caracteres.');
            valid = false;
        } else { clearError('contentError'); }

        if (!valid) e.preventDefault();
    });

    $('#commentForm').on('submit', function (e) {
        const content = $('#commentContent').val().trim();
        if (content.length < 2) {
            showError('commentError', 'El comentario no puede estar vacío.');
            e.preventDefault();
        } else {
            clearError('commentError');
        }
    });

    $('.post-date').each(function () {
        $(this).text(timeAgo($(this).data('date')));
    });

    $('.nav-item').on('mouseenter', function () {
        $(this).find('i').addClass('fa-beat-fade');
    }).on('mouseleave', function () {
        $(this).find('i').removeClass('fa-beat-fade');
    });

    $(document).on('click', '.js-reply-toggle', function () {
        const id = $(this).data('id');
        $('#reply-' + id).slideToggle(200);
    });

    $(document).on('click', '.js-reply-cancel', function () {
        const id = $(this).data('id');
        $('#reply-' + id).slideUp(200);
    });

    $(document).on('click', '.js-vote', function () {
        const btn = $(this);
        const id = btn.data('id');
        const type = btn.data('type');
        const group = btn.closest('.vote-group');

        $.post('/pages/vote.php', { id: id, type: type }, function (res) {
            if (res.error) return;
            group.find('.upvote-count').text(res.upvotes);
            group.find('.downvote-count').text(res.downvotes);
            group.find('.vote-btn').removeClass('voted');
            if (type === 1 && res.upvotes > 0) btn.addClass('voted');
            if (type === 0 && res.downvotes > 0) btn.addClass('voted');
        }, 'json');
    });

    $(document).on('click', '.js-delete-post', function () {
        const id = $(this).data('id');
        if (!confirm('¿Eliminar esta publicación?')) return;
        $.post('/pages/delete-post.php', { id: id }, function (res) {
            if (res.success) window.location.href = '/index.php';
        }, 'json');
    });

    $(document).on('click', '.js-delete-comment', function () {
        const btn = $(this);
        const id = btn.data('id');
        const postId = btn.data('post');
        if (!confirm('¿Eliminar este comentario?')) return;
        $.post('/pages/delete-comment.php', { id: id }, function (res) {
            if (res.success) window.location.href = '/pages/post.php?id=' + postId;
        }, 'json');
    });
    function applyTheme(dark, color) {
        if (dark) {
            $('body').addClass('dark');
            $('#darkBtn').addClass('active');
            $('#lightBtn').removeClass('active');
        } else {
            $('body').removeClass('dark');
            $('#lightBtn').addClass('active');
            $('#darkBtn').removeClass('active');
        }
        document.documentElement.style.setProperty('--primary', color);
        $('.color-swatch').removeClass('active');
        $('.color-swatch[data-color="' + color + '"]').addClass('active');
        $('#customColor').val(color);
    }

    const savedDark = localStorage.getItem('anthive_dark') === 'true';
    const savedColor = localStorage.getItem('anthive_color') || '#e20000';
    applyTheme(savedDark, savedColor);

    $('#darkBtn').on('click', function () {
        localStorage.setItem('anthive_dark', 'true');
        applyTheme(true, localStorage.getItem('anthive_color') || '#e20000');
    });

    $('#lightBtn').on('click', function () {
        localStorage.setItem('anthive_dark', 'false');
        applyTheme(false, localStorage.getItem('anthive_color') || '#e20000');
    });

    $(document).on('click', '.color-swatch', function () {
        const color = $(this).data('color');
        localStorage.setItem('anthive_color', color);
        applyTheme(localStorage.getItem('anthive_dark') === 'true', color);
    });

    $('#customColor').on('input', function () {
        const color = $(this).val();
        localStorage.setItem('anthive_color', color);
        applyTheme(localStorage.getItem('anthive_dark') === 'true', color);
    });

    $(document).on('click', '.js-delete-user', function () {
        const userId = $(this).data('id');
        const username = $(this).data('username');

        openModal(
            'Confirmar eliminación',
            `¿Estás seguro de que deseas eliminar al usuario "${username}"? Esta acción no se puede deshacer.`,
            function () {
                const form = $('<form>', {
                    method: 'POST',
                    action: ''
                }).append($('<input>', {
                    type: 'hidden',
                    name: 'action',
                    value: 'delete'
                })).append($('<input>', {
                    type: 'hidden',
                    name: 'id',
                    value: userId
                }));

                $('body').append(form);
                form.submit();
            }
        );
    });

});