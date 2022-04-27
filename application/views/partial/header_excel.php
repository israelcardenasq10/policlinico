<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo base_url();?>public/images/favicon.ico"> 
    <title>Punto de Venta - El Gran Charlee</title>
  </head>

<!-- BootStrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/bootstrap.min.css">
<!-- DatePicker -->
<!--<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css">-->
<!-- <link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/datepicker.min.css"> -->
<!-- <link rel="stylesheet" href="<?=base_url()?>public/css/select2.min.css"> -->

<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/control_panel_<?=$g_tema?>.css">
<!-- Custom Fonts -->    
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/fonts/abel/stylesheet.css"/>
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/font-awesome/css/font-awesome.css">

<!-- sweetalert-master -->
</script> <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/jquery/css/sweetalert.css">

<body>
<?php if(isset($allowed_modules)){ ?>
<div class="container-fluid">
      <nav class="navbar navbar-default navbar-fixed-top" role="navigation">

          <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                  <span class="sr-only">Desplegar navegación</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
          </div>

          <div class="collapse navbar-collapse navbar-ex1-collapse">
          
              <ul class="nav navbar-nav navbar-left">
                  <li style="font-size: 12px; "><a href="<?=base_url()?>/panel"><span class="glyphicon glyphicon-home" style="color: orange;"></span></a></li>
              </ul>          
          
              <ul class="nav navbar-nav">
                  <?php foreach($allowed_modules->result() as $k=>$module){
                        if($module->parent == 'menu'):?>
                        <li <?php if(@$module_id == $module->module_id) echo 'class="active"'; ?>>
                          <a href="<?=base_url().$module->module_id?>"><?=ucfirst($module->alias)?></a> 
                        </li>
                  <?php endif; 
                        } ?>


                  <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Aplicaciones<b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu">
                          <?php foreach($allowed_modules->result() as $k=>$module){
                                if($module->parent == 'app'):?>
                                <li <?php if(@$module_id == $module->module_id) echo 'class="active"'; ?>>
                                  <a href="<?=base_url().$module->module_id?>"><?=ucfirst($module->alias)?></a> 
                                </li>
                          <?php endif; 
                                } ?>
                      </ul>
                  </li>
                  <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      Master<b class="caret"></b>
                    </a>
                      <ul class="dropdown-menu">
                          <?php foreach($allowed_modules->result() as $k=>$module){
                                if($module->parent == 'master'):?>
                                <li <?php if(@$module_id == $module->module_id) echo 'class="active"'; ?>>
                                  <a href="<?=base_url().$module->module_id?>"><?=ucfirst($module->alias)?></a> 
                                </li>
                          <?php endif; 
                                } ?>
                      </ul>
                  </li>
              </ul>
              <ul class="nav navbar-nav navbar-right" style="color: silver;">
                  <li style="font-size: 12px; ">
                    <br /><span>Bienvenido!</span>
                    <strong style="color: orange;"><?=$username?></strong>
                  </li>

                  <li>
                    <a href="<?=base_url().'tpv/salir'?>" style="padding: 0px 10px;"><img class="img-circle" src="<?=base_url()?>public/images/users/empleados/<?=$de_foto?>" width="45" height="" /></a>
                  </li>
              </ul>
          </div>
      </nav>
</div>


<input type="hidden" id="url_web" value="<?=base_url()?>">
<input type="hidden" id="url_web_public" value="<?=base_url()?>public/">
<input type="hidden" id="perfil_id" value="<?=$this->session->userdata('id_perfil')?>">
<input type="hidden" id="module_id" value="<?=$this->session->userdata('module_id')?>">
<?php } ?>