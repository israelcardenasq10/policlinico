$(document).ready(function() {

    /** Insertar */
    $("#btnadd").on("click", function() {

        if ($("#person_id").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Empleado!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#username").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Username").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#username").focus();
            return false;
        } else if ($("#password").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Password").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#password").focus();
            return false;
        } else if ($("#id_perfil").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Perfil!").slideDown("slow");
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

        if ($("#person_id").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Empleado!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            return false;
        } else if ($("#username").val() == "") {
            $("#msj_valida").html("Por favor ingrese el Username").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#username").focus();
            return false;
        }
        /*else if($("#password").val() == "")
        {
          $( "#msj_valida" ).html( "Por favor ingrese el Password" ).slideDown( "slow" );
          aplicarTiempo("#msj_valida");
          $( "#password" ).focus();
          return false;
        }*/
        else if ($("#id_perfil").val() == "0") {
            $("#msj_valida").html("Por favor seleccione un Perfil!").slideDown("slow");
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