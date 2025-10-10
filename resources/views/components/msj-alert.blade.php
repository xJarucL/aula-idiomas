<style>
#mensaje {
    position: fixed;
    bottom: 20px;
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
    animation: slideUp 0.5s forwards;
}

/* Colores */
.success { background: linear-gradient(135deg, #00897B, #086960ff); }
.error { background: linear-gradient(135deg, #dc2626, #b61313ff); }

/* Animación de entrada desde abajo */
@keyframes slideUp {
    from { transform: translateX(-50%) translateY(100%); opacity: 0; }
    to   { transform: translateX(-50%) translateY(0); opacity: 1; }
}

/* Animación de salida hacia abajo */
@keyframes slideDown {
    from { transform: translateX(-50%) translateY(0); opacity: 1; }
    to   { transform: translateX(-50%) translateY(100%); opacity: 0; }
}
</style>

@if (session('success'))
    <div id="mensaje" class="success">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div id="mensaje" class="error">
        {{ session('error') }}
    </div>
@else
    <div id="mensaje" class="hidden"></div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mensaje = document.getElementById('mensaje');
    if (mensaje && !mensaje.classList.contains('hidden')) {
        setTimeout(() => {
            mensaje.style.animation = 'slideDown 8s forwards';
            setTimeout(() => {
                mensaje.style.display = 'none';
            }, 500);
        }, 4000);
    }
});
</script>
