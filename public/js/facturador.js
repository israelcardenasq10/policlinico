$(document).ready(function() {
    // console.log('facturador_js');
    cargartabla();
    cargartablaresumen();
});
$('#tabresumen').click(function() {
    cargartablaresumen();
})
$("#tabfacturador").click(() => cargartabla())

function cargartabla() {
    fetch(`${url_web}/${module_id}/lista`)
        .then(res => res.json())
        .then(data => {
            // console.log("data", data)
            $('#datos_tabla').DataTable({
                "destroy": true,
                "searching": true,
                "lengthChange": true,
                "dom": 'Bfrtip',
                "order": [
                    [3, "desc"],
                    [4, "desc"],
                ],
                "buttons": [{
                    extend: 'excel',
                    title: "Data_Facturas_y_Boletas",
                    sheetName: 'Exported data'
                }, {
                    extend: 'csv',
                    title: 'Data_Facturas_y_Boletas',
                    sheetName: 'Exported data'
                }],
                "data": data,
                "columns": [
                    { "data": "NUM_RUC" },
                    {
                        "render": function(data, type, rows) {
                            var nomTipoCdp = null;
                            $.each(TipoComprobante, function(key, val) {
                                if (val.id == rows.TIP_DOCU) {
                                    nomTipoCdp = val.nombre;
                                    return false;
                                }
                            });

                            return nomTipoCdp;
                        }
                    },
                    { "data": "NOM_ARCH" },
                    { "data": "FEC_CARG" },
                    { "data": "FEC_GENE" },
                    { "data": "FEC_ENVI" },
                    {
                        "render": function(data, type, rows) {
                            var nomSituacion = null;
                            $.each(ListaSituacion, function(key, val) {
                                if (val.id == rows.IND_SITU) {
                                    nomSituacion = val.nombre;
                                    return false;
                                }
                            });
                            return nomSituacion;
                        }
                    },
                    { "data": "DES_OBSE" }
                ],

            });
        })
}


function cargartablaresumen() {
    fetch(`${url_web}/ventas/verresumencabecera`)
        .then(res => res.json())
        .then(data => {
            // console.log("data", data)
            $('#datos_resumen').DataTable({
                "destroy": true,
                "searching": true,
                "lengthChange": true,
                "order": [
                    [2, "desc"],
                ],
                "data": data.data,
                "columns": [
                    // { "data": "id_resumen" },
                    {
                        "render": function(data, type, row) {
                            let boton = `<button class="btn btn-sm btn-warning btn1" ticket='${row.ntickect}' arch=${row.NOM_ARCH}>Volver Enviar</button>`
                            if (row.ntickect == '') {
                                boton = `<button class="btn btn-sm btn-success btn2" arch=${row.NOM_ARCH} idval=${row.id_resumen}>ObtenerTicket</button>`
                            }
                            return `<div class="btn-group">${boton}</div>`;
                        }
                    },
                    { "data": "NOM_ARCH" },
                    { "data": "fec_resumen" },
                    { "data": "fec_generacion" },
                    { "data": "ntickect" },
                    { "data": "numreg" }
                ],

            });
        })
}
$('#btn_ins_resumen').click(function() {
    let fecha = $('#fech_resumen').val()
    if (fecha) {
        $.ajax({
            url: url_web + 'ventas/generarResumenDiario',
            type: 'POST',
            data: { fecha: fecha },
            success: function(result) {
                // console.log('result', result)
                swal({
                    title: "Excelente!",
                    text: "Se Insertó el registro satisfactoriamente..!",
                    type: "success",
                    closeOnConfirm: true
                });
                cargartablaresumen()
            },
            error: function(jqXHR, textStatus, error) {
                // console.log('jqXHR', jqXHR)
                swal("Error", jqXHR.responseText, "error");
            }
        });
    } else {
        swal({
            title: "Opps..!",
            text: "Ingrese la Fecha..!",
            type: "warning",
            closeOnConfirm: true
        });
    }
})

//traer datos para editar
$('#datos_resumen tbody').on('click', 'button.btn1', function() {
    let arch = $(this).attr('arch');
    let ticket = $(this).attr('ticket');
    $.ajax({
        url: url_web + 'facturador/volverEnviarSunat',
        type: 'POST',
        data: { resumen: arch, ticket: ticket },
        success: function(result) {
            if (result.ok) {
                swal({
                    title: "Excelente!",
                    text: "Se Volvió a Enviar el " + ticket,
                    type: "success",
                    closeOnConfirm: true
                });
                cargartablaresumen()
            }
        },
    });
}).on('click', 'button.btn2', function() {
    let arch = $(this).attr('arch');
    let idval = $(this).attr('idval');
    $.ajax({
        url: url_web + 'facturador/obtticket',
        type: 'POST',
        data: { resumen: arch, id_resumen: idval },
        success: function(result) {
            if (result.ok) {
                swal({
                    title: "Excelente!",
                    text: "Se Genero " + result.ntickect,
                    type: "success",
                    closeOnConfirm: true
                });
                cargartablaresumen()
            }
        },
    });
})


let ListaSituacion = [
    { "id": "01", "nombre": "Por Generar XML" },
    { "id": "02", "nombre": "XML Generado" },
    { "id": "03", "nombre": "Enviado y Aceptado SUNAT" },
    { "id": "04", "nombre": "Enviado y Aceptado SUNAT con Obs." },
    { "id": "05", "nombre": "Rechazado por SUNAT" },
    { "id": "06", "nombre": "Con Errores" },
    { "id": "07", "nombre": "Por Validar XML" },
    { "id": "08", "nombre": "Enviado a SUNAT Por Procesar" },
    { "id": "09", "nombre": "Enviado a SUNAT Procesando" },
    { "id": "10", "nombre": "Rechazado por SUNAT" },
    { "id": "11", "nombre": "Enviado y Aceptado SUNAT" },
    { "id": "12", "nombre": "Enviado y Aceptado SUNAT con Obs." }
]
let TipoComprobante = [
    { "id": "01", "nombre": "Factura" },
    { "id": "03", "nombre": "Boleta de Venta" },
    { "id": "07", "nombre": "Nota de Credito" },
    { "id": "RC", "nombre": "Resumen de Boletas" },
    { "id": "RA", "nombre": "Comunicacion de Baja" },
]