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
            <li class="active"><a href="#lista" id="" data-toggle="tab"><span class="glyphicon glyphicon-th-large"></span> <?=ucwords('listar')?></a></li>
            <?php
                  if(isset($allowed_modules_accion))
                  {
                    foreach ($allowed_modules_accion->result() as $key => $lis):
                      if($lis->tipo == 'mante')
                      {
                        if($lis->accion == 'actualizar')
                          $allow_modifica = true;
                        if($lis->accion == 'eliminar')
                          $allow_elimina = true;
                      }
                    endforeach;
                  }
            ?>
            <?php   if(@$allow_modifica == true):
                      $a_btn_class_mod = 'success';
                      $a_btn_disabled_mod = '';
                      $evento_mod = 'ver';
                    else:
                      $a_btn_class_mod = 'default';
                      $a_btn_disabled_mod = 'pointer-events: none; color: rgba(0,0,0,0.1);';
                      $evento_mod = '';
                    endif;

                    if(@$allow_elimina == true):
                      $a_btn_class_eli = 'danger';
                      $a_btn_disabled_eli = '';
                      $evento_eli = 'eliminar';
                    else:
                      $a_btn_class_eli = 'default';
                      $a_btn_disabled_eli = 'pointer-events: none; color: rgba(0,0,0,0.1);';
                      $evento_eli = '';
                    endif;
                ?>
        </ul>
            <input type="hidden" id="allow_modifica" value="<?=@$allow_modifica?>">
            <input type="hidden" id="allow_elimina" value="<?=@$allow_elimina?>">
               
            <div class="tab-pane active" id="lista">
                <div id="signupbox" class="mainbox col-md-10">
                        <div class="form-horizontal">
                              <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                                <input type="hidden" class="id_mod" id="id_tc" name="id_tc">
                
                                  <div class="col-md-12">
                                      <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords(str_replace("_", " de ", $module_id))?> </h2>   
                                      <h5>Actualice la informacion de <?=ucwords(str_replace("_", " de ", $module_id))?> </h5>
                                      <hr/>
                                  </div>      
                                  <div class="col-md-12" id="form">                       
                                      <div class="form-group">
                                          <div class="col-md-2"></div>
                                           <div class="col-md-2">
                                             
                                              <input type="text" class="form-control" name="compra" id="compra" value="" placeholder="Compra" required>
                                           </div> 
                                           <div class="col-md-2">
                                              
                                              <input type="text" class="form-control" name="venta" id="venta" value="" placeholder="Venta" required>
                                          </div>
                                          <div class="col-md-1">
                                             
                                              <input type="hidden" name="hdpagina" id="hdpagina" value="<?=$p_tipo_cambio?>">
                                              <button type="button" id="btnsave" name="btnsave" class="btn btn-primary">Guardar</button>
                                          </div>
                                          <div class="col-md-3">
                                              <div class="">
                                                <a class="btn btn-default" href="<?=base_url()."panel"?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                              </div>
                                          </div>
                                          <div class="col-md-2 text-right">
                                              <button type="button" id="btngenerarTC" name="btngenerarTC" class="btn btn-success">GENERAR TC</button>
                                              <!-- <a class="btn btn-success" href="<?=base_url().$module_id.'/generarTC'?>">GENERAR TC</a> -->
                                          </div>
                                      </div>  
                                  </div>
                                  <div id="form_edit" class="text-center"></div>
                                  <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px; display: none;"></div>
                                  
                              </form>  
                          </div>
                
                          <div class="col-md-12" id="data_listado">
                              <div class="table-responsive" id="tabla_personal">
                                <table id="datos_tabla" class="display text-label-lg" cellspacing="0" width="100%">
                                  <thead>
                                    <tr>
                                      <th width="12%">ACCION</th>
                                      <th>FECHA</th>
                                      <th>COMPRA</th>
                                      <th>VENTA</th>                                      
                                      <th>MODIFICADO POR</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach($lista as $i=>$lis): ?>
                                            <tr id="service<?=$lis->id_tc?>">
                                              <td>
                                                <button class="btn btn-<?=$a_btn_class_mod?> btn-sm" style="<?=$a_btn_disabled_mod?>" onclick="<?=$evento_mod?>(this.id, 'ver<?=$p_tipo_cambio?>');" id="<?=$lis->id_tc?>"><span class="glyphicon glyphicon-pencil"></span></button>
                                                <button class="btn  btn-<?=$a_btn_class_eli?> btn-sm" style="<?=$a_btn_disabled_eli?>" onclick="<?=$evento_eli?>(this.id, 'eliminar<?=$p_tipo_cambio?>');" id="<?=$lis->id_tc?>"><span class="glyphicon glyphicon-remove"></span></button>
                                              </td>
                                              <td><?=$lis->fecha_registro?></td>
                                              <td><?=$lis->compra?></td>
                                              <td><?=$lis->venta?></td>     
                                              <td><?=$lis->username?></td>
                                            </tr>
                                    <?php endforeach;?>
                                      
                                  </tbody>
                                </table>
                              </div>
                          </div>
                </div>            
            
            
            </div>     
      </div>
      <!-- /tabs -->
    </div>  

    
</div>
<?php $this->load->view("partial/footer"); ?>    