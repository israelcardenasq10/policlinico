<?php //print_r($lista_categorias); ?>
<div id="signupbox" class="mainbox col-md-10">
    <div class="form-horizontal">
          <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
            <input type="hidden" class="id_mod" id="id" name="id">
              <div class="col-md-12">
                  <h2><span class="glyphicon glyphicon-eur"></span> Ordenes de Compras</h2>   
                  <h5>Muestra el listado de Ordenes de Compras</h5>
                  <hr/>
              </div>      
              <div class="pull-right">
                  <a class="btn btn-default" href="<?=base_url().$module_id?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
              </div>
          </form>  
      </div>
      <div class="col-md-12" style="height: 10px;"></div> 

      <div class="col-md-12" id="data_listado">
          <div class="table-responsive" id="tabla_personal">
            <table id="datos_tabla" class="display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th style="width: 30px;">ACCION</th>
                  <th>CODIGO</th>
                  <th>PROVEEDOR</th>
                  <th>DOC. OC</th>
                  <th>FECHA OC</th>
                  <th>ESTADO</th>
                  <th>TOTAL</th>
                  <th>REGISTRO</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($lista_ordenes_compra as $i=>$lis): 
                          if($lis->estado == 'P')
                                $style = 'color: red;';
                              else if($lis->estado == 'A')
                                $style = 'color: #666; text-decoration: line-through;';
                              else 
                                $style = '';
                          ?>
                        <tr style="<?=$style?>">
                          <td>
                            <button class="btn btn-success btn-sm" onclick="verOCGenerado('<?=$lis->person_id?>', '<?=$lis->razon_social?>', '<?=$lis->num_oc?>', '<?=$lis->estado?>');" data-toggle="modal" href="#myModal"><span class="glyphicon glyphicon-pencil"></span></button>
                          </td>
                          <td><?=$lis->num_oc?></td>
                          <td><?=$lis->razon_social?></td>
                          <td><?=$lis->doc_oc?></td> 
                          <td><?=$lis->fecha_oc?></td> 
                            <?php
                              if($lis->estado == 'P')
                                $estado = 'Pendiente';
                              elseif($lis->estado == 'C')
                                $estado = 'Conciliado';
                              else
                                $estado = 'Anulado';
                            ?>
                          <td><?=$estado?></td> 
                          <td><?=$lis->total?></td> 
                          <td><?=$lis->fecha_registro?></td> 
                        </tr>
                <?php endforeach;?>                                      
              </tbody>
            </table>
          </div>
      </div>


      <!-- MODAL DE ORDEN DE COMPRA -->
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
                  <!-- <button type="button" id="btnempty_deta" name="btnempty_deta" class="btn btn-default">Limpiar</button> -->
                  <button type="button" id="btnclose_oc" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
              </div>
            </div>
          </div>
        <!-- -->

</div>  