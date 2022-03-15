<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Control de Acceso - Cafetería Cate</title>
  </head>
<!-- BootStrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/control_panel_0.css">
<!-- Custom Fonts -->    
    <!--<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/fonts/abel/stylesheet.css"/>-->
    <link rel="stylesheet" href="<?=base_url()?>public/bootstrap/font-awesome/css/font-awesome.css">
<!-- sweetalert-master -->
</script> <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/sweetalert-master/dist/sweetalert.css">
<link href="<?=base_url()?>public/css/reloj.css" rel="stylesheet" />
<body>
<?php //if(isset($allowed_modules)){ ?>
<input type="hidden" id="url_web" value="<?=base_url()?>">
<input type="hidden" id="url_web_public" value="<?=base_url()?>public/">
<input type="hidden" id="hora_sistema" value="<?=$hora_sistema?>">
<?php //} ?>
<style>
html, body {
  height: 100%;
}
.container{
  width:1025px;
}
.vertical-center {
  height:100%;
  width:100%;
  text-align: center;  /* align the inline(-block) elements horizontally */
  font: 0/0 a;         /* remove the gap between inline(-block) elements */
}
.vertical-center:before {    /* create a full-height inline block pseudo=element */
  content: ' ';
  display: inline-block;
  vertical-align: middle;  /* vertical alignment of the inline element */
  height: 100%;
}
.vertical-center > .container {
  max-width: 100%;
  display: inline-block;
  vertical-align: middle;  /* vertical alignment of the inline element */
  font: 16px/1 "Helvetica Neue", Helvetica, Arial, sans-serif;        /* <-- reset the font property */
}
.box{
  width:300px;
  height:300px;
  border-top:2px solid #85A7B5;
  border-radius:50%;
  margin:10px auto 50px;
  text-align:center;
  box-shadow:
    0 5px 2px 3px rgba(158, 158, 158, 0.4), 
    0 3px 5px #B7B6B6, 
    0 0 0 2px #BBB7AE, 
    inset 0 -3px 1px 2px rgba(186, 178, 165, 0.5),
    inset 0 3px 1px 2px rgba(246, 245, 241, 0.3);
  cursor:pointer;
  position:relative;
}
.box:active{
}
.box:before{
	content:" ";
	display:block;
	position:absolute;
	z-index:-90;
	width:335px;
	height:335px;
	border-radius:50%;
	border-top:2px solid #CCC8BF;
  border-bottom: 1px solid #F4F3F1;
  box-shadow: inset 0 -2px 0 2px #F7F6F2, inset 0 2px 1px 1px #CCC8BF;
	left:-17.5px; 
	top:-20px;
	background:-moz-linear-gradient(#DAD6CB,#F1EDEA);
	box-shadow: inset 1px 0 1px 0px #D9D9D9; 
}
p, p a{
  text-shadow: 1px 1px 1px white;
  color:#7E7E7E;
  text-align:center;
  font-size:18px;
  line-height:2;
  text-decoration:none;
  margin-bottom:30px;
}
.box span{
  display:inline-block;
  box-shadow: 
    inset 0 1px 1px 1px #7E7E7E, 
    0 1px 1px white;
  height:15px; 
  width:15px;
  border-radius:50%;
  background: linear-gradient(#AEADAA,#BAB7AE);
  margin:42px auto;
}
</style>

<div class="vertical-center">
      <div class="container text-center">        
        <div class="row">            
            <div id="reloj" class="btn btn-info btn-lg box">
                <label id="texto" style="margin-top: 45%; font-size: 35px;">PULSE</label>
                <label id="clave" style="margin-top: 45%; font-size: 25px; display: none;">
                    <input type="password" id="pass" placeholder="Su Clave" style="width: 150px; color:gray; padding: 5px; text-align: center; border-radius: 5px;"/><br/>
                    <button id="btngo" class="btn btn-success" style="margin-top: 12px; font-size: 20px;">GO</button>
                </label>               
            </div>
            <div id="welcome" class="box" style="display: none;">
            </div>
        </div>
        <div id="msj_valida"></div>
		<div id="clock" class="light">
			<div class="display">
				<div class="weekdays"></div>
				<div class="ampm"></div>
				<div class="alarm"></div>
				<div class="digits"></div>
			</div>
		</div>
        <!--
		<div class="button-holder">
			<a class="button">Switch Theme</a>
		</div>
        -->
      </div>
</div> 
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?=base_url()?>public/jquery/jquery-1.12.4.min.js"></script>
<script src="<?=base_url()?>public/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>public/js/control.js"></script>
<!-- RELOJ -->
<script src="<?=base_url()?>public/js/moment.min.js"></script>
<script src="<?=base_url()?>public/js/reloj.js"></script>
<!-- <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->
<script src="<?=base_url()?>public/js/function.js"></script>
<script src="<?=base_url()?>public/js/generales.js"></script>
<!-- sweetalert-master -->
<script src="<?=base_url()?>public/sweetalert-master/dist/sweetalert.min.js"></script>
 </body>
</html>