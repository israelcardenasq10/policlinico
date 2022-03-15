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
	                                            <select class="form-control" name="tipo_doc" id="tipo_doc" style="width: 200px; display: inline;">
	                                                <option value="0"> ----- Seleccione ------ </option>
	                                                <?php if($bus_dato[0]->tipo_doc == 'PAS'){ $s_value = 'PAS'; $s_nombre = 'PASAPORTE'; } ?>
	                                                <?php if($bus_dato[0]->tipo_doc == 'DNI'){ $s_value = 'DNI'; $s_nombre = 'DNI'; } ?>
	                                                <?php if($bus_dato[0]->tipo_doc == 'RUC'){ $s_value = 'RUC'; $s_nombre = 'RUC'; } ?>
											  		<option value="<?=$s_value?>" selected><?=$s_nombre?></option>
	                                            </select> 
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label" style="padding-top: 1px">Modificado: </label>
	                                          <div class="col-xs-3">
	                                            <p class="form-control-static" style="display: inline;"><?=$user_creador_data?></p>
	                                          </div>
	                                        </div>                                       
	                                         
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nro. Documento</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="nro_doc" id="nro_doc" type="text" value="<?=$bus_dato[0]->nro_doc?>" placeholder="Ingrese el Nro. Documento" readonly>
	                                          </div>
	                                        </div>
	                                        <?php if($bus_dato[0]->tipo_doc == 'RUC') :?>
		                                        <div id="" class="form-group row">
		                                          <label for="" class="col-xs-2 col-form-label">Razon Social</label>
		                                          <div class="col-xs-4">
		                                            <input class="form-control" style="text-transform: uppercase;" name="razon_social" id="razon_social" type="text" value="<?=$bus_dato[0]->razon_social?>" placeholder="Ingrese la Razon Social">
		                                          </div>
		                                        </div>
	                                        <?php else: ?> 
	                                        <div id="" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nombres y Apellidos</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="first_name" id="first_name" type="text" value="<?=$bus_dato[0]->first_name?>" placeholder="Ingrese Nombres">
	                                          </div>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="last_name" id="last_name" type="text" value="<?=$bus_dato[0]->last_name?>" placeholder="Ingrese Apellidos">
	                                          </div>
	                                        </div>
	                                    	<?php endif; ?>

	                                    	<div id="" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nombre Corto</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" style="text-transform: uppercase;" name="nombre_corto" id="nombre_corto" type="text" value="<?=$bus_dato[0]->nombre_corto?>" placeholder="Ingrese el Nombre Corto">
	                                          </div>
	                                        </div>
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Tipo Proveedor</label>
	                                          <div class="col-xs-4">
	                                            <select class="form-control" name="tipo_prov" id="tipo_prov" style="width: 200px; display: inline;">
	                                                <option value="0"> ----- Seleccione ------ </option>
	                                                <?php if($bus_dato[0]->tipo_prov == 'PV'){ $s_value = 'PV'; $s_nombre = 'PUNTO DE VENTA'; } ?>
	                                                <?php if($bus_dato[0]->tipo_prov == 'OT'){ $s_value = 'OT'; $s_nombre = 'OTROS'; } ?>
											  		<option value="<?=$s_value?>" selected><?=$s_nombre?></option>
	                                            </select> 
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Categoria Servicio</label>
	                                          <div class="col-xs-4">
	                                            <select class="form-control" name="id_cate_serv" id="id_cate_serv" style="">
	                                                <option value="0"> ----------------- Seleccione ----------------- </option>
	                                                <?php foreach($lista_cat_servicios as $i=>$lis): 
	                                                		if($bus_dato[0]->id_cate_serv == $lis->id_cate_serv): ?>
                                                      		<option value="<?=$lis->id_cate_serv?>" selected><?=$lis->nombre?></option>
                                                      <?php //else:?>
                                                      		<!-- <option value="<?=$lis->id_cate_serv?>"><?=$lis->nombre?></option> -->
                                                    <?php 	endif;
                                                    	  endforeach;?>
	                                            </select> 
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
	                                            <input class="form-control" name="comments" id="comments" type="text" value="<?=$bus_dato[0]->comments?>" placeholder="Escriba un Comentario del Proveedor">
	                                          </div>
	                                        </div>
	                                        <div class="form-group row">
		                                      <label for="" class="col-xs-2 col-form-label">Imagen / Foto</label>
		                                      <div class="col-xs-4">
		                                        <form action="javascript:void(0);" enctype="multipart/form-data" id="frmArchivo" method="">
		                                            <input type="file" class="filestyle" data-buttonText="Examinar.." id="archivo" name="archivo" value="">
		                                        </form> 
		                                      </div>
		                                      <label class="col-xs-2"><div><img src="<?=base_url()?>public/images/users/proveedores/<?=$bus_dato[0]->imagen?>" width="75" height="35" /></div></label>
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

	                                        <div class="col-md-12" style="font-style: italic;">   
	                                        	<hr/>
											      Lista de Servicios del Proveedor <?=$bus_dato[0]->razon_social?>
	                                         	<hr/>
		                                 	</div>

		                                 	<div id="" class="form-group row">
												<label for="" class="col-sm-2 col-form-label">Servicios Proveedor</label>
												<div class="col-sm-4">
												  	<select name="cbolista_principal" id="cbolista_principal" multiple class="form-control" style="height: 170px;">
														<?php foreach($lis_servicios as $lis): ?>
													      <option value="<?=$lis->id_serv_prov?>"><?=$lis->nombres?></option>
													    <?php endforeach;?>
													</select>
												</div>
												<label for="" class="col-sm-1 control-label center">
													<button type="button" style="font-size: 10px; margin-bottom: 3px; margin-top: 50px;" id="btnagregar_option" class="btn btn-success"/>AGREGAR</button>
													<button type="button" style="font-size: 10px; margin-bottom: 3px;" id="btnquitar_option" class="btn btn-danger"/>&nbsp;&nbsp;QUITAR&nbsp;</button>
												</label>
												<div class="col-sm-4">
												  	<select name="cbolista_secundario" id="cbolista_secundario" multiple class="form-control" style="height: 170px;">
														<?php foreach($lis_servicios_prov as $lis):  ?>
													      		<option value="<?=$lis->id_serv_prov?>"><?=$lis->nombres?></option>
													    <?php endforeach;?>
													</select>
												</div>
											</div>

											<div class="col-md-12" style="font-style: italic; margin-top: -10px;">   
	                                        	<hr/>
											      Documento predeterminado para COMPRAS:
	                                         	<hr/>
		                                 	</div>
		                                 	<div id="" class="form-group row">
												<label for="" class="col-xs-2 col-form-label">Seleccione Opci&oacute;n</label>
												<div class="col-xs-9">
												<?php foreach($lista_documentos as $i=>$lis): 
														if($lis->tipo_doc == $bus_dato[0]->id_pref_1) $checked = 'checked';
														else  $checked = ''; ?>
														<label class="radio-inline">
														  <input type="radio" name="rbdocumentos" id="rbdocumento<?=$i?>" value="<?=$lis->tipo_doc?>" <?=$checked?>><?=$lis->descripcion?>&nbsp;&nbsp;
														</label>
												<?php endforeach;?>
												</div>
											</div>
											<div class="col-md-12" style="height: 20px;"></div> 

										<!-- -->
	                                 </div>

	                                 <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>

                                    <div class="col-md-12">
                                        <input type="hidden" name="txtmodo" id="txtmodo" value="modificar" />
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
						            <th>RUC</th>
						            <th>RAZON SOCIAL</th>
						            <th>EMAIL</th>
			                        <th>T. FIJO</th>
			                        <th>CELULAR</th>
						            <!-- <th>DIRECCION</th> -->
						            <th>TIPO</th>
						          </tr>
						        </thead>
						        <tbody>
						          <?php foreach($lista as $i=>$lis): ?>
						            <tr>
						              <td>
						              	<a href="<?=$href_mod.$lis->person_id?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
  						              	<button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>('<?=$lis->person_id?>');" value="<?=$lis->person_id?>"><span class="glyphicon glyphicon-remove"></span></button>
						              </td>
						              <td><?=$lis->tipo_doc?></td>
						              <td><?=$lis->nro_doc?></td>
						              <td><?=$lis->razon_social?></td>
			                          <td><?=$lis->email?></td>
						              <td><?=$lis->phone_number?></td>
						              <td><?=$lis->celular?></td>
			                          <!-- <td><?=$lis->address_1?></td> -->
			                          <td><?=$lis->tipo_prov?></td>
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
	                                            <select class="form-control" name="tipo_doc" id="tipo_doc" style="width: 200px; display: inline;" onChange="agregarCampos('<?=$module_id?>', this.value);">
	                                                <option value="0"> ----- Seleccione ------ </option>                                              
											  		<option value="PAS">PASAPORTE</option>
											  		<option value="DNI">DNI</option>
											  		<option value="RUC">RUC</option>
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

	                                        <div id="div_cliente_razonsocial" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Razon Social</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" style="text-transform: uppercase;" name="razon_social" id="razon_social" type="text" value="" placeholder="Ingrese la Razon Social">
	                                          </div>
	                                        </div>
	                                        <div id="div_cliente_nombres" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nombres y Apellidos</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="first_name" id="first_name" type="text" value="" placeholder="Ingrese Nombres">
	                                          </div>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="last_name" id="last_name" type="text" value="" placeholder="Ingrese Apellidos">
	                                          </div>
	                                        </div>

	                                        <div id="" class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Nombre Corto</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" style="text-transform: uppercase;" name="nombre_corto" id="nombre_corto" type="text" value="" placeholder="Ingrese el Nombre Corto">
	                                          </div>
	                                        </div>
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Tipo Proveedor</label>
	                                          <div class="col-xs-4">
	                                            <select class="form-control" name="tipo_prov" id="tipo_prov" style="width: 200px; display: inline;">
	                                                <option value="0"> ----- Seleccione ------ </option>
											  		<option value="PV">PUNTO DE VENTA</option>
											  		<option value="OT">OTROS</option>
	                                            </select> 
	                                          </div>
	                                          <label for="" class="col-xs-1 col-form-label">Categoria Servicio</label>
	                                          <div class="col-xs-4">
	                                            <select class="form-control" name="id_cate_serv" id="id_cate_serv" style="">
	                                                <option value="0"> ----------------- Seleccione ----------------- </option>
	                                                <?php foreach($lista_cat_servicios as $i=>$lis): ?>
                                                      		<option value="<?=$lis->id_cate_serv?>"><?=$lis->nombre?></option>
                                                    <?php endforeach;?>
	                                            </select> 
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
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="state" id="state" type="text" value="" placeholder="Ingrese el Estado">
	                                          </div>
	                                        </div>
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Codido Zip</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="zip" id="zip" type="text" value="" placeholder="Ingrese el Zip">
	                                          </div>
	                                        </div>
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Pais</label>
	                                          <div class="col-xs-4">
	                                            <input class="form-control" name="country" id="country" type="text" value="" placeholder="Ingrese el Pais">
	                                          </div>
	                                        </div>
	                                    	-->
	                                        <div class="form-group row">
	                                          <label for="" class="col-xs-2 col-form-label">Comentarios</label>
	                                          <div class="col-xs-9">
	                                            <input class="form-control" name="comments" id="comments" type="text" value="" placeholder="Escriba un Comentario del Proveedor">
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

	                                        <div class="col-md-12" style="font-style: italic;">   
	                                        	<hr/>
											      Documento predeterminado para COMPRAS:
	                                         	<hr/>
		                                 	</div>
		                                 	<div id="" class="form-group row">
												<label for="" class="col-xs-2 col-form-label">Seleccione Opci&oacute;n</label>
												<div class="col-xs-9">
												<?php foreach($lista_documentos as $i=>$lis): 
														if($lis->tipo_doc == 'FAC') $checked = 'checked';
														else  $checked = ''; ?>
														<label class="radio-inline">
														  <input type="radio" name="rbdocumentos" id="rbdocumento<?=$i?>" value="<?=$lis->tipo_doc?>" <?=$checked?>><?=$lis->descripcion?>&nbsp;&nbsp;
														</label>
												<?php endforeach;?>
												</div>
											</div>
											<div class="col-md-12" style="height: 20px;"></div> 
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