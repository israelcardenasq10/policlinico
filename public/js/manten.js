// $(document).ready(function() {

//     var url_web = 'http://localhost:8082/allnet_app/';
//     // var url_web = 'http://sigim.allnet.com.pe/index.php/';

//     $("#msj_inci").hide();

//     $('#incidencias').DataTable({
//         "order": [
//             [0, 'desc']
//         ],
//         "pagingType": "full_numbers",
//         "displayLength": 15
//     });

//     $('.input-group.date').datepicker({
//         format: "yyyy-mm-dd",
//         autoclose: true,
//         todayBtn: "linked",
//         todayHighlight: true
//     });

//     //Marcar todos los check
//     /* $("input[name=chktodos_estados]").change(function(){
// 		$('input[type=checkbox]').each( function() {			
// 			if($("input[name=chkestado]:checked").length == 1){
// 				this.checked = true;
// 			} else {
// 				this.checked = false;
// 			}
// 		});
// 	}); */

//     $('#btnfiltrarXFechas').on('click', function() {
//         fecha_1 = $("#txtfecha1_excel").val();
//         fecha_2 = $("#txtfecha2_excel").val();
//         if (fecha_1 == '' || fecha_2 == '') {
//             $(".input_date").css('border-color', 'red');
//             return false;
//         }
//         window.location = url_web + "incidencias/main/filtrarIncidenciasXFecha/" + fecha_1 + "/" + fecha_2;
//     });

//     $('#btnExportarExcelXFechas').on('click', function() {
//         fecha_1 = $("#txtfecha1_excel").val();
//         fecha_2 = $("#txtfecha2_excel").val();
//         window.location = url_web + "incidencias/reportes/exportarExcelIncidencias/" + fecha_1 + "/" + fecha_2;
//     });


//     $('#btncancel_incidencia').on('click', function() {
//         window.location = url_web + "incidencias/main";
//     });

//     //Pasar a GARANTIA
//     $("#btngarantia").on('click', function() {
//         var val_rep = [];
//         var text_rep = [];
//         $('#cborepuesto :selected').each(function(i) {
//             val_rep[i] = $(this).val();
//             text_rep[i] = $(this).text();

//             $("<option value=" + val_rep[i] + ">" + text_rep[i] + "</option>").appendTo("#cborepuesto_garantia");
//         });
//     });
//     $("#btnlimpiarGarantia").on('click', function() {
//         $("#cborepuesto_garantia").val('');
//         $("#cborepuesto_garantia").text('');
//         $("<option value=0>-- REPUESTOS DE GARANTÍA --</option>").appendTo("#cborepuesto_garantia");
//     });

//     //Pasar a NO GARANTIA
//     $("#btnnogarantia").on('click', function() {
//         var val_rep = [];
//         var text_rep = [];
//         $('#cborepuesto :selected').each(function(i) {
//             val_rep[i] = $(this).val();
//             text_rep[i] = $(this).text();

//             $("<option value=" + val_rep[i] + ">" + text_rep[i] + "</option>").appendTo("#cborepuesto_no_garantia");
//         });
//     });
//     $("#btnlimpiarNOGarantia").on('click', function() {
//         $("#cborepuesto_no_garantia").val('');
//         $("#cborepuesto_no_garantia").text('');
//         $("<option value=0>-- REPUESTOS SIN GARANTÍA --</option>").appendTo("#cborepuesto_no_garantia");
//     });

//     //Desactiva el bonton de "Actualizar Incidencia" al digitar un texto en "Trabajos Realizados"
//     $("#txttrabajorealizado").on('keyup', function() {
//         if ($('#txtid_estado').val() >= 2) {
//             $("#btnupdate_incidencia").removeAttr('disabled');
//             $("#btnupdate_incidencia").removeClass('btn-default');
//             $("#btnupdate_incidencia").addClass('btn-primary');
//         }
//     });


//     /** Actualizar Incidencia del Usuario (Cliente) */
//     $("#btnupdate_incidencia").on("click", function() {

//         $("#msj_inci").html('');

//         id_inci = $("#txtid_inci").val();
//         nro_incidente = $("#txtnro_inci").val();
//         razon_social_cliente = $("#txtrazon_social_cliente").val();
//         id_user = $("#txtid_user").val();
//         username = $("#txtusername").val();
//         titulo_incidencia = $("#txttitulo_incidencia").val();
//         hora_solicitud = $("#txthora_solicitud").val();
//         provicia_ciudad = $("#cbociudad").val();
//         agencia = $("#cboagencia").val();
//         direccion = $("#txtdireccion").val();
//         modelo = $("#cbomodelo").val();
//         equipo_serie = $("#txtequipo_serie").val();
//         margesi_serie = $("#txtmargesi_serie").val();
//         fecha_solicitud_cliente = $("#txtfecha_solicitud_cliente").val();

//         contacto = $("#txtcontacto").val();
//         correo = $("#txtcorreo").val();
//         telefono = $("#txttelefono").val();
//         descripcion = $("#txtdescripcion").val();

//         tecnico = $("#cboTecnicos").val();
//         diagnostico = $("#txtdiagnostico").val();
//         trabajo_realizado = $("#txttrabajorealizado").val();
//         observaciones = $("#txtobservaciones").val();
//         estado_impresora = $("#txtestado_impresora").val();
//         //Captura el valor del estado, si esta marcado = 3 (Cerrado) sino = 2 (Pendiente)
//         estado = $("#chkestado").prop("checked") ? $("#chkestado").val() : 2;

//         $('#cborepuesto_garantia > option').attr('selected', 'selected');
//         $('#cborepuesto_no_garantia > option').attr('selected', 'selected');

//         var repuesto_garantia = [];
//         $('#cborepuesto_garantia :selected').each(function(i) {
//             repuesto_garantia[i] = $(this).val();
//             repuesto_garantia.push($(this).val());
//         });

//         var repuesto_no_garantia = [];
//         $('#cborepuesto_no_garantia :selected').each(function(i) {
//             repuesto_no_garantia[i] = $(this).val();
//             repuesto_no_garantia.push($(this).val());
//         });
//         /*alert(repuesto_garantia);
//         return false;*/

//         if (tecnico == '0') {
//             $("#msj_inci").html("Por favor seleccione un T&eacute;cnico!");
//             $("#msj_inci").slideDown("slow");
//             return false;
//         } else {
//             $("#btnupdate_incidencia").text('Actualizando...');
//             $("#btnupdate_incidencia").attr('disabled', true);

//             $.ajax({
//                 url: url_web + 'incidencias/main/actualizarIncidencia',
//                 type: 'POST',
//                 data: {
//                     id_inci: id_inci,
//                     nro_incidente: nro_incidente,
//                     razon_social_cliente: razon_social_cliente,
//                     id_user: id_user,
//                     username: username,
//                     titulo_incidencia: titulo_incidencia,
//                     hora_solicitud: hora_solicitud,
//                     provicia_ciudad: provicia_ciudad,
//                     agencia: agencia,
//                     direccion: direccion,
//                     modelo: modelo,
//                     equipo_serie: equipo_serie,
//                     margesi_serie: margesi_serie,
//                     fecha_solicitud_cliente: fecha_solicitud_cliente,
//                     contacto: contacto,
//                     correo: correo,
//                     telefono: telefono,
//                     descripcion: descripcion,
//                     tecnico: tecnico,
//                     diagnostico: diagnostico,
//                     repuesto_garantia: repuesto_garantia,
//                     repuesto_no_garantia: repuesto_no_garantia,
//                     trabajo_realizado: trabajo_realizado,
//                     observaciones: observaciones,
//                     estado_impresora: estado_impresora,
//                     estado: estado
//                 },
//                 success: function(result) {
//                     //llama y ejecuta la función subirArchivo();
//                     subirArchivos();
//                     //console.log(result);
//                     $("#msj_inci").hide();
//                     $("#dialog-message").dialog("open");
//                     $("#btnupdate_incidencia").text('Actualizar Ticket');
//                 },
//                 error: function(jqXHR, textStatus, error) {
//                     alert("Error: " + jqXHR.responseText);
//                 }
//             });
//         }
//     });

//     $("#dialog-message").dialog({
//         autoOpen: false,
//         modal: true,
//         draggable: true,
//         buttons: {
//             Ok: function() {
//                 $(this).dialog("close");
//                 window.location = url_web + "incidencias/main";
//             }
//         }
//     });

//     $(document).scroll(function(e) {

//         if ($(".ui-widget-overlay")) //the dialog has popped up in modal view
//         {
//             //fix the overlay so it scrolls down with the page
//             $(".ui-widget-overlay").css({
//                 position: 'fixed',
//                 top: '0'
//             });

//             //get the current popup position of the dialog box
//             pos = $(".ui-dialog").position();
//             //adjust the dialog box so that it scrolls as you scroll the page
//             $(".ui-dialog").css({
//                 position: 'fixed',
//                 top: pos.y
//             });
//         }
//     });
//     /** ************************* */


// });

// /* FUNCIONES JAVASCRIPT */
// //var url_web = 'http://localhost:8082/allnet_app/';
// // var url_web = 'http://sigim.allnet.com.pe/index.php/';

// function subirArchivos() {
//     $("#archivo").upload(url_web + 'incidencias/main/cargarFile', {
//             nro_inci: $("#txtnro_inci").val()
//         },
//         function(respuesta) {
//             //Subida finalizada.
//             //console.log(respuesta);
//             if (respuesta === 1) {
//                 $("#msj_inci").html("El archivo ha sido subido correctamente.");
//             } else {
//                 $("#msj_inci").html("El archivo NO se ha podido subir.");
//             }
//         },
//         function(progreso, valor) {
//             //Barra de progreso.
//         });
// }