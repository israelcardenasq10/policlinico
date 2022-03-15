$(document).ready(function(){

  $('[data-toggle="tooltip"]').hover(function(){
      $('.tooltip-inner').css('min-width', '260px');
      $('.tooltip-inner').css('background-color', 'gray');
  });

 $("#tema_0").tooltip({
        title: "<img src='public/images/tema_0.jpg'/>",  
        html: true
    });   

 $("#tema_1").tooltip({
        title: "<img src='public/images/tema_1.jpg' />",  
        html: true
    });   

 $("#tema_2").tooltip({
        title: "<img src='public/images/tema_2.jpg' />",  
        html: true
    });       

  /** Modificar */
  $( "#btnMod" ).on("click", function(){
      //id = $("#hdid").val();  
      if($("#ruc").val() == "")
      {
        $( "#msj_valida" ).html( "Por favor escriba su <strong>RUC</strong>!" ).slideDown( "slow" );
        $( "#ruc" ).focus();
        aplicarTiempo("#msj_valida");
        return false;
      }  
      else
      { 
        $( '#msj_valida' ).removeClass('alert-danger');
        $( "#msj_valida" ).html( "<img src='"+url_web_public+"images/admin/loading.gif' style='border: 0px;' />" ).slideDown( "slow" );
        $( "#btnMod" ).text('Cargando...');
        $( "#btnMod" ).prop('disabled', true);
        $.ajax({
          url: url_web + module_id + '/actualizar',
          type:'POST',
          data: $("#frm1").serialize(),
          success:function(result)
          {
              //llama y ejecuta la función subirArchivo();
              subirArchivos();
            
              swal({
                  title: "Excelente!",   
                  text: "Se Actualizó el registro satisfactoriamente..!",   
                  type: "success",
                  closeOnConfirm: true
                  }, function(){
                     window.location.href = url_web + module_id; 
              });

            $( "#msj_valida" ).slideUp( "slow" );
            $( "#btnMod" ).text('Grabar');
          },
          error: function(jqXHR, textStatus, error)
          {
            alert( "Error: " + jqXHR.responseText);
          }
        });
      }
  });
});
