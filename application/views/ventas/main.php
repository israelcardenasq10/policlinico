<?php $this->load->view("partial/header"); ?>

<div class="container-fluid" style="padding-top: 15px;">
  <!--<h2><?=strtoupper($module_id)?></h2>-->
  
  <p>
  <ol class="breadcrumb">
	<li><a href="<?=base_url()?>panel">Inicio</a></li>
	<li class="active">Lista de <?=ucwords($module_id)?></li>
  </ol>
  </p>
	
	<div class="col-md-12" style="margin-top: -30px;">

      <!-- tabs left -->
      <div class="tabbable tabs-left">
        <ul class="nav nav-tabs">
          <?php
         	if(isset($allowed_modules_accion))
          {
         		$arr_accion = array();
          		foreach ($allowed_modules_accion->result() as $key => $lis):
          			if($modo === 'actualizar')
          			{
          				$disabled = 'pointer-events: none; color: rgba(0,0,0,0.1);';
          				$active = '';
          			}
          			else
          			{
          				if($key == 0) $active = 'active';
  	      				else $active = '';
  	      				
  	      				$disabled = '';
          			}

  	      			if($lis->tipo == 'tabs')
  	      			{ ?>
  	      				<li class="<?=$active?>"><a href="#<?=$lis->accion?>" style="<?=$disabled?>" id="m_<?=$lis->accion?>" data-toggle="tab"><span class="glyphicon glyphicon-th-large"></span> <?=ucwords($lis->accion)?></a></li>
  		  <?php			array_push($arr_accion, $lis->accion);
    		  			}
    		  			elseif($lis->tipo == 'mante')
    		  			{
    		  				if($lis->accion == 'actualizar')
    		  					$allow_modifica = true;
    		  				if($lis->accion == 'anular')
    		  					$allow_anula = true;
    		  			}
    		  			else
    		  			{ ?>
    		  				  <li><a href="<?=base_url().$module_id.'/report'?>" style="<?=$disabled?>" id="m_<?=$lis->accion?>"><?=ucwords($lis->accion)?></a></li>
  		  <?php 	}
  		  		  endforeach;
      		  }      			      
	        ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="<?=$arr_accion[0]?>"> 
            <div class="col-md-10">
              <form id="buscarventa" method="POST" >
                <div class="col-md-2 form-group">
                  <label class="control-label">Tipo Doc. </label>
                  <select id="v_tdoc" name="v_tdoc" class="form-control">
                  <option value="0">Todos</option>
                  <option value="01">Factura</option>
                  <option value="03">Boleta</option>
                  <option value="07">Nota de Crédito</option>
                  <option value="00">Recibo</option>
                  </select>
                </div>
                <div class="col-md-2 form-group">
                  <label class="control-label">Serie Doc. </label>
                  <div class="input-group">
                    <input id="v_sfactu" name="v_sfactu" type="text" pattern="[A-Z0-9]{4}" class="form-control">
                  </div>
                </div>  
                <div class="col-md-2 form-group">
                  <label class="control-label">Número Doc. </label>
                  <div class="input-group">
                    <input id="v_nfactu" name="v_nfactu" type="text" pattern="[0-9]{8}" class="form-control">
                  </div>
                </div>                    
                <div class="col-md-2 form-group">
                  <label class="control-label">Fec. Inicio: </label>
                  <div class="input-group">
                    <input id="v_desde" name="v_desde" type="date" required class="form-control">
                  </div>
                </div>
                <div class="col-md-2 form-group">
                  <label class="control-label">Fec. Fin: </label>
                  <div class="input-group">
                    <input id="v_hasta" name="v_hasta" type="date" required class="form-control">
                  </div>
                </div>
                <div class="col-md-2 form-group">
                  <div class="input-group">
                    <input id="buscar" value="Buscar" type="submit" class="btn btn-success" style="margin-top:2em;margin-left:0px" >
                  </div>
                </div>
              </form>
            </div>
            <div class="col-md-10 table-responsive" id="tabla_personal">
              <table id="datos_tabla_ventas" class="display text-label-lg" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th style="width: 10%;"></th>
                    <th># VENTA</th>
                    <th>BARISTA</th>
                    <th>CLIENTE</th>
                    <th>MESA</th>
                    <th>TP</th>
                    <th>MND</th>
                    <th>FECHA</th>
                    <th>HORA INI</th>
                    <th>HORA FIN</th>
                    <th>NETO (<?=$g_moneda?>)</th>
                    <th>IGV (<?=$g_moneda?>)</th>
                    <th>TOTAL (<?=$g_moneda?>)</th>
                  </tr>
                </thead>
                <tbody>        								 
                </tbody>
                </table>
            </div>
          </div> 
          <div class="tab-pane fade" id="<?=$arr_accion[1]?>">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<!-- <label>Fecha de Resumen a Generar: </label>
									<div class="input-block-level">
										<input id="frmfec_cierre" type="date" class="form-control">
									</div>										 -->
								</div>
							</div>
							<div class="col-md-10 table-responsive" >
								<table id="datos_cierre" >
									<thead>
										<tr>
											<th style="width: 10%;"></th>
											<th>Fecha Cierre</th>
											<th>Turno</th>
											<th>Hora Cierre</th>
											<th>Usuario Cierre</th>
											<th>Total Tickets</th>
											<th>Total Cliente</th>
											<th>Total Efectivo</th>
											<th>Total Tarjetas</th>
											<th>Total Caja</th>
										</tr>
									</thead>
									<tbody>						          
									</tbody>
								</table>
							</div>
						</div>
					</div>             
        </div>
      </div>
      <!-- /tabs -->
    </div>
  <!-- Modal -->
<div class="modal fade" id="myModaldet" data-backdrop="static" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog"  role="document" style="width: 60%;">    
      <!-- Modal content-->
      <div class="modal-content" style="width: 100%;"> 
        <div class="modal-header" style="background-color: #4a226b; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true" style="color: #fff;">&times;</span></button>
          <h5 class="modal-title">Detalle de Venta:</h5>
        </div>
        <div  class="modal-body">
          <div class="row">
              <div class="col-md-12">
                <div class="row">
                      <div class="col-md-12">
                          <div class="form-group row">
                              <label class="col-form-label col-md-3" for="">VENTA Nº</label>
                              <span class="col-md-5 col-form-label" id="vd_num_doc"></span>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group row">
                              <label class="col-form-label col-md-3" for="">Empleado</label>
                              <span class="col-md-5" id="vd_empleado"></span>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group row">
                              <label class="col-form-label col-md-3" for="">Cliente</label>
                              <span class="col-md-5" id="vd_cliente"></span>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group row">
                              <label class="col-form-label col-md-3" for="">Mesa</label>
                              <span class="col-md-5" id="vd_mesa"></span>
                          </div>
                      </div>                                                
                  </div>

                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-form-label col-md-3" for="">Tipo Pago</label>
                            <span class="col-md-5" id="vd_tipo_pago"></span>
                      </div>
                  </div>
              </div>
              <!-- DETALLE DE VENTA -->
              <div id="lista_deta" class="col-md-12" style="">
                <div class="table-responsive" id="tabla_personal2">
                  <table id="datos_tabla_ajax" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>CATEGORIA</th>
                        <th>PRODUCTO</th>
                        <th>CANT.</th>
                        <th>VENTA</th>
                        <th>TOTAL S/</th>
                      </tr>
                    </thead>
                    <tbody id="rowdet">
                    </tbody>
                  </table>
                </div>
                <div class="col-md-12" style="margin-top: 0px; padding-right: 0px;">  
                  <div class="col-md-8" >&nbsp;</div>
                    <div class="col-md-4" style="padding: 0px;">
                        <table id="impprod" class="table table-striped" style="text-align: left;">
                        </table>
                    </div> 
                </div>
              </div>                                        
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div>

 <!-- Modal NOTA CREDITO-->
 <div class="modal fade" id="myModalNC" data-backdrop="static" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog"  role="document" style="width: 60%;">    
      <!-- Modal content-->
      <div class="modal-content" style="width: 100%;"> 
        <div class="modal-header" style="background-color: #4a226b; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true" style="color: #fff;">&times;</span></button>
          <h4 class="modal-title">Generar Nota de Crédito:</h4>
        </div>
        <div  class="modal-body">
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-form-label col-md-3" for="">VENTA Nº</label>
                      <span class="col-md-5 col-form-label" id="vnc_num_doc"></span>
                      <input type="hidden" id="vnc_id_transac">
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-form-label col-md-3" for="">Empleado</label>
                      <span class="col-md-5" id="vnc_empleado"></span>
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-form-label col-md-3" for="">Cliente</label>
                      <span class="col-md-5" id="vnc_cliente"></span>
                  </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-form-label col-md-3" for="">Mesa</label>
                  <span class="col-md-5" id="vnc_mesa"></span>
                </div>
              </div>  
              <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-form-label col-md-3" for="">Tipo Pago</label>
                      <span class="col-md-5" id="vnc_tipo_pago"></span>
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-form-label col-md-3" for="">Tipo de nota de crédito electrónica</label>
                    <div class="col-md-5">
                      <select class="form-control" id="vnc_codigo_nota" name="vnc_codigo_nota">
                        <option value="01">Anulación de la operación</option>
                        <option value="02">Anulación por error en el RUC</option>
                        <option value="03">Corrección por error en la descripción</option>
                        <!-- <option value="04">Descuento global</option>
                        <option value="05">Descuento por ítem</option>
                        <option value="06">Devolución total</option>
                        <option value="07">Devolución por ítem</option>
                        <option value="08">Bonificación</option>
                        <option value="09">Disminución en el valor</option>
                        <option value="10">Otros Conceptos </option>
                        <option value="11">Ajustes de operaciones de exportación</option>
                        <option value="12">Ajustes afectos al IVAP</option> -->
                      </select>
                    </div>  
              </div>                                    
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="ticketNC" class="btn btn-success" data-dismiss="modal">Generar Nota de Crédito</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div>

<!-- Modal Detalle Cierre Caja -->
<div class="modal fade" id="myModaldetcierre" data-backdrop="static" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog"  role="document" style="width: 60%;">    
      <!-- Modal content-->
      <div class="modal-content" style="width: 100%;"> 
        <div class="modal-header" style="background-color: #4a226b; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true" style="color: #fff;">&times;</span></button>
          <h5 class="modal-title">Detalle de Cambio de Turno:</h5>
        </div>
        <div  class="modal-body">
          <div class="row">
              <div class="col-md-3" style="">
                <div class="table-responsive">
                  <table id="datos_tabla_grupocierre" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>MEDIO PAGO</th>
                        <th>Total Venta</th>
                      </tr>
                    </thead>
                    <tbody id="datos_tabla_grupocierre_tbody">
                    </tbody>
                  </table>
                </div>
              </div>                                        
              <div class="col-md-7"> 
                <table id="datos_tabla_detallecierre" class="table-responsive" style="width:100%;" >
                  <thead>
                    <tr>
                      <th>MEDIO PAGO</th>
                      <th>FECHA REG.</th>
                      <th>NUM_DOC</th>
                      <th>Total Venta</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>  
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div>


<?php $this->load->view("partial/footer"); ?>