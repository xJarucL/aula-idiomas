$(document).ready(function () {

    const input = $('#buscador');
    const limpiar = $('#limpiar-busqueda');
    const filters = $('select[data-filter]'); // asegúrate de que tus selects tengan data-filter

    function getFilterValues() {
        let values = {};
        filters.each(function() {
            values[$(this).attr('name')] = $(this).val();
        });
        return values;
    }

    function actualizarTabla() {
        let url = input.data('url');
        let search = input.val();
        let filterValues = getFilterValues();

        $.ajax({
            url: url,
            type: 'GET',
            data: { search, ...filterValues },
            beforeSend: function() {
                $('#tabla-listado').html('<p class="text-center py-4 text-gray-500">Buscando...</p>');
            },
            success: function(data) {
                $('#tabla-listado').html(data);
                toggleLimpiar();
            },
            error: function(xhr) {
                console.log(xhr.responseText); // para depuración
                $('#tabla-listado').html('<p class="text-center text-red-500">Error al buscar.</p>');
            }
        });
    }

    function toggleLimpiar() {
        let hasValue = input.val().length > 0;
        filters.each(function() {
            if($(this).val()) hasValue = true;
        });
        limpiar.toggle(hasValue);
    }

    input.on('keyup', function(e) {
        toggleLimpiar();
        if(e.key === 'Enter') actualizarTabla();
    });

    $('#btn-buscar').on('click', actualizarTabla);
    filters.on('change', actualizarTabla);
    limpiar.on('click', function() {
        input.val('');
        filters.val('');
        actualizarTabla();
    });

    $(document).on('click', '#tabla-listado .pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let search = input.val();
        let filterValues = getFilterValues();

        $.ajax({
            url: url,
            type: 'GET',
            data: { search, ...filterValues },
            success: function(data) {
                $('#tabla-listado').html(data);
                toggleLimpiar();
            }
        });
    });

    toggleLimpiar();
});
