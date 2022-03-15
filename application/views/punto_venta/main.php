<?php $this->load->view("punto_venta/header"); ?>
<style>
/* div{border:solid 1px red} */
</style>
<div class="container-fluid">
	<div class="row" style="border-bottom: solid 1px #555; padding-bottom: 10px;">
        <div class="col-md-4"><img style="float: left; margin-right: 10px;" width="40" heigth="40" class="img-responsive" src="<?=base_url()?>public/images/admin.jpg"/>
        <label class="" style="margin-top: 10px; color: white;">Bienvenido : <br/><?=$username?></label>
        <a href="<?=base_url().'tpv/salir'; ?>" class="btn btn-default">Salir</a>
        </div>
		<div class="col-md-10 text-info"> </div>
	</div>
    
	
    <div class="row">
		<div class="col-md-4 text-info" style=""> AA</div>
        <div class="col-md-4 text-info" style=""> BB</div>
        <div class="col-md-4 text-info" style=""> CC</div>
	</div>
   
    <p></p>
	<div class="row">   
		<div class="col-md-3" style="">
			<ul class="nav nav-stacked">
			<?php foreach ($lis_categorias as $key => $value) { ?>
					<li class="">
							<a class="btn-lg btn-success" href="<?=base_url().'tpv/filtrarProductos/'.$value->id_categoria?>"><span class="glyphicon glyphicon-leaf" style="margin-right: 10px;"></span><?=$value->nombre?></a>
					</li>
			<?php } ?>
			</ul>
		</div>
		
		<div class="col-md-5" style="">
			<?php if($lis_productos != NULL): ?>
				<!-- <h2><?=$nom_categoria?></h2> -->
				<div class="row">
				<?php foreach ($lis_productos as $value) { ?>	
				  <div class="col-xs-6 col-md-4">
				  	<a href="<?=base_url().'tpv/obtenerDesProducto/'.$value->id_categoria.'/'.$value->id_producto?>">
				  		<div><img class="img-circle" src="<?=base_url().'public/images/productos/'.$value->imagen?>" style=""></div>
				  		<div style="height: 50px;"><?=$value->nombre?></div>
				  	</a>
				  </div>
				<?php } ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="col-md-4" style="color: white;">
			<h3>PRODUCTOS SELECCIONADOS</h3>
			<?php $ac_cant = 0;
				  $ac_venta = 0;
				  	if($des_tmppventa != NULL):
	                    foreach ($des_tmppventa as $value) { ?>
	                	<div class="row" style="font-size: 15px; padding: 10px 10px; border-top: 1px dotted #364C36;"> 
	                      <div class="col-xs-3"><?=$value->categoria?></div>
	                      <div class="col-xs-2"><?=$value->cantidad?></div>
	                      <div class="col-xs-3"><?=$value->nombre?></div>
	                      <div class="col-xs-3" style="text-align: right;">S/. <?=$value->venta?></div>
                          <div class="col-xs-1"><button class="btn btn-danger btn-sm">x</button></div>
	                    </div>
            <?php 		$ac_cant += $value->cantidad;
            			$ac_venta += $value->venta;
        				}
                  	endif; ?>

			<div class="row" style="font-weight: bold; font-size: 18px; padding: 10px 10px; border-top: 2px solid #CCC; margin-top: 20px; border-bottom: 2px solid #CCC;"> 
              <div class="col-xs-3"></div>
              <div class="col-xs-2"></div>
              <div class="col-xs-3">Sub Total</div>
              <div class="col-xs-4" style="text-align: right;">S/. <?=number_format($ac_venta/1.18, 2)?></div>            
			 
              <div class="col-xs-3"></div>
              <div class="col-xs-2"></div>
              <div class="col-xs-3">Igv</div>
              <div class="col-xs-4" style="text-align: right;">S/. <?=number_format((($ac_venta/1.18)*0.18), 2)?></div>			
			 
              <div class="col-xs-3"></div>
              <div class="col-xs-2"></div>
              <div class="col-xs-3">TOTAL</div>
              <div class="col-xs-4" style="text-align: right;">S/. <?=number_format($ac_venta, 2)?></div>
            </div>

            <div class="row" style="padding: 20px; 20px; text-align: center;">
            	<?php if($ac_venta > 0): ?>
				<a href="<?=base_url().'tpv/filtrarProductos/2003/'.$person_id?>" class="btn btn-danger">Cancelar Operacion</a>
				<input type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" name="btnfinalizar" id="btnfinalizar" value="Cobrar!">            	
                <input type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" name="btnfinalizar" id="btnfinalizar" value="Cobro Diferido">
            	<?php endif; ?>
            </div>            
		</div>

	</div>		
</div>
<?php $this->load->view("punto_venta/footer"); ?>