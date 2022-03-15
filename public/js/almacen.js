$(document).ready(function() {

    //$( '#btnsave_oc' ).prop( "disabled", true );
    //$( '#btnadddeta' ).hide(); // Borrar luego
    $('#msj_valida_d, #msj_valida_m').hide();

    /** Insertar */
    $("#btnadd").on("click", function() {

        if ($("#id_categoria").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Categoria!").slideDown("slow");
            $("#id_categoria").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#id_serv_prov").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Servicio!").slideDown("slow");
            $("#id_serv_prov").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#id_prov").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Proveedor!").slideDown("slow");
            $("#id_prov").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#id_unidad").val() == "0") {
            $("#msj_valida").html("Por favor seleccione una Unidad!").slideDown("slow");
            $("#id_unidad").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#unidad_medida").val() == "") {
            $("#msj_valida").html("Por favor ingrese la Unidad de Medida!").slideDown("slow");
            $("#unidad_medida").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#cantidad").val() == "") {
            $("#msj_valida").html("Por favor ingrese una Cantidad!").slideDown("slow");
            $("#cantidad").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#costo_serv").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Costo del Servicio!").slideDown("slow");
            $("#costo_serv").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#stock_min").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Stock Minimo del Servicio!").slideDown("slow");
            $("#stock_min").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#costo_porcion").val() == "0.000") {
            $("#msj_valida").html("No puede grabar el insumo si tiene un Costo 0!").slideDown("slow");
            $("#costo_porcion").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else {
            $('#msj_valida').removeClass('alert-danger');
            $("#msj_valida").html("<img src='" + url_web_public + "images/admin/loading.gif' style='border: 0px;' />").slideDown("slow");
            $("#btnadd").text('Cargando...');
            $("#btnadd").attr('disabled', true);

            $.ajax({
                url: url_web + module_id + '/insertar',
                type: 'POST',
                data: $("#frm1").serialize(),
                success: function(result) {
                    swal({
                        title: "Excelente!",
                        text: "Se Insertó el registro satisfactoriamente..!",
                        type: "success",
                        closeOnConfirm: true
                    }, function() {
                        window.location.href = url_web + module_id;
                    });

                    $("#msj_valida").slideUp("slow");
                    $("#btnadd").text('Grabar');
                },
                error: function(jqXHR, textStatus, error) {
                    console.log('jqXHR', jqXHR)
                }
            });

        }
    });
    /** ************************* */


    /** Modificar */
    $("#btnMod").on("click", function() {

        if ($("#id_categoria").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Categoria!").slideDown("slow");
            $("#id_categoria").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#id_serv_prov").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Servicio!").slideDown("slow");
            $("#id_serv_prov").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#id_prov").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Proveedor!").slideDown("slow");
            $("#id_prov").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#id_unidad").val() == "0") {
            $("#msj_valida").html("Por favor seleccione una Unidad!").slideDown("slow");
            $("#id_unidad").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#unidad_medida").val() == "") {
            $("#msj_valida").html("Por favor ingrese la Unidad de Medida!").slideDown("slow");
            $("#unidad_medida").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#cantidad").val() == "") {
            $("#msj_valida").html("Por favor ingrese una Cantidad!").slideDown("slow");
            $("#cantidad").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#costo_serv").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Costo del Servicio!").slideDown("slow");
            $("#costo_serv").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#stock_min").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Stock Minimo del Servicio!").slideDown("slow");
            $("#stock_min").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else {
            $('#msj_valida').removeClass('alert-danger');
            $("#msj_valida").html("<img src='" + url_web_public + "images/admin/loading.gif' style='border: 0px;' />").slideDown("slow");
            $("#btnMod").text('Cargando...');
            $("#btnMod").attr('disabled', true);

            $.ajax({
                url: url_web + module_id + '/actualizar',
                type: 'POST',
                data: $("#frm1").serialize(),
                success: function(result) {
                    swal({
                        title: "Excelente!",
                        text: "Se Actualizó el registro satisfactoriamente..!",
                        type: "success",
                        closeOnConfirm: true
                    }, function() {
                        window.location.href = url_web + module_id;
                    });

                    $("#msj_valida").slideUp("slow");
                    $("#btnMod").text('Grabar');
                },
                error: function(jqXHR, textStatus, error) {
                    console.log('jqXHR', jqXHR)
                }
            });
        }
    });


    // -- Mostrar el listado de Servicios por Categoria
    $('#id_categoria').on('change', function() {
        //$( "#div_servicios" ).html('Cargando listado...');
        $('#id_serv_prov').prop('disabled', true);

        $.ajax({
            url: url_web + module_id + '/verlistaserviciosxcat',
            type: 'POST',
            data: { id_categoria: $(this).val() },
            success: function(result) {
                $("#div_servicios").html(result);
            },
            error: function(jqXHR, textStatus, error) {
                console.log('jqXHR', jqXHR)
            }
        });
    });
    // --

    // INSERTAR detalles de la Orden de Compra (OC)
    $("#btnsave_oc").on("click", function() {

        if ($("#person_id").val() == "") {
            $("#msj_valida_d").html("No existe proveedor seleccionado!").slideDown("slow");
            aplicarTiempo("#msj_valida_d");
            return false;
        } else {
            $(this).text('Cargando...');
            $(this).prop("disabled", true);

            $.ajax({
                url: url_web + module_id + '/insertarordencompra',
                type: 'POST',
                data: $("#frm2").serialize(),
                success: function(result_cod) {
                    $("#btnsave_oc").text('Grabar');
                    $("#btnsave_oc").prop("disabled", false);

                    // Cierra de manera automática el Modal!
                    $("#btnclose_oc").trigger("click");

                    // Muestra mensaje OK
                    swal({
                        title: "Excelente!",
                        text: "La Orden de Compra se registró satisfactoriamente con el código " + result_cod,
                        type: "success",
                        closeOnConfirm: true
                    }, function() {
                        window.location.href = url_web + module_id;
                    });
                },
                error: function(jqXHR, textStatus, error) {
                    $('#msj_valida').addClass('alert-danger');
                    $('#msj_valida').html(jqXHR.responseText);
                }
            });
        }
    });
    // --


    // PROCESO DE MERMAS
    $("#btnsave_merma").on("click", function() {

        if ($("#stock_merma").val() == "") {
            $("#msj_valida_m").html("Por favor ingrese la Cantidad de MERMA!").slideDown("slow");
            aplicarTiempo("#msj_valida_m");
            return false;
        } else {
            $(this).text('Cargando...');
            $(this).prop("disabled", true);

            $.ajax({
                url: url_web + module_id + '/insertarAlmMerma',
                type: 'POST',
                data: $("#frm3").serialize(),
                success: function(result_cod) {
                    $("#btnsave_merma").text('Grabar');
                    $("#btnsave_merma").prop("disabled", false);

                    // Cierra de manera automática el Modal!
                    $("#btnclose_merma").trigger("click");

                    // Muestra mensaje OK
                    swal({
                        title: "Excelente!",
                        text: "La MERMA se registró satisfactoriamente!",
                        type: "success",
                        closeOnConfirm: true
                    }, function() {
                        window.location.href = url_web + module_id;
                    });
                },
                error: function(jqXHR, textStatus, error) {
                    $('#msj_valida_m').addClass('alert-danger');
                    $('#msj_valida_m').html(jqXHR.responseText);
                }
            });
        }
    });

    $('#btnfiltrar' + module_id).on('click', function() {
        fecha1 = $("#fecha1").val();
        fecha2 = $("#fecha2").val();
        cbo_1 = $("#cbo_1").val();

        if (fecha1 == '') fecha1 = 0;
        if (fecha2 == '') fecha2 = 0;
        if (cbo_1 == '0') cbo_1 = 0;

        swal({
            title: "Modulo de Reportes!",
            text: "Por favor espere mientras carga el Reporte.",
            timer: 3000,
            showConfirmButton: false
        });

        window.location = url_web + module_id + "/filtrarmermas/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
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
            text: "Generando Reporte Mermas...",
            timer: 3000,
            showConfirmButton: false
        });

        window.location = url_web + "/reportes/exportarexcelmermas/" + fecha1 + "/" + fecha2 + "/" + cbo_1;
    });
    // --


    // MODIFICAR detalles de la Orden de Compra (OC)
    $("#btnsave_mod_oc").on("click", function() {

        if ($("#person_id").val() == "") {
            $("#msj_valida_d").html("No existe proveedor seleccionado!").slideDown("slow");
            aplicarTiempo("#msj_valida_d");
            return false;
        } else if ($("#estado").val() == "P") {
            $("#msj_valida_d").html("No se puede guardar la OC en un estado Pendiente!").slideDown("slow");
            aplicarTiempo("#msj_valida_d");
            return false;
        } else {
            $(this).text('Cargando...');
            $(this).prop("disabled", true);

            $.ajax({
                url: url_web + module_id + '/actualizarordencompra',
                type: 'POST',
                data: $("#frm2").serialize(),
                success: function(result_estado) {
                    $("#btnsave_mod_oc").text('Grabar');
                    $("#btnsave_mod_oc").prop("disabled", false);

                    // Cierra de manera automática el Modal!
                    $("#btnclose_oc").trigger("click");

                    if (result_estado == 'Conciliado') {
                        // Muestra mensaje OK
                        swal({
                            title: "Conciliado!",
                            text: "Se procesó la transacción satisfactoriamente. Su Almacen está actualizado.",
                            type: "success",
                            closeOnConfirm: true
                        }, function() {
                            window.location.href = url_web + module_id + '/listarordenescompra';
                        });
                    } else {
                        swal({
                            title: "Anulado!",
                            text: "Se procesó la transacción satisfactoriamente...",
                            type: "success",
                            closeOnConfirm: true
                        }, function() {
                            window.location.href = url_web + module_id + '/listarordenescompra';
                        });
                    }

                },
                error: function(jqXHR, textStatus, error) {
                    $('#msj_valida').addClass('alert-danger');
                    $('#msj_valida').html(jqXHR.responseText);
                }
            });
        }
    });
    // --


    $('#id_unidad').on('change', function() {
        if ($(this).val() == 1)
            $('#unidad_medida').val(1);
        else if ($(this).val() == 2)
            $('#unidad_medida').val(1000);
        else if ($(this).val() == 6)
            $('#unidad_medida').val(1000);
        else
            $('#unidad_medida').val(1);

        $("#unidad_medida").trigger("keyup");
        //$('#cantidad, #valor_porcion, #costo_serv, #stock_porcion, #costo_porcion').val('');
    });


    // PROCESO DE CALCULO (Cantidad, Costo Unit., Stock Ins. y Costo Ins.) ALMACEN
    $('#cantidad').on('keyup', function() {
        $.ajax({
            type: 'POST',
            url: url_web + module_id + '/calcularstockalmacen',
            data: {
                id_unidad: $('#id_unidad').val(),
                cantidad: $('#cantidad').val(),
                unidad_medida: $('#unidad_medida').val(),
                valor_porcion: $('#valor_porcion').val()
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                //alert(JSON.stringify(data));
                $("#stock_porcion").val(data[0].stock_insumo);
            },
            error: function(jqXHR, textStatus, error) {
                console.log('jqXHR', jqXHR)
            }
        });
    });

    $('#costo_serv').on('keyup', function() {
        $.ajax({
            type: 'POST',
            url: url_web + module_id + '/calcularcostoservalmacen',
            data: {
                id_unidad: $('#id_unidad').val(),
                cantidad: $('#cantidad').val(),
                costo_serv: $('#costo_serv').val(),
                unidad_medida: $('#unidad_medida').val(),
                valor_porcion: $('#valor_porcion').val()
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                $("#costo_porcion").val(data[0].costo_insumo);
            },
            error: function(jqXHR, textStatus, error) {
                console.log('jqXHR', jqXHR)
            }
        });
    });

    $('#valor_porcion, #unidad_medida').on('keyup', function() {
        //$( '#unidad_medida' ).on('keyup', function(){
        $.ajax({
            type: 'POST',
            url: url_web + module_id + '/calcularstockcostosalmacen',
            data: {
                id_unidad: $('#id_unidad').val(),
                cantidad: $('#cantidad').val(),
                costo_serv: $('#costo_serv').val(),
                unidad_medida: $('#unidad_medida').val(),
                valor_porcion: $('#valor_porcion').val()
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                //alert(JSON.stringify(data));
                $("#stock_porcion").val(data[0].stock_insumo);
                $("#costo_porcion").val(data[0].costo_insumo);
            },
            error: function(jqXHR, textStatus, error) {
                console.log('jqXHR', jqXHR)
            }
        });
    });


    // --


});


// -- Mostrar el listado de Proveedores por Servicio
function verProveedoresXServ(id_serv_prov) {
    $('#id_prov').prop('disabled', true);

    $.ajax({
        url: url_web + module_id + '/verlistaproveedoresxserv',
        type: 'POST',
        data: { id_serv_prov: id_serv_prov },
        success: function(result) {
            $("#div_proveedores").html(result);
        },
        error: function(jqXHR, textStatus, error) {
            console.log('jqXHR', jqXHR)
        }
    });
}
// --

// -- Proceso Orden de Compra
function generarOC(prov_id, proveedor, id_serv_prov, id_almacen) {
    $("#h1_proveedor").text(proveedor);
    $("#person_id").val(prov_id);
    $("#id_almacen").val(id_almacen);
    $("#hd_proveedor").val(proveedor);

    $.ajax({
        url: url_web + module_id + '/verlistaservicios',
        type: 'POST',
        data: {
            prov_id: prov_id,
            id_serv_prov: id_serv_prov
        },
        success: function(result) {
            $("#div_serv_prov").html(result);
        },
        error: function(jqXHR, textStatus, error) {
            console.log('jqXHR', jqXHR)
        }
    });
}

function generarMerma(servicio, id_serv_prov, id_almacen) {
    $("#h1_servicio").text(servicio);
    $("#id_almacen").val(id_almacen);
    //$( "#hd_proveedor" ).val(proveedor);

    $.ajax({
        url: url_web + module_id + '/verFormMerma',
        type: 'POST',
        data: {
            id_almacen: id_almacen,
            id_serv_prov: id_serv_prov
        },
        success: function(result) {
            $("#div_serv_merma").html(result);
        },
        error: function(jqXHR, textStatus, error) {
            console.log('jqXHR', jqXHR)
        }
    });
}


function verOCGenerado(prov_id, proveedor, num_oc, estado) {
    $("#h1_proveedor").text(proveedor);
    $("#person_id").val(prov_id);
    $("#num_oc").val(num_oc);
    $("#hd_proveedor").val(proveedor);

    if (estado == 'A' || estado == 'C')
        $("#btnsave_mod_oc").prop("disabled", true);
    else
        $("#btnsave_mod_oc").prop("disabled", false);

    $("#div_serv_prov").html('Espero mientras carga el listado...');
    $.ajax({
        url: url_web + module_id + '/verlistaserviciosoc',
        type: 'POST',
        data: {
            prov_id: prov_id,
            num_oc: num_oc,
            estado: estado
        },
        success: function(result) {
            $("#div_serv_prov").html(result);
        },
        error: function(jqXHR, textStatus, error) {
            console.log('jqXHR', jqXHR)
        }
    });
}

function calcularCostos(id_serv_prov) {
    var total_unidad = $("#precio" + id_serv_prov).val();
    var cantidad = $("#cantidad" + id_serv_prov).val();

    if (cantidad.length > 0)
        var total = (cantidad * total_unidad);
    else
        var total = (0 * total_unidad);

    if (total !== 0)
        $('#btnsave_oc').prop("disabled", false);

    $("#total" + id_serv_prov).val(total.toFixed(2));
    $("#label_total" + id_serv_prov).text(total.toFixed(2));
}
// --


// Eliminar Insumo de Almacen
function eliminarRegAlmacen(id, id_serv_prov) {
    swal({
        title: "Esta seguro?",
        text: "No podrá recuperar la información...!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Eliminar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false,
        showLoaderOnConfirm: true
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: url_web + module_id + '/eliminar/',
                type: 'POST',
                data: {
                    id: id,
                    id_serv_prov: id_serv_prov
                },
                success: function(result) {
                        if (result == 'error_existe_relacion') {
                            sweetAlert("Error...!", "No se puede Eliminar la información, ya cuenta con Ordenes Asociadas!", "error");
                        } else if (result == 'error_cantidad') {
                            sweetAlert("Error...!", "No se puede Eliminar la información, todavía le queda STOCK!", "error");
                        } else {
                            swal("Eliminado!", "Se Eliminó el registro satisfactoriamente!", "success");
                            location.href = url_web + module_id;
                        }

                    }
                    /*,
                    error: function(jqXHR, textStatus, error)
                    {
                      alert( "Error: " + jqXHR.responseText);
                    }
                    */
            }).fail(function() {
                sweetAlert("Error...!", "No se puede Eliminar la información, ya cuenta con transacciones.", "error");
            });
        } else {
            swal("Cancelado", "La información esta a salvo!", "error");
        }
    });
}
// --