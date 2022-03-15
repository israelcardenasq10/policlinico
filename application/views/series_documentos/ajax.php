<?php if($v_ajax === 'series_documentos'): ?>
		<input type="hidden" id="hddato" name="hddato" value="<?=@$valida_dato?>">
		<div class="table-responsive" id="tabla_personal">
		  <table id="datos_tabla_ajax" class="display" cellspacing="0" width="100%">
		    <thead>
		      <tr>
		        <th width="12%">ACCION</th>
		        <th>LOCAL</th>
				<th>SERIE</th>
				<th>TIPO DOC</th>
				<th>DOCUMENTO</th>
				<th>DESC</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php foreach($lista as $i=>$lis): ?>
		              <tr id="service<?=$lis->id_serie?>">
		                <td>
		                  	<button class="btn btn-success btn-sm" style="" onclick="ver(this.id, 'ver<?=$p_modulo?>');" id="<?=$lis->id_serie?>"><span class="glyphicon glyphicon-pencil"></span></button>
		                  	<!-- <button class="btn btn-danger btn-sm" style="" onclick="eliminar(this.id, 'eliminar<?=$p_modulo?>');" id="<?=$lis->id_serie?>"><span class="glyphicon glyphicon-remove"></span></button> -->
		                </td>
		                <td><?=$lis->local?></td>
						<td><?=$lis->serie?></td>
						<td><?=$lis->tdoc?></td>                                              
						<td><?=$lis->descripcion?></td>
						<td><?=$lis->tipo_doc?></td>
		              </tr>
		      <?php endforeach;?>
		        
		    </tbody>
		  </table>
		</div>
<?php endif; ?>