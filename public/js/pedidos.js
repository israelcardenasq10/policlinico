$(document).ready(function() {
    // var todayDate = new Date().toISOString()//.slice(0,10);
    let d = new Date()
    var todayDate = new Date(d.getTime() - d.getTimezoneOffset() * 60 * 1000).toISOString().split('T')[0]
        // var todayDate = '2021-12-06'

    $("#v_desde").val(todayDate)
    $("#v_hasta").val(todayDate)
    $("#v_desde2").val(todayDate)
    $("#v_hasta2").val(todayDate)
    tablaventas()
    tablaventas2()
});


function tablaventas() {
    let desde = $("#v_desde").val()
    let hasta = $("#v_hasta").val()
    $.ajax({
        url: url_web + module_id + '/verpedidos',
        type: 'POST',
        data: {
            desde: desde,
            hasta: hasta
        },
        success: function(result) {
            // console.log('datos',result)
            $('#datos_tabla_ventas').DataTable({
                "destroy": true,
                "lengthChange": true,
                "order": [
                    [1, "desc"]
                ],
                "dom": 'Bfrtip',
                "buttons": [{
                    extend: 'excel',
                    title: "Data_Ventas_del_dia",
                    sheetName: 'Exported data'
                }, {
                    extend: 'csv',
                    title: 'Data_Ventas_del_dia',
                    sheetName: 'Exported data'
                }],
                "data": result.data,
                "columns": [{
                        "render": function(data, type, row) {
                            return `<button class="btn btn-default btn-sm edit" idtransac=${row.id_tmp_cab}><span class="glyphicon glyphicon-plus"></span></button>`;
                        }
                    },
                    // { "data": "correlativo" },
                    {
                        "data": "correlativo",
                        "render": function(data, type, row) {

                            return String(data).padStart(4, "0");
                        }
                    },
                    { "data": "empleado" },
                    { "data": "hora_ini" },
                    { "data": "hora_fin" },
                    { "data": "fecha" },
                    { "data": "estado" },
                    { "data": "isDelete" },
                    { "data": "total_venta" },
                    { "data": "mesa" },
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
        data: { id_tmp_cab: id },
        success: function(rs) {
            $('#datos_tabla_ajax').DataTable({
                "destroy": true,
                "lengthChange": true,
                "order": [
                    [0, "asc"]
                ],
                "data": rs.data,
                "columns": [
                    { "data": "correlativo" },
                    {
                        "data": "isDelete",
                        "render": function(data, type, row) {
                            return data ? 'SI' : '';
                        }
                    },
                    { "data": "categoria" },
                    { "data": "nombre" },
                    { "data": "nota_comanda" },
                    {
                        "data": "print_comanda",
                        "render": function(data, type, row) {
                            return data ? 'SI' : '';
                        }
                    },
                    {
                        "data": "dividir_cuenta",
                        "render": function(data, type, row) {
                            return data == '0' ? '' : 'SI';
                        }
                    },
                    {
                        "data": "transac_venta",
                        "render": function(data, type, row) {
                            return data == '0' ? '' : 'SI';
                        }
                    },
                    { "data": "cantidad" },
                    {
                        "render": function(data, type, row) {
                            return row.cantidad * row.venta
                        }
                    },
                    { "data": "date_created" },
                    { "data": "created" },
                    { "data": "date_updated" },
                    { "data": "updated" },

                ],
            });

            $('#myModaldet').modal('show');
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
            swal("Advertencia!", 'Error en Traer Datos', "warning");
        }
    });
})


$("#buscarventa").submit(function(e) {
    e.preventDefault()
    tablaventas()
})

$("#buscarventa2").submit(function(e) {
    e.preventDefault()
    tablaventas2()
})

// $('#m_Detalle').click(() => tablaventas2())

function tablaventas2() {
    let desde = $("#v_desde2").val()
    let hasta = $("#v_hasta2").val()
    $.ajax({
        url: url_web + module_id + '/verdetallepedidos',
        type: 'POST',
        data: {
            desde: desde,
            hasta: hasta
        },
        success: function(result) {
            // console.log('datos', result)
            $('#datos_tabla_ventas_detalle').DataTable({
                "destroy": true,
                "lengthChange": true,
                "order": [
                    [0, "desc"],
                    [1, "asc"]
                ],
                "dom": 'Bfrtip',
                "buttons": [{
                    extend: 'excel',
                    title: "Data_Pedidos_del_dia",
                    sheetName: 'Exported data'
                }, {
                    extend: 'csv',
                    title: 'Data_Pedidos_del_dia',
                    sheetName: 'Exported data'
                }],
                "data": result.data,
                "columns": [
                    // { "data": "" },
                    {
                        "data": "nro_ped",
                        "render": function(data, type, row) {

                            return String(data).padStart(4, "0");
                        }
                    },
                    { "data": "correlativo" },
                    {
                        "data": "isDelete",
                        "render": function(data, type, row) {
                            return data ? 'SI' : '';
                        }
                    },
                    { "data": "fecha" },
                    { "data": "hora_ini" },
                    { "data": "hora_fin" },
                    { "data": "categoria" },
                    { "data": "nombre" },
                    { "data": "nota_comanda" },
                    {
                        "data": "print_comanda",
                        "render": function(data, type, row) {
                            return data ? 'SI' : '';
                        }
                    },
                    {
                        "data": "dividir_cuenta",
                        "render": function(data, type, row) {
                            return data == '0' ? '' : 'SI';
                        }
                    },
                    {
                        "data": "transac_venta",
                        "render": function(data, type, row) {
                            return data == '0' ? '' : 'SI';
                        }
                    },
                    { "data": "cantidad" },
                    {
                        "render": function(data, type, row) {
                            return row.cantidad * row.venta
                        }
                    },
                    { "data": "date_created" },
                    { "data": "created" },
                    { "data": "date_updated" },
                    { "data": "updated" },
                ],
            });
        },
        error: function(jqXHR, textStatus, error) {
            console.log(jqXHR.responseText);
        }
    });
}