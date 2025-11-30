<style>
#mensaje, #alerta {
    position: fixed;
    left: 50%;
    transform: translateX(-50%) translateY(100%);
    z-index: 9999;
    min-width: 280px;
    max-width: 90%;
    text-align: center;
    padding: 14px 24px;
    border-radius: 12px;
    color: white;
    font-size: 0.9rem;
    font-weight: 600;
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    opacity: 0;
    display: none;
}

#mensaje { bottom: 20px; }
#alerta  { bottom: 90px; }

.success { background: linear-gradient(135deg, #00897B, #086960ff); }
.error   { background: linear-gradient(135deg, #dc2626, #b61313ff); }

@keyframes slideUp {
    from { transform: translateX(-50%) translateY(100%); opacity: 0; }
    to   { transform: translateX(-50%) translateY(0); opacity: 1; }
}
@keyframes slideDown {
    from { transform: translateX(-50%) translateY(0); opacity: 1; }
    to   { transform: translateX(-50%) translateY(100%); opacity: 0; }
}
</style>


@if (session('success'))
    <div id="mensaje" class="success">{{ session('success') }}</div>
@elseif (session('error'))
    <div id="mensaje" class="error">{{ session('error') }}</div>
@else
    <div id="mensaje" class="hidden"></div>
@endif

<div id="alerta" class="hidden">
    No hay conexión. La operación se guardará y se enviará cuando vuelvas online.
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function mostrar(elemento) {
        if (!elemento || elemento.classList.contains('hidden')) return;

        elemento.style.display = 'block';
        elemento.style.animation = 'slideUp 0.5s forwards';

        setTimeout(() => {
            elemento.style.animation = 'slideDown 0.5s forwards';
            setTimeout(() => elemento.style.display = 'none', 500);
        }, 3500);
    }

    mostrar(document.getElementById('mensaje'));
    mostrar(document.getElementById('alerta'));
});
</script>

