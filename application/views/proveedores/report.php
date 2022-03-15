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
          <select class="form-control" name="cbo_1" id="cbo_1"  style="">
              <option value="0">-------- TODOS --------</option>                        
              <option value="PV" <?php if(@$cbo_1 == 'PV') echo 'selected'; ?>>PUNTO DE VENTA</option>
              <option value="OT" <?php if(@$cbo_1 == 'OT') echo 'selected'; ?>>OTROS</option>
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
    <p class="text-center">A continuación seleccione los filtros para su busqueda: </p>            
    <?php if(@$lista != NULL): ?>
    <div id="lista_excel">
          <table class="table table-striped">
            <thead>
                <tr>
                  <th>TIPO_DOC</th>
                  <th>RUC</th>
                  <th>RAZON SOCIAL</th>
                  <th>EMAIL</th>
                  <th>TEL. FIJO</th>
                  <th>CELULAR</th>
                  <th>DIRECCION</th>
                  <th>DISTRITO</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $i=>$lis): ?>
                  <tr>
                    <td><?=$lis->tipo_doc?></td>
                    <td><?=$lis->nro_doc?></td>
                    <td><?=$lis->razon_social?></td>
                    <td><?=$lis->email?></td>
                    <td><?=$lis->phone_number?></td>
                    <td><?=$lis->celular?></td>
                    <td><?=$lis->address_1?></td>
                    <td><?=$lis->address_2?></td>                    
                  </tr>
                <?php endforeach;?>
              </tbody>
          </table>
    </div>
  <?php endif; ?>
  </div>




</div>

<?php $this->load->view("partial/footer_excel"); ?>