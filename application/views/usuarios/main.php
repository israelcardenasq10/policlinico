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
                                    <input type="hidden" id="id" name="id" value="<?=$bus_dato[0]->id?>">
                                    <div class="col-md-12">
                                        <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?></h2>   
                                        <h5>Actualice la informacion de <?=ucwords($module_id)?></h5>
                                        <hr/>
                                        <div class="pull-right">
                                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                        </div> 
                                    </div>      
                                    <div class="col-md-12">                       
                                            <div class="form-group">
                                                 <div class="col-md-4">
                                                      <label>Empleado</label>                
                                                        <select class="form-control" name="person_id" id="person_id" style="width: 300px; display: inline;">
                                                            <option value="0"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-------------- Seleccione -------------- </option>
                                                            <?php foreach($lista_empleados as $i=>$lis): ?>
                                                              <option value="<?=$lis->person_id?>" selected><?=$lis->nombres?></option>
                                                            <?php endforeach;?>                                                                                                               
                                                        </select>
                                                 </div> 
                                                 <div class="col-md-2">
                                                    <label>Username</label>
                                                    <input type="text" class="form-control" name="username" id="username" value="<?=$bus_dato[0]->username?>" placeholder="Username" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Password</label>
                                                    <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                                </div>
                                                <div class="col-md-2" style="padding-right: 0px;">
                                                      <label>Perfil</label>                
                                                        <select class="form-control" name="id_perfil" id="id_perfil" style="">
                                                           <option value="0">-- Seleccione --</option>
                                                          <?php foreach($lista_perfiles as $lis): 
                                                                if($bus_dato[0]->id_perfil == $lis->id_perfil): ?>
                                                                    <option value="<?=$lis->id_perfil?>" selected><?=$lis->nom_perfil?></option>
                                                          <?php else:?>
                                                                    <option value="<?=$lis->id_perfil?>"><?=$lis->nom_perfil?></option>
                                                          <?php endif;
                                                                endforeach;?>                                                                                                           
                                                        </select>
                                                 </div>
                                                 <div class="col-md-2" style="padding-left: 30px;">
                                                      <label>Estado</label>                
                                                        <select class="form-control" name="deleted" id="deleted" style="">
                                                           <option value="0" <?php if($bus_dato[0]->deleted == 0) echo 'selected'; ?>> Activo </option>
                                                           <option value="1" <?php if($bus_dato[0]->deleted == 1) echo 'selected'; ?>>Inactivo</option>                                                                                                       
                                                        </select>
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
  						            <th style="">ACCION</th>
  						            <th>DOCUMENTO</th>
  						            <th>NOMBRES</th>
  						            <th>EMAIL</th>
  						            <th>USERNAME</th>
  						            <th>PERFIL</th>
                          <th class="text-center">ESTADO</th>
  						          </tr>
  						        </thead>
  						        <tbody>
  						          <?php foreach($lista as $i=>$lis): 
                              if($lis->deleted == 1): 
                                $td_css = 'color: red;';
                                $estado = 'Inactivo';
                              else:
                                $td_css = 'color: blue;';
                                $estado = 'Activo';
                              endif;
                         ?>
  						            <tr>
  						              <td>
                              <?php if($lis->nro_doc == '12345678'):?>
                                  <a href="#" style="pointer-events: none; color: rgba(0,0,0,0.1);"  class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
                                  <button class="btn  btn-default btn-sm" style="pointer-events: none; color: rgba(0,0,0,0.1);"><span class="glyphicon glyphicon-remove"></span></button>
                              <?php else: ?>
                                  <!-- &nbsp;&nbsp;<input type="checkbox" class="chk_" name="chkestado<?=$i?>" id="chkestado<?=$i?>" value="<?=$lis->id?>"> -->
                                  <a href="<?=$href_mod.$lis->id?>" style="<?=$a_btn_disabled_mod?>"  class="btn btn-<?=$a_btn_class_mod?> btn-sm"><span class="glyphicon glyphicon-pencil"></span></a>
                                  <button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>('<?=$lis->id?>');" value="<?=$lis->id?>"><span class="glyphicon glyphicon-remove"></span></button>
                              <?php endif; ?>
  						              	
  						              </td>
  						              <td ><?=$lis->nro_doc?></td>
  						              <td ><?=$lis->nombres?></td>
  						              <td ><?=$lis->email?></td>
  						              <td ><?=$lis->username?></td>
  						              <td ><?=$lis->nom_perfil?></td>
                            <td style="<?=$td_css?>" class="text-center"><?=$estado?></td>
  						              
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
                                <div class="col-md-12">                       
                                        <div class="form-group">
                                              <div class="col-md-4">
                                                  <label>Empleado</label>                
                                                    <select class="form-control" name="person_id" id="person_id" style="width: 300px; display: inline;">
                                                        <option value="0"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-------------- Seleccione -------------- </option>
                                                      <?php foreach($lista_empleados as $i=>$lis): ?>
                                                        <option value="<?=$lis->person_id?>"><?=$lis->nombres?></option>
                                                      <?php endforeach;?>
                                                    </select>
                                              </div> 
                                              <div class="col-md-2">
                                                <label>Username</label>
                                                <input type="text" class="form-control" name="username" id="username" value="" placeholder="Username" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Password</label>
                                                <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                            </div>
                                            <div class="col-md-2" style="padding-right: 0px;">
                                                  <label>Perfil</label>                
                                                    <select class="form-control" name="id_perfil" id="id_perfil" style="">
                                                        <option value="0">-- Seleccione --</option>
                                                      <?php foreach($lista_perfiles as $lis): ?>
                                                        <option value="<?=$lis->id_perfil?>"><?=$lis->nom_perfil?></option>
                                                      <?php endforeach;?>
                                                    </select>
                                              </div>
                                              <div class="col-md-2" style="padding-left: 30px;">
                                                  <label>Estado</label>                
                                                    <select class="form-control" name="deleted" id="deleted" style="">
                                                        <option value="0"> Activo </option>
                                                        <option value="1">Inactivo</option>                                                                                                       
                                                    </select>
                                              </div>
                                        </div>  
                                </div>                                                                               
                                <!-- <div id="msj_valida" class="form-group msj_error col-md-11 text-center"></div> -->
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