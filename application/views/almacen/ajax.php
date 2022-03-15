<?php if($v_ajax === 'genera_oc'): ?>
		<div style="padding-top: 0px;"> Lista de servicios del proveedor seleccionado:
			<div class="pull-right" style="margin-bottom: 15px;">
		          <label class="radio-inline">
				    <input type="radio" name="rbtipocosto" id="rbigv" value="IGV" checked> IGV
				  </label>
				  <label class="radio-inline">
				    <input type="radio" name="rbtipocosto" id="rbinafecto" value="INAFECTO" disabled> Inafecto
				  </label>
				  &nbsp;&nbsp;
		    </div>
	    </div>

        <table class="table table-striped">
          <thead>
            <tr>
              <th>Servicio</th>
              <th>Unidad</th>
              <th>Cantidad</th>
              <th>Costo (S/.)</th>
              <th>Total (S/.)</th>
            </tr>
          </thead>
          <tbody>
          	<?php foreach($lis_servicios_prov as $lis):  ?>
	                <tr <?php if($lis->id_serv_prov == $v_ajax_id_serv_prov) echo 'class="info" style="font-weight: bold;"'; ?>>
	                  <td>
	                  		<input class="form-control" name="id_serv_prov<?=$lis->id_serv_prov?>" id="id_serv_prov<?=$lis->id_serv_prov?>" type="hidden" value="<?=$lis->id_serv_prov?>">
	                  		<?=$lis->nombres?>
	                  </td>
	                  <td>
	                  		<input class="form-control" name="id_unidad<?=$lis->id_serv_prov?>" id="id_unidad<?=$lis->id_serv_prov?>" type="hidden" value="<?=$lis->id_unidad?>">
							<?=$lis->unidad?>
	                  </td>
	                  <td>
	                  		<?php 
	                  			if($lis->id_serv_prov == $v_ajax_id_serv_prov)
	                  				$value_cant = 1;
	                  			else
	                  				$value_cant = 0;
	                  		?>
	                      	<input class="form-control" style="width: 55px; height: 30px;" name="cantidad<?=$lis->id_serv_prov?>" id="cantidad<?=$lis->id_serv_prov?>" type="text" value="<?=$value_cant?>" onkeyup="calcularCostos('<?=$lis->id_serv_prov?>')" onkeypress="return justNumbers(event);">
	                  </td>
	                  <td>
							<input class="form-control" name="precio<?=$lis->id_serv_prov?>" id="precio<?=$lis->id_serv_prov?>" type="hidden" value="<?=$lis->costo?>">
							<?=$lis->costo?>
	                  </td>
	                  <td>
	                  		<?php 
	                  			if($lis->id_serv_prov == $v_ajax_id_serv_prov)
	                  				$value_total = $lis->costo;
	                  			else
	                  				$value_total = 0;
	                  		?>
	                      	<input class="form-control" name="total<?=$lis->id_serv_prov?>" id="total<?=$lis->id_serv_prov?>" type="hidden" value="<?=$value_total?>">
	                      	<label id="label_total<?=$lis->id_serv_prov?>"><?=number_format($value_total, 2)?></label>
	                  </td>
	                </tr>
            <?php endforeach;?>
          </tbody>
        </table>

<?php elseif($v_ajax === 'ver_oc'): 

		if($v_ajax_estado === 'A' || $v_ajax_estado === 'C')
		{
			$disabled = 'disabled';
			$readonly = 'readonly';
		}
		else
		{
			$disabled = '';
			$readonly = '';
		}
?>
				<div class="col-md-12" style="padding: 0px; margin-bottom: 15px;">
					<div class="col-xs-5" style="padding: 0px; padding-top: 7px;"> Lista de servicios del proveedor:</div>

					<div class="col-xs-3" style="padding: 0px;">
						<select class="form-control inline" name="estado" id="estado"  style="" <?=$disabled?>>
						  <option value="P" <?php if(@$v_ajax_estado == 'P') echo 'selected'; ?>>Pendiente</option>
						  <option value="C" <?php if(@$v_ajax_estado == 'C') echo 'selected'; ?>>Conciliado</option>
						  <option value="A" <?php if(@$v_ajax_estado == 'A') echo 'selected'; ?>>Anulado</option>
						</select>
					</div>

					<div class="col-xs-4" style="text-align: right; padding-right: 0px;">
						<label class="radio-inline">
				    <input type="radio" name="rbtipocosto" id="rbigv" value="IGV" checked  <?=$disabled?>> IGV
				  </label>
				  <label class="radio-inline">
				    <input type="radio" name="rbtipocosto" id="rbinafecto" value="INAFECTO"  <?=$disabled?>> Inafecto
				  </label>
						&nbsp;&nbsp;
					</div>

				</div>
	    
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Servicio</th>
                  <th>Unidad</th>
                  <th>Cantidad</th>
                  <th>Costo (S/.)</th>
                  <th>Total (S/.)</th>
                </tr>
              </thead>
              <tbody>
              	<?php foreach($lis_servicios_prov as $lis):  ?>
		                <tr>
		                  <td>
		                  		<input class="form-control" name="id_serv_prov<?=$lis->id_serv_prov?>" id="id_serv_prov<?=$lis->id_serv_prov?>" type="hidden" value="<?=$lis->id_serv_prov?>">
		                  		<?=$lis->nombres?>
		                  </td>
		                  <td>
		                  		<input class="form-control" name="id_unidad<?=$lis->id_serv_prov?>" id="id_unidad<?=$lis->id_serv_prov?>" type="hidden" value="<?=$lis->id_unidad?>">
								<?=$lis->unidad?>
		                  </td>
		                  <td>
		                      	<input class="form-control" style="" name="cantidad<?=$lis->id_serv_prov?>" id="cantidad<?=$lis->id_serv_prov?>" type="hidden" value="<?=$lis->cantidad?>">
		                  		<?=$lis->cantidad?>
		                  </td>
		                  <td>
								<input class="form-control" style="width: 70px; height: 30px;" name="precio<?=$lis->id_serv_prov?>" id="precio<?=$lis->id_serv_prov?>"  <?=$disabled?> type="text" value="<?=$lis->precio?>" onkeyup="calcularCostos('<?=$lis->id_serv_prov?>')" onkeypress="return justNumbers(event);">
								<?php //=$lis->precio?>
		                  </td>
		                  <td>
		                      	<input class="form-control" name="total<?=$lis->id_serv_prov?>" id="total<?=$lis->id_serv_prov?>" type="hidden" value="<?=$lis->total?>">
		                      	<label id="label_total<?=$lis->id_serv_prov?>"><?=$lis->total?></label>
		                  </td>
		                </tr>
	            <?php endforeach;?>
              </tbody>
            </table>
			
<?php elseif($v_ajax === 'mermas'): 
?>			
		<table class="table table-striped">
          <thead>
            <tr>
              <th style="width: 45%;">Proveedor</th>
              <th class="text-center" style="width: 20%;">Unidad</th>
              <th class="text-center" style="width: 20%;">Stock Actual</th>
              <th class="text-center" style="width: 15%;">Merma</th>
            </tr>
          </thead>
          <tbody>
          	<?php foreach($lis_almacen as $lis):  ?>
	                <tr>
	                  <td><?=$lis->razon_social?></td>
	                  <td class="text-center"><?=$lis->valor?></td>
	                  <td class="text-center"><strong><?=$lis->stock_porcion.'</strong> '?>
	                      	<?php 
	                      			if($lis->valor == 'KLG')
	                      				$v_medida = 'grm.';
	                      			else if($lis->valor == 'LTS')
	                      				$v_medida = 'mlts.';
	                      			else if($lis->valor == 'MLD')
	                      				$v_medida = 'grm.';
	                      			else
	                      				$v_medida = strtolower($lis->valor);
	                      			echo $v_medida;
	                      	?>
	                      	<input class="form-control" name="id_almacen" id="id_almacen" type="hidden" value="<?=$lis->id_almacen?>">
							<input class="form-control" name="id_serv_prov" id="id_serv_prov" type="hidden" value="<?=$lis->id_serv_prov?>">
							<input class="form-control" name="stock_actual" id="stock_actual" type="hidden" value="<?=$lis->stock_porcion?>">
	                  </td>
	                  <td><input class="form-control text-center" style="width: 70px; height: 32px;" name="stock_merma" id="stock_merma" type="text" placeholder="<?=$v_medida?>" onkeypress="return justNumbers(event);"></td>
	                </tr>
            <?php endforeach;?>
          </tbody>
        </table>
<?php endif; ?>