<?php //print_r($lista_categorias); ?>
<div id="signupbox" class="mainbox col-md-10">
    <div class="form-horizontal">
          <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
            <input type="hidden" class="id_mod" id="id" name="id">
              <div class="col-md-12">
                  <h2><span class="fa fa-edit fa-1x"></span> Listar <?=ucwords(str_replace("_", " de ", $module_id))?> Categorias</h2>   
                  <h5>Actualice la informacion de <?=ucwords(str_replace("_", " de ", $module_id))?> </h5>
                  <hr/>
              </div>      
              <div class="col-md-12" id="form">                       
                  <div class="form-group">
                      <div class="col-md-1"></div>
                      <div class="col-md-2">
                        <input type="text" class="form-control" name="id_categoria" id="id_categoria" value="" maxlength="6" placeholder="Codigo" required>
                      </div>                  
                      <div class="col-md-4">
                        <input type="text" class="form-control" name="nombre" id="nombre" value="" placeholder="Nombre de la Categoria" required>
                      </div> 
                      <div class="col-md-2">
                          <select class="form-control" name="estado" id="estado">
                              <option value="1"> Activo </option>
                              <option value="0"> Inactivo </option>      
                          </select>
                      </div>
                      <div class="col-md-1">
                          <input type="hidden" name="hdpagina" id="hdpagina" value="<?=$p_modulo?>">
                          <button type="button" id="btnsave" name="btnsave" class="btn btn-primary">Guardar</button>
                      </div>
                      <div class="col-md-1">
                          <div class="">
                            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                          </div>
                      </div>  
                  </div>  
              </div>
              <div id="form_edit" class="text-center"></div>
              <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px; display: none;"></div>
          </form>  
      </div>
      <div class="col-md-12" id="data_listado">
          <div class="table-responsive" id="tabla_personal">
            <table id="datos_tabla" class="display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th width="12%">ACCION</th>
                  <th>CODIGO</th>
                  <th>NOMBRE</th>
                  <th>ESTADO</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($lista as $i=>$lis): ?>
                        <tr id="service<?=$lis->id_categoria?>">
                          <td>
                            <button class="btn btn-success btn-sm"  onclick="ver(this.id, 'ver<?=$p_modulo?>');" id="<?=$lis->id_categoria?>"><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-danger btn-sm"  onclick="eliminar(this.id, 'eliminar<?=$p_modulo?>');" id="<?=$lis->id_categoria?>"><span class="glyphicon glyphicon-remove"></span></button>
                          </td>
                          <td><?=$lis->id_categoria?></td>
                          <td><?=$lis->nombre?></td>   
                          <td><?=$lis->estado?></td> 
                        </tr>
                <?php endforeach;?>                                      
              </tbody>
            </table>
          </div>
      </div>
</div>  