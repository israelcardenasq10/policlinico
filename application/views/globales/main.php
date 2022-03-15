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
            <li class="active"><a href="#actualizar" id="m_actualizar" data-toggle="tab"><?=ucwords('actualizar')?></a></li>
        </ul>
        <div id="msj_inci"></div>
        <div class="tab-content">   
            <div class="tab-pane active" id="actualizar">
		         		<div id="signupbox" class="mainbox col-md-10">
                        <div class="form-horizontal">
                            <form action="#" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                                <input type="hidden" id="id_global" name="id_global" value="<?=$bus_dato[0]->id_global?>">
                                <input type="hidden" id="id_file" name="id_file" value="<?=$bus_dato[0]->id_global?>">
                                <div class="col-md-12">
                                    <h2><span class="fa fa-edit fa-1x"></span> Editar <?=ucwords($module_id)?></h2>   
                                    <h5>Actualice la informacion del <?=ucwords($module_id)?></h5>
                                    <hr/>
                                    <div class="pull-right">
                                        <a class="btn btn-default" href="<?=base_url().'panel'?>"><i class=" fa fa-hand-o-left "></i> Regresar</a>
                                    </div> 
                                </div>
                                <div id="msj_valida" class="form-group col-md-12 text-center alert alert-danger" style="margin-top: 10px;"></div>                                    
                                <div class="col-md-12">
                                    <div class="form-group row">
                                      <label for="ruc" class="col-xs-2 col-form-label">RUC</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="ruc" id="ruc" type="text" value="<?=$bus_dato[0]->ruc?>">
                                      </div>
                                    </div> 
                                    <div class="form-group row">
                                      <label for="razon_social" class="col-xs-2 col-form-label">Razon Social</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="razon_social" id="razon_social" type="text" value="<?=$bus_dato[0]->razon_social?>">
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="nombre_corto" class="col-xs-2 col-form-label">Nombre Corto</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="nombre_corto" id="nombre_corto" type="text" value="<?=$bus_dato[0]->nombre_corto?>">
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="direccion" class="col-xs-2 col-form-label">Direccion</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="direccion" id="direccion" type="text" value="<?=$bus_dato[0]->direccion?>">
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="telefono" class="col-xs-2 col-form-label">Telefono</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="telefono" id="telefono" type="text" value="<?=$bus_dato[0]->telefono?>">
                                      </div>
                                    </div>    
                                    <div class="form-group row">
                                      <label for="email" class="col-xs-2 col-form-label">Email</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="email" id="email" type="text" value="<?=$bus_dato[0]->email?>">
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="email" class="col-xs-2 col-form-label">Web</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="web" id="web" type="text" value="<?=$bus_dato[0]->web?>">
                                      </div>
                                    </div>

                                    <div class="form-group row">
                                      <label for="tema" class="col-xs-2 col-form-label">Tema</label>
                                      <div class="col-xs-4">
                                        <label class="radio-inline"><input <?php if($bus_dato[0]->tema == 0) {echo "checked=checked";}?> type="radio" name="tema" id="tema_0" data-toggle="tooltip" title="" data-placement="top" value="0">Estandar</label>
                                        <label class="radio-inline"><input <?php if($bus_dato[0]->tema == 1) {echo "checked=checked";}?> type="radio" name="tema" id="tema_1" data-toggle="tooltip" title="" data-placement="top" value="1">Luna Azul</label>
                                        <label class="radio-inline"><input <?php if($bus_dato[0]->tema == 2) {echo "checked=checked";}?> type="radio" name="tema" id="tema_2" data-toggle="tooltip" title="" data-placement="top" value="2">Cafe</label>                                      
                                      </div>
                                    </div>   
                                    
                                    <div class="form-group row">
                                      <label for="" class="col-xs-2 col-form-label">Moneda</label>
                                      <div class="col-xs-2">
                                        <input class="form-control" name="simbolo_mn_empre" id="simbolo_mn_empre" type="text" value="<?=$bus_dato[0]->simbolo_mn_empre?>" readonly>
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="" class="col-xs-2 col-form-label">Igv</label>
                                      <div class="col-xs-2">
                                        <input class="form-control" name="igv_empre" id="igv_empre" type="text" value="<?=$bus_dato[0]->igv_empre?>">
                                      </div>
                                      <label for="" class="col-xs-1 col-form-label" style="padding: 0px; margin-left: -10px; margin-top: 5px;">%</label>
                                    </div>
                                    <div class="form-group row">
                                      <label for="" class="col-xs-2 col-form-label">TC</label>
                                      <div class="col-xs-2">
                                        <input class="form-control" name="tc" id="tc" type="text" value="<?=$bus_dato[0]->tc?>" readonly>
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="" class="col-xs-2 col-form-label"># Boleta</label>
                                      <div class="col-xs-2">
                                        <input class="form-control" name="num_bol_ven" id="num_bol_ven" type="text" value="<?=$bus_dato[0]->num_bol_ven?>">                                        
                                      </div>
                                      <div class="col-xs-3" style="padding-top: 8px; padding-left: 0px;">NOTA: Solo modificar de ser importante!</div>
                                    </div>

                                    <div class="form-group row">
                                      <label for="" class="col-xs-2 col-form-label">LogoTipo</label>
                                      <div class="col-xs-4">
                                        <form action="javascript:void(0);" enctype="multipart/form-data" id="frmArchivo" method="">
                                            <input type="file" class="filestyle" data-buttonText="Examinar.." id="archivo" name="archivo" value="">
                                        </form> 
                                      </div> 
                                      <label class="col-xs-2"><div><img src="public/images/<?=$bus_dato[0]->logotipo?>" width="75" height="35" /></div></label>
                                    </div>


                                    <div style="border: 1px solid silver; margin:15px;"></div>
                                    <small>Configuraci&oacute;n de Correos</small>
                                    <div class="form-group row">
                                      <label for="email" class="col-xs-2 col-form-label">Email Envio</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="mail_envio" id="mail_envio" type="text" value="<?=$bus_dato[0]->mail_envio?>">
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="email" class="col-xs-2 col-form-label">Alias Envio</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="mail_envio_alias" id="mail_envio_alias" type="text" value="<?=$bus_dato[0]->mail_envio_alias?>">
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="email" class="col-xs-2 col-form-label">Email Copia</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="mail_copia" id="mail_copia" type="text" value="<?=$bus_dato[0]->mail_copia?>">
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="email" class="col-xs-2 col-form-label">Email Responde</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="mail_responde" id="mail_responde" type="text" value="<?=$bus_dato[0]->mail_responde?>">
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="email" class="col-xs-2 col-form-label">Alias Responde</label>
                                      <div class="col-xs-4">
                                        <input class="form-control" name="mail_responde_alias" id="mail_responde_alias" type="text" value="<?=$bus_dato[0]->mail_responde_alias?>">
                                      </div>
                                    </div>

                                    <div style="border: 1px solid silver; margin:15px;"></div>
                                    <small>Punto de Venta</small>
                                    <div class="form-group row">
                                      <label for="" class="col-xs-2 col-form-label">Img en Producto?</label>
                                      <div class="col-xs-4" style="margin-top: -5px;">
                                          <?php if($bus_dato[0]->pv_prod_images == '1')
                                                  $checked_img = 'checked';
                                                else
                                                  $checked_img = ''; ?>
                                          <div class="checkbox">
                                            <label>
                                              <input type="checkbox" id="chkimaprod" name="chkimaprod" value="1" <?=$checked_img?>>
                                            </label>
                                          </div>
                                      </div>
                                    </div>

                                    <div class="form-group row">
                                      <label for="firma_ticket" class="col-xs-2 col-form-label">Firma Ticket</label>
                                      <div class="col-xs-5">
                                        <input class="form-control" name="firma_ticket" id="firma_ticket" type="text" value="<?=$bus_dato[0]->firma_ticket?>">
                                      </div>
                                    </div>

                                </div>
                                <div class="col-md-12" style="margin-top: 15px;">
                                    <input type="hidden" name="txtmodo" id="txtmodo" value="modificar" /></td>
                                    <button type="button" id="btnMod" name="btnMod" class="btn btn-primary">Grabar</button>
                                    <a href="<?=base_url().'panel'?>" id="hrefcancel" class="btn btn-default">Cancelar</a>
                                </div>
                            </form>
                        </div>	
						    </div>
		     	 	</div>
        </div>
      </div>
</div>
<?php $this->load->view("partial/footer"); ?>