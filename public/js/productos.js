$(document).ready(function() {
    let fecha = moment().format("YYYYMMDDHHmmss");
    console.log('fecha', fecha)

    $('#btnmod_pv').hide();
    $('#btnadddeta').hide();
    $('#msj_valida_d').hide();

    /** Insertar */
    $("#btnadd").on("click", function() {

        if ($("#id_categoria").val() == "0") {
            $("#msj_valida").html("Por favor seleccione una Categoria!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#id_categoria").focus();
            return false;
        } else if ($("#nombre").val() == "") {
            $("#msj_valida").html("Por favor ingrese el nombre del Producto!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#nombre").focus();
            return false;
        } else if ($("#producto_comanda_id").val() == "0") {
            $("#msj_valida").html("Por favor seleccione una Comanda de envio!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#producto_comanda_id").focus();
            return false;
        } else {
            $("#btnadd").prop('disabled', true);

            swal({
                title: "Modulo de Productos!",
                text: "Por favor espere mientras se realiza la Transacción...",
                timer: 3000,
                showConfirmButton: false
            });

            $.ajax({
                url: url_web + module_id + '/insertar',
                type: 'POST',
                data: $("#frm1").serialize(),
                success: function(result_id) {
                    //llama y ejecuta la función subirArchivo();
                    subirArchivosNuevo(result_id);

                    swal("Excelente!", "Se Insertó el registro satisfactoriamente!", "success");
                    $('#btnadddeta').show();

                    $("#btnadd").hide();
                    //$( '#btnmod_pv' ).show();
                    $("#txtid_producto_pv").val(result_id);

                    // Proceso Nuevo detalle
                    $("#id_cab").val(result_id);

                    $("#id_categoria").prop('disabled', true);
                    $("#nombre").prop('readonly', true);
                    $("#nro_producto").prop('readonly', true);

                    $('.icono_fecha').css({ "pointer-events": "none", "color": "rgba(0,0,0,0.1)" });
                    // --
                },
                error: function(jqXHR, textStatus, error) {
                    console.log('jqXHR', jqXHR)
                        //swal("Error", jqXHR.responseText, "error");
                }
            });

        }
    });
    /** ************************* */
    /** Modificar */
    $("#btnMod").on("click", function() {

        var total_producto = parseFloat($("#precio_venta").val());
        var total_insumo = parseFloat($('#precio_insumo').val());

        if ($("#nombre").val() == "") {
            $("#msj_valida").html("Por favor ingrese el nombre del Producto!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#nombre").focus();
            return false;
        } else if ($("#precio_venta").val() == "") {
            $("#msj_valida").html("Por favor ingrese un Precio de Venta!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#precio_venta").focus();
            return false;
        } else if ($("#producto_comanda_id").val() == "0") {
            $("#msj_valida").html("Por favor seleccione una Comanda de envio!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#producto_comanda_id").focus();
            return false;
        } else if (total_producto.toFixed(2) < total_insumo[1]) {
            swal("Mensaje!", "El Costo del Producto debe ser mayor al Costo de Insumo!", "warning");
            $("#producto_comanda_id").focus();
            return false;
        } else {
            $("#btnMod").prop('disabled', true);

            $.ajax({
                url: url_web + module_id + '/actualizar',
                type: 'POST',
                data: $("#frm1").serialize(),
                success: function() {
                    subirArchivos();

                    swal("Excelente!", "Se Actualizó la información satisfactoriamente!", "success");
                    $("#btnMod").prop('disabled', false);
                },
                error: function(jqXHR, textStatus, error) {
                    $('#msj_valida').addClass('alert-danger');
                    $('#msj_valida').html(jqXHR.responseText);
                }
            });
        }
    });

    // -- Modificar Precio de Venta
    $("#btnmod_pv").on("click", function() {

        var total_producto = parseFloat($("#precio_venta").val());
        var total_insumo = parseFloat($('#precio_insumo').val());

        if ($("#precio_venta").val() == "") {
            $("#msj_valida").html("Por favor ingrese un Precio de Venta!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#precio_venta").focus();
            return false;
        } else if (total_producto.toFixed(2) < total_insumo[1]) {
            swal("Mensaje!", "El Costo del Producto debe ser mayor al Costo de Insumo!", "warning");
            $("#producto_comanda_id").focus();
            return false;
        } else {
            $("#btnmod_pv").text('Cargando...');
            $("#btnmod_pv").prop("disabled", true);

            $.ajax({
                url: url_web + module_id + '/actualizar',
                type: 'POST',
                data: {
                    id_producto: $("#txtid_producto_pv").val(),
                    precio_venta: $("#precio_venta").val()
                },
                success: function() {
                    swal("Excelente!", "Se actualizó el Precio de Venta satisfactoriamente!", "success");
                    $("#precio_venta").prop('readonly', true);

                    $("#btnmod_pv").text('Grabar Precio').hide();
                    //$( "#btnmod_pv" ).prop('disabled', false);
                    $("#btnadddeta").prop('disabled', true).hide();
                },
                error: function(jqXHR, textStatus, error) {
                    $('#msj_valida').addClass('alert-danger');
                    $('#msj_valida').html(jqXHR.responseText);
                }
            });
        }
    });

    // -- Mostrar el listado de Servicios por Proveedor
    $('#id_almacen').on('change', function() {
        $.ajax({
            url: url_web + module_id + '/verunidadservicio',
            type: 'POST',
            data: { id_almacen: $(this).val() },
            dataType: 'json',
            success: function(data) {
                if (data[0].estado != 'sin_datos') {
                    $("#unidad").val(data[0].valor);

                    if (data[0].valor == 'LTS') //Litros
                    {
                        $("#valor_porcion").attr('readonly', false);
                        $("#div_text_porcion").text('Mlts.');
                    } else if (data[0].valor == 'KLG') //Kilos
                    {
                        $("#valor_porcion").attr('readonly', false);
                        $("#div_text_porcion").text('Klgs.');
                    } else if (data[0].valor == 'UND') //Unidad
                    {
                        $("#valor_porcion").attr('readonly', false);
                        $("#div_text_porcion").text('Und.');
                    } else if (data[0].valor == 'GRM') //Unidad
                    {
                        $("#valor_porcion").attr('readonly', false);
                        $("#div_text_porcion").text('Grms.');
                    } else {
                        $("#valor_porcion").attr('readonly', true);
                        $("#div_text_porcion").text('Porción');
                    }

                    $("#valor_porcion").val(data[0].valor_insumo);
                    $("#stock_porcion").val(data[0].stock_insumo);
                    $("#hd_costo_porcion").val(data[0].costo_insumo);
                    //$("#costo_porcion").val(data[0].costo_insumo);

                    var total = ($("#hd_costo_porcion").val() * data[0].valor_insumo);
                    $("#costo_porcion").val(total.toFixed(3));
                } else {
                    $("#unidad").val('--');
                    $("#valor_porcion").val('0');
                    $("#stock_porcion").val('0');
                    $("#hd_costo_porcion").val('0.000');
                    $("#costo_porcion").val('0.000');
                }
            },
            error: function(jqXHR, textStatus, error) {
                console.log('jqXHR', jqXHR)
            }
        });
    });

    // -- Eventos para calculos de valor_porcion
    $('#valor_porcion').on('keyup', function() {
        var total = ($("#hd_costo_porcion").val() * $(this).val());
        $("#costo_porcion").val(total.toFixed(3));
    });

    $('#btnempty_deta').on('click', function() {
        $("#id_almacen").val(0);
        $("#unidad").val('');
        $("#valor_porcion").val('0');
        $("#stock_porcion").val('0');
        $("#hd_costo_porcion").val('0.000');
        $("#costo_porcion").val('0.000');
    });
    // --


    // Guarda detalles de la Compra
    $("#btnsave_deta").on("click", function() {

        if ($("#id_almacen").val() == "0") {
            $("#msj_valida_d").html("Por favor seleccione un Servicio!").slideDown("slow");
            aplicarTiempo("#msj_valida_d");
            return false;
        } else {
            $("#btnsave_deta").text('Cargando...');
            $("#btnsave_deta").prop("disabled", true);

            if ($('.id_mod').val() === '') {
                var v_accion = 'insertarDetalle';
                var v_mensaje = 'Insertó';
            } else {
                var v_accion = 'actualizar'; //No tendrá Actualizar por detalle.
                var v_mensaje = 'Actualizó';
            }

            $.ajax({
                url: url_web + module_id + '/' + v_accion,
                type: 'POST',
                data: $("#frm2").serialize(),
                success: function(result) {
                    //$('#data_listado').html(result).slideDown();
                    $('#lista_deta').html(result);

                    $("#btnsave_deta").text('Grabar');
                    $("#btnsave_deta").prop("disabled", false);

                    // Cierra de manera automática el Modal!
                    $("#btnclose_deta").trigger("click");

                    $("#id_almacen").val(0);
                    $("#unidad").val('');
                    $("#valor_porcion").val('');
                    $("#stock_porcion").val('');
                    $("#hd_costo_porcion").val('');
                    $("#costo_porcion").val('');

                    $('#btnmod_pv').show();
                    $("#precio_venta").prop("readonly", false);

                    // Muestra mensaje OK
                    swal("Excelente!", "Se " + v_mensaje + " el registro satisfactoriamente!", "success");

                },
                error: function(jqXHR, textStatus, error) {
                    $('#msj_valida').addClass('alert-danger');
                    $('#msj_valida').html(jqXHR.responseText);
                }
            });
        }
    });

});



function eliminardetalle(id, accion) {
    $('tr').removeClass('selected')
    $('#service' + id).addClass('selected');

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
                url: url_web + module_id + '/' + accion,
                type: "POST",
                data: { id: id },
                success: function(result) {
                    //$( '#msj_valida' ).empty().hide();
                    swal("Eliminado!", "Se Eliminó el registro satisfactoriamente!", "success");

                    $('#service' + id).remove();

                    //Muestra de nuevo el listado
                    $('#lista_deta').html(result);

                },
                error: function(jqXHR, textStatus, error) {
                    $('#msj_valida').addClass('alert-danger');
                    $('#msj_valida').html(jqXHR.responseText);
                }
            });
        } else {
            swal("Cancelado", "La información esta a salvo!", "error");
        }
    });
}

function verNroProducto(id_categoria) {
    $.ajax({
        type: "POST",
        url: url_web + module_id + '/verNroProducto',
        data: { id_categoria: id_categoria },
        dataType: 'json',
        success: function(data) {
            $("#nro_producto").val(data[0].nro_producto);
        }
    });
}