$(document).ready(function() {
    if (json_graf_2) {
        var datos = {
            labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
            datasets: [{
                    label: 'Ventas Soles: ' + json_graf_2[0].moneda,
                    data: [
                        json_graf_2[0].total_venta,
                        json_graf_2[1].total_venta,
                        json_graf_2[2].total_venta,
                        json_graf_2[3].total_venta,
                        json_graf_2[4].total_venta,
                        json_graf_2[5].total_venta,
                        json_graf_2[6].total_venta
                    ],
                    backgroundColor: "rgba(41,128,185,0.8)"
                }]
                /*
                ,{
                    label: 'Biciclette',
                    data: [1109,3653,2874,3450,6590,7100],
                    backgroundColor: "rgba(39,174,96,0.8)"
                }]
                */
        };

        var canvas = document.getElementById('chart_linea').getContext('2d');
        window.line = new Chart(canvas, {
            type: 'line',
            data: datos,
            options: {
                responsive: true,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false
            }
        });


        // setInterval(function(){
        $.ajax({
            type: 'POST',
            url: url_web + module_id + '/actualizarGraficoLine',
            data: {},
            dataType: 'json',
            success: function(data_json) {
                console.log(data_json)
                var newData = [
                    [
                        data_json[0].total_venta,
                        data_json[1].total_venta,
                        data_json[2].total_venta,
                        data_json[3].total_venta,
                        data_json[4].total_venta,
                        data_json[5].total_venta,
                        data_json[6].total_venta
                    ]
                    //[getRandom(),getRandom(),getRandom(),getRandom(),getRandom(),getRandom()]		
                ];

                $.each(datos.datasets, function(i, dataset) {
                    dataset.data = newData[i];
                });

                window.line.update();
            },
            error: function(jqXHR, textStatus, error) {
                alert(jqXHR.responseText);
            }
        });
        // }, 15000);
    }
});