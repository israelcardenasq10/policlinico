<div id="signupbox" class="mainbox col-md-10">
        <div class="form-horizontal">
              <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                <input type="hidden" class="id_mod" id="id" name="id">

                  <div class="col-md-12">
                      <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?> <?=ucwords($p_area)?></h2>   
                      <h5>Actualice la informacion de <?=ucwords($module_id)?> <?=ucwords($p_area)?></h5>
                      <hr/>
                  </div>      
                  <div class="col-md-12" id="form">                       
                      <div class="form-group">
                          <div class="col-md-2"></div>
                           <div class="col-md-2">
                              <!-- <label>Prefijo</label> -->
                              <input type="text" class="form-control" name="id_area" id="id_area" maxlength="2" value="" placeholder="Codigo" style="text-transform: uppercase;" required>
                           </div> 
                           <div class="col-md-4">
                              <!-- <label>Categoria</label> -->
                              <input type="text" class="form-control" style="text-transform: capitalize;" name="nombre" id="nombre" value="" placeholder="Area" required>
                          </div>
                          <div class="col-md-1">
                              <!-- <label>&nbsp;</label><br /> -->
                              <input type="hidden" name="hdpagina" id="hdpagina" value="<?=$p_area?>">
                              <button type="button" id="btnsave" name="btnsave" class="btn btn-primary">Guardar</button>
                          </div>
                          <div class="col-md-1">
                              <div class="">
                                <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                              </div>
                          </div>
                          <div class="col-md-3"></div>
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
                      <th>AREA</th>
                      <th>NOMBRES</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($lista as $i=>$lis): ?>
                            <tr id="service<?=$lis->id_area?>">
                              <td>
                                <button class="btn btn-success btn-sm" onclick="ver(this.id, 'ver<?=$p_area?>');" id="<?=$lis->id_area?>"><span class="glyphicon glyphicon-pencil"></span></button>
                                <button class="btn  btn-danger btn-sm" onclick="eliminar(this.id, 'eliminar<?=$p_area?>');" id="<?=$lis->id_area?>"><span class="glyphicon glyphicon-remove"></span></button>
                              </td>
                              <td><?=$lis->id_area?></td>
                              <td><?=$lis->nombre?></td>                                                  
                            </tr>
                    <?php endforeach;?>
                      
                  </tbody>
                </table>
              </div>
          </div>
</div>
		     	 	