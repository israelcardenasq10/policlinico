$(document).ready(function() {
    // var todayDate = new Date().toISOString()//.slice(0,10);
    let d = new Date()
    var todayDate = new Date(d.getTime() - d.getTimezoneOffset() * 60 * 1000).toISOString().split('T')[0]
        // var todayDate = new Date(d.getTime() - d.getTimezoneOffset() * 60 * 1000).toISOString().split('T')[0]

    $("#v_desde").val(todayDate)
    $("#v_hasta").val(todayDate)
    tablaventas()
});


function anularVenta(id_transac) {
    swal({
            title: "Desea Anular el Ticket?",
            text: "No podrá reversar la operación...!",
            //type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Anular!",
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                url: url_web + module_id + '/anularTicket',
                type: 'POST',
                data: { id: id_transac },
                success: function(result) {
                    if (result == 'ok') {
                        //swal("Anulado!", "Se Anuló el Ticket satisfactoriamente!", "success"); 
                        swal("Ticket Anulado!", "", "success");
                        location.href = url_web + module_id;
                    } else {
                        sweetAlert("Advertencia!", "No se puede Anular el Ticket.", "warning");
                    }
                }
            }).fail(function() {
                sweetAlert("Error...!", "No se puede Anular el Ticket.", "error");
            });
        });
}

function tablaventas() {
    let tdoc = $("#v_tdoc").val()
    let sfactu = $("#v_sfactu").val()
    let nfactu = $("#v_nfactu").val()
    let desde = $("#v_desde").val()
    let hasta = $("#v_hasta").val()

    let genNC = $("#hhdgenNC").val()
    console.log('genNC', genNC)
    $.ajax({
        url: url_web + module_id + '/verVentas',
        type: 'POST',
        data: {
            tdoc: tdoc,
            sfactu: sfactu,
            nfactu: nfactu,
            desde: desde,
            hasta: hasta
        },
        success: function(result) {
            // console.log('datos',result)
            $('#datos_tabla_ventas').DataTable({
                "destroy": true,
                // "searching": false,
                "lengthChange": true,
                "order": [
                    [0, "desc"]
                ],
                "data": result.data,
                "columns": [{
                        "render": function(data, type, row) {
                            let htmlnc = ''
                                // let mytrash = '' // `<button class="btn btn-danger btn-sm" onclick="anularVenta('${row.id_transac}');" value="${row.id_transac}"><span class="glyphicon glyphicon-trash"></span></button>`
                            if (genNC == 'Y' && (row.tdoc == '01' || row.tdoc == '03')) {
                                htmlnc = `<button class="btn btn-primary btn-sm generarNC ${row.isNC?'disabled" disabled':'"'}  idtransac=${row.id_transac}><span class="glyphicon glyphicon-book"></span></button>`

                            }
                            return `<div class="btn-group">
                <button class="btn btn-default btn-sm edit" idtransac=${row.id_transac}><span class="glyphicon glyphicon-plus"></span></button>
                
                ${htmlnc} 
                </div>`;
                        }
                    },
                    { "data": "num_doc" },
                    { "data": "empleado" },
                    { "data": "cliente" },
                    { "data": "alias" },
                    { "data": "tipo_pago" },
                    { "data": "moneda" },
                    { "data": "fecha_registro" },
                    {
                        "data": "hora_ini",
                        "render": function(data, type, row) {
                            return data.substring(0, 5);
                        }
                    },
                    {
                        "data": "hora_fin",
                        "render": function(data, type, row) {
                            return data.substring(0, 5);
                        }
                    },

                    { "data": "subtotal_venta" },
                    { "data": "igv" },
                    { "data": "total_venta" },
                ],
            });
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
            swal("Advertencia!", 'Error en Traer Datos', "warning");
        }
    });

}

$('#datos_tabla_ventas tbody').on('click', 'button.edit', function() {
    var id = $(this).attr('idtransac');
    $.ajax({
        url: url_web + module_id + '/verid',
        type: 'POST',
        data: { id_transac: id },
        success: function(rs) {
            // console.log('data',rs.bus_dato)   
            $('#hdd_id_transac').val(id)
            $("#vd_num_doc").html(rs.bus_dato[0].num_doc)
            $("#vd_empleado").html(rs.bus_dato[0].empleado)
            $("#vd_mesa").html(rs.bus_dato[0].mesa)
                // $("#vd_tipo_pago").html(rs.bus_dato[0].tipo_pago)
            $("#vd_cliente").html(rs.bus_dato[0].tp_ruc + ' | ' + rs.bus_dato[0].n_ruc + ' | ' + rs.bus_dato[0].n_rs)


            let pagos = '';
            rs.lista_mp.map(function(dt) {
                pagos += `<tr><td>${dt.tipo_pago}</td><td>&nbsp;&nbsp;&nbsp;S/ ${dt.monto}</td></tr>`
            })
            $("#vd_tipo_pago").html(pagos)

            $('#rowdet tr').remove()
            $('#impprod tr').remove()
            rs.lista_deta.map(function(dt) {
                // console.log(dt)
                $('#rowdet').append(
                    `<tr>
                        <td>${dt.categoria}</td>
                        <td>${dt.producto}</td>
                        <td>${dt.cantidad}</td>             
                        <td>${dt.venta}</td>             
                        <td>${dt.total}</td>             
                    </tr>`
                )
            })
            $('#impprod').append(
                `<tr>
      <td>NETO</td>             
      <td style="text-align: right;">${rs.bus_dato[0].moneda} ${rs.bus_dato[0].subtotal_venta}</td>             
    </tr>
    <tr>
      <td>IGV</td>             
      <td style="text-align: right;">${rs.bus_dato[0].moneda} ${rs.bus_dato[0].igv}</td>             
    </tr>
    <tr>
      <td>TOTAL</td>             
      <td style="text-align: right;">${rs.bus_dato[0].moneda} ${rs.bus_dato[0].total_venta}</td>             
    </tr>
    `
            )

            $('#myModaldet').modal('show');
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
            swal("Advertencia!", 'Error en Traer Datos', "warning");
        }
    });
}).on('click', 'button.generarNC', function() {
    var id = $(this).attr('idtransac');
    $.ajax({
        url: url_web + module_id + '/verid',
        type: 'POST',
        data: { id_transac: id },
        success: function(rs) {
            $("#vnc_num_doc").html(rs.bus_dato[0].num_doc)
            $("#vnc_id_transac").val(rs.bus_dato[0].id_transac)
            $("#vnc_empleado").html(rs.bus_dato[0].empleado)
            $("#vnc_mesa").html(rs.bus_dato[0].mesa)
            $("#vnc_tipo_pago").html(rs.bus_dato[0].tipo_pago)
            $("#vnc_cliente").html(rs.bus_dato[0].tp_ruc + ' | ' + rs.bus_dato[0].n_ruc + ' | ' + rs.bus_dato[0].n_rs)

            $('#myModalNC').modal('show');
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
            swal("Advertencia!", 'Error en Traer Datos', "warning");
        }
    });
})


$('#btn_reimpresion').click(() => {
    let id_transac = $('#hdd_id_transac').val()
    $.ajax({
        url: url_web + 'Tpv/generarFacBolElectronica/' + id_transac,

        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
            swal("Advertencia!", 'Error en Traer Datos', "warning");
        }
    });
})

function cierreCaja() {
    $.ajax({
        url: url_web + module_id + '/vercierre',
        type: 'POST',
        // data: {fecha  },
        success: function(result) {
            // console.log('datos', result) 
            $('#datos_cierre').DataTable({
                "destroy": true,
                "searching": true,
                "lengthChange": true,
                "order": [
                    [1, "desc"],
                    [2, "asc"],
                ],
                "data": result.data,
                "columns": [{
                        "render": function(data, type, row) {
                            return `<div class="btn-group">
                            <button class="btn btn-default btn-sm ver" idcierre=${row.id_cierre}><span class="glyphicon glyphicon-plus"></span></button>
                        </div>`;
                        }
                    },
                    { "data": "fecha_cierre" },
                    { "data": "turno" },
                    {
                        "data": "hora_cierre",
                        "render": function(data, type, row) {
                            return data.substring(0, 5);
                        }
                    },
                    { "data": "empleado" },
                    { "data": "total_ticket" },
                    { "data": "total_cliente" },
                    { "data": "total_efectivo" },
                    { "data": "total_tarjetas" },
                    { "data": "total_caja" },
                ],
            });
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
            swal("Advertencia!", 'Error en Traer Datos', "warning");
        }
    });
}

$('#datos_cierre tbody').on('click', 'button.ver', function() {
    var id = $(this).attr('idcierre');
    $.ajax({
        url: url_web + module_id + '/verdetcierre',
        type: 'POST',
        data: { id_cierre: id },
        success: function(rs) {
            $('#datos_tabla_grupocierre_tbody tr').remove()
            let mytotal = 0.00
            rs.grupo.map(function(dt) {
                mytotal = mytotal + parseFloat(dt.total_venta);
                $('#datos_tabla_grupocierre_tbody').append(
                    `<tr>
                        <td>${dt.tipo_pago}</td>
                        <td style="text-align: right;">${dt.total_venta}</td>             
                    </tr>`
                )
            })
            $('#datos_tabla_detallecierre').DataTable({
                "destroy": true,
                "bScrollCollapse": true,
                "sScrollY": "500px",
                "paging": false,
                "dom": 'Bfrtip',
                "order": [
                    [0, "asc"]
                ],
                "buttons": [{
                    extend: 'excel',
                    title: "data_cierre",
                    sheetName: 'Exported data'
                }],
                "data": rs.data,
                "columns": [
                    { "data": "tipo_pago" },
                    { "data": "fecha_registro" },
                    { "data": "num_doc" },
                    { "data": "total_venta" }
                ],
            });
            //totales
            $('#datos_tabla_grupocierre_tbody').append(`<tr>
                    <td style="text-align: right;"><b>Total Cierre</b></td>
                    <td style="text-align: right;">${mytotal.toFixed(2)}</td>             
                </tr>`)
            $('#myModaldetcierre').modal('show');



        }
    });
})

$('#m_CierreCaja').click(() => cierreCaja())


$("#buscarventa").submit(function(e) {
    e.preventDefault()
    tablaventas()
})

$('#ticketNC').click(function() {
    let id_transac = $("#vnc_id_transac").val()
    let tpo_nc = $("#vnc_codigo_nota").val()
    let glosa = $("#vnc_codigo_nota option:selected").text()
    swal({
            title: "Está Seguro?",
            text: "Este Proceso no es Reversible!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si Generar NC!",
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                url: url_web + module_id + '/generarNC',
                type: 'POST',
                data: { id_transac: id_transac, tpo_nc: tpo_nc, glosa: glosa },
                success: function(resp) {
                    console.log('resp', resp)

                    swal("Exito!", "Se Generó una Nota Crédito N°:" + resp, "success");
                    tablaventas()
                },
                error: function(jqXHR, textStatus, error) {
                    console.log(jqXHR.responseText);
                }
            });
        });

})