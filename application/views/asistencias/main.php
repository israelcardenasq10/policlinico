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
          <?php     array_push($arr_accion, $lis->accion);
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
          <?php }
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
                                    <input type="hidden" id="id" name="id" value="<?=$bus_dato[0]->id_asistencia?>">
                                    <div class="col-md-12">
                                        <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?></h2>   
                                        <h5>Actualice la informacion de <?=ucwords($module_id)?></h5>
                                        <hr/>
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>      
                                   <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>
                                    <div class="col-md-12">                       
                                            <div class="form-group">
                                                 <div class="col-md-3">
                                                      <label>Empleado</label>                
                                                      <input type="text" class="form-control" name="nombres" id="nombres" value="<?=$bus_dato[0]->nombres?>" placeholder="nombres" readonly="true">   
                                                 </div> 
                                                 <div class="col-md-2">
                                                    <label>Fecha</label>
                                                    <input type="text" class="form-control" name="fecha_login" id="fecha_login" value="<?=$bus_dato[0]->fecha_login?>" placeholder="Fecha" readonly="true">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Hora Ingreso</label>
                                                   <input type="time" class="form-control" name="hora_login" id="hora_login" value="<?=$bus_dato[0]->hora_login?>" placeholder="Hora Entrada" required>
                                                </div>
                                                <div class="col-md-2">
                                                     <label>Hora Salida</label>
                                                     <input type="time" class="form-control" name="hora_logout" id="hora_logout" value="<?=$bus_dato[0]->hora_logout?>" placeholder="Hora Salida" required>
                                                 </div>
                                                <div class="col-md-3">
                                                     <label>Motivo</label>
                                                        <select class="form-control" name="concepto" id="concepto">
                                                          <option value="0" selected="selected">----  Concepto del Cambio----</option>  
                                                          <option value="Permiso del Empleado">Permiso del Empleado</option>
                                                          <option value="Reuni&oacute;n">Reuni&oacute;n</option>
                                                          <option value="Descuento">Descuento</option>
                                                        </select>

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
  						      <table id="datos_tabla" class="display" cellspacing="0" width="100%">
  						        <thead>
  						          <tr>
  						            <th width="9%">ACCION</th>
                          <!--<th>id_asistencia</th>-->
                          <th>EMPLEADO</th>
                          <th>INGRESO</th>
                          <th>SALIDA</th>
                          <th>HORA ING</th>
                          <th>HORA SAL</th>
                          <th>TOTAL</th>
                          <!--<th>hora_tardanza</th>                                    
                          <th>fecha_registro</th>
                          <th>fecha_modifica</th>-->
  						          </tr>
  						        </thead>
  						        <tbody>
  						          <?php foreach($lista as $i=>$lis): ?>
  						            <tr>
  						              <td>
  						              	<a href="<?=$href_mod.$lis->id_asistencia?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
  						              	<!--<button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>('<?=$lis->id_asistencia?>');" value="<?=$lis->id_asistencia?>"><span class="glyphicon glyphicon-remove"></span></button>-->
  						              </td>
                            <!--<td><?=$lis->id_asistencia?></td>-->
                            <td><?=$lis->nombres?></td>
                            <td><?=$lis->fecha_login?></td>
                            <td><?=$lis->fecha_logout?></td>
                            <td><?=$lis->hora_login?></td>
                            <td><?=$lis->hora_logout?></td>
                            <td style="text-align: center;"><?=$lis->horas_trabajo?><strong>Hr.</strong></td>
                            <!--
                            <td><?=$lis->hora_tardanza?></td>
                            <td><?=$lis->fecha_registro?></td>                                        
                            <td><?=$lis->fecha_modifica?></td>  
  						                -->
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
                                    <div class="col-md-12">
                                        <h2><span class="fa fa-edit fa-1x"></span> Agregar <?=ucwords($module_id)?></h2>   
                                        <h5>Insertar la informacion de <?=ucwords($module_id)?></h5>
                                        <hr/>
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>      

                                    <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>
                                    <div class="col-md-12">                       
                                            <div class="form-group">
                                                 <div class="col-md-2">
                                                  <label>Empleado</label>
                                                  
                                                  <select class="form-control" name="cbo_1" id="cbo_1"  style="">
                                                      <option value="0">--- TODOS ---</option>
                                                      <?php foreach($lista_empleados as $i=>$lis):
                                                            if(@$cbo_1 == $lis->id): ?>
                                                                <option value="<?=$lis->id?>" selected><?=$lis->nombres?></option>
                                                          <?php else:?>
                                                                <option value="<?=$lis->id?>"><?=$lis->nombres?></option>
                                                          <?php endif;                                                                
                                                            endforeach;?>     
                                                  </select>                                                                      
                                                         
                                                 </div> 
                                                 <div class="col-md-3">
                                                    <label>Fecha</label>
                                                    <input type="date" class="form-control" name="fecha_login" id="fecha_login" value="" placeholder="Fecha" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Hora Ingreso</label>
                                                   <input type="time" class="form-control" name="hora_login" id="hora_login" value="" placeholder="Hora Entrada" required>
                                                </div>
                                                <div class="col-md-2">
                                                     <label>Hora Salida</label>
                                                     <input type="time" class="form-control" name="hora_logout" id="hora_logout" value="" placeholder="Hora Salida" required>
                                                 </div>
                                                <div class="col-md-3">
                                                     <label>Motivo</label>
                                                        <select class="form-control" name="concepto" id="concepto">
                                                          <option value="0" selected="selected">----  Concepto del Cambio----</option>  
                                                          <option value="Permiso del Empleado">Permiso del Empleado</option>
                                                          <option value="Reuni&oacute;n">Reuni&oacute;n</option>
                                                          <option value="Descuento">Descuento</option>
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