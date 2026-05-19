/**
 * Lógica global para Modales Personalizados
 */
function openModal(title, text, onAccept) {
    const modal = $('#customModal');
    if (!modal.length) return;

    $('#modalTitle').text(title);
    $('#modalText').text(text);

    $('#modalAccept').text('Aceptar');
    $('#modalReject').text('Cancelar');

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

        if (email.length === 0) {
            showError('emailError', 'Introduce tu email o nombre de usuario.');
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

        openModal(
            'Descartar comentario',
            'Esto descartará tu comentario',
            function () {
                $('#reply-' + id).slideUp(200);
            }
        );
    });

    $(document).on('click', '.js-vote-guest', function () {
        openModal(
            'Inicia sesión',
            'Debes iniciar sesión para poder votar.',
            function () { window.location.href = '/pages/login.php'; }
        );
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

        openModal(
            'Eliminar publicación',
            '¿Estás seguro de que deseas eliminar esta publicación? Esta acción no se puede deshacer.',
            function () {
                $.post('/pages/delete-post.php', { id: id }, function (res) {
                    if (res.success) {
                        window.location.href = '/index.php';
                    }
                }, 'json');
            }
        );
    });

    $(document).on('click', '.js-delete-comment', function () {
        const id = $(this).data('id');
        const postId = $(this).data('post');

        openModal(
            'Eliminar comentario',
            '¿Estás seguro de que quieres borrar este comentario? Esta acción no se puede deshacer.',
            function () {
                $.post('/pages/delete-comment.php', { id: id }, function (res) {
                    if (res.success) {
                        window.location.href = '/pages/post.php?id=' + postId;
                    }
                }, 'json');
            }
        );
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

    const savedDark = localStorage.getItem('antnet_dark') === 'true';
    const savedColor = localStorage.getItem('antnet_color') || '#e20000';
    applyTheme(savedDark, savedColor);

    $('#darkBtn').on('click', function () {
        localStorage.setItem('antnet_dark', 'true');
        applyTheme(true, localStorage.getItem('antnet_color') || '#e20000');
    });

    $('#lightBtn').on('click', function () {
        localStorage.setItem('antnet_dark', 'false');
        applyTheme(false, localStorage.getItem('antnet_color') || '#e20000');
    });

    $(document).on('click', '.color-swatch', function () {
        const color = $(this).data('color');
        localStorage.setItem('antnet_color', color);
        applyTheme(localStorage.getItem('antnet_dark') === 'true', color);
    });

    $('#customColor').on('input', function () {
        const color = $(this).val();
        localStorage.setItem('antnet_color', color);
        applyTheme(localStorage.getItem('antnet_dark') === 'true', color);
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

    $(document).on('click', '.js-remove-avatar', function () {
        const userId = $(this).data('id');

        openModal(
            'Eliminar Avatar',
            '¿Estás seguro de que deseas eliminar el avatar de este usuario?',
            function () {
                const form = $('<form>', {
                    method: 'POST',
                    action: ''
                }).append($('<input>', {
                    type: 'hidden',
                    name: 'action',
                    value: 'remove_avatar'
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

    $(document).on('click', '#logoutBtn', function (e) {
        e.preventDefault();
        const logoutUrl = $(this).attr('href');

        openModal(
            'Cerrar sesión',
            '¿Estás seguro de que deseas salir de AntNet?',
            function () {
                window.location.href = logoutUrl;
            }
        );
        $('#modalAccept').text('Cerrar sesión');
    });

    $('.js-cancel-btn').on('click', function (e) {
        e.preventDefault();
        const targetUrl = $(this).attr('href');

        openModal(
            'Descartar cambios',
            'Tienes cambios sin guardar. ¿Seguro que quieres salir?',
            function () {
                window.location.href = targetUrl;
            }
        );
        $('#modalAccept').text('Descartar');
    });

    $('#passwordForm').on('submit', function (e) {
        let valid = true;

        if ($('#current_password').val().trim().length === 0) {
            showError('currentPasswordError', 'Introduce tu contraseña actual.');
            valid = false;
        } else { clearError('currentPasswordError'); }

        if ($('#new_password').val().trim().length < 8) {
            showError('newPasswordError', 'La nueva contraseña debe tener al menos 8 caracteres.');
            valid = false;
        } else { clearError('newPasswordError'); }

        if ($('#confirm_password').val().trim() !== $('#new_password').val().trim()) {
            showError('confirmPasswordError', 'Las contraseñas no coinciden.');
            valid = false;
        } else { clearError('confirmPasswordError'); }

        if (!valid) e.preventDefault();
    });

    if ($('#historyNav').length) {
        const versions = [];
        $('.history-version').each(function () {
            versions.push({
                title: $(this).data('title'),
                content: $(this).data('content'),
                date: $(this).data('date')
            });
        });

        const currentTitle = $('#historyNav').data('current-title');
        const currentContent = $('#historyNav').data('current-content');
        let index = -1;

        function showVersion(i) {
            if (i === -1) {
                $('.post-title').first().text(currentTitle);
                $('.post-body--full').first().text(currentContent);
                $('#histLabel').text('Versión actual (' + (versions.length + 1) + ' de ' + (versions.length + 1) + ')');
                $('#histPrev').prop('disabled', versions.length === 0);
                $('#histNext').prop('disabled', true);
            } else {
                const v = versions[i];
                $('.post-title').first().text(v.title);
                $('.post-body--full').first().html(v.content.replace(/\n/g, '<br>'));
                $('#histLabel').text('Versión ' + (versions.length - i) + ' de ' + (versions.length + 1) + ' — ' + timeAgo(v.date));
                $('#histPrev').prop('disabled', i >= versions.length - 1);
                $('#histNext').prop('disabled', false);
            }
            index = i;
        }

        $('#histPrev').on('click', function () { showVersion(index + 1); });
        $('#histNext').on('click', function () { showVersion(index - 1); });

        showVersion(-1);
    }

    $(document).on('click', '.js-edit-user', function () {
        const id = $(this).data('id');
        const username = $(this).data('username');
        const email = $(this).data('email');

        const rolesAttr = $(this).data('roles');
        const rolesArray = rolesAttr ? String(rolesAttr).split(',') : [];

        $('#editId').val(id);
        $('#editUsername').val(username);
        $('#editEmail').val(email);

        $('#editRole').val(rolesArray);

        $('#editPanel').fadeIn(200);
    });

    $(document).on('click', '#cancelEdit', function () {
        openModal(
            'Confirmar cancelación',
            '¿Estás seguro de que deseas perder los datos modificados?',
            function () {
                $('#editPanel').fadeOut(200);
            }
        );
    });

$('[data-fancybox="galeria"]').fancybox({
    animationEffect: "fade",
    transitionEffect: "fade"
});
});