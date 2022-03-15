<?php// $this->load->view("partial/new_header"); ?>
	<div class="mt-2 ml-2 mr-2">
		<ol class="breadcrumb">
			<li><a href="<?=base_url()?>panel">Inicio</a></li>
			<li class="ml-2 active">/ Lista de <?=ucwords($module_id)?></li>
		</ol>
	</div>
	<div class="col-md-10 offset-md-1">
		<div class="card">
			<div class="card-header p-0" id="headingTwo">
				<nav>
					<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
						<a id="tabfacturador" class="nav-item nav-link active" data-toggle="tab" href="#nav-uno" aria-selected="true">Facturador</a>
						<a id="tabresumen" class="nav-item nav-link" data-toggle="tab" href="#nav-dos" aria-selected="false">Resumen Diario</a>
					</div>
				</nav>
			</div>
			<div class="card-body">
				<section id="tabs">				
					
					<!-- <div class="tab-content pt-4 px-3 px-sm-0"> -->
					<div class="tab-pane active" id="actualizar">
						<div class="tab-pane fade show active" id="nav-uno">
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
						<div class="tab-pane fade" id="nav-dos">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>Fecha de Resumen a Generar: </label>
										<div class="input-group input-group-sm">
											<input id="fech_resumen" type="date" class="form-control">
										</div>										
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label></label>
										<div class="input-group input-group-sm mt-2">
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
												<th style="width: 9%;"></th>
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

	<!--==============================
    MODAL FACTURADOR-RESUMEN
    ==================================-->

    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalTitulo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #522564; color: #fff;">                    
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="panel-title" id="modalTitulo"></h4>
                </div>
                <div class="modal-body">
                    <div class="card">
					<form class="form-horizontal" id="form1" name="form1">
						<div class="form-group">
							<label class="col-md-7 control-label">Codigo: </label>
							<div class="col-md-7 input-group input-group-sm">
								<!-- <input  id="id_centrocosto" name="id_centrocosto" type="hidden"> -->
								<input id="id_resumen" name="id_resumen" type="text" readonly class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-7 control-label">Fecha de Resumen: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="fec_resumen" name="fec_resumen" type="date" readonly class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-7 control-label">Fecha de Generación: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="fec_generacion" name="fec_generacion" type="date"  readonly class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-7 control-label">Nº Boletas: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="numreg" name="numreg" type="text" readonly class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-7 control-label">Nº Tikets: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="ntickect" name="ntickect" type="text" class="form-control">
							</div>
						</div>   
                    </form>
					</div>
                </div>
                <div class="card-footer">
                    <button id="btnadd" ype="button" class="btn btn-success"><i class="fa fa-save"></i>  Guardar</button>
                </div>
            </div>
        </div>
    </div>

	<?php //$this->load->view("partial/new_footer"); ?>