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
          			if($modo === 'actualizar' || $modo === 'categorias' || $modo === 'areas' || $modo === 'mas')
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
          <?php     array_push($arr_accion, $lis->accion);
                }
                elseif($lis->tipo == 'mante')
                {
                    if($lis->accion == 'actualizar')
                      $allow_modifica = true;
                    if($lis->accion == 'eliminar')
                      $allow_elimina = true;
                }
                elseif($lis->tipo == 'left_link')
                {
                    if($lis->accion == 'categorias')
                      $allow_categorias = true;
                    if($lis->accion == 'areas')
                      $allow_areas = true;
                }
                else
                { 
                  if($lis->tipo == 'link'):
                  ?>
                    <li><a href="<?=base_url().$module_id.'/report'?>" style="<?=$disabled?>" id="m_<?=$lis->accion?>"><?=ucwords($lis->accion)?></a></li>
          <?php   endif;
                }
  		  		  endforeach;
      		}
      			    if($modo === 'actualizar'): ?>
      				    <li class="active"><a href="#actualizar" id="" data-toggle="tab"><?=ucwords('actualizar')?></a></li>
          <?php elseif($modo === 'mas'): ?>
      				    <li class="active"><a href="#mas" id="" data-toggle="tab"><?=ucwords('agregar')?></a></li>
          <?php elseif($modo === 'categorias'): ?>
                  <li class="active"><a href="#categorias" id="" data-toggle="tab"><?=ucwords('categorias')?></a></li>
      	  <?php elseif($modo === 'areas'): ?>
                  <li class="active"><a href="#areas" id="" data-toggle="tab"><?=ucwords('areas')?></a></li>
          <?php else:
                    if(@$allow_categorias == true)
                      $disabled_cat = ''; 
                    else
                      $disabled_cat = 'pointer-events: none; color: rgba(0,0,0,0.1);';
                    
                    if(@$allow_areas == true)
                      $disabled_area = '';
                    else
                      $disabled_area = 'pointer-events: none; color: rgba(0,0,0,0.1);';
                  ?>
                  <li><a href="<?=base_url().$module_id.'/listarcategoria'?>" style="<?=@$disabled_cat?>"><?=ucwords('categorias')?></a></li>
                  <li><a href="<?=base_url().$module_id.'/listararea'?>" style="<?=@$disabled_area?>"><?=ucwords('areas')?></a></li>
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
              <!-- MODIFICAR -->
        			 <div class="tab-pane active" id="actualizar">
		         		 <div id="signupbox" class="mainbox col-md-10">
                          <div class="form-horizontal">
                              <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                                  <input type="hidden" id="id_inventario" name="id_inventario" value="<?=$bus_dato[0]->id_inventario?>">
                                  <div class="col-md-12">
                                      <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?></h2>   
                                      <h5>Actualice la informacion del <?=ucwords($module_id)?></h5>
                                      <hr/>
                                      <div class="pull-right">
                                          <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                      </div> 
                                  </div>      
                                  <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>
                                  <div class="col-md-12">                       
                                          <div class="form-group">
                                               <div class="col-md-3">
                         	                         <label>Codigo</label>
                                                      <input type="text" class="form-control" placeholder="Ingrese el codigo del Articulo" name="codigo" id="codigo" value="<?=$bus_dato['0']->codigo?>">
                                                     <p>
                                                      <label>Serie</label>
                                                      <input type="text" class="form-control" placeholder="Ingrese Serie del articulo" name="nro_serie" id="nro_serie" value="<?=$bus_dato['0']->nro_serie?>" maxlength="50">
                                                     </p>
                                                     <p>
                                                    <label>Categoria</label><br />
                                                      <select class="form-control" name="id_cat" id="id_cat" style="width: 222px; display: inline-block;">
                                                          <option value="0">------------- Seleccione ------------</option>
                                                          <?php foreach($lista_categorias as $i=>$lis):
                                                                if($bus_dato[0]->id_cat == $lis->id_cat): ?>
                            												    		        <option value="<?=$lis->id_cat?>" selected><?=$lis->nombre?></option>
                                    												  <?php else:?>
                                    												  			<option value="<?=$lis->id_cat?>"><?=$lis->nombre?></option>
                                    												  <?php endif;                                                                
                                                                endforeach;?>                                                                                                                 
                                                      </select>
                                                      <!-- <a href="<?=base_url().$module_id.'/listarcategoria/editar'?>"  class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></a> -->
                                                    
                                                   </p>
                                               </div>
                                               <div class="col-md-3">
                                                    <label>Area</label>
                                                      <?php //echo $bus_dato['0']->hab_area;//print_r($lista_areas);?>                 
                                                      <select class="form-control" name="hab_area" id="hab_area" style="width: 222px; display: inline;">
                                                          <option value="0"> ------------ Seleccione ------------- </option>
                                                          <?php foreach($lista_areas as $i=>$lis): 
                                                          if($bus_dato[0]->hab_area == $lis->id_area): ?>
                          												    		<option value="<?=$lis->id_area?>" selected><?=$lis->nombre?></option>
                                  												  <?php else:?>
                                  												  			<option value="<?=$lis->id_area?>"><?=$lis->nombre?></option>
                                  												  <?php endif;                                                                
                                                           endforeach;?>                                                                                                                 
                                                      </select> 
                                                      
                                                     <p>
                                                      <label>Stock</label>
                                                      <input type="text" class="form-control" placeholder="Ingrese el Stock del articulo" name="cant_unidad" id="cant_unidad" value="<?=$bus_dato['0']->cant_unidad?>" maxlength="7">
                                                      </p>
                                                      <p>
                                                      <label>Proveedor</label>
                                                      <input type="text" class="form-control" placeholder="Ingrese proveedor del articulos" name="prov_id" id="prov_id" value="<?=$bus_dato['0']->razon_social?>" readonly="true">
                                                     </p>
                                               </div> 
                                               <div class="col-md-3">
                                                  <label>Articulo</label>
                                                  <input type="text" class="form-control" placeholder="Nombre del Articulo" name="descripcion" id="descripcion" value="<?=$bus_dato['0']->descripcion?>">
                                                   <p>
                                                   <label>Precio</label>
                                                  <input type="text" class="form-control" placeholder="Precio del Articulo" name="costo_valor" id="costo_valor" value="<?=$bus_dato['0']->costo_valor?>" maxlength="20">
                                              </div>
                                              <div class="col-md-3">
                                                  <label>Marca</label>
                                                      <input type="text" class="form-control" placeholder="Precio del Articulo" name="marca_modelo" id="marca_modelo" value="<?=$bus_dato['0']->marca_modelo?>" maxlength="20">
                                                  <p>
                                                  <label>Fecha de Adquisicion</label>
                                                  <input type="date" class="form-control" placeholder="Precio del Articulo" name="fecha_registro" id="fecha_registro" value="<?=$bus_dato['0']->fecha_registro?>" maxlength="20">
                                                  </p>
                                              </div>
                                              <div class="row"></div>  
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
                else if($modo === 'mas')
        		    {?>
            			<div class="tab-pane active" id="mas">
                      MASSSSSS
                  </div>
          <?php }
                else if($modo === 'categorias')
                {?>
                <div class="tab-pane active" id="categorias">
                    <?php include('categoria.php'); ?>
                </div>
          <?php }
                else if($modo === 'areas')
                {?>
                <div class="tab-pane active" id="areas">
                    <?php include('areas.php'); ?>
                </div>
          <?php }
        		  else
        		  {?>
                <div class="tab-pane active" id="<?=$arr_accion[0]?>">
                 		<div class="table-responsive" id="tabla_personal">
              			      <table id="datos_tabla" class="display text-label-lg" cellspacing="0" width="100%">
              			        <thead>
              			          <tr>
              			            <th style="">ACCION</th>
                                <th>CODIGO</th>
                                <th>AREA</th>
                                <th>ARTICULO</th>
                                <th>MARCA</th>
                                <th>SERIE</th>
                                <th>STK</th>
                                <th>PRECIO</th>
                                <th>ADQUIRIDO</th>
                                <!--<th>CATEG</th>-->
                                <!--<th>PROVEEDOR</th>-->
              			          </tr>
              			        </thead>
          			             <tbody>
        			                 <?php foreach($lista as $i=>$lis): ?>
            			            <tr>
          			                   <td>
                                      <a href="<?=$href_mod.$lis->id_inventario?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
                                      <button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>('<?=$lis->id_inventario?>');" value="<?=$lis->id_inventario?>"><span class="glyphicon glyphicon-remove"></span></button>
                                      <!--
                                      <a href="<?=base_url().$module_id.'/mas/'.$lis->id_inventario?>"  class="btn btn-default  btn-sm"><span class="glyphicon glyphicon-plus"></span></a>
                                      <button class="btn btn-default  btn-sm" onclick="eliminarReg('<?=$lis->codigo?>');" value="<?=$lis->codigo?>"><span class="glyphicon glyphicon-minus"></span></button>
                                      -->
                                    </td>
                                   <!--<td><?=$lis->codigo?></td>-->
                                   <td><?=$lis->codigo?></td>
                                   <td><?=$lis->nombre_area?></td>
                                   <td><?=$lis->descripcion?></td>
                                   <td><?=$lis->marca_modelo?></td>
                                   <td><?=$lis->nro_serie?></td>
                                   <td><?=$lis->cant_unidad?></td>
                                   <td><?=$lis->costo_valor?></td>
                                   <td><?=$lis->fecha_registro?></td>
                                   <!--<td><?=$lis->nombre?></td>-->
                                   <!--<td><?=$lis->razon_social?></td>-->
            			             </tr>
            			             <?php endforeach;?>
            			             </tbody>
      		                  </table>
                      </div>
    			     	  </div>
                     
                  <!-- NUEVO / INSERTAR-->
			            <div class="tab-pane" id="<?=$arr_accion[1]?>">
                        <div id="signupbox" class="mainbox col-md-10">
                            <div class="form-horizontal">
                                <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                                    <input type="hidden" id="id_inventario" name="id_inventario" value="<?=$lista_nromax['0']->id_inventario+1?>">
                                    <div class="col-md-12">
                                        <h2><span class="fa fa-edit fa-1x"></span> Agregar Articulo al <?=ucwords($module_id)?></h2>   
                                        <h5>Insertar informacion al <?=ucwords($module_id)?></h5>
                                        <hr/>
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>      
                                    
                                    <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>                                    
                                    
                                    <div class="col-md-12">     
                                        
                                        <!--
                                        <div class="form-group row">
                                          <label for="codigo" class="col-xs-2 col-form-label">codigo</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="codigo" id="codigo" type="text" value="">
                                          </div>
                                        </div>
                                        -->    
                                        <div class="form-group row">
                                          <label for="hab_area" class="col-xs-2 col-form-label">Area de Ubicacion</label>
                                          <div class="col-xs-3">
                                            <select class="form-control" name="hab_area" id="hab_area" style="">
                                                <option value="0"> --------- Seleccione ---------- </option>
                                                <?php foreach($lista_areas as $i=>$lis): ?>                                                
                    										  			       <option value="<?=$lis->id_area?>"><?=$lis->nombre?></option>
                      											    <?php endforeach;?>                                                                                                                 
                                            </select> 
                                          </div>
                                        </div>   
                                        <div class="form-group row">
                                          <label for="id_cat" class="col-xs-2 col-form-label">Categoria</label>
                                          <div class="col-xs-3">
                                            <select class="form-control" name="id_cat" id="id_cat"  style="">
                                                <option value="0">--------- Seleccione ----------</option>
                                                <?php foreach($lista_categorias as $i=>$lis): ?>
                                                          <option value="<?=$lis->id_cat?>"><?=$lis->nombre?></option>
                                                <?php endforeach;?>                                                                             
                                            </select>
                                          </div>
                                          <!-- 
                                          <div class="col-xs-1" style="padding-left: 0px;">
                                              <a href="<?=base_url().$module_id.'/listarcategoria'?>"  class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></a>                                              
                                          </div>
                                          -->
                                        </div>                                          
                                         
                                        <div class="form-group row">
                                          <label for="descripcion" class="col-xs-2 col-form-label">Nombre Articulo</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" style="text-transform: capitalize;" name="descripcion" id="descripcion" type="text" value="" placeholder="Ingrese el Nombre del Articulo">
                                          </div>
                                        </div>    
                                        <div class="form-group row">
                                          <label for="marca_modelo" class="col-xs-2 col-form-label">Modelo</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="marca_modelo" id="marca_modelo" type="text" value="" placeholder="Ingrese el Modelo del Articulo">
                                          </div>
                                        </div>    
                                        <div class="form-group row">
                                          <label for="nro_serie" class="col-xs-2 col-form-label">Serie</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="nro_serie" id="nro_serie" type="text" value="" placeholder="Ingrese Serie del Articulo">
                                          </div>
                                        </div>    
                                        <div class="form-group row">
                                          <label for="cant_unidad" class="col-xs-2 col-form-label">Stock</label>
                                          <div class="col-xs-2">
                                            <input class="form-control" name="cant_unidad" id="cant_unidad" type="number" value="" placeholder="0">
                                          </div>
                                        </div>    
                                        
                                        <!--
                                        <div class="form-group row">
                                          <label for="salida_unidad" class="col-xs-2 col-form-label">salida_unidad</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="salida_unidad" id="salida_unidad" type="text" value="">
                                          </div>
                                        </div>    
                                        <div class="form-group row">
                                          <label for="saldo_unidad" class="col-xs-2 col-form-label">saldo_unidad</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="saldo_unidad" id="saldo_unidad" type="text" value="">
                                          </div>
                                        </div>    
                                        <div class="form-group row">
                                          <label for="desc_unidad" class="col-xs-2 col-form-label">desc_unidad</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="desc_unidad" id="desc_unidad" type="text" value="">
                                          </div>
                                        </div>    
                                        <div class="form-group row">
                                          <label for="tipo_inventario" class="col-xs-2 col-form-label">tipo_inventario</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="tipo_inventario" id="tipo_inventario" type="text" value="">
                                          </div>
                                        </div>  
                                          
                                        <div class="form-group row">
                                          <label for="notas" class="col-xs-2 col-form-label">notas</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="notas" id="notas" type="text" value="">
                                          </div>
                                        </div>  
                                        -->  
                                        <div class="form-group row">
                                          <label for="costo_valor" class="col-xs-2 col-form-label">Costo</label>
                                          <div class="col-xs-2">
                                            <input class="form-control" name="costo_valor" id="costo_valor" type="number" value="" placeholder="0.00">
                                          </div>
                                        </div>   
                                        
                                        <!-- 
                                        <div class="form-group row">
                                          <label for="fecha_registro" class="col-xs-2 col-form-label">fecha_registro</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="fecha_registro" id="fecha_registro" type="date" value="">
                                          </div>
                                        </div>
                                        -->
                                        <div class="form-group row">
                                          <label for="" class="col-xs-2 col-form-label">fecha_registro</label>
                                          <div class="col-xs-3">
                                            <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
                                              <input class="form-control input_date" name="fecha_registro" id="fecha_registro" type="text" value="">
                                              <span class="input-group-addon">
                                                  <span class="glyphicon glyphicon-calendar"></span>
                                              </span>
                                            </div>
                                          </div>
                                        </div>
                                        <!--     
                                        <div class="form-group row">
                                          <label for="fecha_baja" class="col-xs-2 col-form-label">fecha_baja</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="fecha_baja" id="fecha_baja" type="text" value="">
                                          </div>
                                        </div>    
                                        
                                        <div class="form-group row">
                                          <label for="fecha_modifica" class="col-xs-2 col-form-label">Fecha de Update</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="fecha_modifica" id="fecha_modifica" type="text" value="">
                                          </div>
                                        </div>    
                                        <div class="form-group row">
                                          <label for="id_owner" class="col-xs-2 col-form-label">Modificado Por:</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="id_owner" id="id_owner" type="text" value="">
                                          </div>
                                        </div>                                 
                                        <div class="form-group row">
                                          <label for="id_compra" class="col-xs-2 col-form-label">id_compra</label>
                                          <div class="col-xs-4">
                                            <input class="form-control" name="id_compra" id="id_compra" type="text" value="">
                                          </div>
                                        </div>
                                        -->    
                                       
                                        <div class="form-group row">
                                          <label for="prov_id" class="col-xs-2 col-form-label">Proveedor</label>
                                          <div class="col-xs-4">
                                            <select class="form-control" name="prov_id" id="prov_id"  style="">
                                                <option value="0">--------- Seleccione Proveedor ----------</option>
                                                <?php foreach($lista_proveedor as $i=>$lis): ?> 
  												  			                 <option value="<?=$lis->person_id?>"><?=$lis->razon_social?></option>
                                                <?php endforeach;?>                                                                                                                 
                                            </select>                                              
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