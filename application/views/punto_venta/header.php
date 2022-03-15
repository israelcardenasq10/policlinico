<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo base_url();?>public/images/favicon.ico"> 
    <title>Punto de Venta</title>
  </head>

<!-- BootStrap -->
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/bootstrap.min.css">
<!-- DatePicker -->
<!--<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css">-->
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/datepicker.min.css">
<!-- jQuery ui -->
<link rel="stylesheet" href="<?=base_url()?>public/jquery-ui/jquery-ui.min.css">

<!--<link rel="stylesheet" href="<?=base_url()?>public/css/page.css">-->

<!-- Custom Fonts -->
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/punto_venta.css">
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/fonts/open_sans/stylesheet.css"/>
 
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">  -->
<!-- <link rel="stylesheet" href="<?=base_url()?>public/bootstrap/font-awesome/css/font-awesome.css"> -->
<link rel="stylesheet" href="<?=base_url()?>public/bootstrap/font-awesome/css/font-awesome.css">

<!-- Scroll 2 -->
<link rel="stylesheet" href="<?=base_url()?>public/pos/css/posajax.css" type="text/css" charset="utf-8" />    <!--cambiar-->

<!-- Datatables -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" type="text/css" charset="utf-8" /> -->
<link rel="stylesheet" href="<?=base_url()?>public/jquery/css/dataTables.bootstrap.min.css">

<!-- sweetalert-master -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>public/jquery/css/sweetalert.css">


<!-- Checkbox / RadioButton -->
<link rel="stylesheet" href="<?=base_url()?>public/css/checkbox.css">
<link rel="stylesheet" href="<?=base_url()?>public/css/select2.min.css">
<!-- Keyboard -->
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/jquery/css/jQKeyboard.css">
	
	<style type="text/css">
	.btn-delete-prod{
		padding: 3px 8px;
		padding-bottom: 1px;
		padding-top: 4px;
	}
	.validar_texbox{
		border: 2px solid red;
	}
	.ui-autocomplete {
		position: absolute;
		top: 100%;
		left: 0;
		z-index: 3000;
		float: left;
		display: none;
		min-width: 160px;   
		padding: 4px 0;
		margin: 0 0 10px 25px;
		list-style: none;
		background-color: #ffffff;
		border-color: #ccc;
		border-color: rgba(0, 0, 0, 0.2);
		border-style: solid;
		border-width: 1px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		-webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		-moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		-webkit-background-clip: padding-box;
		-moz-background-clip: padding;
		background-clip: padding-box;
		*border-right-width: 2px;
		*border-bottom-width: 2px;
	}
	</style>

<input type="hidden" id="url_web" value="<?=base_url()?>">
<input type="hidden" id="url_web_public" value="<?=base_url()?>public/">
<input type="hidden" id="perfil_id" value="<?=$this->session->userdata('id_perfil')?>">
<input type="hidden" id="module_id" value="<?=$this->session->userdata('module_id')?>">
