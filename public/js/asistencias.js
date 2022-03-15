$(document).ready(function() {

    /** Insertar */
    $("#btnadd").on("click", function() {

        if ($("#cbo_1").val() == "0") {
            $("#msj_valida").html("Por favor seleccione el <strong>EMPLEADO</strong>!").slideDown("slow");
            $("#cbo_1").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#fecha_login").val() == "") {
            $("#msj_valida").html("Por favor escriba una <strong>FECHA</strong>!").slideDown("slow");
            $("#fecha_login").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#hora_login").val() == "") {
            $("#msj_valida").html("Por favor la Hora de <strong>INGRESO</strong>!").slideDown("slow");
            $("#hora_login").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#hora_logout").val() == "") {
            $("#msj_valida").html("Por favor la Hora de <strong>SALIDA</strong>!").slideDown("slow");
            $("#hora_logout").focus();
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#concepto").val() == "0") {
            $("#msj_valida").html("Por favor seleccione el <strong>CONCEPTO</strong>!").slideDown("slow");
            $("#concepto").focus();
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
                    console.log('jqXHR', jqXHR)
                }
            });

        }
    });
    /** ************************* */

    /** Modificar */
    $("#btnMod").on("click", function() {
        //id = $("#hdid").val();  
        if ($("#concepto").val() == 0) {
            $("#msj_valida").html("Por favor seleccione un Concepto!").slideDown("slow");
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