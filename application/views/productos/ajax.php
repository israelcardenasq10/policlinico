<?php if($v_ajax === 'detalle'): ?>
		<div class="" style="margin-top: 15px; font-style: italic; margin-bottom: 10px; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">   
		      <h5>Insumos del Producto:</h5>
     	</div>

		<div class="table-responsive" id="tabla_personal2">
		  <table id="datos_tabla_ajax" class="table table-striped" cellspacing="0" width="100%">
		    <thead>
		      <tr>
		        <th style="width: 9%">ACCION</th>
	            <th>SERVICIO/INSUMO</th>
                <th>UNIDAD</th>
                <th>PORCION</th>
                <th>COSTO</th>
		      </tr>
		    </thead>
		    <tbody>
		    <?php $total_deta = 0;
		    	  if($lista != NULL): ?>
		      <?php foreach($lista as $i=>$lis): ?>
		              <tr id="service<?=$lis->id_producto.'-'.$lis->id_almacen?>">
		                <td>
		                  	<!-- <button class="btn btn-success btn-sm" style="" onclick="ver(this.id, 'ver');" id="<?=$lis->id_serv_prov?>"><span class="glyphicon glyphicon-pencil"></span></button> -->
		                  	<button class="btn btn-danger btn-sm" style="" onclick="eliminardetalle(this.id, 'eliminardetalle');" id="<?=$lis->id_producto.'-'.$lis->id_almacen?>"><span class="glyphicon glyphicon-remove"></span></button>
		                </td>
		                <td><?=$lis->nombres?></td>
						<td><?=$lis->valor?></td>
						<td><?=$lis->valor_porcion?></td>
						<td style="text-align: right;"><?=$lis->costo_porcion?></td>
						<?php $total_deta = $total_deta + $lis->costo_porcion; ?>
		              </tr>
		      <?php endforeach;
		      	  endif;?>
		    </tbody>
		  </table>
		</div>

		<!-- TOTALES -->
        <div class="col-md-12" style="margin-top: 20px; padding-right: 0px;">  
            <div class="col-md-8" >&nbsp;</div>
            <div class="col-md-4" style="padding: 0px;">
                    <table class="table table-striped" style="text-align: left;">
                        <tbody>
                            <tr>
                            <td>TOTAL COSTO</td>
                            <th style="text-align: right;" id="c_subtotal"><?=$v_ajax_moneda.' '.number_format($total_deta, 2)?></th>
                            </tr>                                                                                        
                        </tbody>
                    </table>
            </div> 
        </div>
<?php endif; ?>

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
                            <tr id="service<?=$lis->id_categoria?>">
                              <td>
                                <button class="btn btn-success btn-sm" onclick="ver(this.id, 'ver<?=$p_modulo?>');" id="<?=$lis->id_categoria?>"><span class="glyphicon glyphicon-pencil"></span></button>
                                <button class="btn btn-danger btn-sm" onclick="eliminar(this.id, 'eliminar<?=$p_modulo?>');" id="<?=$lis->id_categoria?>"><span class="glyphicon glyphicon-remove"></span></button>
                              </td>
                              <td><?=$lis->id_categoria?></td>
                              <td><?=$lis->nombre?></td>   
                              <td><?=$lis->estado?></td> 
                            </tr>
                    <?php endforeach;?>                                      
                  </tbody>
                </table>
    		</div>
    <?php endif; ?>