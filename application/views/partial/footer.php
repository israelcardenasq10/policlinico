<footer class="footer">
	<div class="container">
        <p class="text-muted">
        	<!-- <small style="color: #777;">
        	@Todos los derechos reservados <a href="http://www.elgrancharlee.com" target="_blank">elgrancharlee.com</a>
        	</small> -->
        </p>
      </div>
</footer>

<!-- Placed at the end of the document so the pages load faster -->
<script src="<?=base_url()?>public/jquery/jquery-1.12.4.min.js"></script>
<script src="<?=base_url()?>public/jquery/js/moment.min.js"></script>
<script src="<?=base_url()?>public/bootstrap/js/bootstrap.min.js"></script>
<!-- SELECT 2 CDN -->
<script type="text/javascript" src="<?=base_url()?>public/js/select2.min.js"></script>
<!-- ClockPicker -->
<!-- <script type="text/javascript" src="<?=base_url();?>public/clockpicker/bootstrap-clockpicker.min.js"></script> -->

<!-- jQuery ui -->
<!-- <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->

<script src="<?=base_url()?>public/js/function.js"></script>
<script src="<?=base_url()?>public/js/generales.js"></script>
<script src="<?=base_url()?>public/js/<?=$module_id?>.js"></script>

<?php if(@$module_id == 'inventario' || @$module_id == 'productos'): ?>
	<script src="<?=base_url()?>public/js/<?=$module_id?>_cat.js"></script>
<?php endif; ?>

<?php if(@$module_id == 'panel'): ?>
	<script type="text/javascript" src="<?=base_url()?>public/chart/dist/Chart.bundle.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            window.json_graf_1 = <?=$lista_graf_1?>; // Variable global de tipo JSON para Gráfico 1.
            window.json_graf_2 = <?=$lista_graf_2?>;
            window.json_graf_3 = <?=$lista_graf_3?>;
        });
    </script>

    <script type="text/javascript" src="<?=base_url()?>public/js/grafico_pie.js"></script>
    <script type="text/javascript" src="<?=base_url()?>public/js/grafico_line.js"></script>
    <script type="text/javascript" src="<?=base_url()?>public/js/grafico_bar.js"></script> 
<?php endif; ?>

<!-- DatePicker --> 
<!--<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>-->
<script src="<?=base_url()?>public/bootstrap/js/bootstrap-datepicker.js"></script>

<!-- DataTables -->
<!--<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>-->
<script src="<?=base_url()?>public/jquery/js/jquery.dataTables.min.js"></script>        
<!-- <script src="https://cdn.datatables.net/1.10.9/js/dataTables.jqueryui.min.js"></script> -->

<!-- botones de exportacion -->
<script src="<?=base_url()?>public/jquery/js/Datatable/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>public/jquery/js/Datatable/buttons.bootstrap.min.js"></script>
<script src="<?=base_url()?>public/jquery/js/Datatable/jszip.min.js"></script>
<script src="<?=base_url()?>public/jquery/js/Datatable/pdfmake.min.js"></script>
<script src="<?=base_url()?>public/jquery/js/Datatable/vfs_fonts.js"></script>
<script src="<?=base_url()?>public/jquery/js/Datatable/buttons.html5.min.js"></script>
<script src="<?=base_url()?>public/jquery/js/Datatable/buttons.print.min.js"></script>

<!-- sweetalert-master -->
<script src="<?=base_url()?>public/jquery/js/sweetalert.min.js"></script>
<!-- <script src="<?=base_url()?>public/jquery/sweetalert2.10.js"></script> -->

<!-- Upload de Archivos a la Nube -->
<script src="<?php echo base_url();?>public/js/upload.js"></script>

<!-- CheckBox On|Off -->
<script src="<?=base_url()?>public/checkbox/js/bootstrap-checkbox.js" ></script>
<script src="<?=base_url()?>public/checkbox/js/bootstrap-checkbox_docs.js" ></script>

<!-- <script>
$(document).ready(function() {
    $('.select2').select2({
        'width':'100%'
    });
});
</script> -->

 </body>
</html>