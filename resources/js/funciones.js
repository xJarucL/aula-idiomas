$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $('form[data-url]').on('submit', function (e) {
        e.preventDefault();

        let $form = $(this);
        let url = $form.data('url');
        let formData = new FormData(this);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#mensaje')
                    .removeClass('error')
                    .addClass(response.class || 'success')
                    .html(response.mensaje || response.message || 'Operación exitosa.')
                    .fadeIn();

                setTimeout(function () {
                    if (response.ruta) {
                        window.location.href = response.ruta;
                    } else {
                        $('#mensaje').fadeOut();
                    }
                }, 2000);
            },
            error: function (xhr) {
                let res = xhr.responseJSON;
                let mensaje = '';

                if (xhr.status === 422 && res?.errores) {
                    mensaje += `<strong>${res.mensaje || 'Error de validación:'}</strong><br>`;
                    for (let campo in res.errores) {
                        res.errores[campo].forEach(function (error) {
                            mensaje += `${error}<br>`;
                        });
                    }
                } else {
                    mensaje = res?.mensaje || res?.message || 'Ha ocurrido un error inesperado.';
                }

                $('#mensaje')
                    .removeClass('success')
                    .addClass(res?.class || 'error')
                    .html(mensaje)
                    .fadeIn();

                setTimeout(function () {
                    $('#mensaje').fadeOut();
                }, 4000);
            }
        });
    });
});
