<?php $this->load->view("partial/header_excel"); ?>

<div class="container-fluid" style="padding-top: 35px;">
  <!--<h2><?=strtoupper($module_id)?></h2>-->
  
  <p>
  <ol class="breadcrumb">
	<li><a href="<?=base_url()?>panel">Inicio</a></li>
	<li class="active"><?=$titulo_main?></li>
  </ol>
  </p>

  <div class="col-md-12">
      <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">      
        <label for="" class="col-sm-2 control-label" style="text-align: right; padding: 10px;">Filtros: </label>
        <div class="col-sm-2">
          <select class="select2 form-control" name="cbo_1" id="cbo_1"  style="">
              <option value="0">-------- TODOS --------</option>
              <?php foreach($lista_empleados as $i=>$lis):
                    if(@$cbo_1 == $lis->id): ?>
                        <option value="<?=$lis->id?>" selected><?=$lis->nombres?></option>
                  <?php else:?>
                        <option value="<?=$lis->id?>"><?=$lis->nombres?></option>
                  <?php endif;                                                                
                    endforeach;?>
          </select>
        </div>
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
        <div class="col-sm-4">
            <button type="button" id="btnfiltrar<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
            <button type="button" id="btnlimpiar<?=$module_id?>" class="btn btn-default"/>Limpiar</button>
            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
            <?php if(@$lista != NULL): ?>
              <button type="button" id="btnExportarExcel<?=$module_id?>" class="btn btn-success"/>Exportar Excel</button>
            <?php endif; ?>
        </div>
      </form>
  </div>



  <div class="col-md-12">
    <p class="text-center">A continuaci??n seleccione los filtros para su busqueda: </p>            
    <?php if(@$lista != NULL): ?>
    <div id="lista_excel">
          <table class="table table-striped">
            <thead>
                <tr>
                  <th># DOC</th>
                  <th>NOMBRES</th>
                  <th>APELLIDOS</th>
                  <th>EMAIL</th>
                  <th>DIRECCION</th>
                  <th>BANCO</th>
                  <th>NRO. CUENTA</th>
                  <th>SIS. PEN</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $i=>$lis): ?>
                  <tr>
                    <td><?=$lis->nro_doc?></td>
                    <td><?=$lis->first_name?></td>
                    <td><?=$lis->last_name?></td>
                    <td><?=$lis->email?></td>
                    <td><?=$lis->address_1?></td>
                    <td><?=$lis->banco?></td>
                    <td><?=$lis->nro_cuenta?></td>
                    <td><?=$lis->sistema_pension?></td>                    
                  </tr>
                <?php endforeach;?>
              </tbody>
          </table>
    </div>
  <?php endif; ?>
  </div>




</div>

<?php $this->load->view("partial/footer_excel"); ?>