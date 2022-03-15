<?php if($v_ajax === 'mesas'): ?>
		<div class="table-responsive" id="tabla_personal">
		  <table id="datos_tabla_ajax" class="display" cellspacing="0" width="100%">
		    <thead>
		      <tr>
                <th width="12%">ACCION</th>
                <th>ID</th>
				<th>MESAS</th>
				<th>ALIAS</th>
				<th>ESTADO</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php foreach($lista as $i=>$lis): ?>
                        <tr id="service<?=$lis->id_mesa?>">
                          <td>
                            <button class="btn btn-success btn-sm" style="" onclick="ver(this.id, 'ver<?=$p_modulo?>');" id="<?=$lis->id_mesa?>"><span class="glyphicon glyphicon-pencil"></span></button>
		                  	<button class="btn btn-danger btn-sm" style="" onclick="eliminar(this.id, 'eliminar<?=$p_modulo?>');" id="<?=$lis->id_mesa?>"><span class="glyphicon glyphicon-remove"></span></button>
                          </td>
                          <td><?=$lis->id_mesa?></td>
                          <td><?=$lis->mesa?></td>
                          <td><?=$lis->alias?></td>
                          <td><?=$lis->estado?></td>
                        </tr>
		      <?php endforeach;?>
		        
		    </tbody>
		  </table>
		</div>
<?php endif; ?>