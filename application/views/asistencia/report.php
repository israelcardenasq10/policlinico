<?php $this->load->view("partial/header_excel"); ?>

<div class="container-fluid" style="padding-top: 35px;">
  <h2><?=strtoupper($module_id)?></h2>
  
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
          <select class="form-control select2" name="cbo_1" id="cbo_1"  style="">
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
            <?php if(@$lista != NULL){ ?>
              <button type="button" id="btnExportarExcel<?=$module_id?>" class="btn btn-success"/>Exportar Excel</button>
            <?php }else{ ?>
              <button type="button" id="btnProcAsist" class="btn btn-warning"/>Procesar Asistencia</button>
            <?php } ?>
            <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
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
                  <th>NRO. DOC.</th>
                  <th>NOMBRES</th>
                  <th>FECHA</th>
                  <th>TUR/MOD</th>
                  <th>H_IN</th>
                  <th>H_SA</th>
                  <th>INGRESO</th>
                  <th>SALIDA</th>
                  <th>M_LABORADAS</th>
                  <th>M_REFRIG</th>
                  <th>H_25</th>
                  <th>H_35</th>
                </tr>
              </thead>
              <tbody>
                <?php //var_dump($lista); 
                foreach($lista as $i=>$lis): ?>
                  <tr>
                    
                    <td><?=$lis->nro_doc?></td>
                    <td><?=$lis->nombres?></td>
                    <td><?=$lis->fecha?></td>
                    <td><? echo $lis->turno.' - '.$lis->modalidad; ?></td>
                    <td><?=$lis->h_ingreso?></td>
                    <td><?=$lis->h_salida?></td>
                    <td><?=$lis->m_laboradas?></td>
                    <td><?=$lis->m_refrigerio?></td>
                    <td><?=$lis->m_25?></td>
                    <td><?=$lis->m_35;?></td>
                  </tr>
                <?php endforeach;?>
              </tbody>
          </table>
    </div>
    <?php endif; ?>
  </div>
</div>


<?php $this->load->view("partial/footer_excel"); ?>