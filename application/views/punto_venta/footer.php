<footer>
	<div class="row" style="color: white;">
	<div class="col-xs-3">Fecha : <?=date('d-m-y H:i:s')?></div>
	<div class="col-xs-3"></div>
	<div class="col-xs-3">

	<?php if(@$nombre_cumple){ ?>
		Feliz Cumplea&ntilde;os : </span> <span style="background-color: black; padding: 0px 10px 0px 10px; color: orange;"><?=strtoupper($nombre_cumple)?>,<?=strtoupper($apelli_cumple)?></span>
	<?php } ?>
	</div>

	<div class="col-xs-3 text-right">TC : <?=$g_tc?></div>
	</div>
	</div>
</footer>

<!-- Placed at the end of the document so the pages load faster -->
<script src="<?=base_url()?>public/jquery/jquery-1.12.4.min.js"></script>
<script src="<?=base_url()?>public/bootstrap/js/bootstrap.min.js"></script>
<!-- Scroll -->
<script type="text/javascript" src="<?=base_url()?>public/pos/js/jquery.carouFredSel-6.2.1.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#cats').carouFredSel({
			auto: false,
			prev: '#prev2',
			next: '#next2',
			pagination: "#pager2",
			mousewheel: true,
			swipe: {
				onMouse: true,
				onTouch: true
			}
		});
	});
</script>


<!-- jQuery ui -->
<script src="<?=base_url()?>public/jquery/js/jquery-ui.min.js"></script>
<script src="<?=base_url()?>public/js/generales.js"></script>
<script src="<?=base_url()?>public/js/punto_venta.js"></script>

<!-- Datatables -->
<script src="<?=base_url();?>public/jquery/js/jquery.dataTables.min.js"></script>  
<!-- sweetalert-master -->
<script src="<?=base_url();?>public/jquery/js/sweetalert.min.js"></script>
<!-- SELECT 2 CDN -->
<script type="text/javascript" src="<?=base_url()?>public/js/select2.min.js"></script>

 </body>
</html>