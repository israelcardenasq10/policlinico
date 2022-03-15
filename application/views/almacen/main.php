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
          			if($modo === 'actualizar' || $modo === 'orden_compra' || $modo === 'mermas')
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
                  if($lis->accion == 'orden_compra')
                    $allow_orden_compra = true;
                  if($lis->accion == 'mermas')
                    $allow_mermas = true;
    		  			}
    		  			else
    		  			{ 
                    if($lis->accion == 'orden_compra')
                      $pag_accion = 'listarordenescompra';
                    elseif($lis->accion == 'mermas')
                      $pag_accion = 'listaralmmermas';
                    else
                      $pag_accion = 'report';

                    if($pag_accion == 'report' || ($modo !== 'orden_compra'  && $modo !== 'mermas'))
                    {
                  ?>
    		  				    <li><a href="<?=base_url().$module_id.'/'.$pag_accion?>" style="<?=$disabled?>" id="m_<?=$lis->accion?>"><?=ucwords(str_replace('_', ' ', $lis->accion))?></a></li>
  		  <?php 	    }
                }
  		  		  endforeach;
      		  }
      			    if($modo === 'actualizar'): ?>
      				    <li class="active"><a href="#actualizar" id="" data-toggle="tab"><?=ucwords('actualizar')?></a></li>
      	  <?php elseif($modo === 'orden_compra'): ?>
                  <li class="active"><a href="#orden_compra" id="" data-toggle="tab"><?=ucwords('Orden Compra')?></a></li>
          <?php elseif($modo === 'mermas'): ?>
                  <li class="active"><a href="#mermas" id="" data-toggle="tab"><?=ucwords('mermas')?></a></li>
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
      	      			$evento_eli = 'eliminarRegAlmacen';
    	      		  else:
    	      		  	$a_btn_class_eli = 'default';
      	      			$a_btn_disabled_eli = 'pointer-events: none; color: rgba(0,0,0,0.1);';
      	      			$evento_eli = '';
    	      		  endif;

                  if(@$allow_orden_compra == true):
                    $a_btn_class_ocompra = 'default';
                    $a_btn_disabled_ocompra = '';
                    //$evento_ocompra = '';
                  else:
                    $a_btn_class_ocompra = 'default';
                    $a_btn_disabled_ocompra = 'pointer-events: none; color: rgba(0,0,0,0.1);';
                    //$evento_ocompra = '';
                  endif;

                  if(@$allow_mermas == true):
                    $a_btn_class_merma = 'info';
                    $a_btn_disabled_merma = '';
                    //$evento_ocompra = '';
                  else:
                    $a_btn_class_merma = 'default';
                    $a_btn_disabled_merma = 'pointer-events: none; color: rgba(0,0,0,0.1);';
                    //$evento_ocompra = '';
                  endif;
                  
	        ?>
        </ul>
        <div class="tab-content">

        <?php if($modo === 'orden_compra')
              { ?>
                <div class="tab-pane active" id="orden_compra">
                    <?php include('orden_compra.php'); ?>
                </div>
        <?php }
              else if($modo === 'mermas')
              { ?>
                <div class="tab-pane active" id="mermas">
                    <?php include('mermas.php'); ?>
                </div>
        <?php
              } 
              else if($modo === 'actualizar')
        		  { ?>
          			<div class="tab-pane active" id="actualizar">
  		         		<!-- Actualizar! -->
  		         		<div id="signupbox" class="mainbox col-md-10">
                      <div class="form-horizontal">
                          <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                              <input type="hidden" id="id_almacen" name="id_almacen" value="<?=$bus_dato[0]->id_almacen?>">
                              <div class="col-md-12">
                                  <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?></h2>   
                                  <h5>Actualice la informacion de <?=ucwords($module_id)?></h5>
                                  <hr/>
                                  <div class="" style="padding-right: 0px;">

                                    <label for="" class="col-xs-2 col-form-label" style="padding: 0px;">Modificado </label>
                                    <div class="col-xs-3" style="padding-bottom: 3px; padding-left: 5px;">
                                      <p class="form-control-static" style="display: inline;"><?=$user_creador_data?></p>
                                    </div>

                                      <a class="btn btn-default pull-right" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                  </div> 
                              </div>      
                              <div class="col-md-12" style="height: 10px;"></div> 

                              <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>                                    
                              
                              <div class="col-md-12">
                                  
                                  <div class="form-group row">
                                    <label for="" class="col-xs-2 col-form-label">Servicio</label>
                                    <div class="col-xs-5" id="div_servicios">
                                      <select class="form-control select2" name="id_serv_prov" id="id_serv_prov"  style="" onchange="verProveedoresXServ(this.value);">
                                          <option value="0">------------------- Seleccione un Servicio ------------------</option>
                                          <?php foreach($lista_servicios as $i=>$lis):
                                                if($lis->id_serv_prov == $bus_dato[0]->id_serv_prov) :?> 
                                                  <option value="<?=$lis->id_serv_prov?>" selected><?=$lis->nombres?></option>
                                          <?php   break;
                                                endif;
                                                endforeach;?>                                
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="prov_id" class="col-xs-2 col-form-label">Proveedor</label>
                                    <div class="col-xs-5" id="div_proveedores">
                                      <select class="form-control" name="id_prov" id="id_prov"  style="">
                                          <option value="0">----------------- Seleccione un Proveedor ------------------</option>
                                          <?php foreach($lista_proveedor as $i=>$lis): 
                                                if($lis->person_id == $bus_dato[0]->id_prov) :?> 
                                                  <option value="<?=$lis->person_id?>" selected><?=$lis->nombre_corto?></option>
                                          <?php else: ?>
                                                  <option value="<?=$lis->person_id?>"><?=$lis->nombre_corto?></option>
                                          <?php endif;
                                                endforeach;?>
                                      </select>                                              
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="" class="col-xs-2 col-form-label">Unidad</label>
                                    <div class="col-xs-2">
                                      <select class="form-control" name="id_unidad" id="id_unidad" style="">
                                          <option value="0">- Seleccione -</option>
                                          <?php foreach($lista_unidades as $i=>$lis): 
                                                if($lis->id_unidad == $bus_dato[0]->id_unidad) :?> 
                                                  <option value="<?=$lis->id_unidad?>" selected><?=$lis->valor?></option>
                                          <?php else: ?>
                                                  <option value="<?=$lis->id_unidad?>"><?=$lis->valor?></option>
                                          <?php endif;
                                                endforeach;?>
                                      </select>
                                    </div>
                                    <label for="" class="col-xs-1 col-form-label">Medida</label>
                                    <div class="col-xs-2">
                                      <input class="form-control" name="unidad_medida" id="unidad_medida" type="text" value="<?=$bus_dato[0]->unidad_medida?>" placeholder="1000" data-toggle="tooltip" data-placement="top" title="Unidad de Medida">
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <label for="cant_unidad" class="col-xs-2 col-form-label">Cantidad</label>
                                    <div class="col-xs-2">
                                      <input class="form-control" name="cantidad" id="cantidad" type="text" value="<?=$bus_dato[0]->cantidad?>" placeholder="0" onkeypress="return justNumbers(event);" disabled>
                                    </div>

                                    <label for="costo_valor" class="col-xs-1 col-form-label">Cto. U.</label>
                                    <div class="col-xs-2">
                                      <input class="form-control" name="costo_serv" id="costo_serv" type="text" value="<?=$bus_dato[0]->costo?>" placeholder="0.00" onkeypress="return justNumbers(event);" data-toggle="tooltip" data-placement="top" title="Costo Unitario">
                                    </div>

                                    <label for="" class="col-xs-1 col-form-label">Stock</label>
                                    <div class="col-xs-2">
                                      <input class="form-control" style="width: 87%;" name="stock_min" id="stock_min" type="text" value="<?=$bus_dato[0]->stock_min?>" placeholder="" onkeypress="return justNumbers(event);" data-toggle="tooltip" data-placement="top" title="Stock Minimo de Insumo">
                                    </div>
                                  </div>    

                                  <div class="form-group row well" style="padding-left: 0px;">
                                    <label for="" class="col-xs-2 col-form-label">Cant. Porci&oacute;n</label>
                                    <div class="col-xs-2">
                                      <input class="form-control" name="valor_porcion" id="valor_porcion" type="text" value="<?=$bus_dato[0]->valor_porcion?>" placeholder="0" onkeypress="return justNumbers(event);" data-toggle="tooltip" data-placement="top" title="Valor del Insumo" disabled>
                                    </div>

                                    <label for="" class="col-xs-1 col-form-label">Stock</label>
                                    <div class="col-xs-2">
                                      <input class="form-control" name="stock_porcion" id="stock_porcion" type="text" value="<?=$bus_dato[0]->stock_porcion?>" placeholder="00" data-toggle="tooltip" data-placement="top" title="Stock del Insumo" readonly>
                                    </div>

                                    <label for="" class="col-xs-1 col-form-label" style="padding: 0px;">Costo (<?=$g_moneda?>)</label>
                                    <div class="col-xs-2">
                                      <input class="form-control" name="costo_porcion" id="costo_porcion" type="text" value="<?=$bus_dato[0]->costo_porcion?>" placeholder="0.00" data-toggle="tooltip" data-placement="top" title="Costo Total del Insumo" readonly>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group row">
                                    <label for="" class="col-xs-2 col-form-label">Es Consignaci&oacute;n?</label>
                                    <div class="col-xs-4" style="margin-top: -5px;">
                                        <?php if($bus_dato[0]->tipo_almacen == 'C')
                                                $checked_img = 'checked';
                                              else
                                                $checked_img = ''; ?>
                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" id="chktipo_almacen" name="chktipo_almacen" value="C" <?=$checked_img?>>
                                          </label>
                                        </div>
                                    </div>
                                  </div>

                                </div>

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
  						      <table id="datos_tabla_compra" class="display text-label-lg" cellspacing="0" width="100%">
						        <thead>
						          <tr>
                          <th style="width: 120px;">ACCION</th>
                          <th>SERVICIO</th>
                          <th>MED.</th>
                          <th>CANT.</th>
                          <th>CTO.(UNT)</th>
                          <th>STOCK</th>
                          <th>CTO.(INS)</th>
                          <th>PROVEEDOR</th>
                          <th>REGISTRA</th>
                          <th>MODIFICA</th>
						          </tr>
						        </thead>
						        <tbody>
                      <?php foreach($lista as $i=>$lis): 
                              if($lis->tipo_almacen == 'C') $style_tr = 'background-color: #efa6274d;';
                              else $style_tr = '';

                              if($lis->stock_min >= $lis->stock_porcion) $style_tr = 'background-color: rgb(217, 83, 79); color: #FFF;';
                               ?>
                      <tr style="<?=$style_tr?>">
                          <td style="<?=$style_tr?>">
                          <a href="<?=$href_mod.$lis->id_almacen?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
                          
                          <button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>('<?=$lis->id_almacen?>', '<?=$lis->id_serv_prov?>');" value="<?=$lis->id_almacen?>"><span class="glyphicon glyphicon-remove"></span></button>

                          <a style="<?=$a_btn_disabled_ocompra?>" onclick="generarOC('<?=$lis->person_id?>', '<?=$lis->razon_social?>', '<?=$lis->id_serv_prov?>', '<?=$lis->id_almacen?>');"  class="btn btn-<?=$a_btn_class_ocompra?> btn-sm" data-toggle="modal" href="#myModal"><span class="glyphicon glyphicon-eur"></span></a>

                          <a style="<?=$a_btn_disabled_merma?>" onclick="generarMerma('<?=$lis->nombres?>', '<?=$lis->id_serv_prov?>', '<?=$lis->id_almacen?>');"  class="btn btn-<?=$a_btn_class_merma?> btn-sm" data-toggle="modal" href="#myModal_merma"><span class="glyphicon glyphicon-share"></span></a>

                          </td>
                          <td><?=$lis->nombres?></td>
                          <td class="text-right"><?=$lis->unidad?></td>
                          <td class="text-right"><?=$lis->cantidad?></td>
                          <td class="text-right"><?=$lis->costo?></td>
                          <td class="text-right"><span class="badge" style="padding: 8px; font-size: 14px;"><?=number_format($lis->stock_porcion)?></span></td>
                          <td class="text-right"><?=$lis->costo_porcion?></td>
                          <td><?=$lis->nombre_corto?></td>
                          <td><?=$lis->fecha_registro?></td>
                          <td><?=$lis->fecha_modifica?></td>
                      </tr>
						          <?php endforeach;?>
						        </tbody>
						      </table>
  						  </div>
  			     	 </div>


                <!-- MODAL DE ORDEN DE COMPRA -->
                  <div id="myModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header" style="padding-bottom: 0px;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h1 class="text-center" id="h1_proveedor"></h1>
                          </div>
                          <div class="modal-body" style="padding-bottom: 0px;">
                            <div class="form-horizontal" style="overflow: hidden;">
                              <form action="#" method="post" name="frm2" id="frm2">
                                <input type="hidden" name="person_id" id="person_id" value="">
                                <input type="hidden" name="id_almacen" id="id_almacen" value="">
                                <input type="hidden" name="hd_proveedor" id="hd_proveedor" value="">
                                <div class="col-md-12" id="div_serv_prov"> </div>
                              </form>
                          </div>
                          <div id="msj_valida_d" class="form-group col-md-12 text-center alert alert-danger"></div>
                        </div>
                        <div class="modal-footer" style="text-align: center;">
                          <button type="button" id="btnsave_oc" name="btnsave_oc" class="btn btn-primary">Grabar</button>
                          <button type="button" id="btnclose_oc" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <!-- -->

                <!-- MODAL DE MERMAS -->
                  <div id="myModal_merma" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header" style="padding-bottom: 0px;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="text-center" id="h1_servicio"></h2>
                          </div>
                          <div class="modal-body" style="padding-bottom: 0px;">
                            <div class="form-horizontal" style="overflow: hidden;">
                              <form action="#" method="post" name="frm3" id="frm3">
                                <input type="hidden" name="id_almacen" id="id_almacen" value="">
                                <div class="col-md-12" id="div_serv_merma"> </div>
                              </form>
                          </div>
                          <div id="msj_valida_m" class="form-group col-md-12 text-center alert alert-danger"></div>
                        </div>
                        <div class="modal-footer" style="text-align: center;">
                          <button type="button" id="btnsave_merma" name="btnsave_merma" class="btn btn-primary">Grabar</button>
                          <button type="button" id="btnclose_merma" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <!-- -->



               	<!-- NUEVO / INSERTAR -->
				<?php 	if(@$arr_accion[1])
						{ ?>
						 <div class="tab-pane" id="<?=$arr_accion[1]?>">
							<div id="signupbox" class="mainbox col-md-10">
								<div class="form-horizontal">
									<form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
										<div class="col-md-12">
											<h2><span class="fa fa-edit fa-1x"></span> Agregar <?=ucwords($module_id)?></h2>   
											<h5>Insertar informacion de <?=ucwords($module_id)?></h5>
											<hr/>
											<div class="pull-right">
												<a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
											</div> 
										</div>      
										
										<div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>                                    
										
										<div class="col-md-12">
											
											<div class="form-group row">
											  <label for="" class="col-xs-2 col-form-label">Servicio</label>
											  <div class="col-xs-5" id="div_servicios">
												<select class="form-control select2" name="id_serv_prov" id="id_serv_prov"  style="" onchange="verProveedoresXServ(this.value);">
													<option value="0">------------------- Seleccione un Servicio ------------------</option>
													<?php foreach($lista_servicios as $i=>$lis): ?>
														  <option value="<?=$lis->id_serv_prov?>"><?=$lis->nombres?></option>
													<?php endforeach;?>  
												</select>
											  </div>
											</div>
											<div class="form-group row">
											  <label for="prov_id" class="col-xs-2 col-form-label">Proveedor</label>
											  <div class="col-xs-5" id="div_proveedores">
												<select class="form-control" name="id_prov" id="id_prov"  style="" disabled>
													<option value="0">----------------- Seleccione un Proveedor ------------------</option>
													<!-- <?php //foreach($lista_proveedor as $i=>$lis): ?> 
														<option value="<?=$lis->person_id?>"><?=$lis->nombre_corto?></option>
													<?php //endforeach;?> -->
												</select>                                              
											  </div>
											</div>
											<div class="form-group row">
											  <label for="" class="col-xs-2 col-form-label">Unidad</label>
											  <div class="col-xs-2">
												<select class="form-control" name="id_unidad" id="id_unidad" style="">
													<option value="0">- Seleccione -</option>
													<?php foreach($lista_unidades as $i=>$lis): ?>
														  <option value="<?=$lis->id_unidad?>"><?=$lis->valor?></option>
													<?php endforeach;?>
												</select>
											  </div>
											  <label for="" class="col-xs-1 col-form-label">Medida</label>
											  <div class="col-xs-2">
												<input class="form-control" name="unidad_medida" id="unidad_medida" type="text" value="" placeholder="1000" data-toggle="tooltip" data-placement="top" title="Unidad de Medida">
											  </div>
											</div>
	   
											<div class="form-group row">
											  <label for="cant_unidad" class="col-xs-2 col-form-label">Cantidad</label>
											  <div class="col-xs-2">
												<input class="form-control" name="cantidad" id="cantidad" type="text" value="1" placeholder="0" onkeypress="return justNumbers(event);">
											  </div>

											  <label for="costo_valor" class="col-xs-1 col-form-label">Cto. U.</label>
											  <div class="col-xs-2">
												<input class="form-control" name="costo_serv" id="costo_serv" type="text" value="" placeholder="0.00" onkeypress="return justNumbers(event);" data-toggle="tooltip" data-placement="top" title="Costo Unitario">
											  </div>

											  <label for="" class="col-xs-1 col-form-label">Stock</label>
											  <div class="col-xs-2">
												<input class="form-control" style="width: 87%;" name="stock_min" id="stock_min" type="text" value="" placeholder="" onkeypress="return justNumbers(event);" data-toggle="tooltip" data-placement="top" title="Stock Minimo de Insumo">
											  </div>
											</div>

											<div class="form-group row well" style="padding-left: 0px;">
											  <label for="" class="col-xs-2 col-form-label">Cant. Porci&oacute;n</label>
											  <div class="col-xs-2">
												<input class="form-control" name="valor_porcion" id="valor_porcion" type="text" value="" placeholder="0" onkeypress="return justNumbers(event);" data-toggle="tooltip" data-placement="top" title="Valor del Insumo">
											  </div>

											  <label for="" class="col-xs-1 col-form-label">Stock</label>
											  <div class="col-xs-2">
												<input class="form-control" name="stock_porcion" id="stock_porcion" type="text" value="" placeholder="00" data-toggle="tooltip" data-placement="top" title="Stock del Insumo" readonly>
											  </div>

											  <label for="" class="col-xs-1 col-form-label" style="padding: 0px;">Costo (<?=$g_moneda?>)</label>
											  <div class="col-xs-2">
												<input class="form-control" name="costo_porcion" id="costo_porcion" type="text" value="" placeholder="0.000" data-toggle="tooltip" data-placement="top" title="Costo Total del Insumo" readonly>
											  </div>
											</div>

											<div class="form-group row">
											  <label for="" class="col-xs-2 col-form-label">Es Consignaci&oacute;n?</label>
											  <div class="col-xs-4" style="margin-top: -5px;">
												  <div class="checkbox">
													<label>
													  <input type="checkbox" id="chktipo_almacen" name="chktipo_almacen" value="C">
													</label>
												  </div>
											  </div>
											</div>
											
										  </div>  

										  <div class="col-md-12">
											  <input type="hidden" name="txtmodo" id="txtmodo" value="insertar" /></td>
											  <button type="button" id="btnadd" name="btnadd" class="btn btn-primary">Grabar</button>
											  <a href="<?=base_url().$module_id?>" id="hrefcancel" class="btn btn-default">Cancelar</a>
										  </div>
									  </form>
								  </div>

							</div>
						   </div>
                     <?php } ?>   
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