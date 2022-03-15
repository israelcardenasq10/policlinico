<?php $this->load->view("partial/header"); ?>
	<div  class="container-fluid" style="padding-top: 15px;">
		<p>
			<ol class="breadcrumb">
				<li><a href="<?=base_url()?>panel">Inicio</a></li>
				<li class="ml-2 active"> Lista de <?=ucwords($module_id)?></li>
			</ol>
		</p>
	</div>
	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">
			<li class="active"><a id="tabfacturador" href="#navuno" data-toggle="tab">Facturador</a></li>
			<li><a id="tabresumen" href="#navdos" data-toggle="tab">Resumen Diario</a></li>
		</ul>
	</div>
	<div class="col-md-10">			
			<div class="panel-body">
				<section id="tabs">	
					<div class="tab-content pt-4 px-3 px-sm-0">
						<div class="tab-pane active" id="navuno">
							<div class="row">
								<!-- <div class="col-md-1 offset-md-1"><button id="btn_actualizar" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Actualizar</button></div> -->
								<div class="col-md-12 pt-4">
									<table id="datos_tabla" class="" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>RUC</th>
												<th>TIPO_DOC</th>
												<th>NUMERO DE DOC</th>
												<th>FECHA DE CARGA</th>
												<th>FECHA GENERACION</th>
												<th>FECHA ENVIO</th>
												<th>SITUACION</th>
												<th>OBSERVACIONES</th>
											</tr>
										</thead>
										<tbody>						          
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="navdos">
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label>Fecha de Resumen a Generar: </label>
										<div class="input-block-level">
											<input id="fech_resumen" type="date" class="form-control">
										</div>										
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label></label>
										<div class="input-group">
											<button id="btn_ins_resumen" type="button" class="btn btn-success">
												<i class="fa fa-save"></i> Generar Resumen
											</button>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<table id="datos_resumen" class="center" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th style="width: 10%;"></th>
												<th>Resumen</th>
												<th>Fecha Resumen</th>
												<th>Fecha Generacion</th>
												<th>Nro Ticket</th>
												<th>Nro Boletas</th>
											</tr>
										</thead>
										<tbody>						          
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	</div>
 
	<?php $this->load->view("partial/footer"); ?>