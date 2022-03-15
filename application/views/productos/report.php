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
        <div class="col-sm-2"></div>
        <label for="" class="col-sm-2 control-label" style="text-align: right; padding: 10px;">Categoria: </label>
        <div class="col-sm-3">
          <select class="form-control" name="cbo_1" id="cbo_1"  style="">
              <option value="0">------------------ TODOS ------------------</option>
              <?php foreach($lista_categorias_prod as $i=>$lis): 
                    if(@$cbo_1 == $lis->id_categoria) :?> 
                      <option value="<?=$lis->id_categoria?>" selected><?=$lis->nombre?></option>
              <?php else: ?>
                      <option value="<?=$lis->id_categoria?>"><?=$lis->nombre?></option>
              <?php endif;
                    endforeach;?>
          </select>

          <input type="hidden" id="fecha1" name="fecha1" value="0">
          <input type="hidden" id="fecha2" name="fecha2" value="0">
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
                  <th>ID PROD.</th>
                  <th>ID CAT.</th>
                  <th>CATEGORIA</th>
                  <th>PRODUCTO</th>
                  <th>PRECIO INSUMO</th>
                  <th>PRECIO VENTA</th>
                  <th>MODIFICADO</th>
                </tr>
            </thead>
              <tbody>
                <?php foreach($lista as $i=>$lis): ?>
                  <tr>
                    <td><?=$lis->id_producto?></td>
                    <td><?=$lis->id_categoria?></td>
                    <td><?=$lis->categoria?></td>
                    <td><?=$lis->nombre?></td>
                    <td><?=number_format($lis->precio_insumo, 2)?></td>
                    <td><?=number_format($lis->precio_venta, 2)?></td>
                    <td><?=$lis->username?></td>
                  </tr>
                <?php endforeach;?>
              </tbody>
          </table>
    </div>
  <?php endif; ?>
  </div>




</div>

<?php $this->load->view("partial/footer_excel"); ?>