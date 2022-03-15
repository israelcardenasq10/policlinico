$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    // $('.clockpicker').clockpicker();

    $('.input-group.date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayBtn: "linked",
        todayHighlight: true
    });

    //Code to datatables
    $('#datos_tabla, #datos_detalle').DataTable({
        "order": [
            [0, 'desc']
        ],
        "pagingType": "full_numbers",
        "displayLength": 10
    });

    // Code to datatables
    $('#datos_tabla_compra').DataTable({
        "order": [
            [0, 'desc']
        ],
        "pagingType": "full_numbers",
        "displayLength": 10,
        "scrollX": false
    });

    // Oculta el "Select Box" de paginación. 
    $('#datos_tabla_length').html('');
    $('#datos_detalle_length').html('');
    $('#datos_tabla_compra_length').html('');



});