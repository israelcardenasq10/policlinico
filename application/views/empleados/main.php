<?php $this->load->view("partial/header"); ?>

<div class="container-fluid" style="padding-top: 15px;">
  <!--<h2><?=strtoupper($module_id)?></h2>-->
  
  <p>
  <ol class="breadcrumb">
	<li><a href="<?=base_url()?>panel">Inicio</a></li>
	<li class="active">Lista de <?=ucwords($module_id)?></li>
  </ol>
  </p>

	<div class="col-md-12" style="margin-top: -30px;">
                  
      <!-- tabs left -->
      <div class="tabbable tabs-left">
        <ul class="nav nav-tabs">
          <?php
         	if(isset($allowed_modules_accion))
          {
         		$arr_accion = array();
          		foreach ($allowed_modules_accion->result() as $key => $lis):
          			if($modo === 'actualizar')
          			{
          				$disabled = 'pointer-events: none; color: rgba(0,0,0,0.1);';
          				$active = '';
          			}
          			else
          			{
          				if($key == 0) $active = 'active';
	      				  else $active = '';

	      				  $disabled = '';
          			}

  	      			if($lis->tipo == 'tabs')
  	      			{ ?>
  	      				<li class="<?=$active?>"><a href="#<?=$lis->accion?>" style="<?=$disabled?>" id="m_<?=$lis->accion?>" data-toggle="tab"><span class="glyphicon glyphicon-th-large"></span> <?=ucwords($lis->accion)?></a></li>
  		  <?php			array_push($arr_accion, $lis->accion);
  		  			}
  		  			elseif($lis->tipo == 'mante')
  		  			{
  		  				if($lis->accion == 'actualizar')
  		  					$allow_modifica = true;
  		  				if($lis->accion == 'eliminar')
  		  					$allow_elimina = true;
  		  			}
  		  			else
  		  			{ ?>
  		  				  <li><a href="<?=base_url().$module_id.'/report'?>" style="<?=$disabled?>" id="m_<?=$lis->accion?>"><?=ucwords($lis->accion)?></a></li>
  		  <?php 	}
  		  		endforeach;
      		}
      			if($modo === 'actualizar'):
      		?>
      				  <li class="active"><a href="#actualizar" id="" data-toggle="tab"><?=ucwords('actualizar')?></a></li>
      	  <?php endif;?>
      	  <?php   if(@$allow_modifica == true):
	      			$a_btn_class_mod = 'success';
	      			$a_btn_disabled_mod = '';
	      			$href_mod = base_url().$module_id.'/ver/';
	      		  else:
	      		  	$a_btn_class_mod = 'default';
	      			$a_btn_disabled_mod = 'pointer-events: none; color: rgba(0,0,0,0.1);';
	      			$href_mod = '';
	      		  endif;

	      		  if(@$allow_elimina == true):
	      			$a_btn_class_eli = 'danger';
	      			$a_btn_disabled_eli = '';
	      			$evento_eli = 'eliminarReg';
	      		  else:
	      		  	$a_btn_class_eli = 'default';
	      			$a_btn_disabled_eli = 'pointer-events: none; color: rgba(0,0,0,0.1);';
	      			$evento_eli = '';
	      		  endif;
	        ?>
        </ul>
        <div class="tab-content">

        	<?php if($modo === 'actualizar')
        		  {?>
          			<div class="tab-pane active" id="actualizar">
  		         		<!-- Actualizar! -->
  		         		<div id="signupbox" class="mainbox col-md-10">
                            <div class="form-horizontal">
                                <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                                    <input type="hidden" id="person_id" name="person_id" value="<?=$bus_dato[0]->person_id?>">
                                    <input type="hidden" id="id_file" name="id_file" value="<?=$bus_dato[0]->person_id?>">
                                    <div class="col-md-12">
                                        <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?></h2>   
                                        <h5>Actualice la informacion de <?=ucwords($module_id)?></h5>
                                        <hr/>
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>      
                                    
                                    <div class="col-md-12"> 
	                                        <div class="form-group row">
	                                          <label for="hab_area" class="col-xs-2 col-form-label">Tipo Documento</label>
	                                          <div class="col-xs-5">
	                                            <select class="form-control select2" name="tipo_doc" id="tipo_doc" style="width: 200px; display: inline;">
	                                                <option value="0"> ----- Seleccione ------ </option>
	                                                <?php if($bus_dato[0]->tipo_doc == 'PAS'){ $s_value = 'PAS'; $s_nombre = 'PASAPORTE'; } ?>
	                                                <?php if($bus_dato[0]->tipo_doc == 'DNI'){ $s_value = 'DNI'; $s_nombre = 'DNI'; } ?>
											  		<option value="<?=$s_value?>" selected><?=$s_nombre?></option>
	                                            </select> 
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label" style="padding-top: 1px">Modificado: </label>
	                                          <div class="col-xs-3">
	                                            <p class="form-control-static" style="display: inline;"><?=$user_creador_data?></p>
	                                          </div>
	                                        </div>                                       
	                                         
	                                        <div class="form-group row">
	                                        <div class="col-xs-6">
		                                        <div class="form-group row">
		                                          <label for="" class="col-xs-4 col-form-label">Nro. Documento</label>
		                                          <div class="col-xs-8">
		                                            <input class="form-control" name="nro_doc" id="nro_doc" type="text" value="<?=$bus_dato[0]->nro_doc?>" placeholder="Ingrese el Nro. Documento" readonly>
		                                          </div>
		                                        </div>
		                                        <div id="" class="form-group row">
		                                          <label for="" class="col-xs-4 col-form-label">Nombres</label>
		                                          <div class="col-xs-8">
		                                            <input class="form-control" name="first_name" id="first_name" type="text" value="<?=$bus_dato[0]->first_name?>" placeholder="Ingrese Nombres">
		                                          </div>
		                                        </div>
		                                        <div id="" class="form-group row">
		                                          <label for="" class="col-xs-4 col-form-label">Apellidos</label>
		                                          <div class="col-xs-8">
		                                            <input class="form-control" name="last_name" id="last_name" type="text" value="<?=$bus_dato[0]->last_name?>" placeholder="Ingrese Apellidos">
		                                          </div>
		                                        </div>
		                                    </div>
		                                    <div class="col-xs-6">
		                                    	 <img src="<?=base_url()?>public/images/users/empleados/<?=$bus_dato[0]->imagen?>"  class="img-thumbnail" width="140" height="" />
		                                    </div>
		                                    </div>

	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Telefono Fijo</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="phone_number" id="phone_number" type="text" value="<?=$bus_dato[0]->phone_number?>" placeholder="Ingrese el # Telefono Fijo">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Celular</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="celular" id="celular" type="text" value="<?=$bus_dato[0]->celular?>" placeholder="Ingrese # Celular">
	                                          </div>
	                                        </div>    
	                                           
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">E-mail</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="email" id="email" type="text" value="<?=$bus_dato[0]->email?>" placeholder="Ingrese el E-mail">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Dirección</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="address_1" id="address_1" type="text" value="<?=$bus_dato[0]->address_1?>" placeholder="Ingrese la Dirección">
	                                          </div>
	                                        </div>
	                                        
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Distrito</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="address_2" id="address_2" type="text" value="<?=$bus_dato[0]->address_2?>" placeholder="Ingrese el Distrito">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Ciudad</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="city" id="city" type="text" value="<?=$bus_dato[0]->city?>" placeholder="Ingrese la Ciudad">
	                                          </div>
	                                        </div>
	                                        
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Comentarios</label>
	                                          <div class="col-xs-9">
	                                            <input class="form-control" name="comments" id="comments" type="text" value="<?=$bus_dato[0]->comments?>" placeholder="Escriba un Comentario del Empleado">
	                                          </div>
	                                        </div>
	                                        <div class="form-group row">
		                                      <label for="" class="col-xs-2 col-form-label">Imagen / Foto</label>
		                                      <div class="col-xs-4">
		                                        <form action="javascript:void(0);" enctype="multipart/form-data" id="frmArchivo" method="POST">
		                                            <input type="file" class="filestyle" data-buttonText="Examinar.." id="archivo" name="archivo" value="">
		                                        </form>
		                                      </div>
		                                      <label for="" class="col-xs-2" style="padding-left: 0px; padding-top: 5px;">(600px * 600px)</label>
		                                      <!-- <label class="col-xs-2"><div><img src="<?=base_url()?>public/images/users/clientes/<?=$bus_dato[0]->imagen?>" width="75" height="35" /></div></label> -->
		                                    </div> 

	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Fecha Nace</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_nace" id="fecha_nace" type="text" value="<?=$bus_dato[0]->fecha_nace?>">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                          <label for="fecha_registro" class="col-xs-1 col-form-label">Registro</label>
	                                          <div class="col-xs-2">
	                                            <input class="form-control" name="fecha_registro" id="fecha_registro" type="text" value="<?=$bus_dato[0]->fecha_registro?>" readonly>
	                                          </div>
	                                          <label for="fecha_registro" class="col-xs-1 col-form-label">Modifica</label>
	                                          <div class="col-xs-2">
	                                            <input class="form-control" name="fecha_modifica" id="fecha_modifica" type="text" value="<?=$bus_dato[0]->fecha_modifica?>" readonly>
	                                          </div>
	                                        </div>

		                                 <div class="col-md-12">   
	                                        <hr/>
	                                        <div class="checkbox">
											    <label class="">
											      <input type="checkbox" id="chkverplanilla" name="chkverplanilla" value='S'>Información de Planilla del Empleado 
											    </label>
											  </div>
	                                        <!-- <h5>Información de Planilla del Empleado</h5> -->
	                                         <hr/>
	                                        <input type="hidden" name="id_emp" id="id_emp" value="<?=$bus_dato[0]->id?>">
		                                 </div>
	                                 	 <div id="div_planilla">

	                                        <div id="" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Banco</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="banco" id="banco" type="text" value="<?=$bus_dato[0]->banco?>" placeholder="Ingrese Banco">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label" style="padding-left: 0px;">Tipo_Cuenta</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="tipo_cuenta" id="tipo_cuenta" type="text" value="<?=$bus_dato[0]->tipo_cuenta?>" placeholder="Ingrese Tipo de Cuenta">
	                                          </div>
	                                        </div>

	                                        <div id="" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nro. Cuenta</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="nro_cuenta" id="nro_cuenta" type="text" value="<?=$bus_dato[0]->nro_cuenta?>" placeholder="Ingrese Nro. de Cuenta">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label" style="padding-left: 0px;">Interbancario</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="interbancario" id="interbancario" type="text" value="<?=$bus_dato[0]->interbancario?>" placeholder="Ingrese Codigo Interbancario">
	                                          </div>
	                                        </div>

	                                        <div class="form-group row">
	                                          <label for="hab_area" class="col-xs-2 col-form-label">Sistema Pensión</label>
	                                          <div class="col-xs-4">
	                                            <select class="form-control" name="sistema_pension" id="sistema_pension" style="width: 150px; display: inline;" onChange="">
	                                                <option value="0"> - Seleccione - </option>                     
											  		<option value="AFP" <?php if($bus_dato[0]->sistema_pension == 'AFP') echo 'selected';?>> AFP </option>
											  		<option value="ONP" <?php if($bus_dato[0]->sistema_pension == 'ONP') echo 'selected';?>> ONP </option>
	                                            </select> 
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label" style="padding-left: 0px;">Pago_Hora</label>
	                                          <div class="col-xs-1" style="width: 34px; padding: 5px 15px; margin-right: -5px;">S/.</div>
	                                          <div class="col-xs-2">
	                                            <input class="form-control" name="pago_horas" id="pago_horas" type="text" value="<?=$bus_dato[0]->pago_horas?>" placeholder="0.00">
	                                        </div>
	                                        </div>
	                                        
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Fecha Ingreso</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_contrato_in" id="fecha_contrato_in" type="text" value="<?=$bus_dato[0]->fecha_contrato_in?>">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                          <label for="" class="col-xs-2 col-form-label" style="text-align: right;">Salida</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_contrato_out" id="fecha_contrato_out" type="text" value="<?=$bus_dato[0]->fecha_contrato_out?>">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                        </div>

	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Fecha Carnet Ingreso</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_carnet_in" id="fecha_carnet_in" type="text" value="<?=$bus_dato[0]->fecha_carnet_in?>">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                          <label for="" class="col-xs-2 col-form-label" style="text-align: right;">Salida</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_carnet_out" id="fecha_carnet_out" type="text" value="<?=$bus_dato[0]->fecha_carnet_out?>">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                        </div>
	                                 </div>
	                                </div>

	                                 <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="txtmodo" id="txtmodo" value="modificar" /></td>
										<button type="button" id="btnMod" name="btnMod" class="btn btn-primary">Grabar</button>
						                <a href="<?=base_url().$module_id?>" id="hrefcancel" class="btn btn-default">Cancelar</a>
                                    </div>
                                </form>
                            </div>	
  						    </div>
  		     	 	</div>
        	<?php }
        		    else
        		    {?>
  			         <div class="tab-pane active" id="<?=$arr_accion[0]?>">
  			         		<div class="table-responsive" id="tabla_personal">
  						      <table id="datos_tabla" class="display text-label-lg" cellspacing="0" width="100%">
						        <thead>
						          <tr>
						            <th style="width: 54px;">ACCION</th>
						            <th>TIPO_DOC</th>
						            <th># DOC</th>
						            <th>NOMBRES</th>
						            <th>EMAIL</th>
			                        <th>T. FIJO</th>
			                        <th>CELULAR</th>
						            <th>DIRECCION</th>
						            <th>DISTRITO</th>
						          </tr>
						        </thead>
						        <tbody>
						          <?php foreach($lista as $i=>$lis): ?>
						            <tr>
						              <td>
						              	<?php if($lis->nro_doc === '12345678'):?>
						              		<a href="#" style="pointer-events: none; color: rgba(0,0,0,0.1);"  class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
  						              		<button class="btn  btn-default btn-sm" style="pointer-events: none; color: rgba(0,0,0,0.1);"><span class="glyphicon glyphicon-remove"></span></button>
  						              	<?php else: ?>
  						              		<a href="<?=$href_mod.$lis->person_id?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
  						              		<button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>('<?=$lis->person_id?>');" value="<?=$lis->person_id?>"><span class="glyphicon glyphicon-remove"></span></button>
  						              	<?php endif; ?>
						              </td>
						              <td><?=$lis->tipo_doc?></td>
						              <td><?=$lis->nro_doc?></td>
						              <td><?=$lis->last_name.' '.$lis->first_name?></td>
			                          <td><?=$lis->email?></td>
						              <td><?=$lis->phone_number?></td>
						              <td><?=$lis->celular?></td>
			                          <td><?=$lis->address_1?></td>
			                          <td><?=$lis->address_2?></td>
						              
						            </tr>
						          <?php endforeach;?>
						        </tbody>
						      </table>
  						  </div>
  			     	 </div>

               		<!-- NUEVO / INSERTAR -->
  			        <div class="tab-pane" id="<?=$arr_accion[1]?>">
                        <div id="signupbox" class="mainbox col-md-10">
                            <div class="form-horizontal">
                                <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
	                                    <div class="col-md-12">
	                                        <h2><span class="fa fa-edit fa-1x"></span> Agregar <?=ucwords($module_id)?></h2>   
	                                        <h5>Insertar informacion al <?=ucwords($module_id)?></h5>
	                                        <hr/>
	                                        <div class="pull-right">
	                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
	                                        </div> 
	                                    </div>
	                                    
	                                    <div class="col-md-12"> 
	                                        <div class="form-group row">
	                                          <label for="hab_area" class="col-xs-2 col-form-label">Tipo Documento</label>
	                                          <div class="col-xs-5">
	                                            <select class="form-control" name="tipo_doc" id="tipo_doc" style="width: 200px; display: inline;" onChange="">
	                                                <option value="0"> ----- Seleccione ------ </option>                                              
											  		<option value="PAS">PASAPORTE</option>
											  		<option value="DNI">DNI</option>
	                                            </select> 
	                                          </div>
	                                        </div>                                       
	                                         
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nro. Documento</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" onblur="verificarCampo(this.value);" name="nro_doc" id="nro_doc" type="text" value="" placeholder="Ingrese el Nro. Documento">
	                                            
	                                          </div>
	                                          <div class="col-xs-5" style="padding-left: 0px;"><div class="valida_ajax msg-red"></div></div>
	                                        </div>

	                                        <div id="" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nombres</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="first_name" id="first_name" type="text" value="" placeholder="Ingrese Nombres">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Apellidos</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="last_name" id="last_name" type="text" value="" placeholder="Ingrese Apellidos">
	                                          </div>
	                                        </div>

	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Telefono Fijo</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="phone_number" id="phone_number" type="text" value="" placeholder="Ingrese el # Telefono Fijo">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Celular</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="celular" id="celular" type="text" value="" placeholder="Ingrese # Celular">
	                                          </div>
	                                        </div>    
	                                           
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">E-mail</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="email" id="email" type="text" value="" placeholder="Ingrese el E-mail">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Dirección</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="address_1" id="address_1" type="text" value="" placeholder="Ingrese la Dirección">
	                                          </div>
	                                        </div>
	                                        
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Distrito</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="address_2" id="address_2" type="text" value="" placeholder="Ingrese el Distrito">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Ciudad</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="city" id="city" type="text" value="" placeholder="Ingrese la Ciudad">
	                                          </div>
	                                        </div>
	                                        <!--
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Estado</label>
	                                          <div class="col-xs-3">
	                                            <input class="form-control" name="state" id="state" type="text" value="" placeholder="Ingrese el Estado">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Codido_Zip</label>
	                                          <div class="col-xs-2">
	                                            <input class="form-control" name="zip" id="zip" type="text" value="" placeholder="Ingrese el Zip">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Pais</label>
	                                          <div class="col-xs-2" style="padding-left: 0px;">
	                                            <input class="form-control" name="country" id="country" type="text" value="" placeholder="Ingrese el Pais">
	                                          </div>
	                                        </div>
	                                        -->
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Comentarios</label>
	                                          <div class="col-xs-9">
	                                            <input class="form-control" name="comments" id="comments" type="text" value="" placeholder="Escriba un Comentario del Empleado">
	                                          </div>
	                                        </div>
	                                        <div class="form-group row">
		                                      <label for="" class="col-xs-2 col-form-label">Imagen / Foto</label>
		                                      <div class="col-xs-4">
		                                        <form action="javascript:void(0);" enctype="multipart/form-data" id="frmArchivo" method="">
		                                            <input type="file" class="filestyle" data-buttonText="Examinar.." id="archivo" name="archivo" value="">
		                                        </form> 
		                                      </div>
		                                      <label for="" class="col-xs-2" style="padding-left: 0px; padding-top: 5px;">(600px * 600px)</label>
		                                    </div> 

	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Fecha Nace</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_nace" id="fecha_nace" type="text" value="">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                          <label for="fecha_registro" class="col-xs-1 col-form-label">Registro</label>
	                                          <div class="col-xs-2">
	                                            <input class="form-control" name="fecha_registro" id="fecha_registro" type="text" value="<?=$fecha_actual?>" readonly>
	                                          </div>
	                                        </div>
   
	                                    

	                                    <div class="col-md-12">   
	                                        <hr/>
	                                          <div class="checkbox">
											    <label class="">
											      <input type="checkbox" id="chkverplanilla" name="chkverplanilla" value='S'>Información de Planilla del Empleado 
											    </label>
											  </div>
	                                        <!-- <h5>Información de Planilla del Empleado</h5> -->
	                                        <hr/> 
	                                    </div>
	                                    <div id="div_planilla">
	                                        <div id="" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Banco</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="banco" id="banco" type="text" value="" placeholder="Ingrese Banco">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label" style="padding-left: 0px;">Tipo_Cuenta</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="tipo_cuenta" id="tipo_cuenta" type="text" value="" placeholder="Ingrese Tipo de Cuenta">
	                                          </div>
	                                        </div>

	                                        <div id="" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nro. Cuenta</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="nro_cuenta" id="nro_cuenta" type="text" value="" placeholder="Ingrese Nro. de Cuenta">
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label" style="padding-left: 0px;">Interbancario</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="interbancario" id="interbancario" type="text" value="" placeholder="Ingrese Codigo Interbancario">
	                                          </div>
	                                        </div>

	                                        <div class="form-group row">
	                                          <label for="hab_area" class="col-xs-2 col-form-label">Sistema Pensión</label>
	                                          <div class="col-xs-4">
	                                            <select class="form-control" name="sistema_pension" id="sistema_pension" style="width: 150px; display: inline;" onChange="">
	                                                <option value="0"> - Seleccione - </option>                     
											  		<option value="AFP"> AFP </option>
											  		<option value="ONP"> ONP </option>
	                                            </select> 
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label" style="padding-left: 0px;">Pago_Hora</label>
	                                          <div class="col-xs-1" style="width: 34px; padding: 5px 15px; margin-right: -5px;">S/.</div>
	                                          <div class="col-xs-2">
	                                            <input class="form-control" name="pago_horas" id="pago_horas" type="text" value="0.00" placeholder="0.00">
	                                          </div>
	                                        </div>
	                                        
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Fecha Ingreso</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_contrato_in" id="fecha_contrato_in" type="text" value="">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                          <label for="" class="col-xs-2 col-form-label" style="text-align: right;">Salida</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_contrato_out" id="fecha_contrato_out" type="text" value="">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                        </div>

	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Fecha Carnet Ingreso</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_carnet_in" id="fecha_carnet_in" type="text" value="">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                          <label for="" class="col-xs-2 col-form-label" style="text-align: right;">Salida</label>
	                                          <div class="col-xs-3">
	                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
		                                            <input class="form-control input_date" name="fecha_carnet_out" id="fecha_carnet_out" type="text" value="">
		                                          	<span class="input-group-addon">
										                <span class="glyphicon glyphicon-calendar"></span>
										            </span>
									            </div>
	                                          </div>
	                                        </div>
	                                    </div>
	                                    </div>

	                                    <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>
	                                    <div class="col-md-12">
	                                        <input type="hidden" name="txtmodo" id="txtmodo" value="insertar" /></td>
						  	                <button type="button" id="btnadd" name="btnadd" class="btn btn-primary">Grabar</button>
		                  	          		<a href="<?=base_url().$module_id?>" id="hrefcancel" class="btn btn-default">Cancelar</a>
	                                    </div>
                                	</form>
    						    </div>
    			           </div>	
    			     	</div>

			     	 <?php if(@$arr_accion[2])
  			     	 	   { ?>
  				     	 	  <div class="tab-pane" id="<?=$arr_accion[2]?>">&nbsp;</div>
				     <?php } ?>

				     <?php if(@$arr_accion[3])
  			     	 	   { ?>
  				     	 	  <div class="tab-pane" id="<?=$arr_accion[3]?>">&nbsp;</div>
				     <?php } ?>
			<?php } ?>

        </div>
      </div>
      <!-- /tabs -->
    </div>

</div>

<?php $this->load->view("partial/footer"); ?>