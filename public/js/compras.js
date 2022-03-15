$(document).ready(function() {

    $('#btnadddeta').hide();
    $('#msj_valida_d').hide();

    /** Insertar Cab */
    $("#btnadd").on("click", function() {

        if ($("#cbotipo_doc").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Tipo Doc. Proveedor!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#cbotipo_doc").focus();
            return false;
        } else if ($("#prov_id").val() == "") {
            $("#msj_valida").html("Por favor ingrese Nro. Documento!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#nro_doc").focus();
            return false;
        } else if ($("#tipo_doc").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Tipo Doc!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#tipo_doc").focus();
            return false;
        } else if ($("#condicion").val() == "") {
            $("#msj_valida").html("Por favor ingrese la condición!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#condicion").focus();
            return false;
        } else if ($("#doc_serie").val() == "") {
            $("#msj_valida").html("Por favor ingrese la Serie Doc.!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#doc_serie").focus();
            return false;
        } else if ($("#doc_numero").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Número Doc.!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#doc_numero").focus();
            return false;
        } else if ($("#fecha_compra").val() == "") {
            $("#msj_valida").html("Por favor ingrese la Fecha de Compra!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#fecha_compra").focus();
            return false;
        } else if ($("#tipo_cambio").val() == "") {
            $("#msj_valida").html("Por favor ingrese una Fecha de Compra valida para obtener el TC!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#tipo_cambio").focus();
            return false;
        } else if ($("#estado_compra").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Estado!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            return false;
        } else {
            $("#btnadd").prop('disabled', true);

            swal({
                title: "Modulo de Compras!",
                text: "Por favor espere mientras se realiza la Transacción...",
                timer: 3000,
                showConfirmButton: false
            });

            $.ajax({
                url: url_web + module_id + '/insertarcab',
                type: 'POST',
                data: $("#frm1").serialize(),
                success: function(result_id) {
                    // Verificar dato duplicado
                    if (result_id == 'existe') {
                        sweetAlert("Error...!", "El Nro. Documento ya existe en el sistema!!", "error");
                        $("#btnadd").prop('disabled', false);
                    } else {
                        if ($("#id_oc").val() != 'NO') {
                            swal({
                                title: "Actualizado!",
                                text: "Su O/C fué asociado satisfactoriamente a su Compra!",
                                type: "success",
                                closeOnConfirm: true
                            }, function() {
                                window.location.href = url_web + module_id;
                            });

                            $("#id_oc").prop('disabled', true);
                        } else {
                            swal("Excelente!", "Se Insertó el registro satisfactoriamente!", "success");
                            $('#btnadddeta').show();
                        }

                        $("#btnadd").prop('disabled', true);

                        // Proceso Nuevo detalle
                        $("#id_cab").val(result_id);

                        //$( '#fecha_vence' ).val($( '#fecha_compra' ).val());
                        $("#nro_ruc").prop('readonly', true);
                        $("#condicion").prop('readonly', true);
                        $("#fecha_vence").prop('disabled', true);
                        $("#doc_serie").prop('readonly', true);
                        $("#doc_numero").prop('readonly', true);
                        $("#fecha_compra").prop('disabled', true);

                        $("#tipo_doc").prop('disabled', true);
                        $("#moneda").prop('disabled', true);

                        $('.icono_fecha').css({ "pointer-events": "none", "color": "rgba(0,0,0,0.1)" });
                    }
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

        if ($("#estado_compra").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Estado!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            return false;
        } else {
            swal({
                title: "Desea Continuar?",
                text: "No podrá revertir una vez guardado la información...!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#007967",
                confirmButtonText: "Continuar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
            }, function(isConfirm) {
                if (isConfirm) {
                    $("#btnMod").prop('disabled', true);

                    $.ajax({
                        url: url_web + module_id + '/actualizar',
                        type: 'POST',
                        data: $("#frm1").serialize(),
                        success: function() {
                            swal("Excelente!", "El doc. de Compra se actualizó en un estado " + $('#estado_compra').val() + " satisfactoriamente!", "success");
                            $("#btnMod").prop('disabled', true);
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
    });



    // -- Buscar Datos por RUC del proveedor
    $('#cbotipo_doc').on('change', function() {
        if ($(this).val() == '0') {
            $('#nro_ruc').attr("readonly", true);
        } else if ($(this).val() == 'DNI') {
            $('#nro_ruc').attr("readonly", false);
            $('#nro_ruc').attr('maxlength', '8');
        } else {
            $('#nro_ruc').attr("readonly", false);
            $('#nro_ruc').attr('maxlength', '11');
        }

        $('#nro_ruc').val('');
    });

    $('#nro_ruc').on('blur', function() {
        if ($(this).val().length >= 8) {
            var ruc = $(this).val();
            var valor_campos;

            $.ajax({
                type: 'POST',
                url: url_web + module_id + '/verproveedor',
                data: { ruc: ruc },
                dataType: 'json',
                success: function(data) {
                    //alert(JSON.stringify(data));
                    //Desplaza Campos recibidos del JSON php
                    if (data[0].estado == 'error') {
                        $("#prov_id").val('');
                        $("#razon_social").val('');
                        $("#tipo_doc").val(0);
                        $("#estado_compra").val(0);

                        $("#label_oc").hide();
                        $("#div_id_oc").html('').hide();
                    } else {
                        $("#prov_id").val(data[0].person_id);
                        $("#razon_social").val(data[0].razon_social);
                        $("#tipo_doc").val(data[0].id_pref_1);
                        $("#estado_compra").val('Pendiente');
                        //$( "#tipo_doc" ).trigger( "change" );

                        // Muestra listado de OC por Proveedor
                        $("#label_oc").show();
                        $("#div_id_oc").show();
                        $.ajax({
                            url: url_web + module_id + '/verocxproveedor',
                            type: 'POST',
                            data: { person_id: data[0].person_id },
                            success: function(result) {
                                console.log(result);
                                $("#div_id_oc").html(result);

                                if ($("#id_oc").val() != 'NO')
                                    $("#estado_compra").val('Cancelado');
                                else
                                    $("#estado_compra").val('Pendiente');

                            },
                            error: function(jqXHR, textStatus, error) {
                                console.log('jqXHR', jqXHR)
                            }
                        });
                        // --
                    }
                },
                error: function(jqXHR, textStatus, error) {
                    console.log('jqXHR', jqXHR)
                }
            });

        }
    });

    $('#fecha_compra').on('change', function() {
        $.ajax({
            url: url_web + module_id + '/vertcxfecha',
            type: 'POST',
            data: { fecha_compra: $(this).val() },
            dataType: 'json',
            success: function(data) {
                console.log('data', data)
                $("#tipo_cambio").val(3.82);
                // $("#tipo_cambio").val(data[0].venta);
            },
            error: function(jqXHR, textStatus, error) {
                console.log('jqXHR', jqXHR)
            }
        });
        $('#fecha_vence').val($('#fecha_compra').val());
    });
    // --

    // -- Mostrar el listado de Servicios por Proveedor
    $('#btnadddeta').on('click', function() {
        $.ajax({
            url: url_web + module_id + '/verlistaservicios',
            type: 'POST',
            data: { prov_id: $("#prov_id").val() },
            success: function(result) {
                $("#div_serv_prov").html(result);
            },
            error: function(jqXHR, textStatus, error) {
                console.log('jqXHR', jqXHR)
            }
        });
    });

    // -- Eventos para calculos en los detalles de la Compra
    $('#cantidad').on('keyup', function() {
        if ($(this).val().length > 0) {
            $("#precio").prop('readonly', false);

            if ($('#id_unidad option:selected').html() === 'GRM') {
                var total_unidad = ($("#precio").val() / 1000);
            } else {
                var total_unidad = $("#precio").val(); //$(this).val();
            }
            var total = ($(this).val() * total_unidad);
            $("#total").val(total.toFixed(2));
        } else {
            $("#precio").prop('readonly', true);

            var total = 0;
            $("#total").val(total.toFixed(2));
        }
    });

    $('#precio').on('keyup', function() {
        if ($('#id_unidad option:selected').html() === 'GRM') {
            var total_unidad = ($(this).val() / 1000);
        } else {
            var total_unidad = $(this).val();
        }
        var total = ($('#cantidad').val() * total_unidad);
        $("#total").val(total.toFixed(2));
    });

    $('#id_unidad').on('change', function() {
        $("#cantidad").val('');
        $("#precio").val('');
        $("#total").val('');
    });

    $('#btnempty_deta').on('click', function() {
        $("#div_serv_prov").val(0);
        $("#id_unidad").val(0);
        $("#cantidad").val('');
        $("#precio").val('');
        $("#total").val('');
        $(".rb_tp").removeAttr('checked');
    });
    // --


    // Guarda detalles de la Compra
    $("#btnsave_deta").on("click", function() {

        if ($("#id_serv_prov").val() == "0") {
            $("#msj_valida_d").html("Por favor seleccione un Servicio!").slideDown("slow");
            aplicarTiempo("#msj_valida_d");
            return false;
        } else if ($("#id_unidad").val() == "0") {
            $("#msj_valida_d").html("Por favor seleccione una Unidad!").slideDown("slow");
            aplicarTiempo("#msj_valida_d");
            return false;
        } else if ($("#cantidad").val() == "") {
            $("#msj_valida_d").html("Por favor ingrese la Cantidad!").slideDown("slow");
            aplicarTiempo("#msj_valida_d");
            $("#cantidad").focus();
            return false;
        } else {
            $("#btnsave_deta").text('Cargando...');
            $("#btnsave_deta").prop("disabled", true);

            if ($('.id_mod').val() === '') {
                var v_accion = 'insertarDetalle';
                var v_mensaje = 'Insertó';
            } else {
                var v_accion = 'actualizar'; //No tendrá Actualizar por detalle de la Compra.
                var v_mensaje = 'Actualizó';
            }

            $.ajax({
                url: url_web + module_id + '/' + v_accion,
                type: 'POST',
                data: $("#frm2").serialize(),
                success: function(result) {
                    //$('#data_listado').html(result).slideDown();
                    $('#lista_deta').html(result);

                    /*
                    $( '#datos_tabla_ajax' ).DataTable({
                      "order": [[ 0, 'desc' ]],
                      "pagingType": "full_numbers",
                      "displayLength": 10
                    });
                    */

                    $("#btnsave_deta").text('Grabar');
                    $("#btnsave_deta").prop("disabled", false);

                    // Cierra de manera automática el Modal!
                    $("#btnclose_deta").trigger("click");

                    $("#cantidad").val('');
                    $("#precio").val('');
                    $("#total").val('');
                    $("#div_serv_prov").val(0);
                    $("#id_unidad").val(0);

                    //$( "input.rb_tp" ).prop( "disabled", true );
                    if ($("#inafecto").is(':checked')) {
                        $("#igv").attr("disabled", true);
                    } else if ($("#igv").is(':checked')) {
                        $("#inafecto").attr("disabled", true);
                    } else {
                        $("#inafecto").attr("disabled", true);
                        $("#igv").attr("disabled", true);
                    }

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

                    //Obtiene el ID del datatable()
                    //var id_table = $( "table" ).attr("id");

                    //Proceso para eliminar registro del datatable();
                    //var table = $( '#' + id_table ).DataTable();
                    //table.row('.selected').remove().draw( false );

                    //Muestra de nuevo el listado
                    $('#lista_deta').html(result);
                    /*
                    $( '#datos_tabla_ajax' ).DataTable({
                      "paging":   false,
                      "ordering": false,
                      "info":     false
                    });
                    */
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