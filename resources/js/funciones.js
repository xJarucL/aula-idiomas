$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let colaOffline = JSON.parse(localStorage.getItem('colaOffline')) || [];

window.mostrarAlerta = function (mensaje, tipo = "success") {
    const alerta = document.getElementById("mensaje");
    if (!alerta) return;

    alerta.innerHTML = mensaje;
    alerta.classList.remove("hidden", "success", "error");
    alerta.classList.add(tipo);

    alerta.style.display = "block";
    alerta.style.animation = "slideUp 0.5s forwards";

    setTimeout(() => {
        alerta.style.animation = "slideDown 0.5s forwards";
        setTimeout(() => alerta.style.display = "none", 500);
    }, 3000);
};

function actualizarDashboard() {
    const dashboard = document.getElementById('dashboard-offline');
    const lista = document.getElementById('lista-pendientes');
    if (!lista) return;

    lista.innerHTML = '';

    if (colaOffline.length === 0) {
        dashboard?.classList.add('hidden');
        return;
    }

    dashboard?.classList.remove('hidden');

    colaOffline.forEach(item => {
        const li = document.createElement('li');
        li.className = "flex items-center justify-between bg-yellow-300 px-3 py-2 rounded shadow-sm text-sm animate-fadeIn";
        li.innerHTML = `
            <span class="font-medium">${item.mensaje || 'Acción pendiente'}</span>
            <span class="text-gray-700 italic text-xs">Pendiente</span>
        `;
        lista.appendChild(li);
    });
}

function guardarEnCola(url, data, mensaje = 'Acción pendiente') {
    data._token_envio = Date.now() + Math.random();
    colaOffline.push({ url, data, mensaje });
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

                guardarEnCola($form.data('url'), dataObj, $form.data('mensaje') || 'Formulario pendiente');
            }

            mostrarAlerta("No hay conexión. Se guardó en pendientes.", "error");
            return resolve();
        }

        $.ajax({
            url: $form.data('url'),
            method: 'POST',
            data: new FormData($form[0]),
            processData: false,
            contentType: false,
            success: function (response) {
                mostrarAlerta(
                    response.mensaje || response.message || "Operación exitosa",
                    response.class || "success"
                );

                if (response.ruta) {
                    setTimeout(() => window.location.href = response.ruta, 1500);
                }

                resolve(response);
            },
            error: function (xhr) {
                let res = xhr.responseJSON;

                mostrarAlerta(
                    res?.mensaje || res?.message || "Error inesperado",
                    "error"
                );

                reject(xhr);
            }
        });
    });
}

$(document).off('submit', 'form[data-url]');
$(document).on('submit', 'form[data-url]', function (e) {
    e.preventDefault();
    enviarFormulario($(this));
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
                    text: 'Se guardó en pendientes.'
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
                    body: JSON.stringify({ id, tipo, _token_envio: Date.now() + Math.random() })
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
                Swal.fire({ icon: 'error', title: 'Error', text: 'Problema al actualizar el tipo.' });
            }
        });
    });
});

async function procesarColaOffline() {
    let cola = JSON.parse(localStorage.getItem('colaOffline')) || [];

    for (let i = 0; i < cola.length; i++) {
        const item = cola[i];

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

            cola.splice(i, 1);
            i--;
            localStorage.setItem('colaOffline', JSON.stringify(cola));

        } catch (error) {
            console.error("Error al enviar solicitud pendiente:", error);
        }
    }

    colaOffline = cola;

    if (cola.length === 0) {
        mostrarAlerta("Todas las acciones pendientes se enviaron correctamente.", "success");
    }

    actualizarDashboard();
}

window.addEventListener('online', procesarColaOffline);
actualizarDashboard();
