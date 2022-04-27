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
           
          			if($modo === 'actualizar' || $modo === 'categorias')
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
    		  			{
                      if($lis->accion == 'reportes'): ?>
                        <li><a href="<?=base_url().$module_id.'/report'?>" style="<?=$disabled?>" id="m_<?=$lis->accion?>"><span class="glyphicon glyphicon-th-large"></span> <?=ucwords($lis->accion)?></a></li>
                <?php // Se brinda un nuevo acceso a CATEGORIAS
                      elseif($lis->accion == 'categorias'):
                        if($modo === 'categorias'):
                          $active = 'active';
                          $disabled = '';
                        else:
                          $active = '';
                        endif;
                ?>
                        <li class="<?=$active?>"><a href="<?=base_url().$module_id.'/listarcategorias'?>" style="<?=$disabled?>" id="m_<?=$lis->accion?>"><span class="glyphicon glyphicon-th-large"></span> <?=ucwords($lis->accion)?></a></li>
                <?php endif;
                     // Cierra
        	      }

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

          <?php if($modo === 'categorias')
                { ?>
                  <div class="tab-pane active" id="categorias">
                      <?php include('categoria.php'); ?>
                  </div>
          <?php }
                else if($modo === 'actualizar')
        		    { ?>
              			<div class="tab-pane active" id="actualizar">
      		         		<div id="signupbox" class="mainbox col-md-10">
                        <div class="form-horizontal">
                            <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                                <input type="hidden" id="id_producto" name="id_producto" value="<?=$bus_dato[0]->id_producto?>">
                                <input type="hidden" id="id_file" name="id_file" value="<?=$bus_dato[0]->id_producto?>">
                                
                                <div class="col-md-12">
                                    <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?></h2>
                                    <h5>Actualice la información de <?=ucwords($module_id)?></h5>
                                    <hr/>
                                    <div class="col-md-12" style="padding-right: 0px;">
                                      <label for="" class="col-xs-1 col-form-label" style="padding: 0px;">Modificado </label>
                                      <div class="col-xs-3" style="padding-bottom: 3px; padding-left: 26px;">
                                        <p class="form-control-static" style="display: inline;"><?=$user_creador_data?></p>
                                      </div>
                                        <a class="btn btn-default pull-right" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                    </div>
                                </div>
                                <div class="col-md-12" style="height: 10px;"></div>

                                <div class="col-md-12">
                                    <div class="col-md-8" >
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-2" for="">Categoria</label>
                                                    <div class="col-md-5">
                                                      <select class="form-control" name="id_categoria" id="id_categoria">
                                                      <option value="0"> ----- Seleccione una Categoria ----- </option>
                                                        <?php foreach($lista_categorias_prod as $i=>$lis):
                                                                if($lis->id_categoria == $bus_dato[0]->id_categoria): ?>
                                                                <option value="<?=$lis->id_categoria?>" selected><?=$lis->nombre?></option>
                                                        <?php      break;
                                                                endif;
                                                              endforeach;?>
                                                        </select>
                                                    </div>

                                                    <!-- <label class="col-form-label col-md-1" for="" style="color: blue;">Comanda</label> -->
                                                    <!-- <div class="col-md-3">
                                                      <select class="form-control" name="producto_comanda_id" id="producto_comanda_id">
                                                        <?php foreach($lista_producto_camanda as $i=>$lis):
                                                                if($lis->id == $bus_dato[0]->producto_comanda_id): ?>
                                                                  <option value="<?=$lis->id?>" selected><?=$lis->orden?></option>
                                                        <?php    else: ?>
                                                                  <option value="<?=$lis->id?>" ><?=$lis->orden?></option>
                                                        <?php   endif;
                                                              endforeach;?>
                                                        </select>
                                                    </div> -->
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" readonly name="nro_producto" id="nro_producto" value="<?=$bus_dato[0]->nro_producto?>" placeholder="">
                                                    </div>
                                                    <!-- <label class="form-contro col-md-1">CÓDIGO</label> -->
                                                    <div class="col-md-3">                                                        
                                                        <input type="text" class="form-control" name="codigo_ant" id="codigo_ant" value="<?=$bus_dato[0]->codigo_ant?>" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-2" for="">Producto</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" name="nombre" id="nombre" value="<?=$bus_dato[0]->nombre?>" style="text-transform: uppercase;" placeholder="Nombre del Producto">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                  <div class="col-md-2">
                                                      <label class="col-form-label" for="">Venta (<?=$g_moneda?>)</label>
                                                      <input class="form-control" name="precio_venta" id="precio_venta" type="text" value="<?=$bus_dato[0]->precio_venta?>" placeholder="0.00" onkeypress="return justNumbers(event);">
                                                  </div>
                                                  <div class="col-md-2">
                                                      <label class="col-form-label" for="">Costo (<?=$g_moneda?>)</label>
                                                      <input class="form-control" name="precio_insumo" id="precio_insumo" type="text" value="<?=$bus_dato[0]->precio_insumo?>" placeholder="0.00" onkeypress="return justNumbers(event);">
                                                  </div>
                                                  <div class="col-md-2">
                                                    <label class="col-form-label" for="activo">Activo</label>
                                                    <select class="form-control" name="activo" id="activo" >
                                                        <?php
                                                        $select = ['SI','NO'];
                                                        foreach($select as $val):
                                                          if($val == $bus_dato[0]->activo):
                                                            echo '<option value="'.$val.'" selected>'.$val.'</option>';
                                                          else:
                                                            echo '<option value="'.$val.'" >'.$val.'</option>';
                                                          endif;
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                  </div>
                                                  <div class="col-md-3">
                                                    <label class="col-form-label" for="unidades">Unidad</label>
                                                    <select class="form-control" name="unidades" id="unidades">                                                          
                                                    <?php foreach($lista_unidades as $i=>$lis): 
                                                        if($lis->valor == $bus_dato[0]->unidades): ?>
                                                          <option value="<?=$lis->valor?>" selected><?=$lis->descripcion?></option>
                                                          <?php    else: ?>
                                                          <option value="<?=$lis->valor?>" ><?=$lis->descripcion?></option>
                                                    <?php   endif;
                                                    endforeach;?>
                                                      </select>
                                                  </div>
                                                  <div class="col-md-3 text-right">
                                                      <input type="hidden" name="txtmodo" id="txtmodo" value="modificar" /></td>
                                                      <button type="button" id="btnMod" name="btnMod" class="btn btn-primary">Grabar</button>
                                                      <!-- <button type="button" id="btnadddeta_mod" name="btnadddeta_mod" class="btn btn-info" data-toggle="modal" href="#myModal">Nuevo Insumo</button> -->
                                                      <a href="<?=base_url().$module_id?>" id="hrefcancel" class="btn btn-default">Cancelar</a>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 thumbnail" style="background-color: #f5f5f5;">
                                        <div class="row">
                                            <div class="col-md-6 col-md-offset-3">
                                                <img src="<?=base_url().'public/images/productos/'.$bus_dato[0]->imagen?>" alt="" style="width: 120px; height: 100px; margin-bottom: 5px; margin-top: 5px;" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                              <form action="javascript:void(0);" enctype="multipart/form-data" id="frmArchivo" method="">
                                                  <input type="file" class="filestyle" data-buttonText="&nbsp;Cargar una Foto.." id="archivo" name="archivo" value="">
                                              </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="msj_valida" class="col-md-12 text-center alert alert-danger" style="margin-top: 0px;"></div>
                                </div>
                            </form>
                        </div>
      						    </div>
      		     	 	</div>
                  <!-- MODAL DETALLES DEL PRODUCTO -->
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
                                  <input type="hidden" name="id_cab" id="id_cab" value="<?=$bus_dato[0]->id_producto?>">
                                  <input type="hidden" class="id_mod" id="" name="">
                                  <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="" class="col-xs-2 col-form-label">Servicio</label>
                                        <div class="col-xs-9" id="">
                                          <select class="form-control" name="id_almacen" id="id_almacen"  style="" onchange="">
                                              <option value="0">-------------------- Seleccione un Servicio --------------------</option>
                                              <?php foreach($lista_servicios as $i=>$lis):
                                                      if($lis->stock_porcion > 0):
                                                    ?>
                                                      <option value="<?=$lis->id_almacen?>"><?=$lis->nombres?></option>
                                              <?php   endif;
                                                    endforeach;?>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-xs-2 col-form-label">&nbsp;</label>
                                          <div class="col-xs-2">
                                            <div class="panel-heading text-center" style="font-weight: bold; padding: 0px">Unidad</div>
                                            <div class="panel-body" style="padding: 0px">
                                            <input class="form-control" name="unidad" id="unidad" type="text" value="" placeholder="" readonly>
                                          </div>
                                        </div>

                                        <div class="col-xs-2">
                                          <div class="panel-heading text-center" id="div_text_porcion" style="font-weight: bold; padding: 0px">Porci&oacute;n</div>
                                          <div class="panel-body" style="padding: 0px">
                                            <input class="form-control" name="valor_porcion" id="valor_porcion" type="text" value="" placeholder="" readonly>
                                          </div>
                                        </div>


                                        <div class="col-xs-2">
                                          <div class="panel-heading text-center" style="font-weight: bold; padding: 0px">Stock</div>
                                          <div class="panel-body" style="padding: 0px">
                                            <input class="form-control" name="stock_porcion" id="stock_porcion" type="text" value="" placeholder="0" readonly>
                                          </div>
                                        </div>

                                        <div class="col-xs-3">
                                          <div class="panel-heading text-center" style="font-weight: bold; padding: 0px">Costo</div>
                                          <div class="panel-body" style="padding: 0px">
                                            <input class="form-control" name="hd_costo_porcion" id="hd_costo_porcion" type="hidden">
                                            <input class="form-control" name="costo_porcion" id="costo_porcion" type="text" value="0.000" style="text-align: right;" placeholder="" readonly>
                                          </div>
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
        	<?php }
        		    else
        		    { ?>
    			         <div class="tab-pane active" id="<?=$arr_accion[0]?>">
                      <div class="col-md-10">
                        <form method="POST" action="<?php echo base_url().$module_id; ?>">
                        <label class="col-form-label col-md-2" for="">Categoria</label>
                          <div class="col-md-4" style="display: inline-block;">
                            <select class="form-control" name="idx_categ" id="idx_categ">
                              <?php foreach($lista_categorias_prod as $i=>$lis):
                                if(@$cbo_1 == $lis->id_categoria) :?> 
                                  <option value="<?=$lis->id_categoria?>" selected><?=$lis->nombre?></option>
                                  <?php endif; ?>                                
                                <option value="<?=$lis->id_categoria?>"><?=$lis->nombre?></option>
                                <?php endforeach;?>
                                <option value="0">TODAS CATEGORIAS</option>
                              </select>
                              
                          </div>
                          <div class="col-md-2">
                          <input type="submit" value="Buscar"/>
                          </div>
                        </form>                      
                      </div>
    			         		<div class="table-responsive col-md-10" id="tabla_personal">
    						      <table id="datos_tabla_compra" class="display text-label-lg" cellspacing="0" width="100%">
  						        <thead>
  						          <tr>
  						            <th style="width: 8%;">ACCION</th>
                          <th>ID</th>
                          <th>CATEGORIA</th>
                          <th>NRO</th>
                          <th>PRODUCTO</th>
                          <th>PRECIO INSUMO</th>
                          <th>PRECIO VENTA</th>
                          <th>ACTIVO</th>
                          <th>MODIFICADO POR</th>
  						          </tr>
  						        </thead>
  						        <tbody>
  						          <?php foreach($lista as $i=>$lis): ?>
  							            <tr style="">
  							              <td>
  	  						              	<a href="<?=$href_mod.$lis->id_producto?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
  	  						              	<button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>('<?=$lis->id_producto?>');" value="<?=$lis->id_producto?>"><span class="glyphicon glyphicon-remove"></span></button>
  							              </td>
                              <td><?=$lis->id_producto?></td>
                              <td><?=$lis->categoria?></td>
                              <td><?=$lis->nro_producto?></td>
                              <td><?=$lis->nombre?></td>
                              <td><?=number_format($lis->precio_insumo, 2)?></td>
                              <td><?=number_format($lis->precio_venta, 2)?></td>
                              <td><?=$lis->activo?></td>
                              <td><?=$lis->username?></td>
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
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="height: 10px;"></div>

                                    <!-- CUERPO  -->
                                    <div class="col-md-12">
                                        <div class="col-md-8" >
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-2" for="">Categoria</label>
                                                        <div class="col-md-5">
                                                          <select class="form-control" name="id_categoria" id="id_categoria" onchange="verNroProducto(this.value);">
                                                          <option value="0"> ----- Seleccione una Categoria ----- </option>
                                                           <?php foreach($lista_categorias_prod as $i=>$lis): ?>
                                                                 <option value="<?=$lis->id_categoria?>"><?=$lis->prefijo."-".$lis->nombre?></option>
                                                           <?php endforeach;?>
                                                           </select>
                                                        </div>

                                                        <!-- <label class="col-form-label col-md-1" for="" style="color: blue;">Comanda</label> -->
                                                        <!-- <div class="col-md-3">
                                                          <select class="form-control" name="producto_comanda_id" id="producto_comanda_id">                                                          
                                                          <?php foreach($lista_producto_camanda as $i=>$lis): ?>
                                                                <option value="<?=$lis->id?>"><?=$lis->orden?></option>
                                                          <?php endforeach;?>
                                                           </select>
                                                        </div> -->
                                                        <div class="col-md-2">
                                                          <input type="text" class="form-control" readonly name="nro_producto" id="nro_producto" value="" >
                                                        </div>
                                                        <!-- <label class="form-contro col-md-1">CÓDIGO</label> -->
                                                        <div class="col-md-3">                                                        
                                                            <input type="text" class="form-control" name="codigo_ant" id="codigo_ant" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-2" for="">Producto</label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="nombre" id="nombre" value="" style="text-transform: uppercase;" placeholder="Nombre del Producto">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                      <div class="col-md-2">
                                                            <label class="col-form-label" for="precio_venta">Venta (<?=$g_moneda?>)</label>
                                                            <input class="form-control" name="precio_venta" id="precio_venta" type="text" value="" placeholder="0.00" onkeypress="return justNumbers(event);">
                                                        </div>
                                                        <div class="col-md-2">
                                                          <label class="col-form-label" for="precio_insumo">Costo (<?=$g_moneda?>)</label>
                                                            <input class="form-control" name="precio_insumo" id="precio_insumo" type="text" value="" placeholder="0.00" onkeypress="return justNumbers(event);">
                                                        </div>
                                                        <div class="col-md-2">
                                                          <label class="col-form-label" for="activo">Activo</label>
                                                          <select class="form-control" name="activo" id="activo" >
                                                              <option value="SI" selected>SI</option>
                                                              <option value="NO">NO</option>
                                                          </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                          <label class="col-form-label" for="unidades">Unidad</label>
                                                          <select class="form-control" name="unidades" id="unidades">                                                          
                                                          <?php foreach($lista_unidades as $i=>$lis): ?>
                                                                <option value="<?=$lis->valor?>"><?=$lis->descripcion?></option>
                                                          <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 text-right">
                                                            <input type="hidden" name="txtmodo" id="txtmodo" value="insertar" /></td>
                                                            <button type="button" id="btnadd" name="btnadd" class="btn btn-primary">Grabar</button>
                                                            <!--  -->
                                                            <input type="hidden" name="txtid_producto_pv" id="txtid_producto_pv" value="" /></td>
                                                            <button type="button" id="btnmod_pv" name="btnmod_pv" class="btn btn-primary">Grabar Precio</button>
                                                            <!-- -->
                                                            <!-- <button type="button" id="btnadddeta" name="btnadddeta" class="btn btn-info" data-toggle="modal" href="#myModal">Nuevo Insumo</button> -->
                                                            <a href="<?=base_url().$module_id?>" id="hrefcancel" class="btn btn-default">Cancelar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 thumbnail" style="background-color: #f5f5f5;">
                                            <div class="row">
                                                <div class="col-md-6 col-md-offset-3">
                                                    <img src="..." alt="&nbsp;(750px * 450px)" style="width: 120px; height: 100px; margin-bottom: 5px; margin-top: 5px;" class="img-thumbnail">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12">
                                                  <form action="javascript:void(0);" enctype="multipart/form-data" id="frmArchivo" method="">
                                                      <input type="file" class="filestyle" data-buttonText="&nbsp;Cargar una Foto.." id="archivo" name="archivo" value="">
                                                  </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="msj_valida" class="col-md-12 text-center alert alert-danger" style="margin-top: 0px;"></div>
                                    </div>

                                    <!-- FIN DE CUERPO -->
                                   </form>
                                </div>


	                          	<!-- DETALLE DEl PRODUCTO -->
	                            <div id="lista_deta" class="col-md-12" style="border-top: 1px solid #CCC;">
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
