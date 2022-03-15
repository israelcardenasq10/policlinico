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
		<li class="active"><a id="tab1" href="#navuno" data-toggle="tab">Perfiles</a></li>
		<!-- <li><a id="tabresumen" href="#navdos" data-toggle="tab">Resumen Diario</a></li> -->
	</ul>
</div>
<div class="col-md-10">			
		<div class="panel-body">
			<section id="tabs">	
				<div class="tab-content pt-4 px-3 px-sm-0">
					<div class="tab-pane active" id="navuno">
						<div class="row">
							<div class="col-md-2 pt-4">
								<button id="btn_mod_new" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Nuevo</button>
							</div>
							<div class="col-md-2 pt-4">
								<select name="id_perfil" id="id_perfil">
									<option value="1">MASTER</option>
									<option value="2">ADMINISTRADOR</option>
									<option value="5">MOZO</option>
									<option value="7">CAJA</option>
									<option value="8">SUPERVISOR</option>
								</select>
							</div>
							<div class="col-md-2 pt-4">
								<button id="btn_search" class="btn btn-sm btn-success"> Buscar</button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 pt-4" style="padding-top: 20px;"> 
								<table id="datos_tabla" class="pt-4" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Acciones</th>
											<th>PERFIL</th>
											<th>MODULE_ID</th>
											<th>ACCION</th>
											<th>ALIAS</th>
											<th>TIPO</th>
											<th>ORDEN</th>
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


<!--==============================
MODAL FACTURADOR-RESUMEN
==================================-->

<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="form-horizontal" id="myform1" name="form1">
				<div class="modal-header" style="background-color: #522564; color: #fff;">                    
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="panel-title" id="modalTitulo">Gestion de Modulo Perfil</h4>
				</div>			
				<div class="modal-body">
					<!-- <div class="panel-body">						 -->
						<div class="form-group row">
							<label class="col-md-4 control-label">ID: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="frmid_ma" name="id_ma" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4 control-label">Perfil: </label>
							<div class="col-md-7 input-group input-group-sm">
								<select name="id_perfil" id="frmid_perfil">
									<option value="1">MASTER</option>
									<option value="2">ADMINISTRADOR</option>
									<option value="5">MOZO</option>
									<option value="7">CAJA</option>
									<option value="8">SUPERVISOR</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4 control-label">module_id: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="frmmodule_id" name="module_id" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4 control-label">Accion: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="frmaccion" name="accion" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4 control-label">alias: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="frmalias" name="alias" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4 control-label">tipo: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="frmtipo" name="tipo" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4 control-label">sort: </label>
							<div class="col-md-7 input-group input-group-sm">
								<input id="frmsort" name="sort" type="numer" class="form-control">
							</div>
						</div>
					<!-- </div> -->
				</div>
				<div class="modal-footer">
					<button id="btnadd" type="submit" class="btn btn-success"><i class="fa fa-save"></i>  Guardar</button>
				</div>
			</div>
		</form>
	</div>
</div>

