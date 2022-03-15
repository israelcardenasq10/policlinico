$(document).ready(function() {

    /** Insertar */
    $("#btnadd").on("click", function() {

        if ($("#hab_area").val() == "0") {
            $("#msj_valida").html("Por favor escriba la <strong>UBICACION</strong> del Articulo!").slideDown("slow");
            $("#hab_area").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#id_cat").val() == "0") {
            $("#msj_valida").html("Por favor escriba la <strong>CATEGORIA</strong> del Articulo!").slideDown("slow");
            $("#id_cat").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#descripcion").val() == "") {
            $("#msj_valida").html("Por favor escriba el <strong>NOMBRE</strong> del Articulo!").slideDown("slow");
            $("#descripcion").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#marca_modelo").val() == "") {
            $("#msj_valida").html("Por favor escribe el <strong>MODELO</strong> del Articulo").slideDown("slow");
            $("#marca_modelo").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#cant_unidad").val() == "") {
            $("#msj_valida").html("Por favor escribe la <strong>CANTIDAD</strong> de Articulos").slideDown("slow");
            $("#cant_unidad").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#costo_valor").val() == "") {
            $("#msj_valida").html("Por favor escribe el <strong>COSTO</strong> del Articulos").slideDown("slow");
            $("#costo_valor").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#fecha_registro").val() == "") {
            $("#msj_valida").html("Por favor escribe una fecha").slideDown("slow");
            $("#fecha_registro").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#prov_id").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un <strong>PROVEEDOR</strong>!").slideDown("slow");
            $("#prov_id").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else {
            $('#msj_valida').removeClass('alert-danger');
            $("#msj_valida").html("<img src='" + url_web_public + "images/admin/loading.gif' style='border: 0px;' />").slideDown("slow");
            $("#btnadd").text('Cargando...');
            $("#btnadd").prop('disabled', true);

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
                    //alert( "Error: " + jqXHR.responseText);
                    //alert( "Error: " + error.responseText);
                    console.log('jqXHR', jqXHR)
                }
            });

        }
    });
    /** ************************* */

    /** Modificar */
    $("#btnMod").on("click", function() {
        //id = $("#hdid").val();  
        if ($("#codigo").val() == "") {
            $("#msj_valida").html("Por favor seleccione un Codigo!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            return false;
        } else {
            $('#msj_valida').removeClass('alert-danger');
            $("#msj_valida").html("<img src='" + url_web_public + "images/admin/loading.gif' style='border: 0px;' />").slideDown("slow");
            $("#btnMod").text('Cargando...');
            $("#btnMod").prop('disabled', true);
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

});