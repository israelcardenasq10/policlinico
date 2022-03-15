<?php $this->load->view("partial/header_excel"); ?>

<div class="container-fluid" style="padding-top: 35px;">
  <!--<h2><?=strtoupper($module_id)?></h2>-->
  
  <p>
  <ol class="breadcrumb">
	<li><a href="<?=base_url()?>panel">Inicio</a></li>
  <?php if($nro_report == 2) $select_link_2 = 'font-weight: bold;';
        else if($nro_report == 3) $select_link_3 = 'font-weight: bold;';
        else if($nro_report == 4) $select_link_4 = 'font-weight: bold;';
        else if($nro_report == 5) $select_link_5 = 'font-weight: bold;';
        else if($nro_report == 6) $select_link_6 = 'font-weight: bold;';
        else if($nro_report == 7) $select_link_7 = 'font-weight: bold;';
        else if($nro_report == 8) $select_link_8 = 'font-weight: bold;';
        else $select_link = 'font-weight: bold;' ?>

	<li><a href="<?=base_url()?>ventas/report" style="text-decoration: none; <?=@$select_link?>"><?=$titulo_main?></a></li>
	<li><a href="<?=base_url()?>ventas/report/2" style="text-decoration: none; <?=@$select_link_2?>"><?=$titulo_main_2?></a></li>
	<li><a href="<?=base_url()?>ventas/report/3" style="text-decoration: none; <?=@$select_link_3?>"><?=$titulo_main_3?></a></li>
	<li><a href="<?=base_url()?>ventas/report/4" style="text-decoration: none; <?=@$select_link_4?>"><?=$titulo_main_4?></a></li>
	<li><a href="<?=base_url()?>ventas/report/5" style="text-decoration: none; <?=@$select_link_5?>"><?=$titulo_main_5?></a></li>
	<li><a href="<?=base_url()?>ventas/report/6" style="text-decoration: none; <?=@$select_link_6?>"><?=$titulo_main_6?></a></li>
	<li><a href="<?=base_url()?>ventas/report/7" style="text-decoration: none; <?=@$select_link_7?>"><?=$titulo_main_7?></a></li>
	<li><a href="<?=base_url()?>ventas/report/8" style="text-decoration: none; <?=@$select_link_8?>"><?=$titulo_main_8?></a></li>
  </ol>
  </p>

  <div class="">
      <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">  

		<?php if($nro_report == 2 || $nro_report == 5 || $nro_report == 6 || $nro_report == 7): ?>
				<div class="col-sm-2"></div>
		<?php endif; ?>
		<?php if($nro_report == 4 || $nro_report == 3 ): ?>
				<div class="col-sm-1"></div>
		<?php endif; ?>
	  
	        <label for="" class="col-sm-1 control-label" style="text-align: right; padding: 10px;">Filtros: </label>
	        <div class="col-sm-2">
	          <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
	            <input type="text" class="form-control input_date" id="fecha1" name="fecha1" placeholder="Fecha Inicial" value="<?php echo @$fecha_1;?>">
	            <span class="input-group-addon">
	                <span class="glyphicon glyphicon-calendar"></span>
	            </span>
	          </div>
	        </div>
	        <div class="col-sm-2">
	          <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
	            <input type="text" class="form-control input_date" id="fecha2" name="fecha2" placeholder="Fecha Final" value="<?php echo @$fecha_2;?>">
	            <span class="input-group-addon">
	                <span class="glyphicon glyphicon-calendar"></span>
	            </span>
	          </div>
	        </div>

		<?php if($nro_report == '' || $nro_report == 1): ?>
				<div class="col-sm-2">
				  <select class="form-control" name="cbo_1" id="cbo_1"  style="">
					  <option value="0">------- TODOS --------</option>
					  <option value="1" <?php if(@$cbo_1 == 1) echo 'selected'; ?>>FACTURAS</option>
					  <option value="2" <?php if(@$cbo_1 == 2) echo 'selected'; ?>>BOLETAS</option>
					  <option value="3" <?php if(@$cbo_1 == 3) echo 'selected'; ?>>TICKETS</option>					  
					  <option value="7" <?php if(@$cbo_1 == 7) echo 'selected'; ?>>CORTESIA</option>
				  </select>
				</div>
				<div class="col-sm-1" style="width: 90px;">
				  <div class="checkbox">
					<label>
					  <input type="checkbox" id="chkanulado" name="chkanulado" value="V" <?php if(@$anulado == 'V') echo 'checked'; ?>>Anulado
					</label>
				  </div>
				</div>

				<div class="col-sm-2">
				  <select class="form-control" name="id_tp" id="id_tp"  style="">
					  <option value="0">------ TODOS TP -------</option>
					  <?php foreach($lista_tp as $i=>$lis): 
							if(@$cbo_2 == $lis->id_tp) :?> 
							  <option value="<?=$lis->id_tp?>" selected><?=$lis->tipo_pago?></option>
					  <?php else: ?>
							  <option value="<?=$lis->id_tp?>"><?=$lis->tipo_pago?></option>
					  <?php endif;
							endforeach;?>
				  </select>
				</div>
				
				<div class="col-sm-2" style="padding: 0px;">
					<button type="button" id="btnfiltrar_venta_<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
					<button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
					<?php if(@$lista != NULL): ?>
					  		<button type="button" id="btnExportarExcel_venta_<?=$module_id?>" class="btn btn-success"/>Exportar</button>
					<?php endif; ?>
				</div>
		<?php endif; ?>
				
		<?php if($nro_report == 2): ?>
				<div class="col-sm-3" style="padding: 0px;">
					<button type="button" id="btnfiltrar_venta_rc_<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
					<button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
					<a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
					<?php if(@$lista != NULL): ?>
					  <button type="button" id="btnExportarExcel_venta_rc_<?=$module_id?>" class="btn btn-success"/>Exportar</button>
					<?php endif; ?>
				</div>
		<?php endif; ?>
		
		<?php if($nro_report == 3): ?>
				<div class="col-sm-3">
				  <select class="form-control" name="cbo_1" id="cbo_1"  style="">
					  <option value="0">-------------- TODOS LOS PRODUCTOS --------------</option>
					  <?php foreach($lista_productos as $i=>$lis): 
							if(@$cbo_1 == $lis->id_producto) :?> 
							  <option value="<?=$lis->id_producto?>" selected><?=$lis->nombre?></option>
					  <?php else: ?>
							  <option value="<?=$lis->id_producto?>"><?=$lis->nombre?></option>
					  <?php endif;
							endforeach;?>
				  </select>
				</div>
				<div class="col-sm-3" style="padding: 0px;">
					<button type="button" id="btnfiltrar_venta_rdp_<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
					<button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
					<a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
					<?php if(@$lista != NULL): ?>
					  		<button type="button" id="btnExportarExcel_venta_rdp_<?=$module_id?>" class="btn btn-success"/>Exportar</button>
					<?php endif; ?>
				</div>
		<?php endif; ?>

		<?php if($nro_report == 4): ?>
				<div class="col-sm-3">
				  <select class="form-control" name="cbo_1" id="cbo_1"  style="">
		              <option value="0">----------------- TODOS ------------------</option>
		              <?php foreach($lista_empleados as $i=>$lis):
		                    if(@$cbo_1 == $lis->id): ?>
		                        <option value="<?=$lis->id?>" selected><?=$lis->nombres?></option>
		                  <?php else:?>
		                        <option value="<?=$lis->id?>"><?=$lis->nombres?></option>
		                  <?php endif;                                                                
		                    endforeach;?>
		          </select>
				</div>

				<div class="col-sm-3" style="padding: 0px;">
					<button type="button" id="btnfiltrar_venta_bar_<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
					<button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
					<?php if(@$lista != NULL): ?>
					  <button type="button" id="btnExportarExcel_venta_bar_<?=$module_id?>" class="btn btn-success"/>Exportar</button>
					<?php endif; ?>
				</div>
		<?php endif; ?>
		
		<?php if($nro_report == 5): ?>
				<div class="col-sm-3" style="padding: 0px;">
					<button type="button" id="btnfiltrar_venta_mb_<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
					<button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
					<a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
					<?php if(@$lista != NULL): ?>
					  <button type="button" id="btnExportarExcel_venta_mb_<?=$module_id?>" class="btn btn-success"/>Exportar</button>
					<?php endif; ?>
				</div>
		<?php endif; ?>

		<?php if($nro_report == 6): ?>
				<div class="col-sm-3" style="padding: 0px;">
					<button type="button" id="btnfiltrar_venta_ccb_<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
					<button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
					<a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
					<?php if(@$lista != NULL): ?>
					  <button type="button" id="btnExportarExcel_venta_ccb_<?=$module_id?>" class="btn btn-success"/>Exportar</button>
					<?php endif; ?>
				</div>
		<?php endif; ?>

		<?php if($nro_report == 7): ?>
				<div class="col-sm-3" style="padding: 0px;">
					<button type="button" id="btnfiltrar_venta_mbcom_<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
					<button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
					<a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
					<?php if(@$lista != NULL): ?>
					  <button type="button" id="btnExportarExcel_venta_mbcom_<?=$module_id?>" class="btn btn-success"/>Exportar</button>
					<?php endif; ?>
				</div>
		<?php endif; ?>

		<?php if($nro_report == 8): ?>
				<div class="col-sm-3" style="padding: 0px;">
					<button type="button" id="btnfiltrar_venta_vfdm_<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
					<button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
					<a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
					<?php if(@$lista != NULL): ?>
					  <button type="button" id="btnExportarExcel_venta_vfdm_<?=$module_id?>" class="btn btn-success"/>Exportar</button>
					<?php endif; ?>
				</div>
		<?php endif; ?>
      </form>
  </div>


  <div class="col-md-12">
    <p class="text-center">A continuación seleccione los filtros para su busqueda: </p>        
    <?php if(@$nro_report == 1 && @$lista != NULL): ?>
			<div id="lista_excel">
				  <table class="table table-striped">
					<thead>
						<tr>
						  <th># DOC.</th>
						  <th>MND</th>
						  <th>ESTADO</th>
						  <th>TC</th>
						  <th>CTO. SERV</th>
						  <th>SUB TOTAL</th>
						  <th>IGV</th>
						  <th>TOTAL</th>
							<?php if(@$cbo_2 == 6): ?>
							<th>EFECTIVO</th>
							<th>TARJETA</th>
							<?php endif; ?>
						  <th>PROCESADO</th>
						  <th>T. PAGO</th>
						  <th>DOC. CLIENTE</th>
						  <th>CLIENTE</th>
						</tr>
					  </thead>
					  <tbody>
						<?php foreach($lista as $i=>$lis): ?>
						  <tr>
							<td><?=$lis->num_doc?></td>
							<td><?=$lis->moneda?></td>
							<td><?=$lis->estado?></td>
							<td><?=$lis->tc?></td>
							<td class="text-right"><?=$lis->costo?></td>
							<td class="text-right"><?=$lis->subtotal_venta?></td>
							<td class="text-right"><?=$lis->igv?></td>
							<td class="text-right" style="font-weight: bold;"><?=$lis->total_venta?></td>
							<?php if(@$cbo_2 == 6): ?>
								<td  class="text-right"><?=$lis->efectivo?></td>
								<td  class="text-right"><?=$lis->tarjeta?></td>
							<?php endif; ?>
							<td><?=$lis->fecha_registro?></td>
							<td><?=$lis->tipo_pago?></td>
							<td><?=$lis->doc_cliente?></td>
							<td><?=$lis->cliente?></td>
						  </tr>
						<?php endforeach;?>
					  </tbody>
				  </table>
			</div>
	<?php endif; ?>
	
	<?php if(@$nro_report == 2 && @$lista != NULL): ?>
			<div id="lista_excel">
			  <table class="table table-striped">
				<thead>
					<tr>
					  <th>FECHA</th>
					  <th>SUB_TOTAL</th>
					  <th>IGV</th>
					  <th>TOTAL</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($lista as $i=>$lis): ?>
					  <tr>
						<td><?=$lis->fecha_registro?></td>
						<td><?=$lis->subtotal_venta?></td>
						<td><?=$lis->igv?></td>
						<td  class="text-right" style="font-weight: bold;"><?=$lis->total_venta?></td>
					  </tr>
					<?php endforeach;?>
				  </tbody>
			  </table>
		</div>
	<?php endif; ?>
	
	<?php if(@$nro_report == 3 && @$lista != NULL): ?>
			<div id="lista_excel">
			  <table class="table table-striped">
				<thead>
					<tr>
					  <th>PRODUCTO</th>
					  <th>CANT. VENTA</th>
					  <th>TOTAL VENTA</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($lista as $i=>$lis): ?>
					  <tr>
						<td><?=$lis->producto?></td>
						<td  class="text-right" style="font-weight: bold;"><?=$lis->venta?></td>
						<td  class="text-right" style="font-weight: bold;"><?=$lis->total?></td>
					  </tr>
					<?php endforeach;?>
				  </tbody>
			  </table>
			</div>
	<?php endif; ?>

	<?php if(@$nro_report == 4 && @$lista != NULL): ?>
			<div id="lista_excel">
			  <table class="table table-striped">
				<thead>
					<tr>
					  <th>DNI</th>
					  <th>NOMBRES</th>
					  <th>USER</th>
					  <th>TOTAL VENTA</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($lista as $i=>$lis): ?>
					  <tr>
						<td><?=$lis->nro_doc?></td>
						<td><?=$lis->barista?></td>
						<td><?=$lis->username?></td>
						<td  class="text-right" style="font-weight: bold;"><?=$lis->total_venta?></td>
					  </tr>
					<?php endforeach;?>
				  </tbody>
			  </table>
			</div>
	<?php endif; ?>
	
	<?php if(@$nro_report == 5 && @$lista != NULL): ?>
			<div id="lista_excel">
			  <table class="table table-striped">
				<thead>
					<tr>
					  <th>ID</th>
					  <th>FECHA</th>
					  <th>HORA</th>
					  <th>MESA</th>
					  <th>USUARIO</th>
					  <th>CATEGORIA</th>
					  <th>FECHA COMANDA</th>
					  <th>COMANDADO</th>
					  <th>NOMBRE PLATO</th>
					  <th>VENTA</th>
					  <th>COMENTARIO</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($lista as $i=>$lis): ?>
					  <tr>
						<td><?=$lis->ID?></td>
						<td><?=$lis->fecha_registro?></td>
						<td style="font-weight: bold;"><?=$lis->hora_registro?></td>
						<td><?=$lis->nro_mesa?></td>
						<td><?=$lis->username?></td>
						<td><?=$lis->categoria?></td>
						<td><?=$lis->fecha_comanda?></td>
						<td><?=$lis->comandado?></td>
						<td><?=$lis->nombre_plato?></td>
						<td><?=number_format($lis->venta, 2)?></td>
						<td><?=$lis->comentario?></td>
						
					  </tr>
					<?php endforeach;?>
				  </tbody>
			  </table>
			</div>
	<?php endif; ?>

	<?php if(@$nro_report == 6 && @$lista != NULL): ?>
			<div id="lista_excel">
			  <table class="table table-striped">
				<thead>
				<tr>
					<th>FECHA</th>
					<th>HORA</th>
					<th>MESA</th>
					<th>USUARIO</th>
					<th>COMANDAS</th>
					<th>COMANDA ELIMINADA?</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($lista as $i=>$lis): ?>
					<tr>
					<td><?=$lis->fecha?></td>
					<td><?=$lis->hora_comanda?></td>
					<td><?=$lis->mesa?></td>
					<td><?=$lis->username?></td>
					<td style="font-weight: bold;"><?=$lis->comandas?></td>					
					<td><?=$lis->eliminado?></td>					
					</tr>
				<?php endforeach;?>
				</tbody>
			  </table>
			</div>
	<?php endif; ?>

	<?php if(@$nro_report == 7 && @$lista != NULL): ?>
			<div id="lista_excel">
			  <table class="table table-striped">
				<thead>
				<tr>
					<th>FECHA COMANDA BORRADA</th>
					<th>MESA</th>
					<th>CATEGORIA</th>
					<th>PRODUCTO</th>
					<th>CANTIDAD</th>
					<th>VENTA</th>
					<th>USUARIO</th>
					<th>FECHA COMANDA</th>
					<th>COMENTARIO</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($lista as $i=>$lis): ?>
					<tr>
					<td><?=$lis->fecha_borrada?></td>
					<td><?=$lis->mesa?></td>
					<td><?=$lis->categoria?></td>
					<td><?=$lis->producto?></td>					
					<td><?=$lis->cantidad?></td>					
					<td><?=$lis->venta?></td>					
					<td><?=$lis->username?></td>					
					<td><?=date('g:i a', strtotime($lis->fecha_completa))?></td>					
					<td><?=$lis->obs?></td>					
					</tr>
				<?php endforeach;?>
				</tbody>
			  </table>
			</div>
	<?php endif; ?>

	<?php if(@$nro_report == 8 && @$lista != NULL): ?>
			<div id="lista_excel">
			  <table class="table table-striped">
				<thead>
				<tr>
					<th>num_doc</th>
					<th>tdoc</th>
					<th>sfactu</th>
					<th>nfactu</th>
					<th>moneda</th>
					<th>subtotal_venta</th>
					<th>igv</th>
					<th>total_venta</th>
					<th>efectivo</th>
					<th>tarjeta</th>
					<th>fecha_registro</th>
					<th>tipo_pago</th>
					<th>nro_doc</th>
					<th>razon_social</th>
					<th>icbper_total</th>
					<th>icbper_cant</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($lista as $i=>$lis): ?>
					<tr>
					<td><?=$lis->num_doc?></td>
					<td><?=$lis->tdoc?></td>
					<td><?=$lis->sfactu?></td>
					<td><?=$lis->nfactu?></td>					
					<td><?=$lis->moneda?></td>					
					<td><?=$lis->subtotal_venta?></td>					
					<td><?=$lis->igv?></td>					
					<td><?=$lis->total_venta?></td>					
					<td><?=$lis->efectivo?></td>					
					<td><?=$lis->tarjeta?></td>					
					<td><?=$lis->fecha_registro?></td>					
					<td><?=$lis->tipo_pago?></td>					
					<td><?=$lis->nro_doc?></td>					
					<td><?=$lis->razon_social?></td>					
					<td><?=$lis->icbper_total?></td>					
					<td><?=$lis->icbper_cant?></td>					
					</tr>
				<?php endforeach;?>
				</tbody>
			  </table>
			</div>
	<?php endif; ?>

  </div>

</div>

<?php $this->load->view("partial/footer_excel"); ?>