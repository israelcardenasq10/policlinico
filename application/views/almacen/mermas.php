<?php //print_r($lista_categorias); ?>
<div id="signupbox" class="mainbox col-md-10">
    <div class="form-horizontal">
          <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
            <input type="hidden" class="id_mod" id="id" name="id">
              <div class="col-md-12">
                  <h2><span class="glyphicon glyphicon-eur"></span> Mermas</h2>   
                  <h5>Muestra el listado de Mermas</h5>
                  <hr/>
              </div>


                <div class="col-md-12">
                  <form action="#" method="post" enctype="multipart/form-data" name="frm4" id="frm4">      
                    <label for="" class="col-sm-1 control-label" style="text-align: right; padding: 10px;">Filtros: </label>
                    <div class="col-sm-3">
                      <select class="form-control" name="cbo_1" id="cbo_1"  style="">
                          <option value="0">----------- TODOS ------------</option>
                          <?php foreach($lista_servicios as $i=>$lis): 
                                if(@$cbo_1 == $lis->id_serv_prov) :?> 
                                  <option value="<?=$lis->id_serv_prov?>" selected><?=$lis->nombres?></option>
                          <?php else: ?>
                                  <option value="<?=$lis->id_serv_prov?>"><?=$lis->nombres?></option>
                          <?php endif;
                                endforeach;?>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
                        <input type="text" style="font-size: 14px;" class="form-control input_date" id="fecha1" name="fecha1" placeholder="Fecha Inicial" value="<?php echo @$fecha_1;?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group date" data-placement="top" data-align="top" data-autoclose="true">
                        <input type="text" style="font-size: 14px;" class="form-control input_date" id="fecha2" name="fecha2" placeholder="Fecha Final" value="<?php echo @$fecha_2;?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                      </div>
                    </div>
                    <div class="col-sm-4" style="padding-right: 0px;">
                        <button type="button" id="btnfiltrar<?=$module_id?>" class="btn btn-primary"/>Filtrar</button>
                        <a class="btn btn-default" href="<?=base_url().$module_id.'/listaralmmermas'?>">Limpiar</a>

                        <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                        <?php if(@$lista_mermas != NULL): ?>
                          <button type="button" id="btnExportarExcel<?=$module_id?>" class="btn btn-success"/><i class="glyphicon glyphicon-list-alt"></i> Excel</button>
                        <?php endif; ?>
                    </div>
                  </form>
              </div>
              
            <!--
              <div class="pull-right">
                  <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
              </div>
            -->
          </form>  
      </div>
      <div class="col-md-12" style="height: 10px;"></div> 

      <div class="col-md-12" id="data_listado">
          <div class="table-responsive" id="tabla_personal">
            <table id="datos_tabla" class="display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <!-- <th style="width: 30px;">ACCION</th> -->
                  <th>ID</th>
                  <th>PROVEEDOR</th>
                  <th>SERVICIO</th>
                  <th>MEDIDA</th>
                  <th>STOCK ANT.</th>
                  <th>STOCK MERMA</th>
                  <th>REGISTRO</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($lista_mermas as $i=>$lis): ?>
                        <tr style="">
                        <!--  
                          <td>
                            <button class="btn btn-success btn-sm" onclick="verOCGenerado('<?=$lis->id_prov?>', '<?=$lis->razon_social?>');" data-toggle="modal" href="#myModal"><span class="glyphicon glyphicon-pencil"></span></button>
                          </td>
                        -->
                          <td><?=$lis->id_merma?></td>
                          <td><?=$lis->razon_social?></td>
                          <td><?=$lis->nombres?></td> 
                          <td><?=$lis->valor?></td>
                          <td><?=$lis->stock_actual?></td> 
                          <td><?=$lis->stock_merma?></td> 
                          <td><?=$lis->fecha_registro?></td> 
                        </tr>
                <?php endforeach;?>                                      
              </tbody>
            </table>
          </div>
      </div>


      <!-- MODAL DE ORDEN DE COMPRA -->
      <!--     
          <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header" style="padding-bottom: 0px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h1 class="text-center" id="h1_proveedor"></h1>
                  </div>
                  <div class="modal-body" style="padding-bottom: 0px;">
                    <div class="form-horizontal" style="overflow: hidden;">
                      <form action="#" method="post" name="frm2" id="frm2">
                        <input type="hidden" name="person_id" id="person_id" value="">
                        <input type="hidden" name="hd_proveedor" id="hd_proveedor" value="">
                        <input type="hidden" name="num_oc" id="num_oc" value="">
                          <div class="col-md-12" id="div_serv_prov"></div>
                      </form>
                  </div>
                  <div id="msj_valida_d" class="form-group col-md-12 text-center alert alert-danger"></div>
                </div>
                <div class="modal-footer" style="text-align: center;">
                  <button type="button" id="btnsave_mod_oc" name="btnsave_mod_oc" class="btn btn-primary">Grabar</button>
                  <button type="button" id="btnclose_oc" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
              </div>
            </div>
          </div>
        -->
        <!-- -->

</div>  