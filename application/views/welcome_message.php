<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Generación de Códigos Únicos </title>

    <!-- ================================
        PLUGIN CSS
    =====================================-->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- ================================
        PLUGIN JAVASCRIPT
    =====================================-->
    <!-- 	<script type="text/javascript" src="<?= sc_url_library('sys', 'DataTables', 'js/jquery.min.js')?>"></script> -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Generación de Códigos Únicos
            </div>
            <div class="card-body">
                <div class="list-table">
                    <table id="dataSeriesProd" class="table table-sm table-bordered table-striped text-center" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código Inicial</th>
                                <th>Código Final</th>
                                <th>Cant. Series</th>
                                <th>Creado por</th>
                                <th>Fecha Creación</th>
                                <th>Imprimir</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const urlnode = 'http://192.168.1.233:6612/icq01/sip/compras/series'
        $(document).ready(function() {

        })

        function cargarDataSerie() {
            fetch(urlnode+'/list')
            .then(res => res.json())
            .then(data => {
				console.log(data)

            })
        }
    </script>
</body>

</html>