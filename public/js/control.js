$(document).ready(function(){

    $( "#clave" ).hide();
    $( "#welcome" ).hide();
    var pagina = $( "#hdpagina" ).val();
    var hora_sistema = $( "#hora_sistema" ).val();

    $( "#reloj" ).on("click", function(){
    	$( "#texto" ).hide();
        $( "#clave" ).show();
        $( "#pass" ).focus();    
    });
  
    $( "#btngo" ).on("click", function(){

        $( this ).prop('disabled', true);
        $( this ).text('Cargando...');

        if($("#pass").val() == "")
        {
            sweetAlert("Por favor ingrese el Password!", "Gracias!", "error");
            $( this ).prop('disabled', false);
            $( this ).text('GO');
        	return false;
        }
        else
        {
            //$( "#reloj" ).html( "<img src='"+url_web_public+"images/admin/load.gif' style='border: 0px;' />" );
        	$.ajax({
        	  url: url_web + '/control/verinfoempleado',
        	  type:'POST',
        	  data: {clave : $("#pass").val() },
        	  success: function(result)
        	  {
                    if (result == "Clave Incorrecto")
                    {
                        sweetAlert("Dato o Password Incorrecto!", "Intente de Nuevo!", "error");
                        $( "#btngo" ).attr('disabled', false);
        	              return false;
                    }
                    else if (result == "Dentro de la Hora" )
                    {   
                        sweetAlert("Cate Perú!", "Usted ya esta registrado, para salir o volver a ingresar espere 1 hora.", "error");
                        $( "#btngo" ).attr('disabled', false);
                    }    
                    else if (result == "Sesion Excedida +2" )
                    {   
                        sweetAlert("No se permite mas de 2 Sesiones en la misma fecha", "Gerencia Cate Peru!", "error");
                        $( this ).attr('disabled', false);                
                    }   
                   else if (hora_sistema <= "00:30" )
                    {   
                        sweetAlert("No puede ingresar antes de las 7:30am", "Cate Peru!", "error");
                        $( "#btngo" ).attr('disabled', false);
                    }                     
                    else 
                    {
                        $( "#reloj" ).hide();
                        $('#welcome').html(result).show();   
                    }  

                        setTimeout(function(){ 
                           // $('#welcome').fadeOut();
                            document.location.href = url_web + "/control";
                        }, 6000);  

                    $( "#btngo" ).prop('disabled', false);
                    $( this ).text('GO');
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
