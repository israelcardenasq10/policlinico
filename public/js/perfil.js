$(document).ready(function() {
    cargartabla();
});

function cargartabla() {
    let id_perfil = $('#id_perfil').val()
    $.ajax({
        url: url_web + module_id + '/listar',
        type: 'POST',
        data: { id_perfil: id_perfil },
        success: function(data) {
            $('#datos_tabla').DataTable({
                "destroy": true,
                "searching": true,
                "lengthChange": true,
                "dom": 'Bfrtip',
                "order": [
                    [2, "desc"]
                ],
                "buttons": [{
                    extend: 'excel',
                    title: "data_perfiles",
                    sheetName: 'Exported data'
                }],
                "data": data,
                "columns": [{
                        "render": function(data, type, row) {
                            return `<div class="btn-group">
                              <button class="btn btn-sm btn-warning btn1" id_ma=${row.id_ma} ><i class="glyphicon glyphicon-edit"></i></button>
                              <button class="btn btn-sm btn-danger btn2" id_ma=${row.id_ma} ><i class="glyphicon glyphicon-trash"></i></button>                              
                            </div>`;
                        }
                    },
                    { "data": "nom_perfil" },
                    { "data": "module_id" },
                    { "data": "accion" },
                    { "data": "alias" },
                    { "data": "tipo" },
                    { "data": "sort" }
                ],

            });
        },
    });

}

$('#btn_search').click(() => cargartabla())
$('#btn_mod_new').click(function() {
    $('#myform1')[0].reset();
    $('#frmid_ma').val(0)
    $('#myModal').modal('show')

})

//traer datos para editar
$('#datos_tabla tbody').on('click', 'button.btn1', function() {
    let id_ma = $(this).attr('id_ma');
    $.ajax({
        url: url_web + module_id + '/getByID',
        type: 'POST',
        data: { id_ma: id_ma },
        success: function(data) {
            // console.log('data', data)
            $('#frmid_ma').val(data[0]['id_ma'])
            $('#frmid_perfil').val(data[0]['id_perfil'])
            $('#frmmodule_id').val(data[0]['module_id'])
            $('#frmaccion').val(data[0]['accion'])
            $('#frmalias').val(data[0]['alias'])
            $('#frmtipo').val(data[0]['tipo'])
            $('#frmsort').val(data[0]['sort'])
            $('#myModal').modal('show')

        },
    });
}).on('click', 'button.btn2', function() {
    let id_ma = $(this).attr('id_ma');
    $.ajax({
        url: url_web + module_id + '/eliminar',
        type: 'POST',
        data: { id: id_ma },
        success: function(result) {
            swal('Eliminado!', '', 'success')
        },
    });
})

$('#myform1').submit(function(e) {
    e.preventDefault()
    $.ajax({
        url: url_web + module_id + '/save',
        type: 'POST',
        data: $('#myform1').serialize(),
        success: function(result) {
            swal('Exito!', '', 'success')
            $('#myModal').modal('hide')
        },
    });

})