import './bootstrap';
import Alpine from 'alpinejs'
import $ from 'jquery';

window.$ = $;
window.jQuery = $;

window.Alpine = Alpine

Alpine.start()

$(document).ready(function () {
    import('./sweetalert.js').then(() => console.log('sweetalert.js cargado'));
    import('./funciones.js').then(() => console.log('funciones.js cargado'));
    import('./buscador.js').then(() => console.log('buscador.js cargado'));
});
