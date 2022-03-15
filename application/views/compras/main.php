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
                                    <input type="hidden" id="id_compra" name="id_compra" value="<?=$bus_dato[0]->id_compra?>">
                                    <div class="col-md-12">
                                        <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?></h2>   
                                        <h5>Actualice la informacion de <?=ucwords($module_id)?></h5>
                                        <hr/>
                                        <div class="col-md-12" style="padding-right: 0px;">
                                        	<label for="" class="col-xs-1 col-form-label" style="padding: 0px;">Modificado </label>
                    											<div class="col-xs-3" style="padding-bottom: 3px;">
                    												<p class="form-control-static" style="display: inline;"><?=$user_creador_data?></p>
                    											</div>
                                            <a class="btn btn-default pull-right" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>      
                                    <div class="col-md-12" style="height: 10px;"></div> 

                                    <div class="col-md-12">
                                        <div class="col-md-8" >
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4" for="">Nro <?=$bus_dato[0]->tipo_doc_prov?></label>
                                                        <div class="col-md-8">
                                                        	<input type="hidden" name="prov_id" id="prov_id" value="<?=$bus_dato[0]->prov_id?>">
                                                        	<!-- <input type="hidden" name="igv_global" id="igv_global" value="<?=$g_igv?>"> -->
                                                            <input type="text" class="form-control" name="nro_ruc" id="nro_ruc" placeholder="Ingrese Nro. Ruc" value="<?=$bus_dato[0]->doc_prov?>" maxlength="11" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3" for="" style="padding: 2px;">Razon Social</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="razon_social" id="razon_social" placeholder="Razon Social" value="<?=$bus_dato[0]->razon_social?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4" for="">Condici&oacute;n</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="condicion" id="condicion" value="<?=$bus_dato[0]->condicion?>" placeholder="Condicion" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3" for="" style="padding: 2px;">Fecha Venc.</label>
                                                        <div class="col-md-6">
                                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
                		                                            <input class="form-control input_date" name="fecha_vence" id="fecha_vence" type="text" value="<?=$bus_dato[0]->fecha_vence?>" placeholder="Fecha de Vencimiento" disabled>
                		                                          	<span class="input-group-addon icono_fecha" style="pointer-events: none; color: rgba(0,0,0,0.1);">
                                										                <span class="glyphicon glyphicon-calendar"></span>
                                										            </span>
                							                               </div>                                                                  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group row">
                                                    	<label class="col-form-label col-md-4" for="">Detracci&oacute;n</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="detraccion" id="detraccion" value="<?=$bus_dato[0]->detraccion?>" placeholder="Detraccion">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group row">
                                                    	<?php if($bus_dato[0]->id_oc != ''): ?>
                                                            <label class="col-form-label col-md-3" id="label_oc" for="" style="padding: 2px;"># O/C.</label>
                                                            <div class="col-md-7" id="div_id_oc" style="">
                                                                <?=@$num_oc?>
                                                            </div>
                                                      <?php endif; ?>
                                                      <!-- 
                                                        <div class="col-md-6 checkbox">
                                                            <label><input type="checkbox" class="chk_activo" name="chkactivo" id="chkactivo" value="S"/>Es un Activo?</label>
                                                        </div>
                                                    	-->
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" style="height: 3px;"></div>
                                            <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 0px;"></div>
                                            
                                            <div class="row" class="">
        	                                    <div class="col-md-12 text-right">
        	                                        <!-- <input type="hidden" name="txtmodo" id="txtmodo" value="modificar" /> -->
  	                                        <?php if($bus_dato[0]->estado_compra !== 'Pendiente')
    	                                        		   $btn_disabled_estado = 'disabled';
    	                                        	  else
    	                                        	  	$btn_disabled_estado = ''; ?>
    						  	                               <button type="button" id="btnMod" name="btnMod" class="btn btn-primary" <?=$btn_disabled_estado?>>Grabar</button>
                                                  <!-- <button type="button" id="btnadddeta" name="btnadddeta" class="btn btn-info" data-toggle="modal" href="#myModal">Nuevo Detalle</button>  -->
    		                  	          		         <a href="<?=base_url().$module_id?>" id="hrefcancel" class="btn btn-default">Cancelar</a>
        	                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="background-color:#efefef">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
	                                                    <select class="form-control" name="tipo_doc" id="tipo_doc" disabled>
		                                                	<option value="0"> ------------- Seleccione Documento ------------- </option>
		                                                	<?php foreach($lista_documentos as $i=>$lis): 
		                                                			if($lis->tipo_doc == $bus_dato[0]->tipo_doc): ?>
	                                                      			<option value="<?=$lis->tipo_doc?>" selected><?=$lis->descripcion?></option>
	                                                    	<?php 		break;
	                                                    			endif;
	                                                    		  endforeach;?>
		                                            	</select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                	<?php $nro_doc = explode('-', $bus_dato[0]->nro_doc); ?>
                                                                    <input type="text" class="form-control" id="doc_serie" name="doc_serie" value="<?=$nro_doc[0]?>" placeholder="Serie" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" id="doc_numero" name="doc_numero" value="<?=$nro_doc[1]?>" placeholder="Numero" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <select id="moneda" name="moneda" class="form-control" disabled>
                                                                    	<?php foreach($lista_monedas as $i=>$lis): 
    		                                                                    	if($lis->moneda == $bus_dato[0]->moneda): ?>
            			                                                      				<option value="<?=$lis->moneda?>"><?=$lis->moneda?></option>
            			                                                    		<?php  break;
            			                                                    				endif;
    				                                                      		      endforeach;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
                    		                                            <input class="form-control input_date" name="fecha_compra" id="fecha_compra" style="font-size: 13px;" type="text" value="<?=$bus_dato[0]->fecha_compra?>" placeholder="Fecha" disabled>
                    		                                          	<span class="input-group-addon icono_fecha" style="pointer-events: none; color: rgba(0,0,0,0.1);">
                                    										                <span class="glyphicon glyphicon-calendar"></span>
                                    										            </span>
                   									                                </div>                                                                        
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" name="tipo_cambio" id="tipo_cambio" value="<?=$bus_dato[0]->tc?>" placeholder="Tc">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                	<?php
                                                                		if($bus_dato[0]->estado_compra !== 'Pendiente')
                                                                			$disabled_estado = 'disabled';
                                                                		else
                                                                			$disabled_estado = '';
                                                                	?>
                                                                    <select class="form-control" name="estado_compra" id="estado_compra" <?=$disabled_estado?>>
						                                                          <option value="0">--- Estado ---</option>                                              
                                																  		<option value="Pendiente" <?php if($bus_dato[0]->estado_compra == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                                																  		<option value="Cancelado" <?php if($bus_dato[0]->estado_compra == 'Cancelado') echo 'selected'; ?>>Cancelado</option>
                                																  		<option value="Anulado" <?php if($bus_dato[0]->estado_compra == 'Anulado') echo 'selected'; ?>>Anulado</option>
                                						                        </select> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                  <!-- DETALLE DE COMPRAS -->
	                            	  <div id="lista_deta" class="col-md-12" style="border-top: 1px solid #CCC; margin-top: 15px;">
    	                            		<div class="" style="margin-top: 15px; font-style: italic; margin-bottom: 10px; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">   
              										      <h5>Detalles de la Compra:</h5>
              								     	  </div>

                  										<!-- <div class="table-responsive" id="tabla_personal2">
                  										  <table id="datos_detalle" class="display" cellspacing="0" width="100%"> -->
                                      <div class="table-responsive" id="tabla_personal2">
                                        <table id="datos_tabla_ajax" class="table table-striped" cellspacing="0" width="100%">
                  										    <thead>
                  										      <tr>
                  										        <th style="width: 9%">ACCION</th>
                  									            <th>SERVICIO</th>
                                                <th>CANTIDAD</th>
                                                <th>UNIDAD</th>
                                                <th>PRECIO UNIT.</th>
                                                <th>INAFECTO</th>
                                                <th>IGV</th>
                                                <th>TOTAL</th>
                  										      </tr>
                  										    </thead>
                  										    <tbody>
                  										    <?php  $total_deta = 0;
                  										      		foreach($lista_deta as $i=>$lis): ?>
                  										              <tr id="service<?=$lis->id_compra.'-'.$lis->correlativo?>">
                  										                <td>
                  										                  	<!-- <button class="btn btn-success btn-sm" style="" onclick="ver(this.id, 'ver');" id="<?=$lis->id_serv_prov?>"><span class="glyphicon glyphicon-pencil"></span></button> -->
                  										                  	<button class="btn btn-default btn-sm" style="pointer-events: none; color: rgba(0,0,0,0.1);" onclick="" id="<?=$lis->id_compra.'-'.$lis->correlativo?>"><span class="glyphicon glyphicon-remove"></span></button>
                  										                </td>
                  										                <td><?=$lis->servicio?></td>
                          														<td><?=$lis->cantidad?></td>
                          														<td><?=$lis->unidad?></td>
                          														<td><?=$lis->precio?></td>
                          														<td><?=$lis->inafecto?></td>
                          														<td><?=$lis->igv?></td>
                          														<td style="text-align: right;"><?=$lis->total?></td>
                  										              </tr>
                  										      <?php endforeach;?>
                  										    </tbody>
                  										  </table>
                  										</div>

              										    <!-- TOTALES -->
              								        <div class="col-md-12" style="margin-top: 0px; padding-right: 0px;">  
              								            <div class="col-md-8" >&nbsp;</div>
              								            <div class="col-md-4" style="padding: 0px;">
              								                    <table class="table table-striped" style="text-align: left;">
              								                        <tbody>
              								                            <tr>
              								                            <td>AFECTO</td>
              								                            <th style="text-align: right;" id="c_igv"><?=number_format($bus_dato[0]->afecto, 2)?></th>
              								                            </tr>

              								                            <tr>
              								                            <td>IGV</td>
              								                            <th style="text-align: right;" id="c_igv"><?=number_format($bus_dato[0]->igv, 2)?></th>
              								                            </tr>

              								                            <tr>
              								                            <td>INAFECTO</td>
              								                            <th style="text-align: right;" id="c_igv"><?=number_format($bus_dato[0]->inafecto, 2)?></th>
              								                            </tr>

              								                            <tr>
              								                            <td>TOTAL</td>
              								                            <th style="text-align: right;" id="c_subtotal"><?=$bus_dato[0]->moneda.'. '.number_format($bus_dato[0]->total, 2)?></th>
              								                            </tr>                                                                                        
              								                        </tbody>
              								                    </table>
                  								            </div> 
                  								      </div>
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
    						      <table id="datos_tabla_compra" class="display text-label-lg" cellspacing="0" width="100%">
  						        <thead>
  						          <tr>
  						            <th style="width: 30px;">ACCION</th>
                          <th>F. COMPRA</th>
                          <th>RUC</th>
                          <th>RAZON SOCIAL</th>
                          <th>DOC</th>
                          <th>NUMERO</th>
                          <!-- <th>ESTADO</th> -->
                          <th>MN</th>
                          <th>TOTAL</th> 
                          <th>AFECTO</th>
                          <th>IGV</th>
                          <th>INAFECTO</th>
                          <th>TC</th>
                          <!-- 
                          <th>SOLES AFEC.</th>
                          <th>SOLES INAF.</th>
                          -->
                          <th>REGISTRO</th>
  						          </tr>
  						        </thead>
  						        <tbody>
  						          <?php foreach($lista as $i=>$lis): 
  						          			if($lis->estado_compra == 'Pendiente')
  						          				$style = 'color: red;';
  						          			else if($lis->estado_compra == 'Anulado')
  						          				$style = 'color: #666; text-decoration: line-through;';
  						          			else 
  						          				$style = '';
  						          ?>
  							            <tr style="<?=$style?>">
  							              <td>
  	  						              	<a href="<?=$href_mod.$lis->id_compra?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
  	  						              	<!-- <button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>('<?=$lis->id_compra?>');" value="<?=$lis->id_compra?>"><span class="glyphicon glyphicon-remove"></span></button> -->
  							              </td>
                              <td><?=$lis->fecha_compra?></td>
                              <td><?=$lis->doc_prov?></td>
                              <td><?=$lis->razon_social?></td>
                              <td><?=$lis->tipo_doc?></td>
                              <td><?=$lis->nro_doc?></td>
                              <!-- <td><?=$lis->estado_compra?></td> -->
                              <td><?=$lis->moneda?></td>
                              <td><?=$lis->total?></td>
                              <td><?=$lis->afecto?></td>
                              <td><?=$lis->igv?></td>
                              <td><?=$lis->inafecto?></td>
                              <td><?=$lis->tc?></td>
                              <!-- 
                              <td><?=$lis->soles_afec?></td>
                              <td><?=$lis->soles_inaf?></td>
                              -->
                              <td><?=$lis->fecha_registro?></td>
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
                                        <h5>Insertar informacion a <?=ucwords($module_id)?></h5>
                                        <hr/>
                                        <div class="pull-left">
                                            <label class="col-form-label col-md-4" for="" style="width: 106px;">Doc Prov.</label>
                                            <select class="form-control" name="cbotipo_doc" id="cbotipo_doc" style="width: 150px; display: inline;"> <!-- onChange="agregarCampos('<?=$module_id?>', this.value);" -->
                                                  <option value="0">&nbsp; -- Seleccione -- </option>
                                                  <option value="DNI">DNI</option>
                                                  <option value="RUC">RUC</option>
                                            </select>
                                        </div>
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>
                                    <div class="col-md-12" style="height: 10px;"></div> 
                                    
                                    <!-- CUERPO  -->
                                    <div class="col-md-12">
                                        <div class="col-md-8" >
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4" for="">Nro Ruc</label>
                                                        <div class="col-md-8">
                                                        	<input type="hidden" name="prov_id" id="prov_id" value="">
                                                        	<input type="hidden" name="igv_global" id="igv_global" value="<?=$g_igv?>">
                                                            <input type="text" class="form-control" name="nro_ruc" id="nro_ruc" placeholder="Ingrese Nro. Ruc" maxlength="11" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3" for="" style="padding: 2px;">Razon Social</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="razon_social" id="razon_social" placeholder="Razon Social" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4" for="">Condici&oacute;n</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="condicion" id="condicion" value="Contado" placeholder="Condicion">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3" for="" style="padding: 2px;">Fecha Venc.</label>
                                                        <div class="col-md-6">
                                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
                		                                            <input class="form-control input_date" name="fecha_vence" id="fecha_vence" type="text" value="" placeholder="Fecha de Vencimiento">
                		                                          	<span class="input-group-addon icono_fecha">
                              										                <span class="glyphicon glyphicon-calendar"></span>
                              										            </span>
              							                                </div>                                                                  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group row">
                                                    	<label class="col-form-label col-md-4" for="">Detracci&oacute;n</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="detraccion" id="detraccion" value="" placeholder="Detraccion">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group row">
                                                      <label class="col-form-label col-md-3" id="label_oc" for="" style="padding: 2px; display: none;"># O/C.</label>
                                                      <div class="col-md-7" id="div_id_oc" style="display: none;">
                                                          Cargando O/C Asociados..
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" style="height: 3px;"></div>
                                            <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 0px;"></div>
                                            
                                            <div class="row" class="">
        	                                    <div class="col-md-12 text-right">
                                                <input type="hidden" name="txtmodo" id="txtmodo" value="insertar" /></td>
                                                <button type="button" id="btnadd" name="btnadd" class="btn btn-primary">Grabar</button>
                                                <button type="button" id="btnadddeta" name="btnadddeta" class="btn btn-info" data-toggle="modal" href="#myModal">Nuevo Detalle</button>
                                                <a href="<?=base_url().$module_id?>" id="hrefcancel" class="btn btn-default">Cancelar</a>
        	                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="background-color:#efefef">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
	                                                    <select class="form-control" name="tipo_doc" id="tipo_doc">
		                                                	<option value="0"> ------------- Seleccione Documento ------------- </option>
		                                                	 <?php foreach($lista_documentos as $i=>$lis): ?>
	                                                      		 <option value="<?=$lis->tipo_doc?>"><?=$lis->descripcion?></option>
	                                                     <?php endforeach;?>
		                                            	     </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" id="doc_serie" name="doc_serie" placeholder="Serie">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" id="doc_numero" name="doc_numero" placeholder="Numero">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <select id="moneda" name="moneda" class="form-control">
                                                                    	<?php foreach($lista_monedas as $i=>$lis): ?>
				                                                      		      <option value="<?=$lis->moneda?>"><?=$lis->moneda?></option>
				                                                    	        <?php endforeach;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
                    		                                            <input class="form-control input_date" name="fecha_compra" id="fecha_compra" style="font-size: 13px;" type="text" value="" placeholder="Fecha">
                    		                                          	<span class="input-group-addon icono_fecha">
                                    										                <span class="glyphicon glyphicon-calendar"></span>
                                    										            </span>
                   									                                </div>                                                                        
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" name="tipo_cambio" id="tipo_cambio" value="" placeholder="Tc" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <select class="form-control" name="estado_compra" id="estado_compra">
          						                                                <option value="0">--- Estado ---</option>                                              
                                																  		<option value="Pendiente">Pendiente</option>
                                																  		<option value="Cancelado">Cancelado</option>
                                																  		<option value="Anulado">Anulado</option>
						                                                        </select> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FIN DE CUERPO -->
                                   </form>
                                </div>
                            	

                                <!-- MODAL DETALLES DE LA COMPRA -->
                                <div id="myModal" class="modal fade" role="dialog">
                  								  <div class="modal-dialog">
                  								    <div class="modal-content">
                  								      <div class="modal-header" style="padding-bottom: 0px;">
                  								        <button type="button" class="close" data-dismiss="modal">&times;</button>
                  								        <h4 class="modal-title">Por favor complete los datos</h4>
                  								      </div>
                  								      <div class="modal-body" style="padding-bottom: 0px;">
                  								        <div class="form-horizontal" style="overflow: hidden;">
                                        		<form action="#" method="post" name="frm2" id="frm2">
                                        				<input type="hidden" name="id_cab" id="id_cab" value="">
                                        				<input type="hidden" class="id_mod" id="" name="">
                                        				<div class="col-md-12">
                                                <div class="form-group row">
                        														<label for="" class="col-xs-2 col-form-label">Servicio</label>
                        														<div class="col-xs-6" id="div_serv_prov">
                        															Cargando lista...
                        															<!-- 
                        															<select class="form-control" name="id_serv_prov" id="id_serv_prov" style="">
                        															    <option value="0"> --------- Seleccione un Servicio --------- </option>
                        															</select> 
                        															-->
                        														</div>
                        														<div class="col-xs-3" style="">
                        															<select class="form-control" name="id_unidad" id="id_unidad" style="">
                        															    <option value="0">-- Unidad --</option>
                        															    <?php foreach($lista_unidades as $i=>$lis): ?>
                        															      		<option value="<?=$lis->id_unidad?>"><?=$lis->valor?></option>
                        															    <?php endforeach;?>
                        															</select> 
                        														</div>
                                                </div>
                                                <div class="form-group row">
                        														<label for="" class="col-xs-2 col-form-label">Cantidad</label>
                        														<div class="col-xs-2">
                        															<input class="form-control" name="cantidad" id="cantidad" type="text" value="" placeholder=""  onkeypress="return justNumbers(event);">
                        														</div>
                        														<label for="" class="col-xs-1 col-form-label">Precio</label>
                        														<div class="col-xs-2">
                        															<input class="form-control" name="precio" id="precio" type="text" value="" placeholder="" readonly>
                        														</div>
                        														<label for="" class="col-xs-1 col-form-label">Total</label>
                        														<div class="col-xs-3">
                        															<input class="form-control" name="total" id="total" type="text" value="" style="text-align: right;" placeholder="" readonly>
                        														</div>
                        			                  </div>
                        			                  <div class="form-group row">
                        														<label for="" class="col-xs-4 col-form-label"></label>
                        														<div class="col-xs-6">
                        															<label class="radio-inline">
                        																&nbsp;&nbsp;
                        															  	<input type="radio" class="rb_tp" name="rbtipopago" id="inafecto" value="INAFECTO">Inafecto&nbsp;&nbsp;&nbsp;
                        															</label>
                        															<label class="radio-inline">
                        															  	<input type="radio" class="rb_tp" name="rbtipopago" id="igv" value="IGV">IGV
                        															</label>
                        														</div>
                        													</div>
                                                </div>

                                            </form>
                                          </div>
                                          <div id="msj_valida_d" class="form-group col-md-12 text-center alert alert-danger"></div>
                  								      </div>
                  								      <div class="modal-footer" style="text-align: center;">
                  								      	<button type="button" id="btnsave_deta" name="btnsave_deta" class="btn btn-primary">Grabar</button>
                  								      	<button type="button" id="btnempty_deta" name="btnempty_deta" class="btn btn-default">Limpiar</button>
                  								        <button type="button" id="btnclose_deta" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  								      </div>
                  								    </div>
                  								  </div>
                  								</div>
                                <!-- -->

	                          	<!-- DETALLE DE COMPRAS -->
	                            <div id="lista_deta" class="col-md-12" style="border-top: 1px solid #CCC; margin-top: 15px;">
	                            	<!-- 
	                            	<div class="" style="margin-top: 15px; font-style: italic; margin-bottom: 10px; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">   
          									      <h5>Detalles de la Compra:</h5>
          		                         	</div>
          	                                <div class="table-responsive" id="tabla_personal2" >
          	      						      <table id="datos_detalle" class="display text-label-lg" cellspacing="0" width="100%">
          	    						        <thead>
          	    						        </thead>
          	    						        <tbody>
          	    						        </tbody>
          	    						      </table>
          	      						  <div>
          	      						  	-->
	                            </div>
                            
                            </div>
			           </div>
			     <!-- </div> -->
                        
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