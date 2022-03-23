$(document).ready(function() {
    //alert('11111');
    // let fecha = moment().format('YYYY-MM-DD')
    // console.log('fecha', fecha)
    jQuery("#nro_doc").on('input', function(evt) {
        // Allow only numbers.
        jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
    })
    $("#btnsave").prop('disabled', true);
    $("#btncomanda").prop('disabled', true);
    $("#btndividir_cuenta").prop('disabled', true);
    $("#btncambiar_mesa").prop('disabled', true);

    $("#terminar").hide();
    $("#ventas_dia").hide();
    $("#div_nota_comanda, #txt_nota_prod").hide();
    $("#div-pago-dif, #spam-pago_dif, #spam-vuelto_dif").hide();
    $("#razon_social").attr('disabled', 'disabled');
    // Global
    var opcion_venta = '3'; // Es la opción de venta que tendra por default.

    // -- DATATABLES
    $('#tb_lista_prod').DataTable({
        "paging": false,
        "ordering": false,
        "scrollY": "220px",
        "scrollCollapse": true,
        "info": false
    });

    $('#tb_lista_prod_dc').DataTable({
        "paging": false,
        "ordering": false,
        "scrollY": "250px",
        "scrollCollapse": true,
        "info": false
    });
    //$('#tb_lista_prod_length, #tb_lista_prod_filter, #tb_lista_prod_info, #tb_lista_prod_paginate').hide();
    $('#tb_lista_prod_filter, #tb_lista_prod_dc_filter').hide();

    $('#tab_mesas').click(function() {
        $.ajax({
            url: url_web + module_id + '/listarMesas',
            type: 'POST',
            cache: false,
            data: {},
            success: function(result) {
                $("#mesas").html(result);
            },
            error: function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseText);
            }
        });
    });

    $('#tab_categorias').click(function() {
        $.ajax({
            url: url_web + module_id + '/verCategorias',
            type: 'POST',
            cache: false,
            data: {},
            success: function(result) {
                $("#pos").html(result);
                //$( "#category2003" ).css({'opacity':'0.5'});
                $("#category1001").click(); //Click automatico
            },
            error: function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseText);
            }
        });
    });

    // --
    $('#btnnotaprod_otro').click(function() {
        $(this).hide();
        $('#txt_nota_prod').show().focus();
    });

    $('#txt_nota_prod').on("blur", function() {
        $('#btnnotaprod_otro').show();
        var id_tmp_cab = $('#hdid_tmp_cab').val();
        var nota_comanda = $('#txt_nota_prod').val().trim();
        $('#txt_nota_prod').hide();
        if (nota_comanda == '') {
            $('#txt_nota_prod').hide();
        } else {
            $.ajax({
                type: 'POST',
                cache: false,
                url: url_web + module_id + '/agregarCampoNotaProd',
                data: {
                    id_tmp_cab: id_tmp_cab,
                    nota_comanda: nota_comanda
                },
                dataType: 'json',
                success: function(data) {
                    if (data[0].id_tmp_cab == -1) {
                        swal("Punto de Venta", "COMANDA generada. No se puede agregar notas a este producto!", "warning")
                    } else {
                        var t_prod = $('#tb_lista_prod').DataTable();
                        t_prod.row.add([
                            '<button class="btn btn-default btn-delete-prod" onclick="eliminarNotaComandaTMPTpv(this.id);" id="' + data[0].id_tmp_cab + '-' + data[0].correlativo + '-' + data[0].id_nota_comanda + '" style="height: 26px; color: #D82424;font-size: 10px;"><span class="glyphicon glyphicon-remove"></span></button>',
                            '-',
                            '<div style="padding: 0px 0px; font-size: 11px;color: #D82424;">' + data[0].nombre + '</div>',
                            '-',
                            // '-',
                            '-'
                        ]).draw(false);
                        // Baja el Scroll
                        var $scrollBody = $(t_prod.table().node()).parent();
                        $scrollBody.scrollTop($scrollBody.get(0).scrollHeight);
                    }
                    $('#txt_nota_prod').val('');
                },
                error: function(jqXHR, textStatus, error) {
                    console.log(jqXHR.responseText);
                }
            });
        }
    });
    // Proceso DIVIDIR CUENTA
    $('#btndividir_cuenta').on('click', function() {
        var id_tmp_cab = $('#hdid_tmp_cab').val();
        $.ajax({
            type: "POST",
            cache: false,
            url: url_web + module_id + '/listarProductosDCuenta',
            data: { id_tmp_cab: id_tmp_cab },
            dataType: 'json',
            success: function(data_json) {
                // Elimina datos de la tabla
                var table = $('#tb_lista_prod_dc').DataTable();
                table
                    .clear()
                    .draw();
                var t_prod = $('#tb_lista_prod_dc').DataTable();

                $.each(data_json, function(i, item) {

                    if (item.tipo == 'producto') {
                        if (item.dividir_cuenta == 1) {
                            dc_checkbox = 'checked="checked"';
                            dc_disabled = '';
                            dc_style = '';
                        } else if (item.dividir_cuenta == 2) {
                            dc_checkbox = 'checked="checked"';
                            dc_disabled = 'disabled="disabled"';
                            dc_style = 'color:#008014e6;';
                        } else {
                            dc_checkbox = '';
                            dc_disabled = '';
                            dc_style = '';
                        }

                        t_prod.row.add([
                            //'<button class="btn btn-default btn-delete-prod" onclick="eliminarProdTMPTpv(this.id);" id="'+item.id_tmp_cab+'-'+item.correlativo+'"><span class="glyphicon glyphicon-remove"></span></button>',
                            '<div class="checkbox">' +
                            '<label style="font-size: 1.3em">' +
                            '<input class="" type="checkbox" name="chkproducto_dc" value="' + item.id_tmp_cab + '-' + item.correlativo + '" ' + dc_checkbox + ' ' + dc_disabled + '>' +
                            '<span class="cr"><i class="cr-icon fa fa-check" style="' + dc_style + '"></i></span>' +
                            '</label>' +
                            '</div>',
                            item.cantidad,
                            item.nombre,
                            '<div class="text-right">' + item.precio_unitario + '</div>',
                            '<div class="text-right">' + item.precio_total + '</div>'
                        ]).draw(false);

                        var $scrollBody = $(t_prod.table().node()).parent();
                        $scrollBody.scrollTop($scrollBody.get(0).scrollHeight);
                    }
                });
            },
            error: function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseText);
            }
        });
    });
    // --

    // Proceso CAMBIAR MESA  
    $('#btncambiar_mesa').on('click', function() {
        var id_tmp_cab = $('#hdid_tmp_cab').val();
        var td_nro_mesa = $('#td_nro_mesa').text();
        $.ajax({
            url: url_web + module_id + '/listarMesasDispoibleCM',
            type: 'POST',
            cache: false,
            data: {
                id_tmp_cab: id_tmp_cab,
                nro_mesa: td_nro_mesa
            },
            success: function(result) {
                $("#div_dividir_mesas").html(result);
            },
            error: function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseText);
            }
        });
    });

    cambiarMesaReservada = function(id_mesa, id_tmp_cab) {
            //$( "#div_nota_comanda" ).show();
            var td_nro_mesa = $('#td_nro_mesa').text();
            $.ajax({
                type: 'POST',
                cache: false,
                url: url_web + module_id + '/cambiarMesaReservada',
                data: {
                    id_mesa: id_mesa,
                    id_tmp_cab: id_tmp_cab,
                    nro_mesa: td_nro_mesa
                },
                dataType: 'json',
                success: function(data) {
                    $("#btnclose_cm").trigger("click");

                    // Elimina datos de la tabla
                    var table = $('#tb_lista_prod').DataTable();
                    table
                        .clear()
                        .draw();
                    // --

                    //alert(JSON.stringify(data));
                    $("#td_encargado").text(data[0].empleado);
                    $("#td_hora_ini").text(data[0].hora_ini);
                    $("#td_nro_venta").text(data[0].correlativo);
                    $("#td_nro_mesa").text(data[0].nro_mesa);

                    // Pasar valores a los campos Ocultos:
                    $("#hdid_tmp_cab").val(data[0].id_tmp_cab);
                    // --

                    $("#tab_categorias").removeClass("boton_desactiva");
                    $("#tab_categorias").trigger("click");

                    // -- Muestra el listado de Productos por Mesa Reservada
                    listarProductosXMesa(data[0].id_tmp_cab);
                    $('#hd_total_venta').val(data[0].precio_total_venta);
                    $('#h3_total_venta').text(data[0].precio_total_venta);
                    // --

                    $('#btnsave').prop('disabled', false);

                    if ($('#hd_total_venta').val() > 0) {
                        $('#btncomanda').prop('disabled', false);
                        $("#btndividir_cuenta").prop('disabled', false);
                        $("#btncambiar_mesa").prop('disabled', false);

                        $('#btn_pre_venta').prop('disabled', false);
                        $('#btn_pre_venta').css({ 'cursor': 'pointer' });
                    }

                },
                error: function(jqXHR, textStatus, error) {
                    console.log('jqXHR', jqXHR)
                }
            });
        }
        // --


    $("#btncobrar_dc").on("click", function() {

        var arr_producto_dc = [];
        $(":checkbox[name=chkproducto_dc]").each(function() {
            if (this.checked) {
                arr_producto_dc.push($(this).val());
            }
        });

        console.log('arr_producto_dc', arr_producto_dc)
        if (arr_producto_dc.length) {
            //alert(JSON.stringify(arr_producto_dc));
            $.ajax({
                type: "POST",
                cache: false,
                url: url_web + module_id + '/calcularVentaProdDCuenta',
                data: { arr_producto_dc: arr_producto_dc },
                dataType: 'json',
                success: function(data) {
                    $('#hd_total_venta').val(data[0].precio_total_venta)
                    $('#txttotal_venta').val(data[0].precio_total_venta)
                },
                error: function(jqXHR, textStatus, error) {
                    console.log(jqXHR.responseText);
                }
            });

            $("#btnclose_dc").click();

        } else {
            swal("Punto de Venta", "Debes seleccionar al menos una Producto.!", "warning");
            return false;
        }

        $('#btnsave').prop('disabled', true);
        $('#btncomanda').prop('disabled', true);
        $("#btncambiar_mesa").prop('disabled', true);
        $('#btn_pre_venta').prop('disabled', true);
        $('#btn_pre_venta').css({ 'cursor': 'auto' });

        $("#exTab3, #ventas_dia").hide();
        $("#terminar").show();
        //$( "#txttotal_venta" ).val($( "#hd_total_venta" ).val());

        $(".btn-delete-prod").prop('disabled', true);
        $("#tecla_borrar").click();

        $("#div-pagos").show();
        $("#div-pago-dif").hide();
        $("#hdtipo_pago_dif").val('');

        $("#rb" + opcion_venta).prop('checked', 'checked'); // Checked por default "TKT".

        $("#spam-pago, #spam-vuelto").show();
        $("#spam-pago_dif, #spam-vuelto_dif").hide();

        //Marca Pago Efectivo por Default!
        $(".cltipopago").removeClass('btn-primary active').removeClass('btn-default');
        $(".cltipopago").addClass('btn-default');
        $('#1').addClass('btn-primary active');
        $("#hdtipo_pago").val(1);
        // --
    });


    $("#btnlimpiar_dc").on("click", function() {
        var id_tmp_cab = $('#hdid_tmp_cab').val();

        $(":checkbox[name=chkproducto_dc]").each(function() {
            if (this.checked) {
                this.checked = false;
            }
        });
        $(this).html('PROCESANDO..').prop('disabled', true);
        $.ajax({
            type: "POST",
            cache: false,
            url: url_web + module_id + '/limpiarVentaProdDCuenta',
            data: { id_tmp_cab: id_tmp_cab },
            dataType: 'json',
            success: function(data_json) {

                $("#btnlimpiar_dc").html('<span class="glyphicon glyphicon-arrow-up"></span> LIMPIAR').prop('disabled', false);

                // Elimina datos de la tabla
                var table = $('#tb_lista_prod_dc').DataTable();
                table
                    .clear()
                    .draw();
                // --

                var t_prod = $('#tb_lista_prod_dc').DataTable();

                $.each(data_json, function(i, item) {

                    if (item.tipo == 'producto') {
                        if (item.dividir_cuenta == 2) {
                            dc_checkbox = 'checked="checked"';
                            dc_disabled = 'disabled="disabled"';
                            dc_style = 'color:#008014e6;';
                        } else {
                            dc_checkbox = '';
                            dc_disabled = '';
                            dc_style = '';
                        }

                        t_prod.row.add([
                            //'<button class="btn btn-default btn-delete-prod" onclick="eliminarProdTMPTpv(this.id);" id="'+item.id_tmp_cab+'-'+item.correlativo+'"><span class="glyphicon glyphicon-remove"></span></button>',
                            '<div class="checkbox">' +
                            '<label style="font-size: 1.3em">' +
                            '<input class="" type="checkbox" name="chkproducto_dc" value="' + item.id_tmp_cab + '-' + item.correlativo + '" ' + dc_checkbox + ' ' + dc_disabled + '>' +
                            '<span class="cr"><i class="cr-icon fa fa-check" style="' + dc_style + '"></i></span>' +
                            '</label>' +
                            '</div>',
                            item.cantidad,
                            item.nombre,
                            '<div class="text-right">' + item.precio_unitario + '</div>',
                            '<div class="text-right">' + item.precio_total + '</div>'
                        ]).draw(false);
                    }
                });
            },
            error: function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseText);
            }
        });

        $('#btnsave').prop('disabled', false);
        $('#btncomanda').prop('disabled', false);
        $('#btndividir_cuenta').prop('disabled', false);
        $('#btncambiar_mesa').prop('disabled', false);
        $('#btn_pre_venta').prop('disabled', false);
        //$( '#btn_pre_venta' ).css({'cursor' : 'auto'});

        $("#exTab3").fadeIn('slow');
        $("#terminar").hide();

        $('#hd_total_venta').val('0.00');
        $("#txttotal_venta").val('0.00');
        $("#txtpago_cliente").val('0.00');
        $("#txtvuelto_cliente").val('0.00');

        $(".btn-delete-prod").prop('disabled', false);
        //$( ".gris_num" ).prop('disabled', true);
        $("#tecla_borrar").click();
    });
    // --

    // Proceso Cierre de Reserva!
    $("#btnsave").on("click", function() {
        //$( "#btnsave" ).text('Ge..');
        $('#btnsave').prop('disabled', true);
        $('#btncomanda').prop('disabled', true);
        $('#btndividir_cuenta').prop('disabled', true);
        $('#btncambiar_mesa').prop('disabled', true);
        $('#btn_pre_venta').prop('disabled', true);
        $('#btn_pre_venta').css({ 'cursor': 'auto' });

        $("#exTab3, #ventas_dia").hide();
        $("#terminar").show();

        // Muestra el Total a Cobrar
        var id_tmp_cab = $('#hdid_tmp_cab').val();
        $.ajax({
            type: "POST",
            cache: false,
            url: url_web + module_id + '/verCobroProducto',
            data: { id_tmp_cab: id_tmp_cab },
            dataType: 'json',
            success: function(data) {
                $('#hd_total_venta').val(data[0].precio_total_venta);
                $('#txttotal_venta').val(data[0].precio_total_venta);
            },
            error: function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseText);
            }
        });
        // --

        $(".btn-delete-prod").prop('disabled', true);
        $("#tecla_borrar").click();

        $("#div-pagos").show();
        $("#div-pago-dif").hide();
        $("#hdtipo_pago_dif").val('');

        $("#rb" + opcion_venta).prop('checked', 'checked'); // Checked por default "TKT".

        $("#spam-pago, #spam-vuelto").show();
        $("#spam-pago_dif, #spam-vuelto_dif").hide();

        //Marca Pago Efectivo por Default!
        $(".cltipopago").removeClass('btn-primary active').removeClass('btn-default');
        $(".cltipopago").addClass('btn-default');
        $('#1').addClass('btn-primary active');
        $("#hdtipo_pago").val(1);
        // --
        $('#rb2').click();

    });
    // --

    // Proceso de Comanda (Cocina, Barra)
    $("#btncomanda").on("click", function() {
        var id_tmp_cab = $('#hdid_tmp_cab').val();
        var total_venta = $("#hd_total_venta").val();

        if (total_venta > 0) {
            $(this).prop('disabled', true);
            $(this).html('<h5><span class="glyphicon glyphicon-print"></span> </br> *********</h5>');
            //$( this ).css({'pointer-events' : 'none'}); // Desactiva por completo el boton para que no se pueda dar mas de un click!

            $.ajax({
                url: url_web + module_id + '/generarComanda',
                type: 'POST',
                data: {
                    id_tmp_cab: id_tmp_cab,
                    total_venta: total_venta
                },
                success: function(result) {
                    $("#btncomanda").prop('disabled', false);
                    //$( "#btncomanda" ).css({'pointer-events':'none'});
                    $("#btncomanda").html('<h5>GENERAR </br> COMANDA</h5>');
                    $("#btncomanda").removeClass('btn-info').addClass('btn-default');
                    //$( "#btncomanda" ).css({'pointer-events' : 'auto'});
                    /*
                    swal({
                        title: "",
                        text: result,
                        html: true,
                        closeOnConfirm: true
                        }, function(){
                           //window.location.href = url_web + module_id;
                    });
                    */
                },
                error: function(jqXHR, textStatus, error) {
                    console.log(jqXHR.responseText);
                }
            });
        }
    });
    // --

    $(".cltipopago").on("click", this.id, function() {
        $(".cltipopago").removeClass('btn-primary active').removeClass('btn-default');
        $(".cltipopago").addClass('btn-default');
        $('#' + this.id).addClass('btn-primary active');

        $('#hdtipo_pago').val(this.id);

        if (this.id == 6) // Pago Mixto o Diferido
        {
            $("#spam-pago, #spam-vuelto").hide();
            $("#spam-pago_dif, #spam-vuelto_dif").show();

            $("#div-pagos").hide();
            $("#div-pago-dif").show();
        }

    });

    // Pago Diferido
    $(".cltipopago_pdif").on("click", this.id, function() {
        $(".cltipopago_pdif").removeClass('btn-primary active').removeClass('btn-default');
        $(".cltipopago_pdif").addClass('btn-default');

        var pdif_id = this.id;
        $('#' + pdif_id).addClass('btn-primary active');
        $('#hdtipo_pago_dif').val(pdif_id.substr(-1));

        //var pago_dif = Math.abs($( '#txtvuelto_cliente' ).val());
        //$( '#txtvuelto_cliente' ).val(pago_dif.toFixed(2));
    });
    // --

    $("#btngenerarVentaPrint").on("click", function() {

        var id_tmp_cab = $('#hdid_tmp_cab').val();
        var doc_pago = $('input:radio[name=rbdoc_pago]:checked').val();
        var tipo_pago = $('#hdtipo_pago').val();
        var tipo_pago_dif = $('#hdtipo_pago_dif').val() > 0 ? $('#hdtipo_pago_dif').val() : 0; // if tenario

        var total_venta = $('#txttotal_venta').val();
        var pago_cliente = $('#txtpago_cliente').val();
        var vuelto_cliente = $('#txtvuelto_cliente').val();
        var id_cliente = $('#hdid_cliente').val();
        var tipo_doc = $('#tpo_doc').val();

        let nruc = $('#nro_doc').val().trim();
        let rsoc = $('#razon_social').val();

        if (pago_cliente == "0.00") {
            swal("Punto de Venta", "Debe ingresar el pago del Cliente!", "warning");
            return false;
        } else if (parseFloat(pago_cliente) < parseFloat(total_venta) && tipo_pago != 6) {
            swal("Punto de Venta", "El pago del Cliente no puede ser menor al Total!", "warning");
            return false;
        } else if (tipo_pago_dif == '0' && tipo_pago == 6) {
            swal("Punto de Venta", "Debe seleccionar una Tarjeta de Pago!", "warning");
            return false;
        } else if (id_cliente == '' || nruc == '' || rsoc == '') {
            swal("Punto de Venta", "Ingrese los Datos del Cliente!", "warning");
            return false;
        } else if (doc_pago == 1 && (tipo_doc != 'RUC' || nruc.length != 11)) { //factura
            swal("Punto de Venta", "Para generar la Factura debe Ingresar un documeno tipo RUC!", "warning");
            return false;
        }
        /*else if (doc_pago == 2 && (tipo_doc != 'DNI' || nruc.length != 8)) { //boleta
                   swal("Punto de Venta", "Solo puede Generar una Boleta con DNI VALIDO", "warning");
                   return false;
               } */
        else if (parseFloat(vuelto_cliente) > 0 && tipo_pago != 1) { //boleta
            swal("Punto de Venta", "No se puede Tener Vuelto en medio de pago que no sea efectivo", "warning");
            return false;
        } else {
            $(this).attr('disabled', true);
            // Desactiva por completo el boton para que no se pueda dar mas de un click!
            $(this).css({ 'pointer-events': 'none' });
            // --
            $(this).html('<h5><span class="glyphicon glyphicon-print"></span> PROCESANDO..</h5>');

            $.ajax({
                url: url_web + module_id + '/generarVenta', //actualizarVenta
                type: 'POST',
                data: {
                    id_tmp_cab: id_tmp_cab,
                    doc_pago: doc_pago,
                    tipo_pago: tipo_pago,
                    tipo_pago_dif: tipo_pago_dif,
                    total_venta: total_venta,
                    pago_cliente: pago_cliente,
                    vuelto_cliente: vuelto_cliente,
                    id_cliente: id_cliente,
                    nruc: nruc,
                    rsoc: rsoc,
                    tipo_doc: tipo_doc,
                },
                success: function(result) {
                    // console.log('result', result)
                    window.location.href = url_web + module_id;
                    $("#btngenerarVentaPrint").html('<h5><span class="glyphicon glyphicon-print"></span> PRINT VENTA</h5>');
                    if (!result.error) {
                        swal({
                            title: "Punto de Venta",
                            text: "Venta realizada satisfactoriamente!",
                            type: "success",
                            html: true,
                            closeOnConfirm: true,
                            timer: 500, //1000=1segundo
                        }, function() {
                            window.location.href = url_web + module_id;
                        });
                    } else {
                        swal("Punto de Venta", result.error, "warning");
                    }

                },
                error: function(jqXHR, textStatus, error) {
                    console.log(jqXHR.responseText);
                }
            });
        }
    });

    $("#btnretornarCarritoVenta").on("click", function() {
        $('#btnsave').prop('disabled', false);
        $('#btncomanda').prop('disabled', false);
        $('#btndividir_cuenta').prop('disabled', false);
        $('#btncambiar_mesa').prop('disabled', false);
        $('#btn_pre_venta').prop('disabled', false);
        //$( '#btn_pre_venta' ).css({'cursor' : 'auto'});

        $("#exTab3").fadeIn('slow');
        $("#terminar").hide();

        $("#txtpago_cliente").val('0.00');
        $("#txtvuelto_cliente").val('0.00');

        $(".btn-delete-prod").prop('disabled', false);
        //$( ".gris_num" ).prop('disabled', true);
        $("#tecla_borrar").click();
    });

    // --

    $('#btn_borrar_venta').click(function() {
        $("#div_nota_comanda").hide();
        $("#btncomanda").removeClass('btn-default').addClass('btn-info');
        var id_tmp_cab = $('#hdid_tmp_cab').val();
        var nro_mesa = $("#td_nro_mesa").text();
        if (id_tmp_cab) {

            swal({
                title: "Está Seguro?",
                text: "Se eliminara los pedidos ya registrados",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Ingrese un sustento",
                showLoaderOnConfirm: true
            }, function(inputValue) {
                if (inputValue === false) return false;
                else if (inputValue.trim() === "") {
                    swal.showInputError("Necesitas Ingresar Un sustento de la Eliminación");
                    return false
                } else {
                    // swal("Exito!", "se elimino el pedido " + inputValue, "success"); 
                    $.ajax({
                        url: url_web + module_id + '/suprimirVta',
                        type: 'POST',
                        data: { id_tmp_cab: id_tmp_cab, obs: inputValue },
                        success: function() {

                            $.ajax({
                                url: url_web + module_id + '/borraVentaProducto',
                                type: 'POST',
                                data: { id_tmp_cab: id_tmp_cab, nro_mesa: nro_mesa },
                                success: function() {
                                    swal('Eliminado!', '', 'success')
                                    $("#td_encargado").text('--');
                                    $("#td_hora_ini").text('--');
                                    $("#td_nro_venta").text('--');
                                    $("#td_nro_mesa").text('--');
                                    $("#hdid_tmp_cab").val('');
                                    // Elimina datos de la tabla
                                    var table = $('#tb_lista_prod').DataTable();
                                    table.clear().draw();
                                    var total_venta = 0;
                                    $('#hd_total_venta').val(total_venta.toFixed(2));
                                    $('#h3_total_venta').text(total_venta.toFixed(2));

                                    $("#tab_mesas").addClass("boton_desactiva");
                                    $("#tab_categorias").addClass("boton_desactiva");
                                    $("#tab_empleado").click(); //Click automatico

                                    $('#btnsave').prop('disabled', true);
                                    $('#btncomanda').prop('disabled', true);
                                    $('#btn_pre_venta').prop('disabled', true);
                                    $('#btn_pre_venta').css({ 'cursor': 'auto' });
                                },
                                error: function(jqXHR, textStatus, error) {
                                    console.log(jqXHR.responseText);
                                }
                            });
                        }
                    })

                }
            });
        } else {
            swal('No hay Datos a Eliminar!', '', 'warning')
        }
    });

    $("#btn_mostrar_ventas").on("click", function() {
        $("#exTab3, #terminar, #cierres_caja").hide();
        $("#ventas_dia").show();
        let camb_mp = $('#hdid_cambioPago').val()
        $.ajax({
            url: url_web + module_id + '/verVentasXDia',
            type: 'POST',
            data: {},
            success: function(result) {
                // console.log('result', result)
                $('#datos_tabla_ajax').DataTable({
                    "destroy": true,
                    "searching": true,
                    "ordering": false,
                    // "bScrollInfinite": true,
                    "bScrollCollapse": true,
                    "sScrollY": "320px",
                    "paging": false,
                    "order": [
                        [0, "desc"]
                    ],
                    "data": result.data,
                    "columns": [{
                            "render": function(data, type, row) {
                                let btn_anular = `<button class="btn btn-default btn-sm" type="button" onclick="anularVta(${row.id_transac})"><span class="glyphicon glyphicon-remove-circle fa-2x"></span></button>`
                                    // let btn_imp= `<button class="btn btn-default btn-sm" type="button" ><span class="glyphicon glyphicon-print"></span></button>`
                                if (row.anulado == "SI" || row.sfactu.substr(0, 1) == 'F') {
                                    btn_anular = '' //`<button class="btn btn-default btn-sm" type="button" ><span class="glyphicon glyphicon-remove-circle fa-2x"></span></button>`
                                }
                                return btn_anular;
                            }
                        },
                        { "data": "num_doc" },
                        { "data": "fecha_registro" },
                        {
                            "render": function(data, type, row) {

                                return `${row.tp_ruc} : ${row.n_ruc}`;
                            }
                        },
                        { "data": "n_rs" },
                        // { "data": "tipo_pago" },
                        {
                            "render": function(data, type, row) {
                                let valor = `<button class="btn btn-default btn-sm" type="button" onclick="changeMP(${row.id_transac} , ${row.id_tp});"> ${row.tipo_pago} </button>`
                                return (camb_mp == '' || row.id_cierre !== null || row.anulado == "SI") ? row.tipo_pago : valor;
                            }
                        }, {
                            "render": function(data, type, row) {

                                return `${row.id_cierre?'C':'A'}`;
                            }
                        },
                        // { "data": "subtotal_venta" },
                        // { "data": "igv" },
                        { "data": "total_venta" }

                    ],
                    "rowCallback": function(row, data, index) {
                        if (data.anulado == "SI") {
                            $(row).find('td').css('background-color', '#ec6565')
                        }
                    }

                });
                if (result.data.length > 0) {
                    let totalvta = parseFloat(result.total_caja[0].total_venta)
                    $('#c_subtotal').html(totalvta.toFixed(2));
                    $('#btncerrar_caja').prop("disabled", false);
                    $('#btnprint_cambio_turno').prop("disabled", false);
                } else {
                    $('#c_subtotal').html("0.00");
                    $('#btncerrar_caja').prop("disabled", true);
                    $('#btnprint_cambio_turno').prop("disabled", true);

                }
            },
            error: function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseText);
            }
        });
    });

    $("#btn_mostrar_cierrecaja").on("click", function() {
        $("#exTab3, #terminar, #ventas_dia").hide();

        $.ajax({
            url: url_web + module_id + '/verVentasXDia',
            type: 'POST',
            data: {},
            success: function(result) {
                $("#ventas_dia").html(result);
                $("#ventas_dia").show();
            },
            error: function(jqXHR, textStatus, error) {
                console.log(jqXHR.responseText);
            }
        });
    });


    $("#btn_pre_venta").on("click", function() {

        var id_tmp_cab = $('#hdid_tmp_cab').val();
        var total_venta = $("#hd_total_venta").val();

        if (total_venta > 0) {
            $(this).html('<span class="glyphicon glyphicon-download fa-2x"></span><p>Imprimiendo..</p>');
            //$( this ).attr('disabled', true);

            $.ajax({
                url: url_web + module_id + '/generarPreVenta',
                type: 'POST',
                data: {
                    id_tmp_cab: id_tmp_cab,
                    total_venta: total_venta
                },
                success: function(result) {
                    $("#btn_pre_venta").html('<span class="glyphicon glyphicon-print fa-2x"></span><p>Pre Vta</p>');
                },
                error: function(jqXHR, textStatus, error) {
                    console.log(jqXHR.responseText);
                }
            });
        }
    });


    $('#btnsalir').click(function() {
        swal({
                title: "¿Desea salir del Sistema?",
                text: "Click en el boton Salir para continuar...!",
                //type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Salir!",
                closeOnConfirm: false
            },
            function() {
                window.location.href = url_web + module_id + '/salir';
            });
    });

    $('#btn_msg').click(function() {
        $(".bs-example").show();
        $("#hecho, #enviado").hide();
        $("#comentario").val('');
    })

    $("#send_btn").click(function() {
        //alert(url_web + module_id + '/msg');
        $(".bs-example").hide();
        $("#hecho").show();
        $.ajax({
            type: "POST",
            url: url_web + module_id + '/msg',
            //data: { comentario : comentario },
            data: $('#form_msj').serialize(),
            success: function(response) {
                $("#hecho").hide();
                $("#enviado").show();
            }
        });
        //return false;
    });

    // Proceso FACTURA CLIENTES

    $("#bus_prod_codigo").autocomplete({
        source: url_web + '/autocompletado/autocompletarProducto',
        minLength: 3,
        select: function(ev, ui) {
            var data = ui.item.value;
            $("#bus_prod_codigo").val('')
            let x = $('#td_nro_mesa').html()

            if (x == '-') {
                swal("Opps!", "Primero debe seleccionar una SALA", "warning");
            } else {
                if (!data.error) {
                    agregarProducto(data.id_categoria, data.id)
                }
            }
            ev.preventDefault();
        },
        focus: function(ev, ui) {
            var label = ui.item.label;
            $("#bus_prod_codigo").val(label);
            ev.preventDefault();
        }
    });

    $("#nro_doc").autocomplete({
        source: url_web + '/autocompletado/autocompletarClienteRuc',
        minLength: 3,
        select: function(event, ui) {
            var rsocial = ui.item.value;
            if (rsocial.razon_social === 'NO EXISTE CLIENTE!') {
                $('#ruc_rs_form-group').removeClass("has-success has-feedback").addClass('has-error has-feedback');

                $('#btncrearCliente').attr('disabled', false).removeClass("btn-default").addClass("btn-success");
                // $( "#txtrazon_social_bus" ).val(rsocial.razon_social);
                $("#nro_doc").val(rsocial.nro_doc);
                $("#razon_social").val('');
                $("#email").val('');
                $("#hdid_cliente").val('');
                $("#razon_social").removeAttr('disabled')
                $("#tpo_doc").removeAttr('disabled')
            } else {
                $('#ruc_rs_form-group').removeClass("has-error has-feedback").addClass('has-success has-feedback');

                $('#btncrearCliente').attr('disabled', true).removeClass("btn-success").addClass("btn-default");
                // $( "#txtrazon_social_bus" ).val(rsocial.nro_doc);
                $("#nro_doc").val(rsocial.nro_doc);
                $("#razon_social").val(rsocial.razon_social);
                $("#email").val(rsocial.email);
                $("#hdid_cliente").val(rsocial.person_id);
                $("#tpo_doc").val(rsocial.tpo_doc);
                $("#razon_social").attr('disabled', 'disabled');
                $("#tpo_doc").attr('disabled', 'disabled');
            }
            event.preventDefault();
        },
        focus: function(event, ui) {
            var rsocial = ui.item.value;
            $("#nro_doc").val(rsocial.nro_doc);
            $("#razon_social").val(rsocial.razon_social);
            $("#razon_social").attr('disabled', 'disabled');
            $("#tpo_doc").attr('disabled', 'disabled');
            event.preventDefault();
        }
    });

    $("#chkproveedor").click(function() {
        // $( "#txtrazon_social_bus" ).val('');
        $("#nro_doc").val('');
        $("#razon_social").val('');
        $("#email").val('');
        $("#hdid_cliente").val('');
    });

    $("#rb1").click(function() { // Factura
        $('#btncrearCliente').attr('disabled', true).removeClass("btn-success").addClass("btn-default");
    });

    $("#btncrearCliente").click(function() {

        if ($("#nro_doc").val() == "") {
            $("#nro_doc").focus();
            return false;
        } else if ($("#razon_social").val() == "") {
            $("#razon_social").focus();
            return false;
        } else if ($("#tpo_doc").val() == "") {
            $("#tpo_doc").focus();
            return false;
        } else if ($("#nro_doc").val().length != 8 && $("#tpo_doc").val() == 'DNI') {
            $("#tpo_doc").focus();
            swal("Opps!", "El DNI no contiene 8 Caracteres", "warning");
            return false;
        } else if ($("#nro_doc").val().length != 11 && $("#tpo_doc").val() == 'RUC') {
            swal("Opps!", "El RUC no contiene 11 Caracteres", "warning");
            $("#tpo_doc").focus();
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: url_web + '/autocompletado/insertarCliente',
                data: $('#frmcliente').serialize(),
                success: function(person_id) {
                    $('#btncrearCliente').attr('disabled', true).removeClass("btn-success").addClass("btn-default");
                    //$( "#txtrazon_social_bus" ).val('CLIENTE REGISTRADO');
                    $('#ruc_rs_form-group').removeClass("has-error has-feedback").addClass('has-success has-feedback');

                    $("#hdid_cliente").val(person_id);
                    // Cierra de manera automática el Modal!
                    //$( ".close" ).trigger( "click" );
                    $("#razon_social").attr('disabled', 'disabled');
                    swal("Exito!", "Se Registro el Cliente", "success");
                },
                error: function(jqXHR, textStatus, error) {
                    swal("Error!", "No se pudo registrar el Cliente", "warning");
                    // console.log('error',error,'\n');
                    console.log(jqXHR.responseText);
                }
            });
        }
    });
});

function identificarEmpleado(id_emple) {
    $("#btncomanda").removeClass('btn-default').addClass('btn-info');
    $.ajax({
        url: url_web + module_id + '/identificarEmpleadoMesa',
        type: 'POST',
        cache: false,
        data: { id_emple: id_emple },
        success: function(result) {
            $("#td_encargado").text('--');
            $("#td_hora_ini").text('--');
            $("#td_nro_venta").text('--');
            $("#td_nro_mesa").text('--');
            $("#hdid_tmp_cab").val('');

            //alert(result);
            // Elimina datos de la tabla
            var table = $('#tb_lista_prod').DataTable();
            table
                .clear()
                .draw();

            var total_venta = 0;
            $('#hd_total_venta').val(total_venta.toFixed(2));
            $('#h3_total_venta').text(total_venta.toFixed(2));
            // --

            $("#tab_mesas").removeClass("boton_desactiva");
            $("#tab_categorias").addClass("boton_desactiva");
            $("#tab_mesas").click(); //Click automatico
            //$( "#btncambiar_mesa" ).prop('disabled', false);
            //$( "#mesas" ).html(result);

            $('#btn_pre_venta').prop('disabled', true);
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
        }
    });
}

function identificarMesa(id_mesa) {
    $.ajax({
        type: 'POST',
        cache: false,
        url: url_web + module_id + '/identificarMesa',
        data: { id_mesa: id_mesa },
        dataType: 'json',
        success: function(data) {
            //alert(JSON.stringify(data));
            if (data[0].id_tmp_cab == -1) { //la mesa ya está ocupada
                //
                swal({
                    title: "Punto de Venta",
                    text: "Esta mesa ya está en ocupada",
                    type: "warning",
                    html: true,
                    closeOnConfirm: true
                }, function() {
                    window.location.href = url_web + module_id;
                });

            } else {
                $("#td_encargado").text(data[0].empleado);
                $("#td_hora_ini").text(data[0].hora_ini);
                $("#td_nro_venta").text(data[0].correlativo);
                $("#td_nro_mesa").text(data[0].nro_mesa);

                // Pasar valores a los campos Ocultos:
                $("#hdid_tmp_cab").val(data[0].id_tmp_cab);
                // --

                // Elimina datos de la tabla
                var table = $('#tb_lista_prod').DataTable();
                table
                    .clear()
                    .draw();

                var total_venta = 0;
                $('#hd_total_venta').val(total_venta.toFixed(2));
                $('#h3_total_venta').text(total_venta.toFixed(2));

                $("#tab_categorias").removeClass("boton_desactiva");
                $("#tab_categorias").trigger("click");
            }


        },
        error: function(jqXHR, textStatus, error) {
            //console.log('jqXHR', jqXHR)
            console.log(jqXHR.responseText);
        }
    });
}

function identificarMesaReservada(id_emple, id_mesa) {
    $("#div_nota_comanda").show();
    $.ajax({
        type: 'POST',
        cache: false,
        url: url_web + module_id + '/identificarMesaReservada',
        data: {
            id_mesa: id_mesa,
            id_emple: id_emple
        },
        dataType: 'json',
        success: function(data) {
            // Elimina datos de la tabla
            var table = $('#tb_lista_prod').DataTable();
            table
                .clear()
                .draw();
            // --

            //alert(JSON.stringify(data));
            $("#td_encargado").text(data[0].empleado);
            $("#td_hora_ini").text(data[0].hora_ini);
            $("#td_nro_venta").text(data[0].correlativo);
            $("#td_nro_mesa").text(data[0].nro_mesa);

            // Pasar valores a los campos Ocultos:
            $("#hdid_tmp_cab").val(data[0].id_tmp_cab);
            // --

            $("#tab_categorias").removeClass("boton_desactiva");
            $("#tab_categorias").trigger("click");

            // -- Muestra el listado de Productos por Mesa Reservada
            listarProductosXMesa(data[0].id_tmp_cab);
            $('#hd_total_venta').val(data[0].precio_total_venta);
            $('#h3_total_venta').text(data[0].precio_total_venta);
            // --

            $('#btnsave').prop('disabled', false);

            if ($('#hd_total_venta').val() > 0) {
                $('#btncomanda').prop('disabled', false);
                $("#btndividir_cuenta").prop('disabled', false);
                $("#btncambiar_mesa").prop('disabled', false);

                $('#btn_pre_venta').prop('disabled', false);
                $('#btn_pre_venta').css({ 'cursor': 'pointer' });
            }

        },
        error: function(jqXHR, textStatus, error) {
            console.log('jqXHR', jqXHR)
        }
    });
}


function listarProductosXMesa(id_tmp_cab) {
    $.ajax({
        type: "POST",
        cache: false,
        //async: true,
        url: url_web + module_id + '/listarProductosXMesa',
        data: { id_tmp_cab: id_tmp_cab },
        dataType: 'json',
        success: function(data_json) {
            // console.log('data_json', data_json)
            //alert(JSON.stringify(data_json));
            let supr = $('#hdid_suprimirvta').val()
            let botn = 'eliminarProdTMPTpv(this.id);"';
            let edits = $('#hdid_edit_comanda').val();
            if (supr == 1) { botn = 'eliminarProdTMPTpvSupr(this.id);"'; }

            var t_prod = $('#tb_lista_prod').DataTable();
            $.each(data_json, function(i, item) {
                if (item.tipo == 'producto') {
                    t_prod.row.add([
                        //'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',
                        '<button class="btn btn-default btn-delete-prod" onclick="' + botn + '" id="' + item.id_tmp_cab + '-' + item.correlativo + '"><span class="glyphicon glyphicon-remove"></span></button>',
                        edits == '' ? item.cantidad : `<button class="btn btn-default" onclick="changeCant('${item.id_tmp_cab}-${item.correlativo}')">${item.cantidad}</button>`,
                        item.nombre,
                        // '<div class="text-right">' + item.comentario + '</div>',
                        '<div class="text-right">' + item.precio_unitario + '</div>',
                        '<div class="text-right">' + item.precio_total + '</div>'
                    ]).draw(false);
                } else {
                    t_prod.row.add([
                        '<button class="btn btn-default btn-delete-prod" onclick="eliminarNotaComandaTMPTpv(this.id);" id="' + item.id_tmp_cab + '-' + item.correlativo + '-' + item.id_nota_comanda + '" style="height: 26px; color: #D82424;font-size: 10px;"><span class="glyphicon glyphicon-remove"></span></button>',
                        '-',
                        '<div style="padding: 0px 0px; font-size: 11px;color: #D82424;">' + item.nombre + '</div>',
                        '-',
                        // '-',
                        '-'
                    ]).draw(false);
                }
            });
        },
        error: function(jqXHR, textStatus, error) {
            console.log('jqXHR', JSON.parse(jqXHR.responseText));
        }
    });
}


function agregarProducto(id_categoria, id_producto) {
    $("#div_nota_comanda").show();
    $("#btncomanda").removeClass('btn-default').addClass('btn-info');

    var id_tmp_cab = $('#hdid_tmp_cab').val();
    var cant_calculador_prod = $('#hd_val_calculadora').val();
    let edits = $('#hdid_edit_comanda').val();
    let newprod = id_producto;

    // console.log('newprod', newprod)
    $.ajax({
        type: 'POST',
        cache: false,
        url: url_web + module_id + '/agregarProducto',
        data: {
            id_categoria: id_categoria,
            id_producto: newprod,
            id_tmp_cab: id_tmp_cab,
            cant_calculador_prod: cant_calculador_prod
        },
        dataType: 'json',
        success: function(data) {
            // console.log(data);
            if (data[0].valida == 'STOCK_0')
                swal(data[0].mensaje, "No puede seleccionar el Producto!")
            else if (data[0].valida == 'STOCK_CALCU')
                swal(data[0].mensaje, "No puede seleccionar el Producto!")
            else {
                if (data[0].valida == 'STOCK_MIN')
                    swal(data[0].mensaje);

                let supr = $('#hdid_suprimirvta').val()
                let botn = 'eliminarProdTMPTpv(this.id);"';
                if (supr == 1) { botn = 'eliminarProdTMPTpvSupr(this.id);"'; }

                var t_prod = $('#tb_lista_prod').DataTable();
                t_prod.row.add([
                    '<button class="btn btn-default btn-delete-prod" onclick="' + botn + '" id="' + data[0].id_tmp_cab + '-' + data[0].correlativo + '"><span class="glyphicon glyphicon-remove"></span></button>',
                    edits == '' ? data[0].cantidad : `<button class="btn btn-default" onclick="changeCant('${data[0].id_tmp_cab}-${data[0].correlativo}')">${data[0].cantidad}</button>`,
                    data[0].nombre,
                    // '<div class="text-right">' + data[0].comentario + '</div>',
                    '<div class="text-right">' + data[0].precio_unitario + '</div>',
                    '<div class="text-right">' + data[0].precio_total + '</div>'
                ]).draw(false);

                // Baja el Scroll
                var $scrollBody = $(t_prod.table().node()).parent();
                $scrollBody.scrollTop($scrollBody.get(0).scrollHeight);
                // --

                //var total_venta = (parseFloat(data[0].precio_total_venta) + parseFloat($( '#hd_total_venta' ).val()));
                var total_venta = data[0].precio_total_venta;
                $('#hd_total_venta').val(total_venta); //.toFixed(2)
                $('#h3_total_venta').text(total_venta); //.toFixed(2)

                $('#btnsave').prop('disabled', false);

                if (total_venta > 0) {
                    $('#btncomanda').prop('disabled', false);
                    $("#btncambiar_mesa").prop('disabled', false);
                    $('#btn_pre_venta').prop('disabled', false);
                    $('#btn_pre_venta').css({ 'cursor': 'pointer' });
                }
            }
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
        }
    });
}

function agregarNotaProd(id_nota_comanda) {
    var id_tmp_cab = $('#hdid_tmp_cab').val();
    $.ajax({
        type: 'POST',
        cache: false,
        url: url_web + module_id + '/agregarNotaProd',
        data: {
            id_tmp_cab: id_tmp_cab,
            id_nota_comanda: id_nota_comanda
        },
        dataType: 'json',
        success: function(data) {
            if (data[0].id_tmp_cab == -1) {
                swal("Punto de Venta", "COMANDA generada. No se puede agregar notas a este producto!", "warning")
            } else {
                var t_prod = $('#tb_lista_prod').DataTable();
                t_prod.row.add([
                    '<button class="btn btn-default btn-delete-prod" onclick="eliminarNotaComandaTMPTpv(this.id);" id="' + data[0].id_tmp_cab + '-' + data[0].correlativo + '-' + data[0].id_nota_comanda + '" style="height: 26px; color: #D82424;font-size: 10px;"><span class="glyphicon glyphicon-remove"></span></button>',
                    '-',
                    '<div style="padding: 0px 0px; font-size: 11px;color: #D82424;">' + data[0].nombre + '</div>',
                    '-',
                    // '-',
                    '-'
                ]).draw(false);

                // Baja el Scroll
                var $scrollBody = $(t_prod.table().node()).parent();
                $scrollBody.scrollTop($scrollBody.get(0).scrollHeight);

            }
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
        }
    });
}


function verProductos(id_categoria) {
    $("#carga_default").hide();
    $.ajax({
        type: "POST",
        url: url_web + module_id + '/filtrarProductos',
        cache: false,
        data: { id_categoria: id_categoria },
        success: function(result) {
            $("#div_productos").html(result);
        }
    });
}

function eliminarProdTMPTpv(id) {
    //alert(id);
    $.ajax({
        type: "POST",
        cache: false,
        url: url_web + module_id + '/eliminarProdTMPTpv',
        data: { id: id },
        dataType: 'json',
        success: function(data) {
            if (data[0].estado_comanda == 'procede') {
                var table = $('#tb_lista_prod').DataTable();
                table
                    .row($('#' + id).parents('tr'))
                    .remove()
                    .draw();

                $('#hd_total_venta').val(data[0].precio_total_venta);
                $('#h3_total_venta').text(data[0].precio_total_venta);
            } else {
                swal("Punto de Venta", "COMANDA generada. No se puede quitar este producto!", "warning")
            }

        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
        }
    });
}

function eliminarProdTMPTpvSupr(id) {

    $.ajax({
        type: "POST",
        cache: false,
        url: url_web + module_id + '/eliminarProdTMPTpv',
        data: { id: id },
        dataType: 'json',
        success: function(data) {
            if (data[0].estado_comanda == 'procede') {
                var table = $('#tb_lista_prod').DataTable();
                table
                    .row($('#' + id).parents('tr'))
                    .remove()
                    .draw();

                $('#hd_total_venta').val(data[0].precio_total_venta);
                $('#h3_total_venta').text(data[0].precio_total_venta);
            } else {
                swal({
                    title: "Está Seguro?",
                    text: "Se eliminara el pedido ya Comandado",
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    inputPlaceholder: "Ingrese un sustento",
                    showLoaderOnConfirm: true
                }, function(inputValue) {
                    if (inputValue === false) return false;
                    else if (inputValue.trim() === "") {
                        swal.showInputError("Necesitas Ingresar Un sustento de la Eliminación");
                        return false
                    } else {
                        $.ajax({
                            url: url_web + module_id + '/eliminarProdTMPTpvSup',
                            type: "POST",
                            data: { id: id, obs: inputValue },
                            success: function(data) {
                                // console.log('data', data[0])
                                swal('Eliminado!', '', 'success')
                                var table = $('#tb_lista_prod').DataTable();
                                table
                                    .row($('#' + id).parents('tr'))
                                    .remove()
                                    .draw();
                                $('#hd_total_venta').val(data[0].precio_total_venta);
                                $('#h3_total_venta').text(data[0].precio_total_venta);
                            }
                        })
                    }
                })
            }

        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
        }
    });


}

function eliminarNotaComandaTMPTpv(id) {
    $.ajax({
        type: "POST",
        cache: false,
        url: url_web + module_id + '/eliminarNotaComandaTMPTpv',
        data: { id: id },
        dataType: 'json',
        success: function(data) {
            // console.log('data', data)
            if (data[0].estado == "ok") {
                var table = $('#tb_lista_prod').DataTable();
                table
                    .row($('#' + id).parents('tr'))
                    .remove()
                    .draw();
            } else {
                swal("Punto de Venta", "No Puedes eliminar comanda Generada!!", "warning")
            }

        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
        }
    });
}

function enviar_variables(valor) {
    $('.gris_num').css({ 'background-color': '#828282' });

    if (valor === 'C') {
        $('#hd_val_calculadora').val(1);
    } else {
        $('#hd_val_calculadora').val(valor);
        $('#tecla' + valor).css({ 'background-color': '#449d44' });
    }
}

function enviar_variables_pago(valor) {
    $('.gris_num').css({ 'background-color': '#828282' });

    if (valor === 'C') {
        var pago_cliente = 0;
        $('#txtpago_cliente').val(pago_cliente.toFixed(2));

        var vuelto_cliente = 0;
        $('#txtvuelto_cliente').val(vuelto_cliente.toFixed(2));
    } else {
        if ($('#txtpago_cliente').val() == 0)
            $('#txtpago_cliente').val('')

        if (valor === '.') {
            $('#tecla_punto').css({ 'background-color': '#449d44' });

            var arr_pago = $('#txtpago_cliente').val().split(".");
            if (arr_pago[1] == '00')
                var pago = (arr_pago[0] + valor);
            else
                var pago = ($('#txtpago_cliente').val() + valor);
        } else {
            $('#tecla_p' + valor).css({ 'background-color': '#449d44' });

            var arr_pago = $('#txtpago_cliente').val().split(".");
            if (arr_pago[1] == '00') {
                var pago_tmp = (arr_pago[0] + valor);
                var pago = pago_tmp + '.00';
            } else {
                if (parseInt(arr_pago[1]) > 0) {
                    var pago_tmp = ($('#txtpago_cliente').val() + valor);
                    var pago = pago_tmp;
                } else if (arr_pago[1] !== '') {
                    var pago_tmp = ($('#txtpago_cliente').val() + valor);
                    var pago = pago_tmp + '.00';
                } else {
                    var pago_tmp = ($('#txtpago_cliente').val() + valor);
                    var pago = pago_tmp;
                }
            }

        }

        $('#txtpago_cliente').val(pago);

        var pago_cliente = $('#txtpago_cliente').val();
        var total_venta = $('#txttotal_venta').val();
        var vuelto_cliente = Math.abs((parseFloat(pago_cliente) - parseFloat(total_venta)));

        $('#txtvuelto_cliente').val(vuelto_cliente.toFixed(2));
    }
}

function imprimirCambioTurno() {
    swal({
            title: "¿Desea realizar el cambio de Turno?",
            text: "Click en Aceptar para continuar...!",
            showCancelButton: true,
            confirmButtonColor: "#339933",
            confirmButtonText: "Aceptar!",
            closeOnConfirm: false
        },
        function() {
            $('#btnprint_cambio_turno').text('PROCESANDO ...');
            $('#btnprint_cambio_turno').attr('disabled', true);

            $.ajax({
                url: url_web + module_id + '/imprimirCambioTurno',
                type: 'POST',
                data: {},
                success: function(result) {
                    console.log('result', result)
                    swal({
                        title: "Punto de Venta",
                        text: "Cambio de turno satisfactoriamente!",
                        type: "success",
                        html: true,
                        closeOnConfirm: true
                    }, function() {
                        window.location.href = url_web + module_id;
                    });
                },
                error: function(jqXHR, textStatus, error) {
                    console.log(jqXHR.responseText);
                }
            });
        });
}

function imprimirCierreCaja() {
    let fecha = moment().format('YYYY-MM-DD')
    $.ajax({
        url: url_web + 'ventas/generarResumenDiario',
        type: 'POST',
        data: { fecha: fecha },
        success: function(result) {
            buscarComprobante()
        },
        error: function(jqXHR, textStatus, error) {
            // console.log('jqXHR', jqXHR)
            swal("Error", jqXHR.responseText, "error");
        }
    });

    swal({
            title: "¿Desea realizar el cierre de Caja?",
            text: "Click en Aceptar para continuar...!",
            showCancelButton: true,
            confirmButtonColor: "#339933",
            confirmButtonText: "Aceptar!",
            closeOnConfirm: false
        },
        function() {
            $('#btncerrar_caja').text('PROCESANDO ...');
            $('#btncerrar_caja').attr('disabled', true);

            $.ajax({
                url: url_web + module_id + '/imprimirCierreCaja',
                type: 'POST',
                data: {},
                success: function(result) {
                    swal({
                        title: "Punto de Venta",
                        text: "Cierre de CAJA satisfactoriamente!",
                        type: "success",
                        html: true,
                        closeOnConfirm: true
                    });
                },
                error: function(jqXHR, textStatus, error) {
                    console.log(jqXHR.responseText);
                }
            });
        });
}


// function reimprimirVentaTicket(id_transac) {
//     swal({
//             title: "¿Desea Re-imprimir el Ticket?",
//             text: "Click en Aceptar para continuar...!",
//             //type: "warning",
//             showCancelButton: true,
//             confirmButtonColor: "#339933",
//             confirmButtonText: "Aceptar!",
//             closeOnConfirm: false
//         },
//         function() {
//             $.ajax({
//                 url: url_web + module_id + '/reimprimirVentaTicket',
//                 type: 'POST',
//                 data: { id_transac: id_transac },
//                 success: function(result) {
//                     swal("Proceso realizado satisfactoriamente!", "", "success");
//                     //location.href = url_web + module_id;
//                 },
//                 error: function(jqXHR, textStatus, error) {
//                     console.log(jqXHR.responseText);
//                 }
//             }).fail(function() {
//                 sweetAlert("Error...!", "Contactarse con el administrador!", "error");
//             });
//         });
// }


function retornarCajaVenta() {
    $("#ventas_dia, #terminar").hide();
    $("#exTab3").show();

    $("#btnsave").prop('disabled', false);
}

function cerrarModalPrint() {
    window.location.href = url_web + module_id;
}

function clientevacio() {
    $("#tpo_doc").val("DNI");
    $("#nro_doc").val("00000000");
    $("#razon_social").val("CLIENTES VARIOS");
    $("#hdid_cliente").val(0);
    $("#razon_social").attr('disabled', 'disabled');

}

$('#btn_refres').click(function() {
    window.location.href = url_web + module_id;
})

function changeCant(idx) {
    $('#myModalCantidad').modal('show')
    let card = idx.split('-')
    $('#id_tmp_cab').val(card[0])
    $('#correlativo').val(card[1])
}

$('#myformCambioCantidad').submit(function(e) {
    e.preventDefault()
    $('btn_changecant').prop('disabled', true);
    let id_tmp_cab = $('#id_tmp_cab').val()
    let correlativo = $('#correlativo').val()
    let newCantidad = $('#newCantidad').val()
    let comentario = $('#comentario').val()

    $.ajax({
        url: url_web + module_id + '/changeCant',
        type: "POST",
        data: { id_tmp_cab: id_tmp_cab, correlativo: correlativo, newCantidad: newCantidad, comentario: comentario },
        success: function(data) {
            // console.log('data', data['id_mesa'])
            identificarMesaReservada(data['id_emple'], data['id_mesa'])
            $('#myModalCantidad').modal('hide')
            $('btn_changecant').prop('disabled', false);
        }
    })

    //identificarMesaReservada(id_emple, id_mesa)
})

function changeMP(id, tp) {
    console.log('tp', tp)
    $('#mp_id_transac').val(id)
    $('#mp_id_tp').val(tp)
    $('#myModalMP').modal('show')
}

$('#btn_changemp').click(function() {
    $('#btn_changemp').prop('disabled', true);
    let id_transac = $('#mp_id_transac').val()
    let id_tp = $('#mp_id_tp').val()
    $.ajax({
        url: url_web + module_id + '/changeMP',
        type: "POST",
        data: { id_transac: id_transac, id_tp: id_tp },
        success: function(data) {
            console.log('data', data)
            $('#myModalMP').modal('hide')
            $('#btn_changemp').prop('disabled', false);
            $('#btn_mostrar_ventas').click()
            swal("Punto de Venta", "Se Actualizó el Medio de Pago", "warning")

        }
    })
})

$("#btn_limp_mesas").click(() => {
    $.ajax({
        url: url_web + module_id + '/limpMesas',
        type: "GET",
        success: function(data) {
            // console.log('data', data)
            swal({
                title: "Punto de Venta",
                text: "Se Limmiparon las mesas Vacias",
                type: "success",
                html: true,
                closeOnConfirm: true
            }, function() {
                window.location.href = url_web + module_id;
            });
        }
    })
})

const anularVta = (id_transac) => {
    swal({
        title: "Punto de Venta",
        text: "Desea Anular esta Venta?",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Ingrese un sustento",
        showLoaderOnConfirm: true
    }, function(inputValue) {
        if (inputValue === false) return false;
        else if (inputValue.trim() === "") {
            swal.showInputError("Necesitas Ingresar Un sustento de la Anulación");
            return false
        } else {
            $.ajax({
                url: url_web + module_id + '/anularVta',
                type: "POST",
                data: { id_transac: id_transac, motivo: inputValue },
                success: function(data) {
                    // console.log('data', data)
                    swal({
                        title: "Punto de Venta",
                        text: "Se Anuló la Venta",
                        type: "success",
                        closeOnConfirm: true
                    }, function() {
                        window.location.href = url_web + module_id;
                    });
                }
            })
        }
    });
}

$('#buscarClienteSumat').click(() => {
    let numdoc = $('#nro_doc').val()
    let tipodoc = $('#tpo_doc').val()
    if (tipodoc == 'DNI' || tipodoc == 'RUC') {
        $.ajax({
            url: `https://consultaruc.win/api/${tipodoc.toLowerCase()}/${numdoc}`,
            type: "GET",
            success: function(data) {
                console.log('data', data.response)
                let a = data.result
                    // $('#nro_doc').val()
                if (data.response.toString() == 'true') {
                    if (a.DNI) {
                        console.log(a)
                        $('#razon_social').val(`${a.Paterno} ${a.Materno} ${a.Nombre}`)
                    } else {
                        console.log('adasfd', a)
                        $('#razon_social').val(a.razon_social)
                    }
                } else {
                    $('#razon_social').val('')
                    $("#hdid_cliente").val('');
                }
            }
        })
    }

})
$('#btn_ventasDia').click(() => {
    let fecha = moment().format('YYYY-MM-DD')
        // console.log('fecha', fecha)
    let url = url_web + '/ventas/exportventas/' + fecha;
    window.open(url, '_blank');
})

const buscarComprobante = () => {
    let i = 0
    setInterval(() => {
        i = i + 1
        console.log("Ejecutar cada 10 seg=> ", i)
        $.ajax({
            url: `${url_web}/facturador/validarResumen`,
            type: "GET",
        })
    }, 10000);
}