<?php if($v_ajax === 'categorias'): ?>
		<input type="hidden" id="hddato" name="hddato" value="<?=@$valida_dato?>">
		<div class="table-responsive" id="tabla_personal">
		  <table id="datos_tabla_ajax" class="display" cellspacing="0" width="100%">
		    <thead>
		      <tr>
		        <th width="12%">ACCION</th>
		        <th>PREFIJO</th>
		        <th>NOMBRES</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php foreach($lista as $i=>$lis): ?>
		              <tr id="service<?=$lis->id_cat?>">
		                <td>
		                  	<button class="btn btn-success btn-sm" onclick="ver(this.id, 'ver<?=$p_categoria?>');" id="<?=$lis->id_cat?>"><span class="glyphicon glyphicon-pencil"></span></button>
		                  	<button class="btn  btn-danger btn-sm" onclick="eliminar(this.id, 'eliminar<?=$p_categoria?>');" id="<?=$lis->id_cat?>"><span class="glyphicon glyphicon-remove"></span></button>
		                </td>
		                <td><?=$lis->id_cat?></td>
		                <td><?=$lis->nombre?></td>                                                  
		              </tr>
		      <?php endforeach;?>
		        
		    </tbody>
		  </table>
		</div>
<?php endif; ?>
<?php if($v_ajax === 'areas'): ?>
		<input type="hidden" id="hddato" name="hddato" value="<?=@$valida_dato?>">
		<div class="table-responsive" id="tabla_personal">
		  <table id="datos_tabla_ajax" class="display" cellspacing="0" width="100%">
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
<?php endif; ?>