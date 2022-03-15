var url_web = $('#url_web').val();
var url_web_public = $('#url_web_public').val();
var perfil_id = $('#perfil_id').val();
var module_id = $('#module_id').val();

// Style to message!
var open_modal_success = '<div class="alert alert-success" role="alert" style="font-size: 20px; text-align: center; margin-bottom: 0px;">';
var close_modal_success = '</div>';

var open_modal_warning = '<div class="alert alert-warning" role="alert" style="font-size: 20px; text-align: center; margin-bottom: 0px;">';
var close_modal_warning = '</div>';

var open_modal_danger = '<div class="alert alert-danger" role="alert" style="font-size: 20px; text-align: center; margin-bottom: 0px;">';
var close_modal_danger = '</div>';
// --

//Oculta en primera Carga!
$("#msj_valida").hide();
$(".valida_ajax").hide();
$("#div_cliente_nombres").hide();

// Tiempos de Mensajes
function aplicarTiempo(valor) {
    setTimeout(function() { $(valor).slideUp(800).fadeOut(800); }, 3000);
}

// ELIMINAR REGISTROS (MODULOS PRINCIPALES)
function eliminarReg(id) {
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
                data: { id: id },
                success: function(result) {
                        swal("Eliminado!", "Se Eliminó el registro satisfactoriamente!", "success");
                        location.href = url_web + module_id;
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


// ELIMINAR REGISTROS (MODULOS SECUNDARIOS)
function eliminar(id, accion) {
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
            $('#msj_valida').removeClass('alert-danger');
            $('#msj_valida').removeClass('alert-success');
            $("#msj_valida").html("<img src='" + url_web_public + "images/admin/load.gif' style='border: 0px;' />").slideDown("slow");
            //$('#data_listado').slideUp();

            $.ajax({
                url: url_web + module_id + '/' + accion,
                type: "POST",
                data: { id: id },
                success: function() {
                        $('#msj_valida').empty().hide();
                        swal("Eliminado!", "Se Eliminó el registro satisfactoriamente!", "success");

                        $('#service' + id).remove();

                        //Obtiene el ID del datatable()
                        var id_table = $("table").attr("id");

                        //Proceso para eliminar registro del datatable();
                        var table = $('#' + id_table).DataTable();
                        table.row('.selected').remove().draw(false);

                        //Muestra de nuevo el listado
                        //$('#data_listado').slideDown();
                    }
                    /*,
                    error: function(jqXHR, textStatus, error)
                    {
                      $( '#msj_valida' ).addClass('alert-danger');
                    $( '#msj_valida' ).html(jqXHR.responseText);
                    }
                    */
            }).fail(function() {
                sweetAlert("Error...!", "No se puede Eliminar la información, ya cuenta con transacciones.", "error");
                $('#msj_valida').empty().hide();
            });
        } else {
            swal("Cancelado", "La información esta a salvo!", "error");
        }
    });
}



// SUBIR ARCHIVOS POR (INSERTAR)
function subirArchivosNuevo(id_insert) {
    $("#archivo").upload(url_web + module_id + '/cargarFile', {
            id: id_insert //Todos Los form que usan Upload deben tener este input en el MAIN
        },
        function(respuesta) {
            //console.log(respuesta);
            if (respuesta === 0) {
                //$("#msj_valida").html("El archivo NO se ha podido subir.");
                alert("Error al subir el archivo!");
                return false;
            }
        },
        function(progreso, valor) {
            //Barra de progreso.
        });
}

// SUBIR ARCHIVOS POR (MODIFICAR)
function subirArchivos() {
    $("#archivo").upload(url_web + module_id + '/cargarFile', {
            id: $("#id_file").val() //Todos Los form que usan Upload deben tener este input en el MAIN
        },
        function(respuesta) {
            //Subida finalizada.
            if (respuesta === 0) {
                //$("#msj_valida").html("El archivo NO se ha podido subir.");
                alert("Error al subir el archivo!");
                return false;
            }
        },
        function(progreso, valor) {
            //Barra de progreso.
        });
}

// VERIFICA SI EXISTE DATOS!
function verificarCampo(valor) {
    $.ajax({
        url: url_web + module_id + '/verificarDato',
        type: 'POST',
        data: { valor: valor },
        success: function(result) {
            if (result === 'error') {
                $('.valida_ajax').text('Error! ' + valor + ' ya se encuentra registrado en el Sistema.').slideDown('slow');
                $("#btnadd").prop('disabled', true);
            } else {
                $(".valida_ajax").hide();
                $("#btnadd").prop('disabled', false);
            }
        },
        error: function(jqXHR, textStatus, error) {
            console.log('jqXHR', jqXHR)
        }
    });
}


// MOSTRAR CAMPOS POR HIDE Y SHOW
function agregarCampos(modulo_id, valor) {
    if (modulo_id == 'clientes' || modulo_id == 'proveedores') {
        if (valor == 'DNI' || valor == 'PAS') {
            $("#div_cliente_razonsocial").hide();
            $("#div_cliente_nombres").show();
        } else {
            $("#div_cliente_nombres").hide();
            $("#div_cliente_razonsocial").show();
        }

    }
}


// [LISTAR DATOS EN SELECT MULTIPLE Y PASAR A OTRO]
$("#btnagregar_option").on('click', function() {
    var val_rep = [];
    var text_rep = [];
    $('#cbolista_principal :selected').each(function(i) {
        val_rep[i] = $(this).val();
        text_rep[i] = $(this).text();

        $("<option value=" + val_rep[i] + ">" + text_rep[i] + "</option>").appendTo("#cbolista_secundario");
    });
    $("#cbolista_principal :selected").remove();
});

$("#btnquitar_option").on('click', function() {
    var val_rep = [];
    var text_rep = [];
    $('#cbolista_secundario :selected').each(function(i) {
        val_rep[i] = $(this).val();
        text_rep[i] = $(this).text();
        $("<option value=" + val_rep[i] + ">" + text_rep[i] + "</option>").appendTo("#cbolista_principal");
    });

    if ($("#cbolista_secundario").val().length > 0)
        $("#cbolista_secundario :selected").remove();

    $("#btnquitar_option").prop("disabled", true);
});

$("#cbolista_secundario").on('change', function() {
    $("#btnquitar_option").prop("disabled", false);
});
// --


// VALIDAR QUE EL DATO INGRESADO SEA UNICAMENTE "NUMERICO"
function justNumbers(e) {
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
        return true;

    return /\d/.test(String.fromCharCode(keynum));
}
// --