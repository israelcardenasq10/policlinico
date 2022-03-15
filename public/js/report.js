$(document).ready(function() {
    //alert(module_id);
    // --
    $('#btnfiltrar' + module_id).on('click', function() {
        fecha1 = $("#fecha1").val();
        fecha2 = $("#fecha2").val();
        cbo_1 = $("#cbo_1").val();

        if (module_id == 'asistencias' || module_id == 'reportes') {
            if (fecha1 == '' || fecha2 == '') {
                sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
                return false;
            }
        }

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });

        window.location = url_web + module_id + "/filtrar/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
    });

    $('#btnlimpiar' + module_id).on('click', function() {
        $("#fecha1").val('');
        $("#fecha2").val('');
        $("#cbo_1").val("0");
        $("#cbo_1").trigger("change");
        $("#btnExportarExcel" + module_id).hide();
        $("#lista_excel").empty();

        if (module_id == 'compras')
            $("#btnExportarCSV" + module_id).hide();
    });

    $('#btnExportarExcel' + module_id).on('click', function() {
        fecha1 = $("#fecha1").val();
        fecha2 = $("#fecha2").val();
        cbo_1 = $("#cbo_1").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });

        if (module_id == 'reportes')
            window.location = url_web + "/reportes/exportarexcel" + module_id + "1/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
        else
            window.location = url_web + "/reportes/exportarexcel" + module_id + "/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
    });

    // -- To File CSV
    $('#btnExportarCSV' + module_id).on('click', function() {
        fecha1 = $("#fecha1").val();
        fecha2 = $("#fecha2").val();
        cbo_1 = $("#cbo_1").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte CSV de " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });

        window.location = url_web + "/reportes/exportarcsv" + module_id + "/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
    });
    // --



    // REPORTE DEL MODULO DE VENTAS 
    $('#btnfiltrar_venta_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();
        var cbo_1 = $("#cbo_1").val();
        var anulado = $('input:checkbox[name=chkanulado]:checked').val();
        var cbo_2 = $("#id_tp").val();

        if (fecha1 == '' || fecha2 == '') {
            sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
            return false;
        }

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;
        if (anulado == 'undefined') anulado = 0;
        if (cbo_2 == '0') cbo_2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + module_id + "/filtrar/" + fecha1 + "/" + fecha2 + "/" + cbo_1 + "/" + anulado + "/" + cbo_2;
    });

    $('#btnfiltrar_venta_rc_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '' || fecha2 == '') {
            sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
            return false;
        }
        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + module_id + "/filtrar_rc/" + fecha1 + "/" + fecha2;
    });

    $('#btnfiltrar_venta_rdp_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();
        var cbo_1 = $("#cbo_1").val();

        if (fecha1 == '' || fecha2 == '') {
            sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
            return false;
        }
        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + module_id + "/filtrar_rdp/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
    });

    $('#btnfiltrar_venta_bar_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();
        var cbo_1 = $("#cbo_1").val();

        if (fecha1 == '' || fecha2 == '') {
            sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
            return false;
        }
        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + module_id + "/filtrar_bar/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
    });

    $('#btnfiltrar_venta_mb_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '' || fecha2 == '') {
            sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
            return false;
        }
        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + module_id + "/filtrar_mb/" + fecha1 + "/" + fecha2;
    });

    $('#btnfiltrar_venta_ccb_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '' || fecha2 == '') {
            sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
            return false;
        }
        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + module_id + "/filtrar_ccb/" + fecha1 + "/" + fecha2;
    });

    $('#btnfiltrar_venta_mbcom_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '' || fecha2 == '') {
            sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
            return false;
        }
        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + module_id + "/filtrar_mbcom/" + fecha1 + "/" + fecha2;
    });

    $('#btnfiltrar_venta_vfdm_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '' || fecha2 == '') {
            sweetAlert("Por favor ingrese las Fechas!", "Gracias!", "error");
            return false;
        }
        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + module_id + "/filtrar_vfdm/" + fecha1 + "/" + fecha2;
    });

    $('#btnExportarExcel_venta_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();
        var cbo_1 = $("#cbo_1").val();
        var anulado = $('input:checkbox[name=chkanulado]:checked').val();
        var cbo_2 = $("#id_tp").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;
        if (anulado == 'undefined') anulado = 0;
        if (cbo_2 == '0') cbo_2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + "/reportes/exportarexcel" + module_id + "/" + fecha1 + "/" + fecha2 + "/" + cbo_1 + "/" + anulado + "/" + cbo_2;
    });

    $('#btnExportarExcel_venta_rc_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + "/reportes/exportarexcel" + module_id + "RC/" + fecha1 + "/" + fecha2;
    });

    $('#btnExportarExcel_venta_rdp_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();
        var cbo_1 = $("#cbo_1").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + "/reportes/exportarexcel" + module_id + "RDP/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
    });

    $('#btnExportarExcel_venta_bar_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();
        var cbo_1 = $("#cbo_1").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + "/reportes/exportarexcel" + module_id + "Bar/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
    });

    $('#btnExportarExcel_venta_mb_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + "/reportes/exportarexcel" + module_id + "MB/" + fecha1 + "/" + fecha2;
    });

    $('#btnExportarExcel_venta_ccb_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + "/reportes/exportarexcel" + module_id + "CCB/" + fecha1 + "/" + fecha2;
    });

    $('#btnExportarExcel_venta_mbcom_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + "/reportes/exportarexcel" + module_id + "CMBR/" + fecha1 + "/" + fecha2;
    });

    $('#btnExportarExcel_venta_vfdm_' + module_id).on('click', function() {
        var fecha1 = $("#fecha1").val();
        var fecha2 = $("#fecha2").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Generando Reporte " + module_id + "...",
            timer: 3000,
            showConfirmButton: false
        });
        window.location = url_web + "/reportes/exportarexcel" + module_id + "VFDM/" + fecha1 + "/" + fecha2;
    });
    // CIERRA REPORTE MODULO DE VENTAS

});