$(document).ready(function(){

var pagina = $( "#hdpagina" ).val();
var paginaCat = $( "#hdpaginaCat" ).val();

  // INSERTAR / MODIFICAR
  $( "#btnsave" ).on("click", function(){

  	$( '#msj_valida' ).removeClass('alert-success');
  	$( '#msj_valida' ).addClass('alert-danger');

	if($("#nombres").val() == "")
	{
		$( "#msj_valida" ).html( "Por favor ingrese nombre del Articulo o Servicio!" ).slideDown( "slow" );
		aplicarTiempo("#msj_valida");
        $( "#nombres" ).focus();
		return false;
	}
	else if($("#id_cate_serv").val() == "0")
	{
		$( "#msj_valida" ).html( "Por favor selecione una Categoria!" ).slideDown( "slow" );
		aplicarTiempo("#msj_valida");
		$( "#id_cate_serv" ).focus();
		return false;
	}
	/*else if($("#cuenta_conta").val() == "")
	{
		$( "#msj_valida" ).html( "Por favor ingrese el Nro de Cuenta Contable!" ).slideDown( "slow" );
		aplicarTiempo("#msj_valida");
		$( "#cuenta_conta" ).focus();
		return false;
	}*/
	else
	{
		$( "#data_listado" ).html( "<div style='text-align: center;'><img src='"+url_web_public+"images/admin/load.gif' style='border: 0px;' /></div>" ).slideDown( "slow" );
		$( "#btnsave" ).text('Cargando...');
		$( "#btnsave" ).prop( "disabled", true );

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

			    swal("Excelente!", "Se "+v_mensaje+" el registro satisfactoriamente!", "success")

			    $( "#btnsave" ).text('Grabar');
			    $( "#btnsave" ).prop( "disabled", false );
			    //aplicarTiempo("#msj_valida");
			    $("#frm1 input").val('');
	            $( "#id_cate_serv" ).val(0);
	            $( "#id_cate_serv" ).trigger( "change" );            
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


  // INSERTAR / MODIFICAR CATEGORIA
  $( "#btnsavec" ).on("click", function(){

  	$( '#msj_valida' ).removeClass('alert-success');
  	$( '#msj_valida' ).addClass('alert-danger');

	if($("#id_cate_serv").val() == "")
	{
		$( "#msj_valida" ).html( "Por favor ingrese un Codigo!" ).slideDown( "slow" );
		aplicarTiempo("#msj_valida");
        $( "#id_cate_serv" ).focus();
		return false;
	}
	else if($("#nombre").val() == "")
	{
		$( "#msj_valida" ).html( "Por favor ingrese un Nombre!" ).slideDown( "slow" );
		aplicarTiempo("#msj_valida");
		$( "#nombre" ).focus();
		return false;
	}
	else
	{
		$( "#data_listado" ).html( "<div style='text-align: center;'><img src='"+url_web_public+"images/admin/load.gif' style='border: 0px;' /></div>" ).slideDown( "slow" );
		$( "#btnsavec" ).text('Cargando...');
		$( "#btnsavec" ).prop( "disabled", true );
		
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
		  url: url_web + module_id + '/'+v_accion+paginaCat,
		  type:'POST',
		  data: $("#frm1").serialize(),
		  success: function(result)
		  {
			    $('#data_listado').html(result);

				$( '#datos_tabla_ajax' ).DataTable({
			      "order": [[ 0, 'desc' ]],
			      "pagingType": "full_numbers",
			      "displayLength": 10
			    });

			    swal("Excelente!", "Se "+v_mensaje+" el registro satisfactoriamente!", "success")

			    $( "#btnsavec" ).text('Grabar');
			    $( "#btnsavec" ).prop( "disabled", false );
			    //aplicarTiempo("#msj_valida");
			    $("#frm1 input").val('');
                $( "#id_cate_serv" ).prop( "readonly",false );              
		  },
		  error: function(jqXHR, textStatus, error)
		  {
		  	$( '#msj_valida' ).addClass('alert-danger');
		  	$( '#msj_valida' ).html(jqXHR.responseText);
		  }
		});
	}
  }); 

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
	    	//Pasa Valores directamente a los DIVS a traves de Json
            if(accion == "vercategorias")
            {             
                $( "#id" ).val(data[0].id_cate_serv);            
                $( "#nombre" ).val(data[0].nombre);
                $( "#id_cate_serv" ).val(data[0].id_cate_serv);                
                $( "#id_cate_serv" ).prop( "readonly",true );
            }
            else
            {
                $( "#id_serv_prov" ).val(data[0].id_serv_prov);
                $( "#cuenta_conta" ).val(data[0].cuenta_conta);
                $( "#nombres" ).val(data[0].nombres);
                $( "#id_cate_serv" ).val(data[0].id_categoria);
                $( "#id_cate_serv" ).trigger( "change" );                
            }
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