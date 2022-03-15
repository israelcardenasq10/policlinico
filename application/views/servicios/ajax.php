<?php if($v_ajax === 'servicios'): ?>
		<div class="table-responsive" id="tabla_personal">
            <table id="datos_tabla_ajax" class="display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th width="12%">ACCION</th>
                  <th>NOMBRE</th>
                  <th>CATEGORIA</th>
                  <th>CUENTA CONTABLE</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($lista as $i=>$lis): ?>
                        <tr id="service<?=$lis->id_serv_prov?>">
                          <td>
                            <button class="btn btn-success btn-sm" onclick="ver(this.id, 'ver<?=$p_modulo?>');" id="<?=$lis->id_serv_prov?>"><span class="glyphicon glyphicon-pencil"></span></button>
                            <button class="btn btn-danger btn-sm" onclick="eliminar(this.id, 'eliminar<?=$p_modulo?>');" id="<?=$lis->id_serv_prov?>"><span class="glyphicon glyphicon-remove"></span></button>
                          </td>
                          <td><?=$lis->nombres?></td>
                          <td><?=$lis->nombre?></td>   
                          <td><?=$lis->cuenta_conta?></td>                                                 
                        </tr>
                <?php endforeach;?>                                      
              </tbody>
            </table>
		</div>
<?php else: ?>

    <?php if($v_ajax === 'categorias'): ?>
    		<div class="table-responsive" id="tabla_personal">
                <table id="datos_tabla_ajax" class="display" cellspacing="0" width="100%">
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
                            <tr id="service<?=$lis->id_cate_serv?>">
                              <td>
                                <button class="btn btn-success btn-sm" onclick="ver(this.id, 'ver<?=$p_modulo?>');" id="<?=$lis->id_cate_serv?>"><span class="glyphicon glyphicon-pencil"></span></button>
                                <button class="btn btn-danger btn-sm" onclick="eliminar(this.id, 'eliminar<?=$p_modulo?>');" id="<?=$lis->id_cate_serv?>"><span class="glyphicon glyphicon-remove"></span></button>
                              </td>
                              <td><?=$lis->id_cate_serv?></td>
                              <td><?=$lis->nombre?></td>   
                              <td><?=$lis->estado?></td> 
                            </tr>
                    <?php endforeach;?>                                      
                  </tbody>
                </table>
    		</div>
    <?php endif; ?>
        
<?php endif; ?>