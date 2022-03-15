<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url();?>public/images/favicon.ico"> 
    <title>Punto de Venta</title>
    <!-- IMPORTACIÓN PARA BOOTSTRAP -->
    <link href="public/bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>public/bootstrap/css/bootstrap.css">    
    <style>
    	body {
        background-image:url(public/images/back_caf1.jpg);    
    	background-repeat: no repeat;
    	background-attachment: fixed;
    	background-position: center;
    	-webkit-background-size: cover;
    	-moz-background-size: cover;
    	-o-background-size: cover;
    	background-size: cover;
        }
        
        .main {
            margin: 30px 0px 0px 0px;
            text-align: center;
        }
        
        .form-login {
            background-color: #fff;
            padding: 25px;
            border-radius: 6px;
            border-color:#d2d2d2;
            border:solid 1px silver;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        
        }
        
        p {
            font-size: 16px;
            line-height: 25px;
            padding-top: 20px;
        }        
        
    </style>
    <!-- Keyboard -->
    <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/jquery/css/jQKeyboard.css">
</head>
<body>
<div class="main">            
<div class="container">
    <div class="row">
        <div class="col-xs-3"></div>
        <div class="col-xs-6">
        <div class="form-login">
            <div class="row">
                <div class="col-xs-6" style="margin-left: -15px; margin-right: -15px;"><img class="img-responsive" src="<?=base_url();?>public/images/<?=$g_logotipo?>" style="display: inline;"/></div>
                
				<div class="col-xs-6">
                        <form action="<?php echo base_url().'login'; ?>" method="post" id="loginform" name="loginform" class="form-horizontal" role="form">          
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon1"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" name="username" id="username" class="form-control input-sm " placeholder="Usuario">
                            </div>
                            <br />
                            <div class="input-group">
                              <span class="input-group-addon" id="sizing-addon1"><span class="glyphicon glyphicon-cloud-upload"></span></span>
                              <input type="password" name="password" id="password" class="form-control input-sm " placeholder="Clave" />
                            </div>            
                            <br />
                            <div class="wrapper">
                            <!-- <span class="group-btn col-xs-9" style="padding-left: 0px;"> -->
                            <span class="group-btn">
                                <button type="submit" id="btnlogin_acceder" class="btn btn-success btn-block">Acceder</button>
                            </span>
                            <!--
                            <span class="group-btn col-xs-3" style="padding-right: 0px;">     
                                <button type="button" id="btncerrar" class="btn btn-default btn-block" onclick="">
                                    <center><span class="glyphicon glyphicon-remove" style="color: red;"></span></center>
                                </button>
                            </span>
                            -->
                            </div>
                        </form>
                </div>
            </div>
         </div>    
            <?php echo validation_errors('<div style="color: red;">', '</div>');
                                if ($this->session->flashdata('notice')):
                                    echo '<div style="color: red;">';
                                    echo $this->session->flashdata('notice');
                                    echo '</div>'; 
                                endif  ?>
            </div>        
        </div>
        <div class="col-md-3"></div>
        </div>
    </div>
</div>
<?php 
?>

<!-- CIERRA IMPORTACIÓN BOOTSTRAP -->
<script src="<?php echo base_url();?>public/jquery/jquery-1.12.4.min.js"></script>
<script src="<?php echo base_url();?>public/bootstrap/js/bootstrap.min.js"></script>
<!-- Keyboard -->
<script type="text/javascript">
    $(document).ready(function(){
        $( '#btnlogin_acceder' ).on('click', function(){
            $( this ).text('Cargando...'); 
            //$( this ).attr("disabled", true); no funciona para CHROME en la vesion JQUERY 3.
        });
        // $( '#username, #password' ).on('click', function(){
        //    $('#jQKeyboardContainer').addClass("center-block");
        // });

        /*
        $('#btncerrar').click(function(){
            $(window).trigger('beforeunload');
        });
        */      
    });
</script>

<script> 
    function salirNavegador(){
        window.onbeforeunload = function(){return true;};  
    }
</script>

 </body>
</html>