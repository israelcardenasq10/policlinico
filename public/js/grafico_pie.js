$(document).ready(function(){
	//alert(JSON.stringify(json_graf_1));
	//alert(json_graf_1[0].id_producto);
	
	var array_cantidad = [];
	var array_producto = [];
	$.each(json_graf_1, function(i, valor){
		array_cantidad.push(valor.cantidad_venta);
		array_producto.push(valor.producto);
	});

	var datos = {
			type: "doughnut", //pie
			data : {
				datasets :[{
					//data : [],
					data : array_cantidad,
					backgroundColor: [
						"#f56954",
						"#3c8dbc",
						"#f39c12",
						"#949FB1",
						"#00a65a"
					],
				}],
				labels : array_producto
			},
			options : {
				responsive : true,
				animationEasing: "easeOutBounce",
				animateRotate: true,
				animateScale: false
			}
		};

	var canvas = document.getElementById('chart_pie').getContext('2d');
	window.doughnut = new Chart(canvas, datos);

	// Se ejecuta de manera automática cada 5 Seg.
	setInterval(function(){
		datos.data.datasets.splice(0);
		datos.data.labels.splice(0); // Borra el Array en primera instancia.

		$.ajax({ 
		    type: 'POST', 
		    url: url_web + module_id + '/actualizarGraficoPie', 
		    data: { }, 
		    dataType: 'json',
		    success: function (data_json) 
		    {
		    	var array_cantidad = [];
				$.each(data_json, function(i, valor){
					array_cantidad.push(valor.cantidad_venta);
				});

				var newData = {
					data : array_cantidad,
					backgroundColor: [
						"#f56954",
						"#3c8dbc",
						"#f39c12",
						"#949FB1",
						"#00a65a"
					]	
				};

				datos.data.datasets.push(newData);
				
				$.each(data_json, function(i, valor){
					datos.data.labels.push(valor.producto); //Agrega etiquetas
				});

				window.doughnut.update(); // Actualiza el Gráfico!
		    },
			error: function(jqXHR, textStatus, error)
			{
				alert(jqXHR.responseText);
			}
		});

	}, 100000);
	// --
});