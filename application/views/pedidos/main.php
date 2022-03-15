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
      <div class="tabbable tabs-left col-md-1">
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
      </div>
        <div class="tab-content col-md-11">
          <div class="tab-pane active" id="<?=$arr_accion[0]?>"> 
            <div class="col-md-12">
              <form id="buscarventa" method="POST" >                    
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
            <div class="col-md-12 table-responsive" id="tabla_pedidos">
              <table id="datos_tabla_ventas" class="display text-label-lg" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th style="width: 10%;"></th>
                    <th># PEDIDO</th>
                    <th>MESERO</th>
                    <th>HORA INI</th>
                    <th>HORA FIN</th>
                    <th>FECHA</th>
                    <th>ESTADO</th>
                    <th>ELIMINADO</th>
                    <th>TOTAL VENTA</th>
                    <th>MESA</th>
                  </tr>
                </thead>
                <tbody>        								 
                </tbody>
                </table>
            </div>
          </div> 
          <div class="tab-pane fade" id="<?=$arr_accion[1]?>">
						<div class="row">
            <div class="col-md-12">
              <form id="buscarventa2" method="POST" >                    
                <div class="col-md-2 form-group">
                  <label class="control-label">Fec. Inicio: </label>
                  <div class="input-group">
                    <input id="v_desde2" name="v_desde2" type="date" required class="form-control">
                  </div>
                </div>
                <div class="col-md-2 form-group">
                  <label class="control-label">Fec. Fin: </label>
                  <div class="input-group">
                    <input id="v_hasta2" name="v_hasta2" type="date" required class="form-control">
                  </div>
                </div>
                <div class="col-md-2 form-group">
                  <div class="input-group">
                    <input id="buscar2" value="Buscar" type="submit" class="btn btn-success" style="margin-top:2em;margin-left:0px" >
                  </div>
                </div>
              </form>
            </div>
            <div class="col-md-12 table-responsive" id="tabla_pedidos">
              <table id="datos_tabla_ventas_detalle" class="display text-label-lg" cellspacing="0" width="100%">
									<thead>
										<tr>
                    <th style="width: 5%;">Nro Ped</th>
                    <th style="width: 5%;">CORRELATIVO</th>
                        <th>ELIMINADO</th>
                        <th>FECHA</th>
                        <th>HOR-INI</th>
                        <th>HOR-FIN</th>
                        <th>CATEGORIA</th>
                        <th>PRODUCTO</th>
                        <th>NOTA COMANDA</th>
                        <th>SE COMANDÓ</th>
                        <th>SE DIVIDIO CUENTA</th>
                        <th>SE COBRÓ</th>
                        <th>CANTIDAD</th>
                        <th>TOTAL</th>
                        <th>FECHA CREADA</th>
                        <th>USR CREADOR</th>
                        <th>FECHA ACTUALIZADA</th>
                        <th>USR ACTUALIZADOR</th>
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
  <!-- Modal -->
<div class="modal fade" id="myModaldet" data-backdrop="static" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
    <div class="modal-dialog modal-lg"  role="document" style="width: 90%;">    
      <!-- Modal content-->
      <div class="modal-content" style="width: 100%;"> 
        <div class="modal-header" style="background-color: #4a226b; color: #fff;">
          <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true" style="color: #fff;">&times;</span></button>
          <h5 class="modal-title">Detalle de PEDIDO:</h5>
        </div>
        <div  class="modal-body">
          <div class="row">
              <!-- DETALLE DE VENTA -->
              <div id="lista_deta" class="col-md-12" style="">
                <div class="table-responsive" id="tabla_personal2">
                  <table id="datos_tabla_ajax" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th style="width: 5%;">CORRELATIVO</th>
                        <th>ELIMINADO</th>
                        <th>CATEGORIA</th>
                        <th>PRODUCTO</th>
                        <th>NOTA COMANDA</th>
                        <th>SE COMANDÓ</th>
                        <th>SE DIVIDIO CUENTA</th>
                        <th>SE COBRÓ</th>
                        <th>CANTIDAD</th>
                        <th>TOTAL</th>
                        <th>FECHA CREADA</th>
                        <th>USR CREADOR</th>
                        <th>FECHA ACTUALIZADA</th>
                        <th>USR ACTUALIZADOR</th>
                      </tr>
                    </thead>
                  </table>
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


<?php $this->load->view("partial/footer"); ?>