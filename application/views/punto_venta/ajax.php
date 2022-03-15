<?php if($v_ajax === 'tab_categorias'): ?>
    <div class="list_carousel">
      <ul id="cats">
        <?php foreach($lis_categorias as $key => $lis ): ?>
                <li>
                	<button id="category<?=$lis->id_categoria?>" type="button" value='' 
					class='f<?=$key>15?10:$key ?>' onclick="verProductos('<?=$lis->id_categoria?>')" ><?=$lis->nombre?>
					</button>
                </li>
        <?php endforeach;?>
      </ul>
      <a class="prev" id="prev2" href="#"><span>prev</span></a>
      <a class="next" id="next2" href="#"><span>next</span></a>
    </div>


	<!-- Scroll 2 -->
	<script type="text/javascript" language="javascript" src="<?=base_url()?>public/pos/js/jquery.carouFredSel-6.2.1.js"></script>

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
<?php endif; ?>
