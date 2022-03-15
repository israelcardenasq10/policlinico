$(document).ready(function() {

    var pagina = $("#hdpagina").val();
    console.log('pagina', pagina)

    // INSERTAR / MODIFICAR
    $("#btnsave").on("click", function() {

        $('#msj_valida').removeClass('alert-success');
        $('#msj_valida').addClass('alert-danger');

        if ($("#id_categoria").val() == "") {
            $("#msj_valida").html("Por favor ingrese un Codigo!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#id_categoria").focus();
            return false;
        } else if ($("#nombre").val() == "") {
            $("#msj_valida").html("Por favor ingrese un Nombre!").slideDown("slow");
            aplicarTiempo("#msj_valida");
            $("#nombre").focus();
            return false;
        } else {
            //$( '#msj_valida' ).removeClass('alert-danger');
            $("#data_listado").html("<div style='text-align: center;'><img src='" + url_web_public + "images/admin/load.gif' style='border: 0px;' /></div>").slideDown("slow");
            $("#btnsave").text('Cargando...');
            $("#btnsave").prop("disabled", true);
            //$('#data_listado').slideUp();
            //$('#data_listado').empty();

            if ($('.id_mod').val() === '') {
                var v_accion = 'insertar';
                var v_mensaje = 'Insertó';
            } else {
                var v_accion = 'actualizar';
                var v_mensaje = 'Actualizó';
            }

            $.ajax({
                url: url_web + module_id + '/' + v_accion + pagina,
                type: 'POST',
                data: $("#frm1").serialize(),
                success: function(result) {
                    $('#data_listado').html(result);

                    $('#datos_tabla_ajax').DataTable({
                        "order": [
                            [0, 'desc']
                        ],
                        "pagingType": "full_numbers",
                        "displayLength": 10
                    });

                    swal("Excelente!", "Se " + v_mensaje + " el registro satisfactoriamente!", "success");

                    $("#btnsave").text('Grabar');
                    $("#btnsave").prop("disabled", false);

                    //aplicarTiempo("#msj_valida");
                    $("#frm1 input").val('');
                    $("#id_categoria").prop("readonly", false);
                    $("#estado").val('1')
                },
                error: function(jqXHR, textStatus, error) {
                    $('#msj_valida').addClass('alert-danger');
                    $('#msj_valida').html(jqXHR.responseText);
                }
            });
        }
    });
    // --

});


// MOSTRAR DATOS A BUSCAR 
function ver(id, accion) {
    $('tr').removeClass('selected');
    $('#service' + id).addClass('selected');

    $("#form").hide();
    $("#form_edit").html("<img src='" + url_web_public + "images/admin/load.gif' style='border: 0px;' />").slideDown("slow");

    $.ajax({
        type: 'POST',
        url: url_web + module_id + '/' + accion,
        data: { id: id },
        dataType: 'json',
        success: function(data) {
            //$( '#msj_valida' ).html(data).show();
            //Desplaza Campos recibidos del JSON php
            $("#id").val(data[0].id_categoria);
            $("#id_categoria").val(data[0].id_categoria);
            $("#nombre").val(data[0].nombre);

            $("#estado").val(data[0].estado);
            $("#id_categoria").prop("readonly", true);

            // --
            $("#form_edit").empty()
            $("#form").show();
        },
        error: function(jqXHR, textStatus, error) {
            $('#msj_valida').addClass('alert-danger');
            $('#msj_valida').html(jqXHR.responseText);
        }
    });
}