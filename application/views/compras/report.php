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
              <option value="Pendiente" <?php if(@$cbo_1 == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
              <option value="Cancelado" <?php if(@$cbo_1 == 'Cancelado') echo 'selected'; ?>>Cancelado</option>
              <option value="Anulado" <?php if(@$cbo_1 == 'Anulado') echo 'selected'; ?>>Anulado</option>
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
              <button type="button" id="btnExportarExcel<?=$module_id?>" class="btn btn-success"/><i class="glyphicon glyphicon-list-alt"></i> Excel</button>
              <button type="button" id="btnExportarCSV<?=$module_id?>" class="btn btn-info"/><i class="glyphicon glyphicon-list-alt"></i> CSV</button>
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
                  <th>F. COMPRA</th>
                  <th>DOCUMENTO</th>
                  <th>RUC</th>
                  <th>RAZON SOCIAL</th>
                  <th>MN</th>
                  <th>TOTAL</th>
                  <th>SITUACION</th>
                  <th>AFECTO</th>
                  <th>IGV</th>
                  <th>INAFECTO</th>
                  <th>TC</th>
                  <th>TOTAL S/.</th>
                  <th>TOTAL USD</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lista as $i=>$lis): ?>
                  <tr>
                    <td><?=$lis->Fecha?></td>
                    <td><?=$lis->Documento?></td>
                    <td><?=$lis->RUC?></td>
                    <td><?=$lis->Razon_Social_Nombre?></td>
                    <td><?=$lis->Mnd?></td>
                    <td><?=$lis->Total?></td>
                    <td><?=$lis->Situacion?></td>
                    <td><?=$lis->Afecto?></td>
                    <td><?=$lis->IGV?></td>
                    <td><?=$lis->Inafecto?></td>
                    <td><?=$lis->Cambio?></td>
                    <td><?=$lis->Total_Soles?></td>
                    <td><?=$lis->Total_USD?></td>
                  </tr>
                <?php endforeach;?>
              </tbody>
          </table>
    </div>
  <?php endif; ?>
  </div>




</div>

<?php $this->load->view("partial/footer_excel"); ?>