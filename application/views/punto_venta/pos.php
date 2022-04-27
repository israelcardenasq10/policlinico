<?php $this->load->view("punto_venta/header"); ?>

<?php
	if(isset($allowed_modules_accion))
	{
	foreach ($allowed_modules_accion->result() as $key => $lis):
		if($lis->tipo == 'mante')
		{
			if($lis->accion == 'sup_vta'){
				$sup_vta = true; 
			}	
			if($lis->accion == 'cobro') {
				$cobro = true;	
			}	
			if($lis->accion == 'd_c') {
				$d_c = true;
			}		
			if($lis->accion == 'ventas') {
				$ventas = true;
			} 
      if($lis->accion == 'edit') {
				$edit = true;
			}
      if($lis->accion == 'camb_mp') {
				$camb_mp = true;
			}
		}
    
	endforeach;
	}
?>

<div class="container-fluid">
  <div class="row">          
      <div class="col-md-5" >
        <div class="row" style="margin-left: 0px; margin-right: 0px; color: white; margin-bottom: 5px;">
            <div class="col-xs-12" style="background-color: #707376; border-radius: 14px 14px 0px 0px; line-height: 30px;">
                <div class="col-xs-3 " style="padding-left: 3px;" >Encargado <span class="badge" id="td_encargado">-</span></div>
                <input type="hidden" id="hdid_tmp_cab" name="hdid_tmp_cab">
                <input type="hidden" id="hdid_suprimirvta" value="<?=@$sup_vta?>">                    
                <input type="hidden" id="hdid_edit_comanda" value="<?=@$edit?>">                    
                <input type="hidden" id="hdid_cambioPago" value="<?=@$camb_mp?>">                    
                <div class="col-xs-3 " >Hora Ini <span class="badge" id="td_hora_ini">-</span></div>
                <div class="col-xs-3 " ># Venta <span class="badge" id="td_nro_venta">-</span></div>
                <div class="col-xs-3 " ># Sala <span class="badge" id="td_nro_mesa">-</span></div>
            </div>
        </div>
        <div style="background-color: white; padding: 0px 15px 10px 15px">
            <div class="row">
              <div id="" class="col-xs-12" style="height: 280px;">
                  <table id="tb_lista_prod" class="table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead class="text-success" style="background-image: linear-gradient(to bottom, #eee, #ccc);">
                          <tr>
                              <th style="width: 5%">&nbsp;</th>
                              <th style="width: 5%">#</th>
                              <th style="width: 80%">Descripcion</th>
                              <!-- <th style="width: 40%">Nota Comanda</th> -->
                              <th style="width: 5%">P.Unit</th>
                              <th style="width: 5%">P.Total</th>
                          </tr>
                      </thead>
                      <tbody id="body_prod"></tbody>
                  </table>
              </div>
              <!-- <div id="div_nota_comanda" class="col-md-12" style="height: 45px;">
                <div class="col-md-8 text-right" style="">
                  <button type="button" id="btnnotaprod_1" onclick="agregarNotaProd('1')" class="btn btn-info" style="padding: 5px;">B.CON</button>
                  <button type="button" id="btnnotaprod_2" onclick="agregarNotaProd('2')" class="btn btn-info" style="padding: 5px;">S.VER</button>
                  <button type="button" id="btnnotaprod_3" onclick="agregarNotaProd('3')" class="btn btn-info">C.PAP</button>
                  <button type="button" id="btnnotaprod_4" onclick="agregarNotaProd('4')" class="btn btn-info">S.PAP</button>
                  <button type="button" id="btnnotaprod_5" onclick="agregarNotaProd('5')" class="btn btn-info" style="padding: 5px;">LL</button>
                  <button type="button" id="btnnotaprod_6" onclick="agregarNotaProd('6')" class="btn btn-info" style="padding: 5px;">ASECO</button>
                  <button type="button" id="btnnotaprod_7" onclick="agregarNotaProd('7')" class="btn btn-info" style="padding: 5px;">S.CHI</button>
                  <button type="button" id="btnnotaprod_8" onclick="agregarNotaProd('8')" class="btn btn-info" style="padding: 5px;">W.F.</button>
                </div>
                <div class="col-md-4 text-left" style="padding-left: 0px;">
                  <button type="button" id="btnnotaprod_otro" class="btn btn-info" style="padding: 5px;">OTRO</button>
                  <input type="text"class="form-control" id="txt_nota_prod" name="txt_nota_prod" style="text-transform: uppercase;" value=""/>
                </div>
              </div>  -->
            </div>
            <div class="row" style="border-top: double silver; border-bottom: solid gray; margin: 0px 0px 5px 0px; background-color: #F0F0F0;">
                <div class="col-md-9 text-left"><h3 style="margin-top: 5px;">TOTAL <?=$g_moneda?></h3></div>
                <div class="col-md-3 text-right">
                    <input type="hidden" id="hd_total_venta" name="hd_total_venta" value="0" />
                    <h3 style="margin-top: 5px;" id="h3_total_venta"><?=number_format(0, 2)?></h3>
                </div>
            </div>
            <div class="row" style="border-top: double silver; border-bottom: solid gray; margin: 0px 0px 5px 0px; background-color: #F0F0F0;">
                <div class="col-md-3 text-left"><h3 style="margin-top: 5px;">BUSCAR POR CODIGO:</h3></div>
                <div class="col-md-9 text-right">
                  <!-- <select class="js-data-example-ajax" name="bus_prod_codigo" id="bus_prod_codigo"></select>                   -->
                  <input class="form-control" style="text-transform: uppercase;" name="bus_prod_codigo" id="bus_prod_codigo" type="text" value="" placeholder="ESCRIBA..." onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-xs-6">
                    <table cellspacing="0">
                        <tbody>
                        <tr>
                            <td><input type="button" value="1" class="gris_num" id="tecla1" onclick="enviar_variables(this.value);"></td>
                            <td><input type="button" value="2" class="gris_num" id="tecla2" onclick="enviar_variables(this.value);"></td>
                            <td><input type="button" value="3" class="gris_num" id="tecla3" onclick="enviar_variables(this.value);"></td>
                            <td><input type="reset" value="C" class="gris_num" id="tecla_borrar" onclick="enviar_variables(this.value);"></td>
                        </tr>
                        <tr>
                            <td><input type="button" value="4" class="gris_num" id="tecla4" onclick="enviar_variables(this.value);"></td>
                            <td><input type="button" value="5" class="gris_num" id="tecla5" onclick="enviar_variables(this.value);"></td>
                            <td><input type="button" value="6" class="gris_num" id="tecla6" onclick="enviar_variables(this.value);"></td>
                            <td><input type="button" value="." class="gris_num" id="teclapunto" onclick="enviar_variables(this.value);"></td>
                        </tr>
                        <tr>
                            <td><input type="button" value="7" class="gris_num" id="tecla7" onclick="enviar_variables(this.value);"></td>
                            <td><input type="button" value="8" class="gris_num" id="tecla8" onclick="enviar_variables(this.value);"></td>
                            <td><input type="button" value="9" class="gris_num" id="tecla9" onclick="enviar_variables(this.value);"></td>
                            <td><input type="button" value="0" class="gris_num" id="tecla0" onclick="enviar_variables(this.value);"></td>
                        </tr>
                      </tbody>
                    </table>
                    <input type="hidden" name="hd_val_calculadora" id="hd_val_calculadora" value="1" />
                </div>
                <div class="col-xs-5" style="padding-right: 15px;">    
                  <div class="row" >            
                    <?php if(@$cobro == 1): ?>
                      <div class="col-xs-12" style="padding-right: 0px;padding-left: 0px;padding-bott: 0px;padding-bottom: 0.25em;">
                        <button type="button" id="btnsave" name="btnsave" class="btn btn-block btn-success active center-block" style=""><h5><i class="glyphicon glyphicon-usd"></i> COBRAR</h5></button>
                      </div>
                      <?php endif; ?>                    
                      <!-- <div class="col-xs-4" style="padding-right: 0px;padding-left: 0px;">
                          <button type="button" id="btncomanda" name="btncomanda" class="btn btn-block btn-info active center-block"><h5>GENERAR</br>COMANDA</h5></button>
                      </div> -->
                      <?php if(@$d_c == 1): ?>
                      <div class="col-xs-6" style="padding-right: 0.2em;padding-left: 0.2em;">
                          <button type="button" id="btndividir_cuenta" name="btndividir_cuenta"  data-toggle="modal" href="#myModalDC" class="btn btn-block btn-primary active center-block"><h5>DIVIDIR </br>CUENTA</h5></button>
                      </div>
                      <div class="col-xs-6" style="padding-right: 0px;padding-left: 0px;">
                          <button type="button" id="btncambiar_mesa" name="btncambiar_mesa"  data-toggle="modal" href="#myModalCM" class="btn btn-block btn-warning active center-block"><h5>CAMBIAR </br>SALA</h5></button>
                      </div>
                      <?php endif; ?>
                    </div>
                </div>
            </div>
            <div id="myModalDC" class="modal fade" role="dialog">
              <div class="modal-dialog" style=""> 
                  <div class="modal-content">
                  <div class="modal-header btn-primary active" style="padding-bottom: 0px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">DIVISION DE MESA - SELECCIONE PRODUCTOS</h4>
                  </div>
                  <div class="modal-body" style="">
                    <div id="div_dividir_cuenta" class="form-horizontal" style="overflow: hidden;">                             
                    <form action="#" method="post" name="frm2" id="frm2">                              
                      <table id="tb_lista_prod_dc" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          <thead class="text-success" style="background-image: linear-gradient(to bottom, #eee, #ccc);">
                              <tr>
                                  <th style="width: 16%"></th>
                                  <th style="width: 10%">#</th>
                                  <th style="width: 50%">Descripcion</th>
                                  <th style="width: 12%">P.Unit</th>
                                  <th style="width: 12%">P.Total</th>
                              </tr>
                          </thead>
                          <tbody id="body_prod_dc"></tbody>
                      </table>
                    </form>                             
                    </div>
                  </div>
                  <div class="modal-footer" style="text-align: center;">
                    <button type="button" id="btncobrar_dc" name="btncobrar_dc" class="btn btn-primary active" style="padding: 10px"><span class="glyphicon glyphicon-usd"></span> DIVIDIR => COBRAR</button>
                    <button type="button" id="btnlimpiar_dc" class="btn btn-default active" style="padding: 10px"><span class="glyphicon glyphicon-arrow-up"></span> LIMPIAR</button>
                    <button type="button" id="btnclose_dc" class="btn btn-default active" data-dismiss="modal" style="padding: 10px"><span class="glyphicon glyphicon-share-alt"></span> SALIR</button>
                  </div>
                  </div>
              </div>
            </div>
            <div id="myModalCM" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg" style="width: 80%;">
                  <div class="modal-content">
                  <div class="modal-header btn-primary active" style="padding-bottom: 0px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">CAMBIO DE MESAS</h4>
                  </div>
                  <div class="modal-body" style="">
                    <!-- <div id="div_dividir_cuenta" class="form-horizontal" style="overflow: hidden;">                             
                    </div> --> 
                    <div id="div_dividir_mesas" style="overflow: hidden;"></div>

                  </div>
                  <div class="modal-footer" style="text-align: center;">
                    <!-- <button type="button" id="btncambia_cm" name="btncambia_cm" class="btn btn-primary active" style="padding: 10px"><span class="glyphicon glyphicon-usd"></span> ACTUALIZAR</button> -->
                    <button type="button" id="btnclose_cm" name="btnclose_cm" class="btn btn-default active" data-dismiss="modal" style="padding: 10px"><span class="glyphicon glyphicon-share-alt"></span> SALIR</button>
                  </div>
                  </div>
              </div>
            </div>
            <div class="row">
                  <div class="col-xs-12" style="">
                      <div class="btn-group btn-group-justified">
                          <div class="btn-group">                                  
                              <button id="btn_refres" type="button" class="btn btn-nav" style="padding: 6px;">
                                  <span class="glyphicon glyphicon-refresh fa-2x"></span>
                                  <p class="hidden-xs">Actualizar</p>
                              </button>
                              </a>
                          </div>
                          <div class="btn-group">                                  
                              <button id="btn_ventasDia" type="button" class="btn btn-nav" style="padding: 6px;">
                                  <span class="glyphicon glyphicon-download-alt fa-2x"></span>
                                  <p class="hidden-xs">Export</p>
                              </button>
                              </a>
                          </div>
                  <?php if(@$sup_vta==true){ ?>
                          <div class="btn-group">
                              <button type="button" id="btn_borrar_venta" class="btn btn-nav">
                                  <span class="glyphicon glyphicon-trash fa-2x"></span>
                                  <p>Supr Vta</p>
                              </button>
                          </div>	
                          <div class="btn-group">
                              <button type="button" id="btn_limp_mesas" class="btn btn-nav">
                              <span class="glyphicon glyphicon-random fa-2x"></span>
                                  <p>Limpiar Mesas</p>
                              </button>
                          </div>
                  <?php } if(@$ventas == true){ ?>							
                          <div class="btn-group">
                              <button type="button" id="btn_mostrar_ventas" class="btn btn-nav">
                                  <span class="glyphicon glyphicon-leaf fa-2x"></span>
                                  <p>Ventas</p>
                              </button>
                          </div>
                  <?php } ?>
                          <div class="btn-group">
                              <button type="button" id="btn_pre_venta" class="btn btn-nav" disabled style="cursor: auto;">
                                  <span class="glyphicon glyphicon-print fa-2x"></span>
                                  <p>Pre Vta</p>
                              </button>
                          </div>
                          <div class="btn-group">
                              <button type="button" id="btnsalir" class="btn btn-nav">
                                  <span class="glyphicon glyphicon-log-out fa-2x"></span>
                                  <p>Salir</p>
                              </button>
                          </div>
                      </div>
                  </div>
            </div>
        </div>
      </div>
      <div class="col-md-7">
        <div id="exTab3" class="">
          <ul  class="nav nav-pills">
            <li class="active"><a href="#empleados" id="tab_empleado" data-toggle="tab" class="glyphicon glyphicon-home" style="color: cyan;"> Empleados</a></li>
            <li class=""><a href="#mesas" id="tab_mesas" data-toggle="tab" class="glyphicon glyphicon-user boton_desactiva" style="color: cyan;"> Sala</a></li>
            <li class=""><a href="#categorias" id="tab_categorias" data-toggle="tab" class="glyphicon glyphicon-envelope boton_desactiva" style="color: cyan;"> Productos</a></li>
          </ul>
          <div class="tab-content">
                <div class="tab-pane active" id="empleados">
                <h1>Seleccione un Usuario!</h1>
                <?php foreach ($lis_empleados as $key => $lis) { ?>
                        <div class="col-md-3 col-xs-4 text-center" style="padding-right: 5px; padding-left: 5px;">
                            <div class="thumbnail">
                                <img class="img-rounded" style="cursor: pointer;" src="<?=base_url()?>public/images/users/empleados/<?=$lis->imagen?>" alt="" width="120" onclick="identificarEmpleado('<?=$lis->id_emple?>');">
                                <div class="caption">
                                    <?php $arr_nom = explode(' ', $lis->first_name); ?>
                                    <h5><?=strtoupper($arr_nom[0]);?></h5>
                                </div>
                            </div>
                        </div>
                <?php } ?>
                </div>
                <div class="tab-pane " id="mesas"></div>
                <div class="tab-pane" id="categorias">
                    <div id="pos">
                    </div>
                    <div class="tab-content" style="overflow-y: scroll; height: 520px;">
                        <div class="row">
                            <div id="div_productos">
                            </div>
                        </div>
                    </div>
                </div>
            <div class="hidden-sm hidden-xs" style=""></div>
          </div>
        </div>
        <div id="terminar" >
            <div class="row">
                  <div class="panel panel-default custom"><span class="glyphicon glyphicon-usd"></span> PAGO EN CAJA</div>
                  <div class="col-xs-6">
                      <div class = "input-group input-group-lg">
                            <span class = "input-group-addon" style="font-weight: bold;">Total&nbsp; <?=$g_moneda?></span>
                            <input type = "text" id="txttotal_venta" name="txttotal_venta" class = "form-control text-right" placeholder = "" value="" readonly="true"/>
                      </div>
                      <p></p>
                      <div class = "input-group input-group-lg">
                          <span class = "input-group-addon" style="font-weight: bold;">Pag&oacute;&nbsp; <?=$g_moneda?></span>
                            <input type = "text" id="txtpago_cliente" name="txtpago_cliente" class = "form-control text-right"  value="0.00" readonly />
                      </div>
                        <p></p>
                        <div class = "input-group input-group-lg">
                            <span class = "input-group-addon" style="font-weight: bold; padding-right: 11px;">Vuelto <?=$g_moneda?></span>
                            <input type = "text" id="txtvuelto_cliente" name="txtvuelto_cliente" class = "form-control text-right"  value="0.00" readonly="true">
                      </div>
                  </div>
                  <div class="col-xs-6"><br />
                      <!-- <table cellspacing="0">
                          <tbody>
                          <tr>
                              <td><input type="button" value="1" class="gris_num" id="tecla_p1" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="button" value="2" class="gris_num" id="tecla_p2" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="button" value="3" class="gris_num" id="tecla_p3" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="reset" value="C" class="gris_num" id="tecla_borrar" onclick="enviar_variables_pago(this.value);"></td>
                          </tr>
                          <tr>
                              <td><input type="button" value="4" class="gris_num" id="tecla_p4" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="button" value="5" class="gris_num" id="tecla_p5" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="button" value="6" class="gris_num" id="tecla_p6" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="button" value="." class="gris_num" id="tecla_punto" onclick="enviar_variables_pago(this.value);"></td>
                          </tr>
                          <tr>
                              <td><input type="button" value="7" class="gris_num" id="tecla_p7" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="button" value="8" class="gris_num" id="tecla_p8" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="button" value="9" class="gris_num" id="tecla_p9" onclick="enviar_variables_pago(this.value);"></td>
                              <td><input type="button" value="0" class="gris_num" id="tecla_p0" onclick="enviar_variables_pago(this.value);"></td>
                          </tr>
                        </tbody>
                      </table> -->
                  </div>
            </div>
              <p></p>
              <div class="row">
                  <div class="panel panel-default custom"><span class="glyphicon glyphicon-briefcase"></span> FORMA DE PAGO
                  </div>
                  <div class="row">
                    <form action="#" type="POST" name="frmtmpMP" id="frmtmpMP">
                      <div class="col-xs-3">
                        <select class="form-control" id="cbo_tpmpago" name="cbo_tpmpago" required>
                        <option value="1">Efectivo</option>
                        <option value="2">Visa</option>
                        <option value="3">MasterCard</option>
                        <option value="4">Diners Club</option>
                        <option value="5">American Express</option>
                        <option value="7">Yape</option>
                        <option value="8">Transferencia</option>
                        </select>
                      </div>
                      <div class="col-xs-3">
                        <input class="form-control" required type="text" name="txt_monto_pago" id="txt_monto_pago" onkeypress="return filterFloat(event,this);" >
                      </div>
                      <div class="col-xs-2">
                        <input class="form-control btn btn-success" type="submit" value="Añadir" >
                      </div>
                    </form>                                            
                  </div>
                  <div class="panel panel-default col-xs-10">
                    <table id="tbl_mediopago" class="table scroll table-bordered table-striped dt-responsive text-center">
                        <thead>
                            <tr>
                                <th width="55%">Tipo Pago</th>
                                <th width="30%">Monto</th>
                                <th width="15%">Accion</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_mediopago">
                        </tbody>
                    </table>
                  </div>                    
              </div>
              <p></p>
              <div class="row" id="sty_fpag">
                  <div class="panel panel-default custom"><span class="glyphicon glyphicon-print"></span> IMPRIMIR TICKET</div>
                    <div class="col-md-12">
                      <form action="#" method="post" enctype="multipart/form-data" name="frmcliente" id="frmcliente">
                        <div class="col-xs-4">
                          <label for="tpo_doc" class="text-right">Tipo Doc. (*)</label>
                          <select class="form-control" id="tpo_doc" name="tpo_doc">
                          <option value="DNI">DNI</option>
                          <option value="RUC">RUC</option>
                          <option value="CEXT">Carnet de extranjería</option>
                          <option value="PASS">PASAPORTE</option>
                          </select>
                        </div>
                        <div class="col-xs-4">
                        <label for="nro_doc" class="text-right">RUC / DNI (*)</label>
                          <input class="form-control" style="text-transform: uppercase;" name="nro_doc" id="nro_doc" type="text" value="" placeholder="ESCRIBA..." onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        <div class="col-xs-1" style="margin-top: 1.8em">
                          <button type="button" id="buscarClienteSumat" name="buscarClienteSumat" class="btn btn-info" >SUNAT</button>
                        </div>                        
                        <div class="col-xs-6">
                          <label for="razon_social" class="text-right">Razon Social /Apellidos y Nombres (*)</label>
                          <input class="form-control" style="text-transform: uppercase;" name="razon_social" id="razon_social" type="text" value="" placeholder="Apellidos y Nombres">
                        </div>
                        <div class="col-xs-1" style="margin-top: 1.8em">
                          <button type="button" id="btncrearCliente" name="btncrearCliente" class="btn btn-default" disabled>Guardar</button>
                        </div>                        
                        <div class="col-xs-6">
                          <input name="email" id="email" type="hidden" value="" placeholder="cliente@dominio.com">
                        </div>
                      </form>
                    </div>

                    <div class="col-xs-12 checkbox">
                        <label class="">
                          <div class="radio">
                              <label style="font-size: 1.5em">
                                  <input type="radio" id="rb<?=$lista_documentos[0]->id_serie?>" name="rbdoc_pago" value="<?=$lista_documentos[0]->id_serie?>" >
                                  <span class="cr" style="background-color: #286090;"><i class="cr-icon fa fa-circle"></i></span>
                                  <?=$lista_documentos[0]->descripcion?>
                              </label>
                          </div>
                        </label>
                        <label class="">
                          <div class="radio">
                              <label style="font-size: 1.5em">
                                  <input type="radio" id="rb<?=$lista_documentos[1]->id_serie?>" name="rbdoc_pago" value="<?=$lista_documentos[1]->id_serie?>" >
                                  <span class="cr" style="background-color: #286090;"><i class="cr-icon fa fa-circle"></i></span>
                                  <?=$lista_documentos[1]->descripcion?>
                              </label>
                          </div>
                        </label>

                        <label class="">
                          <div class="radio">
                              <label style="font-size: 1.5em">
                                  <input type="radio" id="rb<?=$lista_documentos[2]->id_serie?>" name="rbdoc_pago" value="<?=$lista_documentos[2]->id_serie?>" >
                                  <span class="cr" style="background-color: #286090;" onclick="clientevacio()"><i class="cr-icon fa fa-circle"></i></span>
                                  <?=$lista_documentos[2]->descripcion?>
                              </label>
                          </div>
                        </label>
                        <input type="hidden" id="hdid_cliente" name="hdid_cliente">
                    </div>

                    <div class="col-xs-12">
                      <!-- <div class="col-xs-3">
                        <button type="button" class="btn btn-info " data-toggle="modal" href="#myModal"><h5><span class="glyphicon glyphicon-user" ></span>Ver Datos Cliente</h5></button>
                      </div> -->
                      <div class="col-xs-3">
                        <div>
                          <button type="button" class="btn btn-success " id="btngenerarVentaPrint"><h5><span class="glyphicon glyphicon-print"></span> PRINT VENTA</h5></button>
                        </div>
                      </div>
                      <div class="col-xs-3 " >
                        <div class="">
                          <button class="btn btn-danger "  id="btnretornarCarritoVenta"><h5><span class="glyphicon glyphicon-arrow-left"></span> RETORNAR</h5></button>
                        </div>
                      </div>
                    </div>
                </div>
              <p></p>
        </div>
        <div id="ventas_dia">
          <div class="row">
            <div class="panel panel-default custom"><span class="glyphicon glyphicon-usd"></span> LISTADO DE VENTA DIARIA</div>
              <div class="col-md-12" style="background: #FFF;">
                <div class="table-responsive" id="tabla_personal2"  style="overflow-y: scroll; height: 450px;">
                  <table id="datos_tabla_ajax" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th width="3%"></th>
                        <th># VTA</th>
                        <th>Fecha</th>
                        <th>NroDoc</th>
                        <th>Cliente</th>
                        <th>TP</th>
                        <th>CIERRE</th>
                        <!-- <th>NETO (S/)</th> -->
                        <!-- <th>IGV (S/)</th> -->
                        <th>TOTAL (S/)</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="col-md-12" style="margin-top: 4px; padding-right: 0px;">
                  <div class="col-md-7" >&nbsp;</div>
                  <div class="col-md-3" style="padding-left: 20px;">                       
                    <h3><b>TOTAL VENTA</b></h3>                                       
                  </div>
                  <div class="col-md-2" style="padding: 0px;">
                    <h3 id="c_subtotal"></h2> 
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:25px; padding-top: 10px;">
                <div class="col-xs-4">
                  <button type="button" class="btn btn-default " id="btncerrar_caja" onclick="imprimirCierreCaja();">
                    <h5><span class="glyphicon glyphicon-print"></span> PRINT CIERRE CAJA</h5>
                  </button>
                </div>           
                <div class="col-xs-4 " >
                  <button type="button" class="btn btn-primary " id="btnprint_cambio_turno" onclick="imprimirCambioTurno();" >
                    <h5><span class="glyphicon glyphicon-print"></span> CAMBIO DE TURNO</h5>
                  </button>
                </div>
                <div class="col-xs-4" >
                  <button type="button" class="btn btn-danger btn-block"  id="btnretornarCarritoVenta_2" onclick="retornarCajaVenta();"> <h5><span class="glyphicon glyphicon-arrow-left"></span> RETORNAR</h5></button>
                </div>
            </div>         
          </div>
        </div>
        <div id="cierres_caja">
        </div>
      </div>  
  </div>
</div>

<div id="myModalCantidad" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="myformCambioCantidad">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><b>Cambiar Cantidad de los Platos Comandadas</b></h4>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="newCantidad" class="col-sm-4 col-form-label">Ingrese la Nueva Cantidad</label>
            <div class="col-sm-7">
              <input type="hidden" id="id_tmp_cab">
              <input type="hidden" id="correlativo">
              <input type="number" max="10" class="form-control" id="newCantidad" required >
            </div>
          </div>
          <div class="form-group row">
            <label for="comentario" class="col-sm-4 col-form-label">ingrese Sustento del Cambio</label>
            <div class="col-sm-7">
              <input type="Text" maxlength="500" class="form-control" id="comentario" required>
            </div>
          </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" id="btn_changecant" class="btn btn-success" >Guardar y Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="myModalMP" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="myformCambioMP">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><b>Cambiar Medio Pago</b></h4>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-sm-4">
              <label for="newMP" class="col-form-label">Seleccione el Medio de Pago correcto</label>
              <input type="hidden" id="mp_id_transac">
              <input type="hidden" id="mp_id_transac_mp">
              <select id="mp_id_tp_mp" name="mp_id_tp_mp" class="form-control">
                  <?php  
                  foreach($lis_tpagos as $lis){
                    echo '<option value="'.$lis->id_tp.'">'.$lis->tipo_pago.'</option>';
                  } ?>                
              </select>
            </div>
            <div class="col-sm-8">
              <label for="newMP_coment" class="col-form-label">Motivo del Cambio</label>
              <textarea type="txt" id="newMP_coment" class="form-control" rows="3"></textarea>
            </div>
          </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" id="btn_changemp" class="btn btn-success" >Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $this->load->view("punto_venta/footer"); ?>
