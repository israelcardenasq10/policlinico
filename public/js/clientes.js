$(document).ready(function() {

    /** Insertar */
    $("#btnadd").on("click", function() {

        if ($("#tipo_doc").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Tipo Doc!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#nro_doc").val() == "") {
            $("#msj_valida").html("Por favor ingrese Nro. Documento!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#nro_doc").focus();
            return false;
        } else if ($("#phone_number").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Nro. Telefonico!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#phone_number").focus();
            return false;
        } else if ($("#email").val() == "") {
            $("#msj_valida").html("Por favor ingrese el E-mail!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#email").focus();
            return false;
        } else if ($("#address_1").val() == "") {
            $("#msj_valida").html("Por favor ingrese la Dirección!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#address_1").focus();
            return false;
        } else if ($("#address_2").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Distrito!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#address_2").focus();
            return false;
        } else if ($("#city").val() == "") {
            $("#msj_valida").html("Por favor ingrese la Ciudad!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#city").focus();
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
                    //llama y ejecuta la función subirArchivo();
                    subirArchivosNuevo(result);

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
                        //swal("Error", jqXHR.responseText, "error");  
                }
            });

        }
    });
    /** ************************* */

    /** Modificar */
    $("#btnMod").on("click", function() {

        if ($("#tipo_doc").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Tipo Doc!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#nro_doc").val() == "") {
            $("#msj_valida").html("Por favor ingrese Nro. Documento!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#nro_doc").focus();
            return false;
        } else if ($("#phone_number").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Nro. Telefonico!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#phone_number").focus();
            return false;
        } else if ($("#email").val() == "") {
            $("#msj_valida").html("Por favor ingrese el E-mail!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#email").focus();
            return false;
        } else if ($("#address_1").val() == "") {
            $("#msj_valida").html("Por favor ingrese la Dirección!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#address_1").focus();
            return false;
        } else if ($("#address_2").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Distrito!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#address_2").focus();
            return false;
        } else if ($("#city").val() == "") {
            $("#msj_valida").html("Por favor ingrese la Ciudad!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#city").focus();
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
                    //llama y ejecuta la función subirArchivo();
                    subirArchivos();

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
                    swal("Error", 'El Tipo y Numero de Documento ya existe en Clientes', "error")
                    $("#msj_valida").slideUp("slow");
                    $("#btnMod").text('Grabar');
                    $("#btnMod").prop('disabled', false);
                    console.log('jqXHR', jqXHR)
                }
            });

        }
    });

    $('#nro_doc').on('keyup', function() {
        if ($("#tipo_doc").val() == "RUC")
            $('#nro_doc').prop('maxLength', 11);
        else
            $('#nro_doc').prop('maxLength', 16);
    });

});