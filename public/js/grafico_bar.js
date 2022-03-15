$(document).ready(function() {
    //alert(JSON.stringify(json_graf_3));	

    var array_costo = [];
    var array_venta = [];
    $.each(json_graf_3, function(i, valor) {
        array_costo.push(valor.costo);
        array_venta.push(valor.total_venta);
    });


    var datos = {
        labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        datasets: [{
                label: "Año pasado " + json_graf_3[0].moneda,
                backgroundColor: "#d2d6de",
                data: array_costo
            },
            {

                label: "Ventas " + json_graf_3[0].moneda,
                backgroundColor: "rgba(41,128,185,0.8)",
                data: array_venta
            }
        ]
    };


    var canvas = document.getElementById('chart_bar').getContext('2d');
    window.bar = new Chart(canvas, {
        type: "bar",
        data: datos,
        options: {
            elements: {
                rectangle: {
                    borderWidth: 1,
                    borderColor: "#666666",
                    borderSkipped: 'bottom',
                    //scaleGridLineColor: "rgba(0,0,0,.05)"
                }
            },
            responsive: true
                /*
                title : {
                	display : true,
                	text : "Prueba de grafico de barras"
                }
                */
        }
    });


    // setInterval(function(){
    $.ajax({
        type: 'POST',
        url: url_web + module_id + '/actualizarGraficoBar',
        data: {},
        dataType: 'json',
        success: function(data_json) {
            var array_costo = [];
            var array_venta = [];
            $.each(data_json, function(i, valor) {
                array_costo.push(valor.costo);
                array_venta.push(valor.total_venta);
            });

            var newData = [
                array_costo,
                array_venta,
            ];

            $.each(datos.datasets, function(i, dataset) {
                dataset.data = newData[i];
            });

            window.bar.update();
        },
        error: function(jqXHR, textStatus, error) {
            alert(jqXHR.responseText);
        }
    });

    // }, 5000);


    //function getRandom(){
    //	return Math.round(Math.random() * 100);
    //}

});