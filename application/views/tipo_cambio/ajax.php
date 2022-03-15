<?php if($v_ajax === 'tipo_cambio'): ?>
		<input type="hidden" id="hdfecha" name="hdfecha" value="<?=@$fecha_existe?>">
		<div class="table-responsive" id="tabla_personal">
		  <table id="datos_tabla_ajax" class="display" cellspacing="0" width="100%">
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
		                  	<button class="btn btn-success btn-sm" onclick="ver(this.id, 'ver<?=$p_tipo_cambio?>');" id="<?=$lis->id_tc?>"><span class="glyphicon glyphicon-pencil"></span></button>
		                  	<button class="btn btn-danger btn-sm" onclick="eliminar(this.id, 'eliminar<?=$p_tipo_cambio?>');" id="<?=$lis->id_tc?>"><span class="glyphicon glyphicon-remove"></span></button>
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
<?php endif; ?>