$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let colaOffline = JSON.parse(localStorage.getItem('colaOffline')) || [];

function actualizarDashboard() {
    const dashboard = document.getElementById('dashboard-offline');
    const lista = document.getElementById('lista-pendientes');
    lista.innerHTML = '';

    if (colaOffline.length === 0) {
        dashboard.classList.add('hidden');
        return;
    }

    dashboard.classList.remove('hidden');

    colaOffline.forEach(item => {
        const li = document.createElement('li');
        li.className = "flex items-center justify-between bg-yellow-300 px-3 py-2 rounded shadow-sm text-sm animate-fadeIn";
        li.innerHTML = `
            <span class="font-medium">${item.alerta || 'Acción pendiente'}</span>
            <span class="text-gray-700 italic text-xs">Pendiente</span>
        `;
        lista.appendChild(li);
    });
}

function guardarEnCola(url, data, alerta = 'Acción pendiente') {
    colaOffline.push({ url, data, alerta });
    localStorage.setItem('colaOffline', JSON.stringify(colaOffline));
    actualizarDashboard();
}

function enviarFormulario($form, guardarOffline = true) {
    return new Promise((resolve, reject) => {
        if (!navigator.onLine) {
            if (guardarOffline) {
                let formData = new FormData($form[0]);
                let dataObj = {};
                formData.forEach((value, key) => dataObj[key] = value);

                guardarEnCola($form.data('url'), dataObj, $form.data('alerta') || 'Formulario pendiente');
            }

            $('#alerta')
                .removeClass('hidden')
                .removeClass('success')
                .addClass('error')
                .html('No hay conexión. La operación se guardará y se enviará cuando vuelvas online.')
                .fadeIn();
            return resolve();
        }

        $.ajax({
            url: $form.data('url'),
            method: 'POST',
            data: new FormData($form[0]),
            processData: false,
            contentType: false,
            success: function(response) {
                $('#alerta')
                    .removeClass('hidden')
                    .removeClass('error')
                    .addClass(response.class || 'success')
                    .html(response.alerta || response.message || 'Operación exitosa.')
                    .fadeIn();
                setTimeout(() => {
                    if (response.ruta) window.location.href = response.ruta;
                    else $('#alerta').fadeOut();
                }, 2000);
                resolve(response);
            },
            error: function(xhr) {
                let res = xhr.responseJSON;
                let alerta = !navigator.onLine
                    ? 'No hay conexión. La operación se guardará y se enviará cuando vuelvas online.'
                    : res?.alerta || res?.message || 'Ha ocurrido un error inesperado.';

                $('#alerta')
                    .removeClass('hidden')
                    .removeClass('success')
                    .addClass('error')
                    .html(alerta)
                    .fadeIn();
                setTimeout(() => $('#alerta').fadeOut(), 4000);
                reject(xhr);
            }
        });
    });
}

$(document).ready(function() {
    $('form[data-url]').on('submit', async function(e) {
        e.preventDefault();
        const $form = $(this);
        await enviarFormulario($form);
    });

    actualizarDashboard();
});

document.addEventListener('DOMContentLoaded', () => {
    const selects = document.querySelectorAll('.select-tipo-usuario');

    selects.forEach(select => {
        select.addEventListener('change', async (e) => {
            const id = e.target.getAttribute('data-id');
            const tipo = e.target.value;

            if (!navigator.onLine) {
                guardarEnCola('/coordinacion/usuarios/cambiar-tipo', { id, tipo }, 'Cambio de tipo de usuario');
                Swal.fire({
                    icon: 'error',
                    title: 'Sin conexión',
                    text: 'El cambio se guardará y se aplicará automáticamente al volver online.'
                });
                return;
            }

            try {
                const response = await fetch('/coordinacion/usuarios/cambiar-tipo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id, tipo })
                });

                const data = await response.json();
                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    location.reload();
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Hubo un problema al actualizar el tipo de usuario.' });
            }
        });
    });
});

async function procesarColaOffline() {
    let cola = JSON.parse(localStorage.getItem('colaOffline')) || [];

    for (const item of cola) {
        try {
            if (item.url === '/coordinacion/usuarios/cambiar-tipo') {
                await fetch(item.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(item.data)
                });
            } else {
                const formData = new FormData();
                for (let key in item.data) formData.append(key, item.data[key]);
                await $.ajax({
                    url: item.url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false
                });
            }
        } catch (error) {
            console.error('Error al enviar solicitud pendiente:', error);
        }
    }

    colaOffline = [];
    localStorage.removeItem('colaOffline');

    $('#alerta')
        .removeClass('hidden')
        .removeClass('error')
        .addClass('success')
        .html('Todas las acciones pendientes se enviaron correctamente.')
        .fadeIn();
    actualizarDashboard();
    setTimeout(() => $('#alerta').fadeOut(), 4000);
}

window.addEventListener('online', () => {
    procesarColaOffline();
});
