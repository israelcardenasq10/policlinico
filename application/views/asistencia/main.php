<?php $this->load->view("partial/header"); ?>

<div class="container-fluid" style="padding-top: 15px;">
  
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
        		  { ?>
          			<div class="tab-pane active" id="actualizar">
  		         		<!-- Actualizar! -->
  		         		<div id="signupbox" class="mainbox col-md-10">
                            <div class="form-horizontal">
                                <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                                    <input type="hidden" id="personahorario" name="personahorario" value="<?=$bus_dato[0]->personahorario?>"/>
                                    <div class="col-md-12">
                                        <h2><span class="fa fa-edit fa-1x"></span> Editar Horario</h2>   
                                        <h5>Actualice la informacion de Horario</h5>
                                        <hr>
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>      
                                   <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>
                                    <div class="col-md-12">                       
                                      <div class="form-group">
                                        <div class="col-md-3">
                                        <label>Empleado</label>
                                        <select class="form-control" name="cbo_1" id="cbo_1"  style="">
                                            <option value="0">------ TODOS -------</option>
                                            <?php foreach($lista_empleados as $i=>$lis):
                                                  if($lis->id == $bus_dato[0]->id_empleado): ?>
                                                      <option value="<?=$lis->id?>" selected><?=$lis->nombres?></option>
                                                <?php else:?>
                                                      <option value="<?=$lis->id?>"><?=$lis->nombres?></option>
                                                <?php endif;                                                                
                                                  endforeach;?>     
                                        </select> 
                                        </div> 
                                        <div class="col-md-3">
                                          <label>Desde</label>
                                          <input type="date" class="form-control" name="desde" id="desde" value="<?=$bus_dato[0]->desde?>" placeholder="Desde" required>
                                          </div>
                                          <div class="col-md-3">
                                              <label>Hasta</label>
                                              <input type="date" class="form-control" name="hasta" id="hasta" value="<?=$bus_dato[0]->hasta?>" placeholder="Hasta" >
                                          </div>
                                          <div class="col-md-2">
                                                <label>Turno</label>
                                                <select class="form-control" name="turno" id="turno">
                                                    <option value="0" >--Turno--</option> 
                                                    <?php foreach($turno as $lis):
                                                      if($lis->id == $bus_dato[0]->turno): ?>
                                                          <option value="<?=$lis->id?>" selected><?=$lis->turno?></option>
                                                    <?php else:?>
                                                          <option value="<?=$lis->id?>"><?=$lis->turno?></option>
                                                    <?php endif;                                                                
                                                      endforeach;?>
                                                  </select>
                                          </div>
                                          <div class="col-md-1">
                                                <label>Modalidad</label>
                                                  <select class="form-control" name="modalidad" id="modalidad">
                                                    <option value="0" >Mod</option>  
                                                    <?php foreach($modalidad as $lis):
                                                      if($lis->id == $bus_dato[0]->modalidad): ?>
                                                          <option value="<?=$lis->id?>" selected><?=$lis->id?></option>
                                                    <?php else:?>
                                                          <option value="<?=$lis->id?>"><?=$lis->id?></option>
                                                    <?php endif;                                                                
                                                      endforeach;?>
                                                  </select>
                                            </div>
                                            <div class="col-md-2">
                                              <label>Lunes Ingreso</label>
                                              <input type="time" class="form-control" name="h_in_lu" id="h_in_lu" value="<?=$bus_dato[0]->h_in_lu?>" placeholder="Hora Entrada" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Lunes Salida</label>
                                              <input type="time" class="form-control" name="h_sa_lu" id="h_sa_lu" value="<?=$bus_dato[0]->h_sa_lu?>" placeholder="Hora Salida" required>
                                          </div>
                                          <div class="col-md-2">
                                              <label>Martes Ingreso</label>
                                              <input type="time" class="form-control" name="h_in_ma" id="h_in_ma" value="<?=$bus_dato[0]->h_in_ma?>" placeholder="Hora Entrada" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Martes Salida</label>
                                              <input type="time" class="form-control" name="h_sa_ma" id="h_sa_ma" value="<?=$bus_dato[0]->h_sa_ma?>" placeholder="Hora Salida" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Miercoles Ingreso</label>
                                              <input type="time" class="form-control" name="h_in_mi" id="h_in_mi" value="<?=$bus_dato[0]->h_in_mi?>" placeholder="Hora Entrada" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Miercoles Salida</label>
                                              <input type="time" class="form-control" name="h_sa_mi" id="h_sa_mi" value="<?=$bus_dato[0]->h_sa_mi?>" placeholder="Hora Salida" required>
                                          </div>
                                          <div class="col-md-2">
                                              <label>Jueves Ingreso</label>
                                              <input type="time" class="form-control" name="h_in_ju" id="h_in_ju" value="<?=$bus_dato[0]->h_in_ju?>" placeholder="Hora Entrada" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Jueves Salida</label>
                                              <input type="time" class="form-control" name="h_sa_ju" id="h_sa_ju" value="<?=$bus_dato[0]->h_sa_ju?>" placeholder="Hora Salida" required>
                                          </div>
                                          <div class="col-md-2">
                                              <label>Viernes Ingreso</label>
                                              <input type="time" class="form-control" name="h_in_vi" id="h_in_vi" value="<?=$bus_dato[0]->h_in_vi?>" placeholder="Hora Entrada" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Viernes Salida</label>
                                              <input type="time" class="form-control" name="h_sa_vi" id="h_sa_vi" value="<?=$bus_dato[0]->h_sa_vi?>" placeholder="Hora Salida" required>
                                          </div>
                                          <div class="col-md-2">
                                              <label>Sabado Ingreso</label>
                                              <input type="time" class="form-control" name="h_in_sa" id="h_in_sa" value="<?=$bus_dato[0]->h_in_sa?>" placeholder="Hora Entrada" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Sabado Salida</label>
                                              <input type="time" class="form-control" name="h_sa_sa" id="h_sa_sa" value="<?=$bus_dato[0]->h_sa_sa?>" placeholder="Hora Salida" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Domingo Ingreso</label>
                                              <input type="time" class="form-control" name="h_in_do" id="h_in_do" value="<?=$bus_dato[0]->h_in_do?>" placeholder="Hora Entrada" required>
                                          </div> 
                                          <div class="col-md-2">
                                              <label>Domingo Salida</label>
                                              <input type="time" class="form-control" name="h_sa_do" id="h_sa_do" value="<?=$bus_dato[0]->h_sa_do?>" placeholder="Hora Salida" required>
                                          </div>
                                          <div class="col-md-2">
                                                <label>Ref</label>
                                                <input type="time" class="form-control" name="h_refrigerio" id="h_refrigerio" value="<?=$bus_dato[0]->h_refrigerio?>" placeholder="Refrigerio" required>
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
                  <div >
  			         		<div class="table-responsive" id="tabla_personal">
  						      <table id="datos_tabla" class="display" cellspacing="0" width="100%">
  						        <thead>
  						          <tr>
  						            <th>ACCION</th>
                          <th>EMPLEADO</th>
                          <th>DESDE</th>
                          <th>HASTA</th>
                          <th>TUR/MOD</th>
                          <th>LUNES</th>
                          <th>MARTES</th>
                          <th>MIERCOLES</th>
                          <th>JUEVES</th>
                          <th>VIERNES</th>
                          <th>SABADO</th>
                          <th>DOMINGO</th>
                          <th>REFRIG</th>
  						          </tr>
  						        </thead>
  						        <tbody>
                      
                        <?php 
                        foreach($lista_horario as $i=>$lis): ?>
  						            <tr>
  						              <td>
  						              	<a href="<?=$href_mod.$lis->personahorario?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
  						              </td>
                            <td><?=$lis->nombres?></td>
                            <td><?=$lis->desde?></td>
                            <td><?=$lis->hasta?></td>
                            <td><?=$lis->turno?>/<?=$lis->modalidad?></td>
                            <td><?=substr($lis->h_in_lu,0,5)?>-<?=substr($lis->h_sa_lu,0,5)?></td>
                            <td><?=substr($lis->h_in_ma,0,5)?>-<?=substr($lis->h_sa_ma,0,5)?></td>
                            <td><?=substr($lis->h_in_mi,0,5)?>-<?=substr($lis->h_sa_mi,0,5)?></td>
                            <td><?=substr($lis->h_in_ju,0,5)?>-<?=substr($lis->h_sa_ju,0,5)?></td>
                            <td><?=substr($lis->h_in_vi,0,5)?>-<?=substr($lis->h_sa_vi,0,5)?></td>
                            <td><?=substr($lis->h_in_sa,0,5)?>-<?=substr($lis->h_sa_sa,0,5)?></td>
                            <td><?=substr($lis->h_in_do,0,5)?>-<?=substr($lis->h_sa_do,0,5)?></td>
                            <td><?=substr($lis->h_refrigerio,0,5)?></td>
  						            </tr>
  						          <?php endforeach;?>
  						        </tbody>
  						      </table>
                  </div>
  						  </div>
  			     	 </div>

               <!-- NUEVO / INSERTAR-->
  			         <div class="tab-pane" id="<?=$arr_accion[1]?>">
  			         		<div id="signupbox" class="mainbox col-md-10">
                            <div class="form-horizontal">
                                <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                                    <div class="col-md-12">
                                        <h2><span class="fa fa-edit fa-1x"></span> Agregar Horario</h2>   
                                        <h5>Insertar la informacion de Horarios</h5>
                                        <hr/>
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>      

                                    <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>
                                    <div class="col-md-11">                       
                                        <div class="form-group">
                                              <div class="col-md-3">
                                              <label>Empleado</label>
                                              <select class="form-control" name="cbo_1" id="cbo_1" style="width:250px">
                                                  <option value="0">------ TODOS -------</option>
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
                                                <label>Desde</label>
                                                <input type="date" class="form-control" name="desde" id="desde" value="" placeholder="Desde" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Hasta</label>
                                                <input type="date" class="form-control" name="hasta" id="hasta" value="" placeholder="Hasta" >
                                            </div>
                                            <div class="col-md-2">
                                                  <label>Turno</label>
                                                  <select class="form-control" name="turno" id="turno">
                                                      <option value="0" selected="selected">--Turno--</option>  
                                                      <?php foreach($turno as $lis):
                                                      if($lis->id == $bus_dato[0]->turno): ?>
                                                            <option value="<?=$lis->id?>" selected><?=$lis->turno?></option>
                                                      <?php else:?>
                                                            <option value="<?=$lis->id?>"><?=$lis->turno?></option>
                                                      <?php endif;                                                                
                                                      endforeach;?>
                                                    </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Modalidad</label>
                                                <select class="form-control" name="modalidad" id="modalidad">
                                                  <option value="0" selected="selected">Mod</option>  
                                                  <?php foreach($modalidad as $lis):
                                                  if($lis->id == $bus_dato[0]->modalidad): ?>
                                                    <option value="<?=$lis->id?>" selected><?=$lis->id?></option>
                                                  <?php else:?>
                                                    <option value="<?=$lis->id?>"><?=$lis->id?></option>
                                                  <?php endif;                                                                
                                                    endforeach;?>
                                                </select>
                                              </div>
                                              <div class="col-md-2">
                                                <label>Lunes Ingreso</label>
                                                <input type="time" class="form-control" name="h_in_lu" id="h_in_lu" value="" placeholder="Hora Entrada" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Lunes Salida</label>
                                                <input type="time" class="form-control" name="h_sa_lu" id="h_sa_lu" value="" placeholder="Hora Salida" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Martes Ingreso</label>
                                                <input type="time" class="form-control" name="h_in_ma" id="h_in_ma" value="" placeholder="Hora Entrada" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Martes Salida</label>
                                                <input type="time" class="form-control" name="h_sa_ma" id="h_sa_ma" value="" placeholder="Hora Salida" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Miercoles Ingreso</label>
                                                <input type="time" class="form-control" name="h_in_mi" id="h_in_mi" value="" placeholder="Hora Entrada" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Miercoles Salida</label>
                                                <input type="time" class="form-control" name="h_sa_mi" id="h_sa_mi" value="" placeholder="Hora Salida" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Jueves Ingreso</label>
                                                <input type="time" class="form-control" name="h_in_ju" id="h_in_ju" value="" placeholder="Hora Entrada" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Jueves Salida</label>
                                                <input type="time" class="form-control" name="h_sa_ju" id="h_sa_ju" value="" placeholder="Hora Salida" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Viernes Ingreso</label>
                                                <input type="time" class="form-control" name="h_in_vi" id="h_in_vi" value="" placeholder="Hora Entrada" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Viernes Salida</label>
                                                <input type="time" class="form-control" name="h_sa_vi" id="h_sa_vi" value="" placeholder="Hora Salida" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Sabado Ingreso</label>
                                                <input type="time" class="form-control" name="h_in_sa" id="h_in_sa" value="" placeholder="Hora Entrada" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Sabado Salida</label>
                                                <input type="time" class="form-control" name="h_sa_sa" id="h_sa_sa" value="" placeholder="Hora Salida" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Domingo Ingreso</label>
                                                <input type="time" class="form-control" name="h_in_do" id="h_in_do" value="" placeholder="Hora Entrada" required>
                                            </div> 
                                            <div class="col-md-2">
                                                <label>Domingo Salida</label>
                                                <input type="time" class="form-control" name="h_sa_do" id="h_sa_do" value="" placeholder="Hora Salida" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Ref</label>
                                                <input type="time" class="form-control" name="h_refrigerio" id="h_refrigerio" value="" placeholder="Refrigerio" required>
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
  				     	 	  <div class="tab-pane" id="<?=$arr_accion[2]?>">&nbsp;
  		         		<!-- Marcacion! -->
                      <div class="col-md-3">
                        <form action="<?=base_url().$module_id.'/importarArchivoDatos'?>" method="POST" enctype="multipart/form-data">
                          <div class="form-group">
                              <label>Ingresar archivo:</label>
                              <div class="input-group">
                                  <div class="input-group-addon">
                                      <i class="fa fa-file-archive-o text-success"></i>
                                  </div>
                                  <input type="file" name="archivoExcel" id="archivoExcel" class="form-control" required>
                              </div>
                              <button type="submit" class="btn btn-info btnImportarArchivo" > Importar Archivo <i class="fa fa-sign-in"></i></button>
                          </div>   
                        </form>
                      </div>
                      <div class="col-md-6">
                        <div class="table-responsive" id="tabla_personal">
                        <table id="datos_tabla" class="display" cellspacing="0" >
                          <thead>
                            <tr>
                              <!-- <th width="5%">ACCION</th> -->
                              <th width="80%">EMPLEADO</th>
                              <th width="20%">FECHA HORA</th>
                            </tr>
                          </thead>
                          <tbody>
                          
                            <?php 
                            foreach($lista_marcas as $i=>$lis): ?>
                              <tr>
                                <!--td>
                                  <a href="<?=$href_mod.$lis->id_marcaciones?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
                                </td-->
                                <td><?=$lis->nombres?></td>
                                <td><?=$lis->fecha_hora?></td>
                              </tr>
                            <?php endforeach;?>
                          </tbody>
                        </table>
                      </div>
                    </div>  
                  </div>
				     <?php } ?>

				     <?php if(@$arr_accion[3]) //
  			     	 	   { ?>
  				     	 	  <div class="tab-pane" id="<?=$arr_accion[3]?>">&nbsp;
                      
                    </div>
				     <?php } ?>
			<?php } ?>

        </div>
      </div>
      <!-- /tabs -->
    </div>

</div>

<?php $this->load->view("partial/footer"); ?>