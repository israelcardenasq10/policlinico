$(document).ready(function(){

var pagina = $( "#hdpagina" ).val();

  // INSERTAR / MODIFICAR
  $( "#btnsave" ).on("click", function(){

  	$( '#msj_valida' ).removeClass('alert-success');
  	$( '#msj_valida' ).addClass('alert-danger');

	if($("#serie").val() == "")
	{
		$( "#msj_valida" ).html( "Por favor ingrese la Serie!" ).slideDown( "slow" );
		aplicarTiempo("#msj_valida");
		return false;
	}
	else if($("#tipo_doc").val() == "")
	{
		$( "#msj_valida" ).html( "Por favor ingrese el Tipo Doc.!" ).slideDown( "slow" );
		aplicarTiempo("#msj_valida");
		$( "#nombre" ).focus();
		return false;
	}
	else if($("#descripcion").val() == "")
	{
		$( "#msj_valida" ).html( "Por favor ingrese el Nombre del Documento.!" ).slideDown( "slow" );
		aplicarTiempo("#msj_valida");
		$( "#descripcion" ).focus();
		return false;
	}
	else
	{
		//$( '#msj_valida' ).removeClass('alert-danger');
		$( "#data_listado" ).html( "<div style='text-align: center;'><img src='"+url_web_public+"images/admin/load.gif' style='border: 0px;' /></div>" ).slideDown( "slow" );
		$( "#btnsave" ).text('Cargando...');
		$( "#btnsave" ).prop( "disabled", true );
		//$('#data_listado').slideUp();
		//$('#data_listado').empty();

		if($( '.id_mod' ).val() === '')
		{
			var v_accion = 'insertar';
			var v_mensaje = 'Insertó';
		}
		else
		{
			var v_accion = 'actualizar';
			var v_mensaje = 'Actualizó';
		}

		$.ajax({
		  url: url_web + module_id + '/'+v_accion+pagina,
		  type:'POST',
		  data: $("#frm1").serialize(),
		  success: function(result)
		  {
			    //$('#data_listado').html(result).slideDown();
			    $('#data_listado').html(result);
	   
				$( '#datos_tabla_ajax' ).DataTable({
			      "order": [[ 0, 'desc' ]],
			      "pagingType": "full_numbers",
			      "displayLength": 10,
			      "fnDrawCallback": function( oSettings ) {
						// Accesos de privilegios al matenimiento
						if($("#allow_modifica").val() == '')
				  		{
				  			$( '.btn-success' ).addClass('btn-default');
				  			$( '.btn-success' ).removeClass('btn-success');
				  			$( '.btn-default' ).css({"pointer-events": "none", "color": "rgba(0,0,0,0.1)"});
				  		}
				  		if($("#allow_elimina").val() == '')
				  		{
				  			$( '.btn-danger' ).addClass('btn-default');
				  			$( '.btn-danger' ).removeClass('btn-danger');
				  			$( '.btn-default' ).css({"pointer-events": "none", "color": "rgba(0,0,0,0.1)"});
				  		}
				   }
			    });

				// Verificar dato duplicado
				if($('#hddato').val() == 'existe')
			  	{
	                sweetAlert("Error...!", "El Tipo Documento ya existe en el sistema!!", "error");
			  	}
			  	else
			  	{
			  		swal("Excelente!", "Se "+v_mensaje+" el registro satisfactoriamente!", "success");
			  	}
			  	// --

			    $( "#btnsave" ).text('Grabar');
			    $( "#btnsave" ).prop( "disabled", false );

			    //aplicarTiempo("#msj_valida");
			    $("#frm1 input").val('');
		  },
		  error: function(jqXHR, textStatus, error)
		  {
		  	$( '#msj_valida' ).addClass('alert-danger');
		  	$( '#msj_valida' ).html(jqXHR.responseText);
		  }
		});
	}
  });
  // --

});


// MOSTRAR DATOS A BUSCAR 
function ver(id, accion)
{
	$( 'tr' ).removeClass('selected');
	$( '#service' + id).addClass('selected');

	$( "#form" ).hide();
	$( "#form_edit" ).html( "<img src='"+url_web_public+"images/admin/load.gif' style='border: 0px;' />" ).slideDown( "slow" );

	$.ajax({ 
	    type: 'POST', 
	    url: url_web + module_id + '/' + accion, 
	    data: { id: id },
	    dataType: 'json',
	    success: function (data) 
	    {
	    	//alert(JSON.stringify(data));
	       	//$( '#msj_valida' ).html(data).show();
	    	//Desplaza Campos recibidos del JSON php
            $( "#id_serie" ).val(data[0].id_serie);
            $( "#serie" ).val(data[0].serie);
            $( "#tipo_doc" ).val(data[0].tipo_doc);
			$( "#descripcion" ).val(data[0].descripcion);
			$( "#local" ).val(data[0].local);
			$( "#tdoc" ).val(data[0].tdoc);
            // --
	        $( "#form_edit" ).empty()
		  	$( "#form" ).show();
	    },
		error: function(jqXHR, textStatus, error)
		{
			$( '#msj_valida' ).addClass('alert-danger');
			$( '#msj_valida' ).html(jqXHR.responseText);
		}
	});
}
