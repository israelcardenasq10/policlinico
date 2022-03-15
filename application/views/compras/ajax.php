<?php if($v_ajax === 'compras_detalle'): ?>
		<div class="" style="margin-top: 15px; font-style: italic; margin-bottom: 10px; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">   
		      <h5>Detalles de la Compra:</h5>
     	</div>

		<div class="table-responsive" id="tabla_personal2">
		  <table id="datos_tabla_ajax" class="table table-striped" cellspacing="0" width="100%">
		    <thead>
		      <tr>
		        <th style="width: 9%">ACCION</th>
	            <th>SERVICIO</th>
                <th>CANTIDAD</th>
                <th>UNIDAD</th>
                <th>PRECIO UNIT.</th>
	            <th>INAFECTO</th>
                <th>IGV</th>
	            <th>TOTAL</th>
		      </tr>
		    </thead>
		    <tbody>
		    <?php if($lista != NULL): ?>
		      <?php $total_deta = 0;
		      		foreach($lista as $i=>$lis): ?>
		              <tr id="service<?=$lis->id_compra.'-'.$lis->correlativo?>">
		                <td>
		                  	<!-- <button class="btn btn-success btn-sm" style="" onclick="ver(this.id, 'ver');" id="<?=$lis->id_serv_prov?>"><span class="glyphicon glyphicon-pencil"></span></button> -->
		                  	<button class="btn btn-danger btn-sm" style="" onclick="eliminardetalle(this.id, 'eliminardetalle');" id="<?=$lis->id_compra.'-'.$lis->correlativo?>"><span class="glyphicon glyphicon-remove"></span></button>
		                </td>
		                <td><?=$lis->servicio?></td>
						<td><?=$lis->cantidad?></td>
						<td><?=$lis->unidad?></td>
						<td><?=$lis->precio?></td>
						<td><?=$lis->inafecto?></td>
						<td><?=$lis->igv?></td>
						<td style="text-align: right;"><?=$lis->total?></td>
		              </tr>
					<?php 	$total_deta += $lis->total;

							if($lis->igv == 'S' && $lis->inafecto == 'N') // IGV
								$valor = 'IGV';
							else if($lis->igv == 'N' && $lis->inafecto == 'S') // INAFECTO
								$valor = 'INAFECTO';
							else //AMBOS
								$valor = 'AMBOS';
					?>
		      <?php endforeach;?>

		      		<?php 	if($valor == 'IGV')
		      				{
		      					$igv = ($total_deta * $v_ajax_igv / (100 + $v_ajax_igv));
		      					$total_afecto = ($total_deta - $igv);
		      					$total_inafecto = 0;
		      					$total = $total_deta;
		      				}
		      				else if($valor == 'INAFECTO')
		      				{
		      					$total_afecto = 0;
		      					$igv = 0;
		      					$total_inafecto = $total_deta;
		      					$total = $total_inafecto;
		      				}
		      				else
		      				{
		      					$igv = ($total_deta * $v_ajax_igv / 100);
		      					$total_afecto = $total_deta;
		      					$total_inafecto = 0;
		      					$total = ($total_deta + $igv);
		      				}
		      		?>
		    <?php else: // No contiene ningún detalle
		    					$igv = 0;
		      					$total_afecto = 0;
		      					$total_inafecto = 0;
		      					$total = 0;		
		    	  endif; ?>
		    </tbody>
		  </table>
		</div>

		<!-- TOTALES -->
        <div class="col-md-12" style="margin-top: 0px; padding-right: 0px;">  
            <div class="col-md-8" >&nbsp;</div>
            <div class="col-md-4" style="padding: 0px;">
                    <table class="table table-striped" style="text-align: left;">
                        <tbody>
                        	<!-- 
	                        	<tr>
	                            <th>MONEDA</th>
	                            <th style="text-align: right;" id="c_total">S/.</th>
	                            </tr>
                        	-->
                            <!--
	                            <tr>
	                            <td>TOTAL</td>
	                            <th style="text-align: right;" id="c_total"><?=number_format($total, 2)?></th>
	                            </tr>
                        	-->
                            <tr>
                            <td>AFECTO</td>
                            <th style="text-align: right;" id="c_igv"><?=number_format($total_afecto, 2)?></th>
                            </tr>

                            <tr>
                            <td>IGV</td>
                            <th style="text-align: right;" id="c_igv"><?=number_format($igv, 2)?></th>
                            </tr>

                            <tr>
                            <td>INAFECTO</td>
                            <th style="text-align: right;" id="c_igv"><?=number_format($total_inafecto, 2)?></th>
                            </tr>

                            <tr>
                            <td>TOTAL</td>
                            <th style="text-align: right;" id="c_subtotal"><?=$v_ajax_moneda.'. '.number_format($total, 2)?></th>
                            </tr>                                                                                        
                        </tbody>
                    </table>
            </div> 
        </div>
<?php endif; ?>